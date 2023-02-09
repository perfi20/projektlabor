<?php
require('inc/config.php');
require('inc/loggedInHeader.php');
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

<?php require('inc/footer.php'); ?>