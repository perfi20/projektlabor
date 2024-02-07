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
  $pw = validateInput($_POST['password']);
  $pwSecured = hash('sha256', $pw);

  $postfields = json_encode(['username' => $username, 'pw' => $pwSecured]);

  $result = curl('users', 'POST', $postfields);
 
  // login successful -> redirect to home page
  if($result->success == true) {

    $_SESSION['username'] = $result->username;
    $_SESSION['userID'] = $result->id;
    $_SESSION['email'] = $result->email;
    //$_SESSION['access_level'] = $result->access_level;
    // set admin access for demo purposes
    $_SESSION['access_level'] = 1;

    $_SESSION['token'] = $result->token;
    
    header('location: /');
  }

  // login toast
  $GLOBALS["toastFunction"] = "showToast('$result->success', '$result->message');";

}

?>

<div class="container position-absolute top-50 start-50 translate-middle">
<form action="/login" class="was-validated border border-secondary p-5 rounded" style="--bs-border-opacity: .5;" method="POST">
<h1>Login</h1>
  <div class="form-floating my-5">
    <input type="text" class="form-control bg-dark text-light"
      value="<?php echo isset($username) ? "$username" : ""; ?>" name="username"
      id="floatingUsername" placeholder="Username" required autofocus autocomplete="username">
    <label for="floatingUsername">Username</label>
  </div>
  <div class="form-floating mb-5">
    <input type="password" class="form-control bg-dark text-light" value=""name="password"
      id="floatingPassword" placeholder="Password" required autocomplete="current-password"
    >
    <label for="floatingPassword">Password</label>
  </div>
  <button type="submit" name="submit" class="btn btn-outline-success align-middle">Login</button>
</form>
</div>

<?php
include_once('inc/footer.php');
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