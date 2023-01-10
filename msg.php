<?php
require "inc/config.php";
require "inc/header.php";
?>
<?php
$knev = $_SESSION["knev"];
$guest = $_SESSION["guest"];
$query = "SELECT * FROM chat where msgid in (CONCAT('$knev','$guest'),CONCAT('$guest','$knev'))";
$result = mysqli_query($db, $query);
$chat = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_free_result($result);

if (isset($_POST['messageSubmit']) && isset($_POST['message']) && $_POST['message'] != "") {
  $message = $_POST['message'];
  $msgid = $knev . $guest;
  $sql = "INSERT INTO chat (msgid, msgfrom, msgto, msg) VALUES ('$msgid', '$knev', '$guest', '$message')";
  mysqli_query($db, $sql);
  header('Refresh:0');
}

?>
<!DOCTYPE html>
<html lang="hu">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
  <title>Kör</title>
</head>

<body class="bg-secondary">
  <nav class="navbar navbar-expand bg-light">
    <div class="container">
      <a class="navbar-brand" href="index.php"><i class="bi bi-circle"></i> Kör</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-sm-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="chat.php">Vissza</a>
          </li>

        </ul>
      </div>
    </div>
  </nav>
  <div class="alert alert-info">
    <div class="container">
      <strong>Info!</strong> Szerkesztes alatt!
    </div>
  </div>
  <div class="container">

    <?php foreach ($chat as $for) : ?>
        <?php echo $for['msgfrom']; ?>:
        <h1><?php echo $for['msg']; ?></h1>
      <br>
    <?php endforeach; ?>
  </div>
  <?php
  include "inc/footer.php";
  ?>
<!-- Üzenet küldés -->
<div class="container mt-1">
  <form action="msg.php" method="POST">
    <div class="input-group">
      <input class="form-control" placeholder="Ide írja az üzenetét" name="message" id="message">
      <button class="btn btn-outline-light" type="submit" name="messageSubmit">Üzenet küldése</button>
    </div>
  </form>
</div>

</body>

</html>