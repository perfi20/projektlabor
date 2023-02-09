<?php
session_start(); 
error_reporting(0);
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

<body class="bg-dark text-light">

	<nav class="navbar bg-body-tertiary navbar-expand-sm sticky-top" data-bs-theme="dark">
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
						</ul>
					</li>
					<li class="nav-item w-100">
						<?php if ($_SESSION['admin'] == 1) {
							echo '<a class="nav-link" href="admin.php">Admin</a>';
						} ?>
						
					</li>
				</ul>
			</div>
		</div>
	</nav>