<?php 

// LIST USER BY ID
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    // auth
    $bearer_token = get_bearer_token();
    $is_jwt_valid = is_jwt_valid($bearer_token);

    if (!$is_jwt_valid) {
        $data = array('error' => 'Access denied');
        return;
    }

    // query
    $user = $_GET['user'];
    $stmt = $pdo->prepare('SELECT id, knev, email, admine FROM user WHERE id = ? ');
    $stmt->execute([$user]);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return;

}

// delete user by id
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $data = json_decode(file_get_contents('php://input'));

    // auth
    $bearer_token = get_bearer_token();
    $is_jwt_valid = is_jwt_valid($bearer_token);

    if (!$is_jwt_valid) {
        $data = array('error' => 'Access denied');
        return;
    }

    // query
    $stmt = $pdo->prepare('DELETE FROM user WHERE id = ?');
    $stmt->execute([$data->id]);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // query failed
    if (!$data) {
        http_response_code(401);
        $data = array('success' => false);
    }

    $data = array('success' => true);
    return;

}

// EDIT USER BY ID
if ($_SERVER['REQUEST_METHOD'] == 'PATCH') {
    $data = json_decode(file_get_contents('php://input'));

    // auth
    // $bearer_token = get_bearer_token();
    // $is_jwt_valid = is_jwt_valid($bearer_token);

    // if (!$is_jwt_valid) {
    //     $data = array('error' => 'Access denied');
    //     return;   
    // }

        // check id
        $idCheck = $pdo->prepare('SELECT id FROM user WHERE id = ?');
        $idCheck->execute([$data->id]);
        $idCheck = $idCheck->fetch(PDO::FETCH_ASSOC);

        if (!$idCheck) {
            $data = array(
                'status' => 'user_not_found',
                'error' => 1
            );
            return;
        }


        // check if the requested changes dont change anything
        $changeCheck = $pdo->prepare('SELECT * FROM user WHERE id = ? AND username = ? ANd email = ? AND access_level = ?');
        $changeCheck->execute([$data->id, $data->username, $data->email, $data->access_level]);
        $changeCheck = $changeCheck->fetch((PDO::FETCH_ASSOC));

        if ($changeCheck) {
            $data = array(
                'success' => false,
                'status' => 'no_changes'
            );
            return;
        }

        // query
        $stmt = $pdo->prepare('UPDATE user SET username = ?, email = ?, access_level = ? WHERE id = ?');
        $stmt->execute([$data->username, $data->email, $data->acccess_level, $data->id]);
        
        $data = array(
            'success' => true
        );
        return;

}