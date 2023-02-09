<?php

require('inc/loggedInHeader.php');
?>

	<div class="container">
		<div class="alert alert-info">
			<strong>Udvozollek <?php echo $_SESSION['knev']; ?>!</strong> Home <a href="inc/logout.php" class="alert-link"> Kijelentkezes</a>
		</div>
	</div>
<?php
require('inc/event.php');
require('inc/footer.php');
?>