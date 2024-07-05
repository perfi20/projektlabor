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
    <link rel="stylesheet" href="/darkmode-toggle.css">
	<title>Circle</title>
  <base href="https://perfi.hu/">
</head>

<body>
 
<nav class="navbar bg-body-tertiary navbar-expand-sm sticky-top">
	<div class="container">
		<a class="navbar-brand" href="/"><i class="bi bi-circle"></i> Circle</a>
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav me-auto mb-2 mb-lg-0">

				<li class="nav-item w-100">
					<?php if (isset($_SESSION['access_level']) && $_SESSION['access_level'] >= 1) {
						echo '<a class="nav-link" href="/admin/statistics">Admin</a>';
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
          <span class="navbar-text" href="#"><?php echo $data->current_weather->temperature; ?> °C <img width="30" height="24" class="d-inline-block align-text-top" alt="Weather" src="/src/<?php echo $file ?>.svg"> </span>

          <!-- theme -->
          <?php include_once('components/theme.php'); ?>

		</div>

		<div class="dropdown">
      <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        <?php // show profile picture

          $id = $_SESSION["userID"];
          $path = 'src/'.$id;
          $files = scandir($path);

          if (!empty($files)) {
            foreach ($files as $file) {
              $filePath = $path .'/'. $file;
            }
            
          } else {
            // default profile picture
            $filePath = 'src/profile_pic.jpg';
          }
          
        ?>
        <img src="<?php echo $filePath; ?>" alt="" width="32" height="32" class="rounded-circle me-2">
        
        
        <strong><?php echo $_SESSION['username']; ?></strong>
      </a>
      <ul class="dropdown-menu dropdown-menu text-small shadow">
        <li><a class="dropdown-item" href="/user/posts/create">New post</a></li>
        <li><a class="dropdown-item" href="/user/posts">My posts</a></li>
        <li><a class="dropdown-item" href="/user/profile">Profile</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="/logout">Sign out</a></li>
      </ul>
      
    </div>

  </div>
	</div>
</nav>