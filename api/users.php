<?php

// LIST ALL USER
if ($_SERVER['REQUEST_METHOD'] == 'GET') { 
    
    // jwt validation
    $bearer_token = get_bearer_token();
    $is_jwt_valid = is_jwt_valid($bearer_token);

    // query
    if (!$is_jwt_valid) {
        $data = array('success' => false, 'error' => 'Access denied');
        return;
    }

    try {
        $stmt = $pdo->prepare('SELECT id, username, email, access_level, created_at, updated_at FROM user');
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        $data = array('success' => false, 'message' => 'Failed to load Users', 'error' => $e->getMessage());
        return;
    }

    return;
}

// LOGIN USER
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $input = json_decode(file_get_contents('php://input', true));

    try {
        $stmt = $pdo->prepare('SELECT id, username, email, access_level FROM user WHERE username = ? AND pw = ?');
        $stmt->execute([$input->username, $input->pw]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        // login failed
        if (!$data) {
            $data = array('success' => false, 'message' => 'Invalid Creditentials!');
            return;
        }

        $access_level = $data['access_level'];
        $email = $data['email'];
        $username = $data['username'];
        $id = $data["id"];

        // jwt
        $headers = array('alg' => 'HS256', 'typ' => 'JWT');
<<<<<<< HEAD
        $payload = array('username' => $username, 'exp' => (time() + 3600)); // token valid for 1 hours
=======
        $payload = array('username' => $username, 'exp' => (time() + 36000)); // token valid for 10 hours
>>>>>>> f7bb41a4ac79e8a78d2a24c1de5b630fd6e09fa1
        $jwt = generate_jwt($headers, $payload);

        $data = array(
            'success' => true,
            'message' => 'Login Successful!',
            'token' => $jwt,
            'username' => $username,
            'id' => $id,
            'email' => $email,
            'access_level' => $access_level
        );
            
    } catch (PDOException $e) {
        $data = array('success' => false, 'message' => 'Login Failed!', 'error' => $e->getMessage());
        return;
    }
    
    return;

 }

// REGISTER USER
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {

    $input = json_decode(file_get_contents('php://input', true));

    try {
        // check if unique username exists
        $checkUsername = $pdo->prepare('SELECT id FROM user WHERE username = ? ');
        $checkUsername->execute([$input->username]);
        $checkUsername = $checkUsername->fetch(PDO::FETCH_ASSOC);

        // check if unique email exists
        $checkEmail = $pdo->prepare('SELECT id FROM user WHERE email = ?');
        $checkEmail->execute([$input->email]);
        $checkEmail = $checkEmail->fetch(PDO::FETCH_ASSOC);

        // username and email already exists - needs to be checked first!
        if ($checkUsername && $checkEmail) {
            $data = array('success' => false, 'message' => 'Username and Email already exists!');
            return;
        }
        // username already exists
        if ($checkUsername) { 
            $data = array('success' => false, 'message' => 'Username already exists!');
            return;
        }

        // email already exists
        if ($checkEmail) { 
            $data = array('success' => false, 'message' => 'Email already exists!'); // username and email already exists
            return;
        }

        // all check passed
        $stmt = $pdo->prepare('INSERT IGNORE INTO user (username, email, pw) VALUES (?, ?, ?)');
        $stmt->execute([$input->username, $input->email, $input->password]);

        // jwt
        $headers = array('alg' => 'HS256', 'typ' => 'JWT');
        $payload = array('username' => $username, 'exp' => (time() + 36000)); // token valid for 10 hours
        $jwt = generate_jwt($headers, $payload);

        $id = $pdo->lastInsertId();

        $data = array(
            'success' => true,
            'message' => 'Register Successful!',
            'token' => $jwt,
            'id' => $id,
            'access_level' => 0
        );

    } catch (PDOException $e) {
        $data = array('success' => false, 'message' => 'Registration failed!', 'error' => $e->getMessage());
        return;
    }

    return;
}