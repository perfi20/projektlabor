<?php
$myname = $_SESSION['knev'];
$query = "SELECT knev FROM labor where knev!='$myname' order by knev";
$result = mysqli_query($db, $query);
$knev = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_free_result($result);
$searchname = mysqli_real_escape_string($db, $_POST['knev']);
$ok = true;
if (isset($_POST["search"])) {
  if (isset($_POST['knev'])) {
    $sql = "SELECT knev FROM labor WHERE knev='$searchname'";
    $res = mysqli_query($db, $sql);
    if ((mysqli_num_rows($res) < 1)) {
      $jelszo_error = "Nem regisztrált Felhasználó!";
      $ok = false;
    }
  }
  if ($ok) {
    $_SESSION['guest'] = $searchname;
    header('location: msg.php');
  }
}
?>
<form action="chat.php" method="POST">
  <div class="input-group">
    <input class="form-control" list="datalistOptions" id="exampleDataList" placeholder="Felhasználó keresése" name="knev">
    <datalist id="datalistOptions">
      <?php foreach ($knev as $post) : ?>
        <option value="<?php echo $post['knev']; ?>">
        <?php endforeach; ?>
    </datalist>
    <button class="btn btn-outline-light" type="submit" name="search" id="button-addon2">msg</button>
  </div>
  
  <?php if (isset($jelszo_error)) : ?>
    <span class="text-light bg-danger"><?php echo $jelszo_error; ?></span>
  <?php endif ?>
</form>