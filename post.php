<?php

include('inc/loggedInHeader.php');

?>

<link href="https://fonts.googleapis.com/css?family=Playfair&#43;Display:700,900&amp;display=swap" rel="stylesheet">
<link href="forum.css" rel="stylesheet">

  <div class="alert alert-info">
    <strong>Udvozollek <?php echo $_SESSION['knev']; ?>!</strong>
  </div>

  <?php
    $id = $_GET["post"];
    $postfields = json_encode(['id' => $id]);
    $data = curl('posts', 'POST', $postfields);
    if (!$data->success == true) {
      //echo $id;
    }
    echo $data->id;
  ?>
<main class="container">

  <div class="row g-5">
    <div class="col-md-8">
      <?php echo $data->content; ?>
    </div>
  </div>

  <a class="btn btn-default bg-secondary text-light mb-4" href="forum.php">Back</a>

</main>

<?php include('inc/footer.php'); ?>