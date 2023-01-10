<?php

require 'inc/config.php';
session_start();
if (!empty($_SESSION['email'])) {
  header('location: home.php');
} else {
  session_unset();
  session_destroy();
}
?>
<!DOCTYPE html>
<html lang="hu">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
  <title>Kör</title>
</head>

<body class="bg-secondary">
  <nav class="navbar navbar-expand-sm bg-light">
    <div class="container">
      <a class="navbar-brand" href="index.php"><i class="bi bi-circle"></i> Kör</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-sm-0">
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
  <div class="alert alert-info">
    <div class="container">
      <strong>Info!</strong> Szerkesztes alatt!
    </div>
  </div>
  <?php require "inc/event.php"; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>

</html>