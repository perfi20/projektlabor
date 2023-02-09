<?php

require('inc/notLoggedInHeader.php');

if (!empty($_SESSION['email'])) {
  header('location: home.php');
} else {
  session_unset();
  session_destroy();
}

?>

<div class="alert alert-info">
  <div class="container">
    <strong>Info!</strong> Szerkesztes alatt!
  </div>
</div>

<h1>TODO: bemutatkozó rész</h1>

<?php
require('inc/event.php');
require('inc/footer.php');
?>