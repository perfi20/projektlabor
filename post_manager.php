<?php

session_start();

require_once('components/curl.php');
require_once('inc/loggedInHeader.php');
require_once('components/validateInput.php');

if (empty($_SESSION['username'])) {
    header('location: index.php');
  }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="./js/postManager.js"></script>
    <link rel="stylesheet" href="./css/postManager.css">
</head>
<body>
    <?php include_once('./components/_create_post.php'); ?>
    
</body>
</html>

<?php
require_once('inc/footer.php');