<?php

// list general statistics
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    // jwt validation
    $bearer_token = get_bearer_token();
    $is_jwt_valid = is_jwt_valid($bearer_token);

    // query
    if ($is_jwt_valid) {
        
        // user count
        $userCount = $pdo->prepare("SELECT COUNT(id) AS 'users' FROM labor_users");
        $userCount->execute();
        $userCount = $userCount->fetch(PDO::FETCH_ASSOC);

        // event count
        $eventCount = $pdo->prepare("SELECT COUNT(id) AS 'events' FROM labor_forum WHERE event = 1");
        $eventCount->execute();
        $eventCount = $eventCount->fetch(PDO::FETCH_ASSOC);

        // post count
        $postCount = $pdo->prepare("SELECT COUNT(id) AS 'posts' FROM labor_forum");
        $postCount->execute();
        $postCount = $postCount->fetch(PDO::FETCH_ASSOC);

        // msg count
        $msgCount = $pdo->prepare("SELECT COUNT(id) AS 'msgs' FROM labor_chat");
        $msgCount->execute();
        $msgCount = $msgCount->fetch(PDO::FETCH_ASSOC);

        // query failed
        // if (!$userCount || !$eventCount || !$postCount || !$msgCount) {
        //     $data = array('status' => 0);
        //     return;
        // }

        // all good

        $users = $userCount['users'];
        $events = $eventCount['events'];
        $posts = $postCount['posts'];
        $msgs = $msgCount['msgs'];

        $data = array(
            'status' => 1,
            'users' => $users,
            'events' => $events,
            'posts' => $posts,
            'msgs' => $msgs
        );
        return;
    } else {
        $data = array('error' => 'Access denied');
        return;
    }
}