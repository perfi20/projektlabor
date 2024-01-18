<?php
// get all posts
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $data = json_decode(file_get_contents('php://input', true));

    $stmt = $pdo->prepare('SELECT * FROM post');
    $stmt->execute();

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return;
}

// get a post with post id or posts from a specific user
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $input = json_decode(file_get_contents('php://input', true));

    // select post from post id
    if (isset($input->id) && $input->id !== "") {
        $stmt = $pdo->prepare("SELECT * FROM post WHERE id = ?");
        $stmt->execute([$input->id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            $data = array('success' => false, 'error' => 'post not found');
            return;
        }

        // view counter
        if (isset($input->ip) && $input->ip !== "") {

            $stmt = $pdo->prepare("INSERT INTO views (ip, postID, userID) VALUES (?, ?, ?)");
            $stmt->execute([$input->ip, $input->id, $input->userID]);
            $data = $fasz->fetch(PDO::FETCH_ASSOC);
            return;
        }

        $content = $data['content'];
        $data = array('success' => true, 'content' => $content);
        return;
    }

    // select posts from user name
    if (isset($input->user) && $input->user !== "") {

        $username = $input->user;
        // get user id from username
        $getUserID = $pdo->prepare("SELECT id FROM user WHERE username = ?");
        $getUserID->execute([$username]);
        $userID = $getUserID->fetch(PDO::FETCH_ASSOC);
        if (!$userID) {
            $data = array('success' => false, 'error' => 'user not found');
            return;
        }

        $stmt = $pdo->prepare("SELECT * FROM post WHERE publisher = ?");
        $stmt->execute([$userID["id"]]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$data) {
            $data = array('success' => false);
        }

        $content = array(
            'success' => true,
            'publisher' => $username,
            'posts' => $data,
        );

        $data = $content;
        return;
    }

    // list posts by category
    if (isset($input->category) && $input->category !== "") {

        $stmt = $pdo->prepare("SELECT * FROM post WHERE category = ?");
        $stmt->execute([$input->category]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$data) {
            $data = array('success' => false, 'error' => 'category not found');
            return;
        }

        $content = array(
            'success' => true,
            'category' => $data["category"],
            'posts' => $data
        );

        $data = $content;
        return;
    }

    // get recent n posts
    if (isset($input->recent) && $input->recent !== "") {

        $stmt = $pdo->prepare("SELECT DISTINCT * FROM post ORDER BY created_at DESC limit 3 ");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$data) {
            $data = array(
                'success' => false,
                'error' => 'failed to get post'
            );
            return;
        }

        $content = array(
            'success' => true,
            'posts' => $data
        );
        $data = $content;
        return;
    }

    // select distinct month and year post creation dates for displaying archives
    if (isset($input->archive) && $input->archive!== "") {

        $stmt = $pdo->prepare("SELECT DISTINCT (DATE_FORMAT(created_at, '%M %Y')) AS month_year
            FROM post ORDER BY DATE_FORMAT(created_at, '%Y') ASC, DATE_FORMAT(created_at, '%m') ASC;
        ");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$data) {
            $data = array(
                'success' => false,
                'error' => 'failed to get archives'
            );
            return;
        }
        return;
    }

    // select 3 random post for main page
    if (isset($input->main) && $input->main !== "") {

        $stmt = $pdo->prepare("SELECT * FROM post ORDER BY RAND() LIMIT 3");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$data) {
            $data = array(
                'success' => false,
                'error' => 'failed to get posts'
            );
            return;
        }

        $content = array(
            'success' => true,
            'posts' => $data
        );
        $data = $content;
        return;
    }

    // select 3 random featured posts
    if (isset($input->featured) && $input->featured !== "") {

        $stmt = $pdo->prepare("SELECT * FROM post WHERE featured = true ORDER BY RAND() LIMIT 3");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$data) {
            $data = array(
                'success' => false,
                'error' => 'failed to get featured posts'
            );
            return;
        }

        $content = array(
            'success' => true,
            'posts' => $data
        );
        $data = $content;
        return;
    }

    return;
}

// new post
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $data = json_decode(file_get_contents('php://input', true));

    $bearer_token = get_bearer_token();
    $is_jwt_valid = is_jwt_valid($bearer_token);

    if (!$is_jwt_valid) {
         $data = array('success' => false, 'error' => 'Access denied!');
        return;
    }

    // query user and check for permissions
    $userCheck = $pdo->prepare("SELECT id, username, access_level FROM user WHERE username = ?");
    $userCheck->execute([$data->publisher]);
    $userCheckResult = $userCheck->fetch(PDO::FETCH_ASSOC);
    if ($userCheckResult) {
        $publisher = $userCheckResult["id"];
    }
    
    try {
    $stmt = $pdo->prepare("INSERT INTO post (title, category, summary, publisher, content)
                            VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$data->title, $data->category , $data->summary, $publisher, $data->content]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    $lastId = $pdo->lastInsertId();
    $data = array('success' => true, 'id' => $lastId);
    } catch (PDOException $e) {
        $data = array('success' => false, 'error' => $e->getMessage());
    }
    return;

}

// edit post by id
if ($_SERVER['REQUEST_METHOD'] == 'PATCH') {
    $data = json_decode(file_get_contents('php://input', true));

    $bearer_token = get_bearer_token();
    $is_jwt_valid = is_jwt_valid($bearer_token);

    if (!$is_jwt_valid) {
        $data = array('error' => 'Access denied!');
        return;
    }

    $stmt = $pdo->prepare("UPDATE post SET title = ?, summary = ?, publisher = ?, content = ?");
    $stmt->execute([$data->title, $data->summary, $data->publisher, $data->content]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    return;
}

// delete post by id
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $data = json_decode(file_get_contents('php://input', true));

    $bearer_token = get_bearer_token();
    $is_jwt_valid = is_jwt_valid($bearer_token);

    if (!$is_jwt_valid) {
        array('error' => 'Access deniec!');
        return;
    }

    $stmt = $pdo->prepare("DELETE FROM post WHERE id = ?");
    $stmt->execute([$data->id]);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($data) {
        $data = array('success' => true);
        return;
    }
    
    $data = array('success' => false);
    return;
}