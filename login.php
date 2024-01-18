<?php

session_start();

include_once('components/curl.php');
include_once('inc/notLoggedInHeader.php');
include_once('components/validateInput.php');

if (isset($_SESSION['username']) && $_SESSION['username'] !== "") {
  header('location: index.php');
}

$username = null;
$error = null;

if (isset($_POST['submit'])){

  $username = validateInput($_POST['username']);
  $pw = validateInput($_POST['pw']);
  $pwSecured = hash('sha256', $pw);

  $postfields = json_encode(['username' => $username, 'pw' => $pwSecured]);

  $data = curl('users', 'POST', $postfields);
 
  // login successful -> redirect to home page
  if($data->success == true){

    $_SESSION['username'] = $data->username;
    $_SESSION['userID'] = $data->id;
    $_SESSION['access_level'] = $data->access_level;
    $_SESSION['token'] = $data->token;

    header('location: index.php');
  }

  // login failed
  $error = 'Invalid creditentials!';
}

?>

<div class="container position-absolute top-50 start-50 translate-middle">
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);  ?>" class="was-validated border border-secondary p-5 rounded" style="--bs-border-opacity: .5;" method="POST">
<h1>Login</h1>
  <div class="mb-5">
    <label class="form-label mt-3" for="username">Username:</label>
    <input type="text" class="form-control bg-dark text-light" value="<?php echo $username; ?>" name="username" required>
  </div>
  <div class="mb-5">
    <label class="form-label" for="pw">Password:</label>
    <input type="password" class="form-control bg-dark text-light" value="" name="pw" required>
    <?php if (isset($error)) : ?>
      <span class="text-light bg-danger mt-5"><?php echo $error; ?></span>
    <?php endif ?>
  </div>
  <button type="submit" name="submit" class="btn btn-outline-success align-middle">Login</button>
</form>
</div>

<?php
include_once('inc/footer.php');
?>