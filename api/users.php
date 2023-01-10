<?php

// LIST ALL USER
if ($_SERVER['REQUEST_METHOD'] == 'GET') { 
    
    // jwt validation
    $bearer_token = get_bearer_token();
    $is_jwt_valid = is_jwt_valid($bearer_token);

    // query
    if ($is_jwt_valid) {
        $stmt = $pdo->prepare('SELECT id, knev, email, admine FROM labor');
        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return;
    } else {
        $data = array('error' => 'Access denied');
        return;
    }
    return;
}

// LOGIN USER
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $data = json_decode(file_get_contents('php://input', true));

    // check if unique email exists
    $checkEmail = $pdo->prepare('SELECT id FROM labor WHERE email = ?');
    $checkEmail->execute([$data->email]);
    $checkEmail = $checkEmail->fetch(PDO::FETCH_ASSOC);

    // check if password is wrong
    $checkPw = $pdo->prepare('SELECT id FROM labor WHERE jszo = ? ');
    $checkPw->execute([$data->jelszo]);
    $checkPw = $checkPw->fetch(PDO::FETCH_ASSOC);

    // check if email is wrong
    if (!$checkEmail) {
        $data = array('status' => 'email_wrong', 'err_code' => 2);
        return;
    }
    
    // check if pw is wrong
    if (!$checkPw) {
        $data = array('status' => 'pw_wrong', 'err_code' => 3);
        return;
    }

    // all check passed
    $stmt = $pdo->prepare('SELECT knev, email, admine FROM labor WHERE email = ? AND jszo = ?');
    $stmt->execute([$data->email, $data->jelszo]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    // 
    $admin = $data['admine'];
    $email = $data['email'];
    $username = $data['knev'];

    // jwt
    $headers = array('alg' => 'HS256', 'typ' => 'JWT');
    $payload = array('username' => $username, 'exp' => (time() + 36000)); // token valid for 1 hours
    $jwt = generate_jwt($headers, $payload);

    // response
    $data = array('status' => 1, 'err_code' => 1, 'token' => $jwt, 'knev' => $username, 'email' => $email, 'admin' => $admin);
    return;
 }

// REGISTER USER
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {

    $data = json_decode(file_get_contents('php://input', true));

    // check if unique username exists
    $checkUsername = $pdo->prepare('SELECT id FROM labor WHERE knev = ? ');
    $checkUsername->execute([$data->knev]);
    $checkUsername = $checkUsername->fetch(PDO::FETCH_ASSOC);

    // check if unique email exists
    $checkEmail = $pdo->prepare('SELECT id FROM labor WHERE email = ?');
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
    $stmt = $pdo->prepare('INSERT IGNORE INTO labor (knev, email, jszo) VALUES (?, ?, ?)');
    $stmt->execute([$data->knev, $data->email, $data->jelszo]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $data = array('status' => 1);
    return;
}