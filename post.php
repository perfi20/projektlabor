<?php
require "inc/config.php";
require "inc/header.php";
?>
<!DOCTYPE html>
<html lang="hu">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
  <title>Események</title>
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
          <li class="nav-item w-100">
            <a class="nav-link" aria-current="page" href="forum.php">Fórum</a>
          </li>
          <li class="nav-item w-100">
            <a class="nav-link" href="chat.php">Chat</a>
          </li>
          <li class="nav-item dropdown w-100">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Egyebek
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="inc/logout.php">Kijelentkezes</a></li>
              <?php
              if ($_SESSION['admin']) {
                echo '<li>
								<hr class="dropdown-divider">
							</li>
							<li><a class="dropdown-item" href="https://adminlabor.buzasgergo.hu">Uj oldal letrehozasa</a></li>';
              };
              ?>
              <!-- <li><a class="dropdown-item" href="#">Another action</a></li>
							<li>
								<hr class="dropdown-divider">
							</li>
							<li><a class="dropdown-item" href="#">Something else here</a></li> -->
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <div class="container">
    <div class="alert alert-info">
      <strong>Udvozollek <?php echo $_SESSION['knev']; ?>!</strong>
    </div>
    <?php
    $id = mysqli_real_escape_string($db, $_GET['id']);
    $query = "SELECT * FROM forum WHERE id = '$id'";
    $result = mysqli_query($db, $query);
    $post = mysqli_fetch_assoc($result);
    mysqli_close($db);
    ?>
    <div class="container mb-3 rounded bg-dark">
      <div class="text-center text-light">
        <h1><?php echo $post['title']; ?></h1>
        <h6 class="card-subtitle mb-2 text-muted"><?php echo $post['tags']; ?></h6>
        <small>Létrehozva <?php echo $post['published']; ?></small>
        <p class="mt-3"><?php echo $post['body']; ?></p>
      </div>
      <a class="btn btn-default bg-secondary text-light mb-4" href="home.php">Vissza</a>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>

</html>