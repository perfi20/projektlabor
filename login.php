<?php
require('components/curl.php');
require "inc/config.php";
session_start();
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

<!DOCTYPE html>
<html lang="hu">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
  <title>Játszóház</title>
</head>

<body class="bg-secondary">
  <nav class="navbar navbar-expand-lg bg-light">
    <div class="container">
      <a class="navbar-brand" href="index.php"><i class="bi bi-circle"></i> Kör</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="login.php">Bejelentkezes</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="register.php">Regisztracio</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <div class="container">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" class="was-validated" method="POST">
      <label class="form-label text-light mt-3" for="email">Email:</label>
      <div class="mb-3">
        <input type="email" class="form-control" placeholder="Email" value="<?php echo $email; ?>" name="email" required>
        <?php if (isset($email_error)) : ?>
          <span class="text-light bg-danger"><?php echo $email_error; ?></span>
        <?php endif ?>
      </div>
      <div class="mb-3">
        <label class="form-label text-light" for="pwd">Jelszó:</label>
        <input type="password" class="form-control" placeholder="Jelszó" value="<?php echo $jelszo; ?>" name="jelszo" required>
        <?php if (isset($jelszo_error)) : ?>
          <span class="text-light bg-danger mt-3"><?php echo $jelszo_error; ?></span>
        <?php endif ?>
      </div>
      <button type="submit" name="submit" class="btn btn-outline-light">Bejelentkezés</button>
    </form>
  </div>
  <?php
  include "inc/footer.php";
  ?>
</body>

</html>