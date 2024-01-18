<?php

// LIST ALL USER
if ($_SERVER['REQUEST_METHOD'] == 'GET') { 
    
    // jwt validation
    // $bearer_token = get_bearer_token();
    // $is_jwt_valid = is_jwt_valid($bearer_token);

    // query
    // if ($is_jwt_valid) {
        $stmt = $pdo->prepare('SELECT id, username, email, access_level, created_at, updated_at FROM user');
        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return;
//    } else {
//         $data = array('error' => 'Access denied');
//         return;
//     }
//     return;
}

// LOGIN USER
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $data = json_decode(file_get_contents('php://input', true));

    // all check passed
    $stmt = $pdo->prepare('SELECT id, username, email, access_level FROM user WHERE username = ? AND pw = ?');
    $stmt->execute([$data->username, $data->pw]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    // login failed
    if (!$data) {
        $data = array('success' => false);
        return;
    }

    // 
    $access_level = $data['access_level'];
    $email = $data['email'];
    $username = $data['username'];
    $id = $data["id"];

    // jwt
    $headers = array('alg' => 'HS256', 'typ' => 'JWT');
    $payload = array('username' => $username, 'exp' => (time() + 36000)); // token valid for 1 hours
    $jwt = generate_jwt($headers, $payload);

    // response - send back the email for the client to use
    $data = array(
        'success' => true,
        'token' => $jwt,
        'username' => $username,
        'id' => $id,
        'email' => $email,
        'access_level' => $access_level
    );
    return;
 }

// REGISTER USER
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {

    $data = json_decode(file_get_contents('php://input', true));

    // check if unique username exists
    $checkUsername = $pdo->prepare('SELECT id FROM user WHERE username = ? ');
    $checkUsername->execute([$data->username]);
    $checkUsername = $checkUsername->fetch(PDO::FETCH_ASSOC);

    // check if unique email exists
    $checkEmail = $pdo->prepare('SELECT id FROM user WHERE email = ?');
    $checkEmail->execute([$data->email]);
    $checkEmail = $checkEmail->fetch(PDO::FETCH_ASSOC);

    // username and email already exists - needs to be checked first!
    if ($checkUsername && $checkEmail) {
        $data = array('status' => 'username_and_email_exists');
        return;
    }
    // username already exists
    if ($checkUsername) { 
        $data = array('status' => 'username_exists');
        return;
    }

    // email already exists
    if ($checkEmail) { 
        $data = array('status' => 'email_exists'); // username and email already exists
        return;
    }

    // all check passed
    $stmt = $pdo->prepare('INSERT IGNORE INTO user (username, email, pw) VALUES (?, ?, ?)');
    $stmt->execute([$data->username, $data->email, $data->pw]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $data = array('status' => 1);
    return;
}