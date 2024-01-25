<?php

// list general statistics
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $input = json_decode(file_get_contents('php://input', true));

    //auth
    $bearer_token = get_bearer_token();
    $is_jwt_valid = is_jwt_valid($bearer_token);

    if (!$is_jwt_valid) {
        $data = array('success' => false, 'error' => 'Access denied!');
        return;
    }

    // get stats
    if (isset($input->view) && $input->view == 'stats') {

        try {
            // user count
        $userCount = $pdo->prepare("SELECT COUNT(id) AS 'users' FROM user");
        $userCount->execute();
        $userCount = $userCount->fetch(PDO::FETCH_ASSOC);

        // post count
        $postCount = $pdo->prepare("SELECT COUNT(id) AS 'posts' FROM post");
        $postCount->execute();
        $postCount = $postCount->fetch(PDO::FETCH_ASSOC);

        $users = $userCount['users'];
        $posts = $postCount['posts'];

        $data = array(
            'success' => true,
            'users' => $users,
            'posts' => $posts
        );

        } catch (PDOException $e) {
            $data = array('success' => false, 'message' => $e->getMessage());
            return;
        }

        return;
    }

    // get all posts
    if (isset($input->view) && $input->view === 'posts') {

        // pagination
        $limit = $input->limit;

        try {        
            $pagination = $pdo->prepare(
                "SELECT COUNT(p.id) AS posts, u.username FROM post p
                INNER JOIN user u ON p.publisher = u.id"
            );
            $pagination->execute();
            $pages = $pagination->fetch(PDO::FETCH_ASSOC);
            $totalPages = ceil($pages["posts"] / $limit);
            $starting_limit = ($input->page - 1) * $limit;

        } catch (PDOException $e) {
            $data = array('success' => false, 'message' => $e->getMessage());
            return;
        }

        try {
            $stmt = $pdo->prepare(
            "SELECT p.id, p.title, p.category, p.cover, p.summary, p.created_at, p.updated_at,p.content, p.featured,
            u.username, COUNT(v.id) AS views
            FROM post p JOIN user u ON p.publisher = u.id LEFT JOIN views v ON p.id = v.postID
            GROUP BY p.id ORDER BY created_at DESC LIMIT $starting_limit, $limit"
            );
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $content = array(
            'success' => true,
            'page' => $input->page,
            'total_pages' => $totalPages,
            'posts' => $data
            );
            $data = $content;

        } catch (PDOException $e) {
            $data = array('success' => false, 'message' => $e->getMessage());
            return;
        }

        return;
    }

    // get all users
    if (isset($input->view) && $input->view === "users") {

        // pagination
        $limit = $input->limit;

        try {
            $pagination = $pdo->prepare(
                "SELECT COUNT(id) AS users FROM user"
            );
            $pagination->execute();
            $pages = $pagination->fetch(PDO::FETCH_ASSOC);
            $totalPages = ceil($pages["users"] / $limit);
            $starting_limit = ($input->page - 1) * $limit;
        } catch (PDOException $e) {
            $data = array('success' => false);
            return;
        }

        try {
            $stmt = $pdo->prepare(
            "SELECT u.id, u.username, u.email, u.created_at, u.updated_at, u.access_level, COUNT(p.id) AS postNumber
            FROM user u LEFT JOIN post p ON u.id = p.publisher GROUP BY u.id
            ORDER BY u.created_at DESC LIMIT $starting_limit, $limit"
            );
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $content = array(
            'success' => true,
            'page' => $input->page,
            'total_pages' => $totalPages,
            'users' => $data
            );

            $data = $content;
        } catch (PDOException $e) {
            $data = array('success' => false);
            return;
        }

        return;

    }
        
    return;
}

// admin edit user
if ($_SERVER["REQUEST_METHOD"] == 'PATCH') {

    $input = json_decode(file_get_contents('php://input', true));

    //auth
    $bearer_token = get_bearer_token();
    $is_jwt_valid = is_jwt_valid($bearer_token);

    if (!$is_jwt_valid) {
        $data = array('success' => false, 'error' => 'Access denied!');
        return;
    }

    try {
        $stmt = $pdo->prepare('UPDATE user SET username = ?, email = ?, access_level = ? WHERE id = ?');
        $stmt->execute([$input->newUsername, $input->newEmail, $input->newAccessLevel, $input->id]);
        $data = array('success' => true, 'message' => 'User edited Successfully!');

    } catch (PDOException $e) {
        $data = array('success' => false, 'message' => 'Failed to edit User!');
        return;
    }

}