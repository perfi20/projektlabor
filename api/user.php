<?php 

// LIST USER BY ID
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $input = json_decode(file_get_contents('php://input'));

    // auth
    $bearer_token = get_bearer_token();
    $is_jwt_valid = is_jwt_valid($bearer_token);

    if (!$is_jwt_valid) {
        $data = array('success' => false, 'message' => 'Access Denied!', 'error' => 'Access Denied!');
        return;
    }

    try {
        $stmt = $pdo->prepare('SELECT id, username, email, created_at, updated_at, access_level FROM user WHERE username = ? ');
        $stmt->execute([$input->username]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        $content = array('success' => true, 'user' => $data);

        $data = $content;

    } catch (PDOException $e) {
        $data = array('success' => false, 'message' => 'Failed to load User', 'error' => $e->getMessage());
        return;
    }

    return;

}

// delete user by id
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $input = json_decode(file_get_contents('php://input'));

    // auth
    $bearer_token = get_bearer_token();
    $is_jwt_valid = is_jwt_valid($bearer_token);

    if (!$is_jwt_valid) {
        $data = array('success' => false, 'message' => 'Access Denied!', 'error' => 'Access Denied!');
        return;
    }

    try {
        $stmt = $pdo->prepare('DELETE FROM user WHERE id = ?');
        $stmt->execute([$input->id]);
        $data = array('success' => true, 'message' => 'User deleted successfully!');

    } catch (PDOException $e) {
        $data = array('success' => false, 'message' => 'Failed to delete User!', 'error' => $e->getMessage());
    }

    return;

}

// EDIT USER BY ID
if ($_SERVER['REQUEST_METHOD'] == 'PATCH') {

    $input = json_decode(file_get_contents('php://input', true));

    //auth
    $bearer_token = get_bearer_token();
    $is_jwt_valid = is_jwt_valid($bearer_token);

    if (!$is_jwt_valid) {
        $data = array('success' => false, 'error' => 'Access denied!');
        return;
    }

    try {
        // check id
        $idCheck = $pdo->prepare("SELECT id, username, pw, email, access_level FROM user WHERE id = ?");
        $idCheck->execute([$input->id]);
        $idCheck = $idCheck->fetch(PDO::FETCH_ASSOC);

        if (!$idCheck) {
            $data = array('success' => false, "message" => 'User not found!');
            return;
        }

        $prevName = $idCheck['username'];
        $prevEmail = $idCheck['email'];
        $prevPassword = $idCheck['pw'];
        $prevAccess = $idCheck["access_level"];

    } catch (PDOException $e) {
        $data = array('success' => false, 'message' => 'Server error!', 'pdoErr' => $e->getMessage());
        return;
    }

    // edit username if newUsername  is set
    if (isset($input->newUsername)) {

        // input is invalid
        if ($input->newUsername === "") {
            $data = array('success' => false, 'message' => 'Invalid input!');
            return;
        }

        // no change
        if ($input->newUsername === $prevName) {
            $data = array('success' => false, 'message' => 'New Username cannot be the same!');
            return;
        }

        // check if username already exists
        $userCheck = $pdo->prepare("SELECT username FROM user WHERE username = ?");
        $userCheck->execute([$input->newUsername]);
        $userCheck = $userCheck->fetch(PDO::FETCH_ASSOC);
        if ($userCheck) {
            $data = array('success' => false, 'message' => 'Username already exists!');
            return;
        }
        
        try {

            $stmt = $pdo->prepare("UPDATE user SET username = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$input->newUsername, $input->id]);
            $data = array('success' => true, 'message' => 'Username changed successfully!');

        } catch (PDOException $e) {
            $data = array('success' => false, 'message' => 'Failed to change username!', 'pdoErr' => $e->getMessage());
        }
        return;
    }

    // edit email if newEmail is set
    if (isset($input->newEmail)) {

        // if input is empty or invalid
        if ($input->newEmail === "") {
            $data = array('success' => false, 'message' => 'Invalid input!');
            return;
        }

        // no change
        if ($prevEmail === $input->newEmail) {
            $data = array('success' => false, 'message' => 'New Email cannot be the same!');
            return;
        }

        // check if email already exists
        $emailCheck = $pdo->prepare("SELECT email FROM user WHERE email = ?");
        $emailCheck->execute([$input->newEmail]);
        $emailCheck = $emailCheck->fetch(PDO::FETCH_ASSOC);
        if ($emailCheck) {
            $data = array('success' => false, 'message' => 'Email already exists!');
            return;
        }

        try {
            $stmt = $pdo->prepare("UPDATE user SET email = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$input->newEmail, $input->id]);
            $data = array('success' => true, 'message' => 'Email successfully changed!');

        } catch (PDOException $e) {
            $data = array('success' => false, 'message' => 'Failed to change Email!', 'pdoErr' => $e->getMessage());
            return;
        }
        return;
    }

    // edit password if old and new password is set
    if (isset($input->pw) && isset($input->newPassword)) {

        // if input is empty or invalid
        if ($input->newPassword === "") {
            $data = array('success' => false, 'message' => 'Invalid input!');
            return;
        }

        // no change
        if ($prevPassword !== $input->pw) {
            $data = array('success' => false, 'message' => 'Previous Password Invalid!');
            return;
        }

        try {
            $stmt = $pdo->prepare("UPDATE user SET pw = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$input->newPassword, $input->id]);
            $data = array('success' => true, 'message' => 'Password successfully changed!');

        } catch (PDOException $e) {
            $data = array('success' => false, 'message' => 'Failed to change Password!', 'pdoErr' => $e->getMessage());
            return;
        }
        return;
    }

    return;
}