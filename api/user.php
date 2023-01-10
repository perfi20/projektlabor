<?php 

// LIST USER BY ID
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    // jwt validation
    $bearer_token = get_bearer_token();
    $is_jwt_valid = is_jwt_valid($bearer_token);

    if ($is_jwt_valid) {
        $user = $_GET['user'];
        $stmt = $pdo->prepare('SELECT id, knev, email, admine FROM labor WHERE id = ? ');
        $stmt->execute([$user]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return;
    } else {
        $data = array('error' => 'Access denied');
        return;
    }
    return;
}

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $data = json_decode(file_get_contents('php://input'));

    $bearer_token = get_bearer_token();
    $is_jwt_valid = is_jwt_valid($bearer_token);

    if ($is_jwt_valid) {
        $stmt = $pdo->prepare('DELETE FROM labor WHERE id = ?');
        $stmt->execute([$data->id]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!$data) {
            http_response_code(401);
            $data = array('status' => '0');
        }
        else $data = array('status' => '1');
        return;
    } else {
        $data = array('error' => 'Access denied');
        return; 
    }
    return;
}

// EDIT USER BY ID
if ($_SERVER['REQUEST_METHOD'] == 'PATCH') {
    $data = json_decode(file_get_contents('php://input'));

    $bearer_token = get_bearer_token();
    $is_jwt_valid = is_jwt_valid($bearer_token);

    if ($is_jwt_valid) {
        // check id
        $idCheck = $pdo->prepare('SELECT id FROM labor WHERE id = ?');
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
        $changeCheck = $pdo->prepare('SELECT * FROM labor WHERE id = ? AND knev = ? ANd email = ? AND admine = ?');
        $changeCheck->execute([$data->id, $data->knev, $data->email, $data->admin]);
        $changeCheck = $changeCheck->fetch((PDO::FETCH_ASSOC));

        if ($changeCheck) {
            $data = array(
                'status' => 'no_changes',
                'error' => 1
            );
            return;
        }

        $stmt = $pdo->prepare('UPDATE labor SET knev = ?, email = ?, admine = ? WHERE id = ?');
        $stmt->execute([$data->knev, $data->email, $data->admin, $data->id]);
        
        $data = array(
            'status' => 'success',
            'error' => 0
        );
        return;
    } else {
        $data = array('error' => 'Access denied');
        return;   
    }
    return;
}