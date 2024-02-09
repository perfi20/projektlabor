<?php

session_start();

include_once('components/curl.php');
include_once('inc/notLoggedInHeader.php');
include_once('components/validateInput.php');

if (isset($_SESSION['username']) && $_SESSION['username'] !== "") {
  header('location: /');
}

if (isset($_POST['submit'])) {

  $username = validateInput($_POST['username']);
  $email = validateInput($_POST['email']);
  $password = validateInput($_POST['password']);
  $passwordConfirm = validateInput($_POST["passwordConfirm"]);
  $pwSecured = hash('sha256', $passwordConfirm);

  // only submit if passwords are the same
  if ($password === $passwordConfirm) {

    $postfields = json_encode(['username' => $username, 'email' => $email, 'password' => $pwSecured]);
    $result = curl('users', 'PUT', $postfields);

    $GLOBALS["toastFunction"] = "showToast('$result->success', '$result->message');";

    if($result->success == true) {

      $_SESSION['username'] = $username;
      $_SESSION['userID'] = $result->id;
      $_SESSION['email'] = $email;
      $_SESSION['access_level'] = $result->access_level;
      $_SESSION['token'] = $result->token;

      header('location: /');

    }

  } else {
    $GLOBALS["toastFunction"] = "showToast('false', 'Passwords are not matching!');";
  }

}

?>

  <div class="container position-absolute top-50 start-50 translate-middle">
    <form action="/register" class="was-validated border border-secondary p-5 rounded" style="--bs-border-opacity: .5;" method="POST">

        <h1>Register</h1>

      <div class="form-floating my-5">
        <input type="text" class="form-control" pattern="{3,50}"
          title="Username must be between 3 and 50 characters!!" id="floatingUsername" name="username"
          placeholder="Username" value="<?php echo $username; ?>" required autofocus
        >
        <label for="floatingUsername">Username</label>
      </div>

      <div class="form-floating mb-5">
        <input type="email" class="form-control" pattern="[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}$" title="Wrong email format!" id="floatingEmail" name="email" placeholder="Email" value="<?php echo $email; ?>" required>
        <label for="floatingEmail">Email</label>
      </div>

      <div class="form-floating mb-5">
        <input type="password" class="form-control"
          pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,50}"
          title="Must contain at least one number and one uppercase adn lowercase letter, and between 8 and 50 characters"
          id="floatingPassword" name="password" placeholder="Password" value="<?php echo $pw; ?>" required
        >
        <label for="floatingPassword">Password</label>
      </div>

      <div class="form-floating mb-5">
        <input type="password" class="form-control" pattern="{6,25}" id="floatingPasswordConfirm" name="passwordConfirm" placeholder="Password" required>
        <label for="floatingPasswordConfirm">Password confirmation</label>
      </div>

      <button type="submit" name="submit" class="btn btn-outline-success">Register</button>

    </form>
  </div>

  <?php
  include('inc/footer.php');
  ?>

<!-- toast -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
  <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
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