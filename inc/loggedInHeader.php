<?php
include_once('./components/curl.php');
?>

<!DOCTYPE html>
<html lang="hu">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
	<title>Circle</title>
</head>

<body class="bg-dark text-light">

<nav class="navbar bg-body-tertiary navbar-expand-sm sticky-top" data-bs-theme="dark">
	<div class="container">
		<a class="navbar-brand" href="./index.php"><i class="bi bi-circle"></i> Circle</a>
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav me-auto mb-2 mb-lg-0">
				<li class="nav-item w-100">
					<a class="nav-link" href="./chat.php">Chat</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#">About</a>
				</li>
				<li class="nav-item w-100">
					<?php if (isset($_SESSION['access_level']) && $_SESSION['access_level'] >= 1) {
						echo '<a class="nav-link" href="admin.php">Admin</a>';
					} ?>
					
				</li>
			</ul>
			<?php // WEATHER

            $data = weather();
            $weatherCode = $data->current_weather->weathercode;

            $latitude = 47.1875;
            $longitude = 18.4375;
            $now = time();

            $sun = date_sun_info ( $now, $longitude, $latitude);
            $light = $sun['civil_twilight_begin'];
            $dark = $sun['civil_twilight_end'];
            
            if (($now > $light && $now < $dark)) {
              $night = 0;
            } else {
              $night = 1;
            }

            // no day/night
            if ($weatherCode == 3 || $weatherCode == 45 || $weatherCode == 48 || $weatherCode == 51 || $weatherCode == 53 || $weatherCode == 55 || $weatherCode == 56 || $weatherCode == 57) {
              $night = "";
            }

            // specifics
            if ($weatherCode == 80) $weatherCode = 61;
            if ($weatherCode == 81) $weatherCode = 63;
            if ($weatherCode == 82) $weatherCode = 65;
            if ($weatherCode == 85) $weatherCode = 73;
            if ($weatherCode == 86) $weatherCode = 75;
            if ($weatherCode == 96 || $weatherCode == 99) {
              $weatherCode = 9;
              $night = "";
            }

            $file = $weatherCode . $night;

          ?>
          <span class="navbar-text" href="#"><?php echo $data->current_weather->temperature; ?> °C <img width="30" height="24" class="d-inline-block align-text-top" alt="Felhős" src="./src/<?php echo $file ?>.svg"> </span>
		</div>

		<div class="dropdown">
      <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        <img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2">
        <strong><?php echo $_SESSION['username']; ?></strong>
      </a>
      <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
        <li><a class="dropdown-item" href="./post_manager.php?new">New post</a></li>
        <li><a class="dropdown-item" href="./post_manager.php">My posts</a></li>
        <li><a class="dropdown-item" href="#">Profile</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="./inc/logout.php">Sign out</a></li>
      </ul>
	  
    </div>

  </div>
	</div>
</nav>