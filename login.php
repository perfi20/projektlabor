<?php
require('components/curl.php');
require('inc/notLoggedInHeader.php');

if (!empty($_SESSION['email'])) {
  header('location: home.php');
} else {
  session_unset();
  session_destroy();
}

if (isset($_POST['submit'])){

  $email = $_POST['email'];
  $pwraw = $_POST['jelszo'];
  $pw = hash('sha256', $pwraw);

  $postfields = json_encode(['email' => $email, 'jelszo' => $pw]);

  $data = curl('users', 'POST', $postfields);


  if($data->err_code == 2){
    $email_error = "Hibás e-mail!";
  } else if ($data->err_code == 3) {
    $jelszo_error = "Hibás jelszó!";
  } else {
    session_start();
    $_SESSION['email'] = $data->email;
    $_SESSION['knev'] = $data->knev;
    $_SESSION['admin'] = $data->admin;
    $_SESSION['token'] = $data->token;
    header('location: home.php');
  }
}

?>

<div class="container">
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" class="was-validated" method="POST">
  <label class="form-label mt-3" for="email">Email:</label>
  <div class="mb-3">
    <input type="email" class="form-control" placeholder="Email" value="<?php echo $email; ?>" name="email" required>
    <?php if (isset($email_error)) : ?>
      <span class="text-light bg-danger"><?php echo $email_error; ?></span>
    <?php endif ?>
  </div>
  <div class="mb-3">
    <label class="form-label" for="pwd">Jelszó:</label>
    <input type="password" class="form-control" placeholder="Jelszó" value="<?php echo $jelszo; ?>" name="jelszo" required>
    <?php if (isset($jelszo_error)) : ?>
      <span class="text-light bg-danger mt-3"><?php echo $jelszo_error; ?></span>
    <?php endif ?>
  </div>
  <button type="submit" name="submit" class="btn btn-outline-light">Bejelentkezés</button>
</form>
</div>

<?php
require('inc/footer.php');
?>