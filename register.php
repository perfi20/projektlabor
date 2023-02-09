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

  $knev = $_POST['knev'];
  $email = $_POST['email'];
  $pwraw = $_POST['jelszo2'];
  $pw = hash('sha256', $pwraw);

  $postfields = json_encode(['knev' => $knev, 'email' => $email, 'jelszo' => $pw]);

  $data = curl('users', 'PUT', $postfields);

  if($data->status == 'username_and_email_exists'){
    $knev_error = "Foglalt felhasználónév!";
    $email_error = "Regisztrált E-mail cím!";
  } else if ($data->status == 'username_exists') {
    $knev_error = "Foglalt felhasználónév!";
  } else if ($data->status == 'email_exists') {
    $email_error = "Regisztrált E-mail cím!";
  } else {
    session_start();
      $_SESSION['email'] = $email;
      $_SESSION['knev'] = $knev;
      header('location: home.php');
  }
}

?>

  <div class="container">
    <form action="register.php" class="was-validated" method="POST">
      <div class="form-floating my-3">
        <input type="text" class="form-control" pattern="{0,25}" title="Kérem adja meg a Felhasználónevét, maximum 25 karakter!" id="floatingKnev" name="knev" placeholder="Keresztnev" vvalue="<?php echo $knev; ?>" required>
        <label for="floatingKnev">Felhasználónév</label>
        <?php if (isset($knev_error)) : ?>
          <span class="text-light bg-danger"><?php echo $knev_error; ?></span>
        <?php endif ?>
      </div>
      <div class="form-floating mb-3">
        <input type="email" class="form-control" pattern="{0,25}" title="Kérem adja meg a E-mail címét, maximum 25 karakter!" id="floatingEmail" name="email" placeholder="name@example.com" value="<?php echo $email; ?>" required>
        <label for="floatingEmail">Email cím</label>
        <?php if (isset($email_error)) : ?>
          <span class="text-light bg-danger"><?php echo $email_error; ?></span>
        <?php endif ?>
      </div>
      <div class="form-floating mb-3">
        <input type="password" class="form-control" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,25}" title="Tartalmaznia kell: 1 kis és nagybetüt és 1 számot illetve minimum 6 maximum 25 karakternek kell lennie!" id="floatingPassword" name="jelszo1" placeholder="Jelszó" value="<?php echo $jelszo1; ?>" required>
        <label for="floatingPassword">Jelszó</label>
      </div>
      <div class="form-floating mb-3">
        <input type="password" class="form-control" pattern="{6,25}" id="floatingPassword" name="jelszo2" placeholder="Password" required>
        <label for="floatingPassword">Jelszó megerősítése</label>
        <?php if (isset($jelszo2_error)) : ?>
          <span class="text-light bg-danger"><?php echo $jelszo2_error; ?></span>
        <?php endif ?>
      </div>
      <button type="submit" name="submit" class="btn btn-outline-light">Regisztracio</button>
    </form>
  </div>
  <?php
  require('inc/footer.php');
  ?>
</body>

</html>