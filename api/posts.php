<?php

// get a post with post id or posts from a specific user
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $input = json_decode(file_get_contents('php://input', true));

    // select post from post id
    if (isset($input->id) && $input->id !== "") {

        try {
            $stmt = $pdo->prepare(
                "SELECT p.title, p.category, p.cover, p.created_at, p.content, u.username, u.id FROM post p
                INNER JOIN user u ON p.publisher = u.id WHERE p.id = ?
            ");
            $stmt->execute([$input->id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            $publisher = $data["id"];
            $content = array(
                'success' => true,
                'title' => $data["title"],
                'category' => $data["category"],
                'cover' => $data["cover"],
                'publisher' => $data["username"],
                'created_at' => $data["created_at"],
                'content' => $data["content"]
            );
            $data = $content;

        } catch (PDOException $e) {
            $data = array('success' => false, 'error' => 'post not found');
            return;
        }

        // view counter
        if (isset($input->ip) && $input->ip !== "") {

            $stmt = $pdo->prepare("INSERT INTO views (ip, postID, authorID, userID) VALUES (?, ?, ?, ?)");
            $stmt->execute([$input->ip, $input->id, $publisher, $input->userID]);
        }

        return;
    }

    // select posts from a specific user
    if (isset($input->user) && $input->user !== "") {

        // pagination
        $limit = $input->limit;

        try {
            $pagination = $pdo->prepare(
                "SELECT COUNT(p.id) AS posts, u.username FROM post p
                INNER JOIN user u ON p.publisher = u.id WHERE u.username = ?"
            );
            $pagination->execute([$input->user]);
            $pages = $pagination->fetch(PDO::FETCH_ASSOC);
            $totalPages = ceil($pages["posts"] / $limit);
            $starting_limit = ($input->page - 1) * $limit;
        } catch (PDOException $e) {
            $data = array('success' => false);
            return;
        }

        // filtering
        if (isset($input->sort) && isset($input->order)) {
            $sort = $input->sort;
            $order = $input->order;
        } else {
            $sort = 'created_at';
            $order = 'asc';
        }

        try {
            $stmt = $pdo->prepare(
            "SELECT p.id, p.title, p.category, p.cover, p.summary, DATE_FORMAT(p.created_at, '%Y-%m-%d') AS created_at,
            DATE_FORMAT(p.updated_at, '%Y-%m-%d') AS updated_at, p.content, p.featured, u.username
            FROM post p INNER JOIN user u ON p.publisher = u.id WHERE u.username = ?
            ORDER BY $sort $order LIMIT $starting_limit, $limit"
            );
            $stmt->execute([$input->user]);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $content = array(
                'success' => true,
                'publisher' => $input->user,
                'page' => $input->page,
                'total_pages' => $totalPages,
                'posts' => $data
            );

            $data = $content;

        } catch (PDOException $e) {
            $data = array('success' => false, 'message' => 'Failded to load Posts!', 'error' => $e->getMessage());
            return;
        }

        return;
    }

    // list posts by category
    if (isset($input->category) && $input->category !== "") {

        // pagination
        $limit = $input->limit;

        try {
            $pagination = $pdo->prepare("SELECT COUNT(id) AS posts FROM post WHERE category = ?");
            $pagination->execute([$input->category]);
            $pages = $pagination->fetch(PDO::FETCH_ASSOC);
            $totalPages = ceil($pages["posts"] / $limit);
            $starting_limit = ($input->page - 1) * $limit;
        } catch (PDOException $e) {
            $data = array('success' => false);
            return;
        }

        try {
            $stmt = $pdo->prepare(
                "SELECT p.id, p.title, p.category, p.cover, p.created_at, p.content, u.username FROM post p
                INNER JOIN user u ON p.publisher = u.id WHERE category = ?
                ORDER BY created_at DESC LIMIT $starting_limit, $limit"
            );
            $stmt->execute([$input->category]);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $content = array(
                'success' => true,
                'category' => $data["category"],
                'page' => $input->page,
                'total_pages' => $totalPages,
                'posts' => $data
            );
            $data = $content;

        } catch(PDOException $e) {
            $data = array('success' => false, 'error' => 'category not found');
            return;
        }

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

        $stmt = $pdo->prepare(
            "SELECT DISTINCT (DATE_FORMAT(created_at, '%M %Y')) AS month_year,
            DATE_FORMAT(created_at, '%Y') AS y, DATE_FORMAT(created_at, '%m') AS m
            FROM post ORDER BY DATE_FORMAT(created_at, '%Y') ASC, DATE_FORMAT(created_at, '%m') ASC;
        ");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$data) {
            $data = array(
                'success' => false,
                'message' => 'failed to get archives!',
                'error' => 'failed to get archives!'
            );
            return;
        }

    }

    // select posts by date - archive
    if (isset($input->year) || isset($input->month)) {


        //$firstDate = date("Y-m-d H:i:s", strtotime("$input->year-$input->month-1"));
        //$lastDate = date("Y-m-d H:i:s", strtotime("$input->year-$input->month-31"));
        //$date = "$input->year-$input->month";

        // pagination
        $limit = $input->limit;

        try {
            $pagination = $pdo->prepare("SELECT COUNT(id) AS posts FROM post
            WHERE YEAR(created_at) = ? AND MONTH(created_at) = ?");
            $pagination->execute([$input->year, $input->month]);
            $pages = $pagination->fetch(PDO::FETCH_ASSOC);
            $totalPages = ceil($pages["posts"] / $limit);
            $starting_limit = ($input->page - 1) * $limit;
        } catch (PDOException $e) {
            $data = array('success' => false);
            return;
        }

        $stmt = $pdo->prepare(
            "SELECT p.id, p.title, p.category, p.cover, p.created_at, p.content, u.username FROM post p
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
            INNER JOIN user u ON p.publisher = u.id WHERE YEAR(p.created_at) = ?
            AND MONTH(p.created_at) = ?
=======
            INNER JOIN user u ON p.publisher = u.id WHERE YEAR(created_at) = ?
            AND MONTH(created_at) = ?
>>>>>>> f7bb41a4ac79e8a78d2a24c1de5b630fd6e09fa1
=======
            INNER JOIN user u ON p.publisher = u.id WHERE YEAR(created_at) = ?
            AND MONTH(created_at) = ?
>>>>>>> f7bb41a4ac79e8a78d2a24c1de5b630fd6e09fa1
=======
            INNER JOIN user u ON p.publisher = u.id WHERE YEAR(created_at) = ?
            AND MONTH(created_at) = ?
>>>>>>> refs/remotes/origin/2024
            ORDER BY created_at ASC LIMIT $starting_limit, $limit
        ");
        $stmt->execute([$input->year, $input->month]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$data) {
            $data = array(
                'success' => false,
                'message' => 'failed to get posts!',
                'error' => 'failed to get posts!',
            );
            return;
        }

        $content = array(
            'success' => true,
            'page' => $input->page,
            'total_pages' => $totalPages,
            'posts' => $data
        );
        $data = $content;
        return;
    }

    // select 3 random post for main page
    if (isset($input->main) && $input->main !== "") {

        try {
            $stmt = $pdo->prepare(
            "SELECT p.id, p.title, p.category, p.cover, p.created_at, p.content, u.username FROM post p
            INNER JOIN user u ON p.publisher = u.id ORDER BY RAND() LIMIT 3
            ");
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $content = array(
            'success' => true,
            'posts' => $data
            );
            $data = $content;

        } catch (PDOException $e) {
            $data = array(
                'success' => false,
                'error' => 'failed to get posts'
            );
            return;
        }
        
        return;
    }

    // select 3 random featured posts
    if (isset($input->featured) && $input->featured !== "") {

        try {
            $stmt = $pdo->prepare(
                "SELECT p.id, p.title, p.category, p.cover, p.summary, DATE_FORMAT(p.created_at, '%M %d') AS created_at, u.username
                FROM post p JOIN user u ON p.publisher = u.id WHERE featured = true ORDER BY RAND() LIMIT 3
                ");
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $content = array(
            'success' => true,
            'posts' => $data
            );
            $data = $content;

        } catch (PDOException $e) {
            $data = array(
                'success' => false,
                'error' => 'failed to get featured posts'
            );
            return;
        }
        return;
    }

    return;
}

// new post
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $input = json_decode(file_get_contents('php://input', true));

    $bearer_token = get_bearer_token();
    $is_jwt_valid = is_jwt_valid($bearer_token);

    if (!$is_jwt_valid) {
         $data = array('success' => false, 'message' => 'Access Denied!', 'error' => 'Access dDnied!');
        return;
    }

    // query user and check for permissions
    $publisher = "";

    $userCheck = $pdo->prepare("SELECT id, username, access_level FROM user WHERE username = ?");
    $userCheck->execute([$input->publisher]);
    $userCheckResult = $userCheck->fetch(PDO::FETCH_ASSOC);
    if ($userCheckResult) {
        $publisher = $userCheckResult["id"];
    }
    
    try {
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
        $stmt = $pdo->prepare("INSERT INTO post (title, category, cover, summary, publisher, content)
                                VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$input->title, $input->category, $input->cover_image, $input->summary, $publisher, $input->content]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        $lastId = $pdo->lastInsertId();
        $data = array('success' => true,'message' => 'Post created successfully!', 'id' => $lastId);
=======
=======
>>>>>>> f7bb41a4ac79e8a78d2a24c1de5b630fd6e09fa1
=======
>>>>>>> refs/remotes/origin/2024
    $stmt = $pdo->prepare("INSERT INTO post (title, category, cover, summary, publisher, content)
                            VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$input->title, $input->category, $input->cover_image, $input->summary, $publisher, $input->content]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    $lastId = $pdo->lastInsertId();
    $data = array('success' => true,'message' => 'Post created successfully!', 'id' => $lastId);
<<<<<<< HEAD
<<<<<<< HEAD
>>>>>>> f7bb41a4ac79e8a78d2a24c1de5b630fd6e09fa1
=======
>>>>>>> f7bb41a4ac79e8a78d2a24c1de5b630fd6e09fa1
=======
>>>>>>> refs/remotes/origin/2024
    } catch (PDOException $e) {
        $data = array('success' => false, 'message' => 'Failed to create Post!', 'error' => $e->getMessage());
    }
    return;

}

// edit post by id
if ($_SERVER['REQUEST_METHOD'] == 'PATCH') {
    $input = json_decode(file_get_contents('php://input', true));

    $bearer_token = get_bearer_token();
    $is_jwt_valid = is_jwt_valid($bearer_token);

    if (!$is_jwt_valid) {
        $data = array('success' => false, 'message' => 'Access denied!', 'error' => 'Access denied!');
        return;
    }

    // check if anything changes
    try {
        $stmt = $pdo->prepare('SELECT title, category, cover, summary, featured FROM post WHERE id = ?');
        $stmt->execute([$input->id]);
        $prevData = $stmt->fetch(PDO::FETCH_ASSOC);

        $prevTitle = $prevData["title"];
        $prevCategory = $prevData["category"];
        $prevCover = $prevData["cover"];
        $prevSummary = $prevData["summary"];
        $prevFeatured = $prevData["featured"];

    } catch (PDOException $e) {
        $data = array('success' => false, 'message' => 'Failed to load post!', 'error' => $e->getMessage());
        return;
    }

    if ($input->title === $prevTitle && $input->category === $prevCategory
        && $input->summary === $prevSummary && $input->featured == $prevFeatured
        && $input->cover === $prevCover
    ) {
        $data = array('success' => false, 'message' => 'No changes!');
        return;
    }


    try {
        $stmt = $pdo->prepare("UPDATE post
            SET title = ?, category = ?, cover = ?, summary = ?, updated_at = NOW(), featured = ?
            WHERE id = ?"
        );
        $stmt->execute([$input->title, $input->category, $input->cover, $input->summary, $input->featured, $input->id]);
        $data = array('success' => true, 'message' => 'Post successfully edited!');
    } catch (PDOException $e) {
        $data = array('success' => false, 'message' => 'Failed to edit post!', 'error' => $e->getMessage());
        return;
    }

    return;
}

// delete post by id
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $input = json_decode(file_get_contents('php://input', true));

    $bearer_token = get_bearer_token();
    $is_jwt_valid = is_jwt_valid($bearer_token);

    if (!$is_jwt_valid) {
        array('success' => false, 'message' => 'Access denied!', 'error' => 'Access denied!');
        return;
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM post WHERE id = ?");
        $stmt->execute([$input->id]);
        $data = array('success' => true, 'message' => 'Post deleted Successfully!');
    } catch (PDOException $e) {
        $data = array('success' => false, 'message' => 'Failed to delete Post!', 'error' => $e->getMessage());
        return;
    }

    return;
}