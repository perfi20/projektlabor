<?php

// LIST MESSAGES FROM A SPECIFIC CHAT
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $bearer_token = get_bearer_token();
    $is_jwt_valid = is_jwt_valid($bearer_token);

    if ($is_jwt_valid) {
        $from = $_GET['chat'];
        $to = $_GET['to'];
        $stmt = $pdo->prepare('SELECT * FROM labor_chat WHERE msgfrom = ? AND msgto = ?');
        $stmt->execute([$from, $to]);
        $data1 = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt2 = $pdo->prepare('SELECT * FROM labor_chat WHERE msgfrom = ? AND msgto = ?');
        $stmt2->execute([$to, $from]);
        $data2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

        $data = array_merge($data1, $data2);

        return;
    } else {
        $data = array('error' => 'Access denied');
        return; 
    }
    return;
}

// LIST USERS CHAT PARTNERS
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $bearer_token = get_bearer_token();
    $is_jwt_valid = is_jwt_valid($bearer_token);

    if ($is_jwt_valid) {
        $data = json_decode(file_get_contents('php://input', true));

        $stmt = $pdo->prepare('SELECT DISTINCT msgto FROM labor_chat WHERE msgfrom = ? ORDER BY msgDate DESC');
        $stmt->execute([$data->knev]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return; 
    } else {
        $data = array('error' => 'Access denied');
        return; 
    }
    return;
}

// ADD NEW MESSAGE
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {

    $bearer_token = get_bearer_token();
    $is_jwt_valid = is_jwt_valid($bearer_token);

    if ($is_jwt_valid) {
        $data = json_decode(file_get_contents('php://input', true));

        $stmt = $pdo->prepare("INSERT IGNORE INTO labor_chat (msgid, msgfrom, msgto, msg)
                                VALUES (?, ?, ?, ?)");
        $stmt->execute([$data->msgid, $data->msgfrom, $data->msgto, $data->msg]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        $data = array('status' => 1);
        return;
    } else {
        $data = array('error' => 'Access denied');
        return; 
    }
    return;
}