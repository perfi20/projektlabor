<?php

session_start();

$GLOBALS["toastFunction"] = "";

require_once('components/curl.php');
require_once('components/validateInput.php');
require_once('inc/loggedInHeader.php');

// check login state
if (empty($_SESSION['username'])) {
    header('location: index.php');
}

// change username
if (isset($_POST["editUsername"])) {

    $newUsername = $_POST["newUsername"];
    $newUsernameValidated = validateInput($newUsername);

    // success
    if (!empty($newUsername)) {

        $postfield = json_encode(['id' => $_SESSION['userID'], 'newUsername' => $newUsernameValidated]);
        $result = curl('user', 'PATCH', $postfield);

        $GLOBALS["toastFunction"] = "showToast('$result->success', '$result->message');";

        if ($result->success === true) {
            // edit posts author link
            $usernameChangedPostfield = json_encode(['usernameChanged' => $newUsernameValidated, 'id' => $_SESSION["userID"]]);
            $usernameChanged = curl('posts', 'PATCH', $usernameChangedPostfield);


            $_SESSION["username"] = "";
            header('Location: login.php');
        }
    } else {
        // username is empty
        $GLOBALS["toastFunction"] = "showToast('false', 'New username cannnot be empty!');";
    }

}

// change email
if (isset($_POST["editEmail"])) {

    $email = $_POST["newEmail"];
    $emailValidated = filter_var($email, FILTER_VALIDATE_EMAIL);
    $emailSanitized = filter_var($emailValidated, FILTER_SANITIZE_EMAIL);

    // success
    if ($email !== "" && $emailSanitized !== false) {

        $postfield = json_encode([
        'id' => $_SESSION['userID'],
        'newEmail' => htmlspecialchars($emailSanitized)
        ]);
        $result = curl('user', 'PATCH', $postfield);

        $GLOBALS["toastFunction"] = "showToast('$result->success', '$result->message')";

    }

    if ($emailSanitized === "") {
        $GLOBALS["toastFunction"] = "showToast('false', 'New Email invalid!')";
    }

}

// change password
if (isset($_POST['editPassword'])) {

    $pw = $_POST['pw'];
    $newPassword1 = $_POST['newPassword1'];
    $newPassword2 = $_POST['newPassword2'];

    // invalid inputs
    if ($pw === "" || $newPassword1 === "" || $newPassword2 === "") {
            $GLOBALS["toastFunction"] = "showToast('false', 'Password cannot be empty!')";
        } else if ($newPassword1 !== $newPassword2) {
            $GLOBALS["toastFunction"] = "showToast('false', 'New passwords doesnt match!')";
        }

    // valid inputs
    if ($pw !== "" && $newPassword1 !== "" && $newPassword2 !== "" && $newPassword1 === $newPassword2) {
        
        $pwValidated = validateInput($pw);
        $newPasswordValidated = validateInput($newPassword2);

        $pwHashed = hash('sha256', $pwValidated);
        $newPasswordHashed = hash('sha256', $newPasswordValidated);

        $postfield = json_encode([
            'id' => $_SESSION['userID'],
            'pw' => $pwHashed,
            'newPassword' => $newPasswordHashed,
        ]);

        $result = curl('user', 'PATCH', $postfield);

        $GLOBALS["toastFunction"] = "showToast('$result->success', '$result->message')";

    }
    
}
    
// MAIN CONTENT
// i dont know why, but this way data get refreshed insantly without page reload
if (!isset($_GET["magic"])) {

    // load user data from session
    $userPostfields = json_encode(['username' => $_SESSION['username']]);
    $userResult = curl('user', 'POST', $userPostfields, true);
    $user = $userResult["user"];
    $userMessage = $userResult["message"];
    if ($userResult["success"] !== true) {
        $GLOBALS["toastFunction"] = "showToast('false', '$userMessage')";
    }
        
?>

<main class="container">

    <table class="table text-white">
        <tbody>
            <!-- username -->
            <tr>
                <td>Username</td>
                <td>
                    <h3 id="username"><?php echo $user["username"]; ?></h3>
                </td>
                <td>
                    <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal"
                        data-bs-target="#editUsernameModal">
                        Change
                    </button>
                </td>
            </tr>

            <!-- email -->
            <tr>
                <td>Email</td>
                <td>
                    <h3><?php echo $user["email"]; ?></h3>
                </td>
                <td>
                <button type="button" class="btn btn-outline-warning"
                    data-bs-toggle="modal" data-bs-target="#editEmailModal">
                    Change
                </button>
                </td>
            </tr>

            <!-- password -->
            <tr>
                <td>Password</td>
                <td></td>
                <td>
                    <button type="button" class="btn btn-outline-warning"
                        data-bs-toggle="modal" data-bs-target="#editPasswordModal">
                        Change
                    </button> 
                </td>
            </tr>

            <!-- Profile picture -->
            <tr>
                <td>Profile Picture</td>
                <td></td>
                <td>
                    <button type="button" class="btn btn-outline-warning"
                        data-bs-toggle="modal" data-bs-target="#editPictureModal">
                        Change
                    </button>
                </td>
            </tr>

            <!-- role -->
            <tr>
                <td>Role</td>
                <td>
                    <h3><?php echo $user["access_level"] === 0 ? "user" : "admin"; ?></h3>
                </td>
                <td></td>
            </tr>

            <!-- created_at -->
            <tr>
                <td>Account created</td>
                <td>
                    <h3><?php echo $user["created_at"]; ?></h3>
                </td>
                <td></td>
            </tr>

            <!-- created_at -->
            <tr>
                <td>Account updated</td>
                <td>
                    <h3><?php echo $user["updated_at"]; ?></h3>
                </td>
                <td></td>
            </tr>

        </tbody>
    </table>

    <!-- edit username modal -->

    <div class="modal fade" id="editUsernameModal" tabindex="-1"
        aria-labelledby="editUsernameModalLabel" aria-hidden="true" data-bs-theme="dark">
        <div class="modal-dialog">
            <div class="modal-content bg-dark">

                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editUsernameModalLabel">Change Username</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="nodal-body">
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

                        <div class="mb-3">
                            <label for="prevUsername">Previous Username:</label>
                            <input type="text" name="prevUsername" id="prevUsername" class="form-control bg-dark text-light"  value="<?php echo $user["username"]; ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="newUsername">New Username:</label>
                            <input type="text" name="newUsername" id="newUsername" class="form-control bg-dark text-light" placeholder="<?php echo $user["username"]; ?>" autofocus>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-outline-warning" name="editUsername" id="editUsername">Change</button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- edit email modal -->

    <div class="modal fade" id="editEmailModal" tabindex="-1"
        aria-labelledby="editEmailModalLabel" aria-hidden="true" data-bs-theme="dark">
        <div class="modal-dialog">
            <div class="modal-content bg-dark">

                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editEmailModalLabel">Change Email</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="nodal-body">
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

                        <div class="mb-3">
                            <label for="prevEmail">Previous Email:</label>
                            <input type="text" name="prevEmail" class="form-control bg-dark text-light"  value="<?php echo $user["email"]; ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="newEmail">New Email:</label>
                            <input type="text" name="newEmail" class="form-control bg-dark text-light" placeholder="<?php echo $user["email"]; ?>" autofocus>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-outline-warning" name="editEmail" id="editEmail">Change</button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- edit password modal -->

    <div class="modal fade" id="editPasswordModal" tabindex="-1"
        aria-labelledby="editPasswordModalLabel" aria-hidden="true" data-bs-theme="dark">
        <div class="modal-dialog">
            <div class="modal-content bg-dark">

                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editPasswordModalLabel">Change Password</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="nodal-body">
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

                        <div class="mb-3">
                            <label for="pw">Password:</label>
                            <input type="password" name="pw" class="form-control bg-dark text-light">
                        </div>

                        <div class="mb-3">
                            <label for="newPassword1">New Password:</label>
                            <input type="password" name="newPassword1" class="form-control bg-dark text-light" autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="newPassword2">New Password Again:</label>
                            <input type="password" name="newPassword2" class="form-control bg-dark text-light">
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-outline-warning" name="editPassword" id="editPassword">Change</button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- edit profile picture modal -->

    <div class="modal fade" id="editPictureModal" tabindex="-1"
        aria-labelledby="editPictureModalLabel" aria-hidden="true" data-bs-theme="dark">
        <div class="modal-dialog">
            <div class="modal-content bg-dark">

                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editPictureModalLabel">Change Profile Picture</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="nodal-body">
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

                        <div class="mb-3">
                            <label for="pw">Picture:</label>
                            <input type="file" accept="image/*" name="picture" id="picture">
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-outline-warning" name="editPicture" id="editPicture">Change</button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>

</main>

<?php 
}

require_once('inc/footer.php'); 
?>

<!-- toast -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
  <div id="liveToast" class="toast text-dark" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header" id="toastHeader">

      <strong class="me-auto" id="toastTitle"></strong>
      <small>11 mins ago</small>
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body" id="toastMessage">
    
    </div>
  </div>
</div>

<script src="./js/eventHandler.js"></script>

<script>

<?php
    if ($GLOBALS["toastFunction"] !== "") {
        echo $GLOBALS["toastFunction"];
    } else {
        $GLOBALS["toastFunction"] = "";
    } 
?>

// prevent form resubmission when page is refreshed
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}

</script>