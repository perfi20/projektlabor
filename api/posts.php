<?php
// get all posts
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $stmt = $pdo->prepare('SELECT * FROM labor_forum');
    $stmt->execute();

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return;
}

// get a post with id
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input', true));

    $stmt = $pdo->prepare('SELECT * FROM labor_forum WHERE id = ?');
    $stmt->execute($data->id);

    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    return;
}

// new post
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $data = json_decode(file_get_contents('php://input', true));

    $bearer_token = get_bearer_token();
    $is_jwt_valid = is_jwt_valid($bearer_token);

    if ($is_jwt_valid) {
        $stmt = $pdo->prepare("INSERT IGNORE INTO labor_forum (title, body, publisherID, event)
                                VALUES (?, ?, ?, ?)");
        $stmt->execute([$data->title, $data->body, $data->pubID, $data->event]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return;
    } else {
        $data = array('error' => 'Access denied');
        return; 
    }
    return;
}