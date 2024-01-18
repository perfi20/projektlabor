<?php

include('inc/config.php');
include_once('components/curl.php');
include('inc/loggedInHeader.php');

?>

<div class="container">
	<div class="alert alert-info">
		<strong>Info!</strong> Home <a href="inc/logout.php" class="alert-link"> Kijelentkezes</a>
	</div>
	<?php
	include "inc/search.php";
	?>
</div>
<div class="container mt-1">
	<?php
	include "inc/chat.php";
	?>
</div>

<?php include('inc/footer.php'); ?>
