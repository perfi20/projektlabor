<?php

require('components/curl.php');
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
	<div class="content">
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
								<li><a class="dropdown-item" href="inc/logout.php">Kijelentkezés</a></li>
								<?php
								if ($_SESSION['admin']) {
									echo '<li>
									<hr class="dropdown-divider">
								</li>
								<li><a class="dropdown-item" href="https://adminlabor.buzasgergo.hu">Uj oldal letrehozasa</a></li>';
								};
								?>
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

	<div class="container">
		<!-- admin panel list group -->
		<div class="container d-flex align-items-start">
			<div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
				<button
					class="btn btn-lg btn-light m-2 p-2 active" id="v-pills-events-tab"
					data-bs-toggle="pill"data-bs-target="#v-pills-events" type="button"
					role="tab" aria-controls="v-pills-events" aria-selected="true">Események
				</button>
				<button
					class="btn btn-lg btn-light m-2 p-2" id="v-pills-forum-tab"
					data-bs-toggle="pill"data-bs-target="#v-pills-forum" type="button"
					role="tab" aria-controls="v-pills-forum" aria-selected="false">Fórum
				</button>
				<button
					class="btn btn-lg btn-light m-2 p-2" id="v-pills-users-tab"
					data-bs-toggle="pill"data-bs-target="#v-pills-users"
					type="button" role="tab" aria-controls="v-pills-users" aria-selected="false">Felhasználók
				</button>
				<button
					class="btn btn-lg btn-light m-2 p-2" id="v-pills-statistics-tab"
					data-bs-toggle="pill"data-bs-target="#v-pills-statistics" type="button"
					role="tab" aria-controls="v-pills-statistics" aria-selected="false">Statisztikák
				</button>
				<button
					class="btn btn-lg btn-light m-2 p-2" id="v-pills-other-tab"
					data-bs-toggle="pill"data-bs-target="#v-pills-other" type="button"
					role="tab" aria-controls="v-pills-other" aria-selected="false">Egyéb
				</button>
			</div>
			
			<div class="container tab-content m-3" id="v-pills-tabContent">
					<!-- LIST POSTS -->
					<div class="tab-pane fade show active" id="v-pills-events" role="tabpanel" aria-labelledby="v-pills-events-tab" tabindex="0">
						<?php include('components/_posts.php'); ?>
					</div>

					<!-- TODO: FORUM -->
					<div class="tab-pane fade" id="v-pills-forum" role="tabpanel" aria-labelledby="v-pills-forum-tab" tabindex="1">
						Some placeholder content in a paragraph relating to "Profile". And some more content, used here just
						to pad out and fill this tab panel. In production, you would obviously have more real content here.
						And not just text. It could be anything, really. Text, images, forms.
					</div>

					<!-- LIST USERS -->
					<div class="tab-pane fade" id="v-pills-users" role="tabpanel" aria-labelledby="v-pills-users-tab" tabindex="2">
						<?php include('components/_users.php'); ?>
					</div>

					<!-- STATS -->
					<div class="tab-pane fade" id="v-pills-statistics" role="tabpanel" aria-labelledby="v-pills-statistics-tab" tabindex="3">
						<?php include('components/_stats.php'); ?>
					</div>

					<!-- TODO: OTHER -->
					<div class="tab-pane fade" id="v-pills-other" role="tabpanel" aria-labelledby="v-pills-other-tab" tabindex="3">
						Some placeholder content in a paragraph relating to "asd". And some more content, used here just
						to pad out and fill this tab panel. In production, you would obviously have more real content here.
						And not just text. It could be anything, really. Text, images, forms.
					</div>	
			</div>
		</div>
	</div>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>
</html>