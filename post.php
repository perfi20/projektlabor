<?php

require('inc/loggedInHeader.php');

?>

<div class="container">
  <div class="alert alert-info">
    <strong>Udvozollek <?php echo $_SESSION['knev']; ?>!</strong>
  </div>

  <?php
    $id = $_GET['id'];
    $postfields = json_encode(['id' => $id]);
    $data = curl('posts', 'POST', $postfields);
  ?>

  <div class="container mb-3 rounded bg-dark">
    <div class="text-center text-light">
      <h1><?php echo $data['title']; ?></h1>
      <small>Létrehozva <?php echo $data['published']; ?></small>
      <p class="mt-3"><?php echo $data['body']; ?></p>
    </div>
    <a class="btn btn-default bg-secondary text-light mb-4" href="home.php">Vissza</a>
  </div>
</div>

<?php require('inc/footer.php'); ?>