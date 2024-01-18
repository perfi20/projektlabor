<?php

include_once('components/curl.php');
include('inc/notLoggedInHeader.php');
include_once('components/validateInput.php');

if (!empty($_COOKIE['email'])) {
  header('location: index.php');
}

// initializing variables
$username = null;
$email = null;
$pw = null;

if (isset($_POST['submit'])){

  $username = validateInput($_POST['username']);
  $email = validateInput($_POST['email']);
  $pw = validateInput($_POST['pw']);
  $pwSecured = hash('sha256', $pw);

  $postfields = json_encode(['username' => $username, 'email' => $email, 'pw' => $pwSecured]);

  $data = curl('users', 'PUT', $postfields);

  // TODO: API ADJA VISSZA AZ ERRORT, CSAK SIMÁN ÍRJA KI GECI
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
      $_SESSION['username'] = $username;
      header('location: index.php');
  }
}

?>

  <div class="container position-absolute top-50 start-50 translate-middle">
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="was-validated border border-secondary p-5 rounded" style="--bs-border-opacity: .5;" method="POST">
        <h1>Regisztráció</h1>
      <div class="form-floating my-5">
        <input type="text" class="form-control bg-dark text-light" pattern="{0,25}" title="Kérem adja meg a Felhasználónevét, maximum 25 karakter!" id="floatingUsername" name="username" placeholder="Keresztnev" value="<?php echo $username; ?>" required>
        <label for="floatingUsername">Felhasználónév</label>
        <?php if (isset($knev_error)) : ?>
          <span class="text-light bg-danger"><?php echo $knev_error; ?></span>
        <?php endif ?>
      </div>
      <div class="form-floating mb-5">
        <input type="email" class="form-control bg-dark text-light" pattern="{0,25}" title="Kérem adja meg a E-mail címét, maximum 25 karakter!" id="floatingEmail" name="email" placeholder="name@example.com" value="<?php echo $email; ?>" required>
        <label for="floatingEmail">Email cím</label>
        <?php if (isset($email_error)) : ?>
          <span class="text-light bg-danger"><?php echo $email_error; ?></span>
        <?php endif ?>
      </div>
      <div class="form-floating mb-5">
        <input type="password" class="form-control bg-dark text-light" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,25}" title="Tartalmaznia kell: 1 kis és nagybetüt és 1 számot illetve minimum 6 maximum 25 karakternek kell lennie!" id="floatingPassword" name="pw" placeholder="Jelszó" value="<?php echo $pw; ?>" required>
        <label for="floatingPassword">Jelszó</label>
      </div>
      <div class="form-floating mb-5">
        <input type="password" class="form-control bg-dark text-light" pattern="{6,25}" id="floatingPassword" name="pwAgain" placeholder="Password" required>
        <label for="floatingPassword">Jelszó megerősítése</label>
        <?php if (isset($jelszo2_error)) : ?>
          <span class="text-light bg-danger"><?php echo $jelszo2_error; ?></span>
        <?php endif ?>
      </div>
      <button type="submit" name="submit" class="btn btn-outline-success">Regisztráció</button>
    </form>
  </div>

  <?php
  include('inc/footer.php');
  ?>
</body>

</html>