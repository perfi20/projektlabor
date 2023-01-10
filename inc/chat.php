<?php
  // new message sent
  if (isset($_POST['submit'])) {

    $msgto = $_POST['msgto'];
    $msgfrom = $_SESSION['knev'];
    $msgid = $msgfrom . $msgto;
    $msg = $_POST['msg'];

    $postfields = json_encode([
      'msgid' => $msgid,
      'msgfrom' => $msgfrom,
      'msgto' => $msgto,
      'msg' => $msg
      ]);

    $data = curl('chat', 'PUT', $postfields);

    if ($data->status == 1) {
      header('Refresh:0');
    }
  }

?>


<!-- chat -->
<div class="container">
  <div class="container d-flex align-items-start">
    <!-- pills -->
    <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
      <?php
        $index = 0;
        $postfields = json_encode(['knev' => $_SESSION['knev']]);
        $chat = curl('chat', 'POST', $postfields, true);

        foreach ($chat as $pills) : ?>
      <button
        class="btn btn-lg btn-light m-2 p-2" id="v-pills-<?php echo $pills['msgto']; ?>-tab"
        data-bs-toggle="pill"data-bs-target="#v-pills-<?php echo $pills['msgto']; ?>" type="button"
        role="tab" aria-controls="v-pills-<?php echo $pills['msgto']; ?>" aria-selected="false"><?php echo $pills['msgto']; ?>
      </button>
      <?php endforeach; ?>
    </div>

    <!-- pill contents -->
    <div class="container tab-content m-3" id="v-pills-tabContent">
    <?php foreach ($chat as $content) : 
      $index++;
    ?>
      <div class="tab-pane fade" id="v-pills-<?php echo $content['msgto']; ?>" role="tabpanel" aria-labelledby="v-pills-<?php echo $content['msgto']; ?>-tab" tabindex="<?php echo $index; ?>">
          <!-- content -->
        <?php
          $msgs = curl("chat=".$_SESSION['knev']."&to=".$content['msgto'], 'GET', NULL, true);
          foreach ($msgs as $msg) :
        ?>
          <?php echo $msg['msgfrom']; ?>
          <?php echo $msg['msgDate']; ?>
          <h1><?php echo $msg['msg']; ?></h1>
          <br>
        <?php endforeach; ?>
        <!-- üzenet írás -->
        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
            <input type="hidden" class="form-text text-light" name="msgto" id="msgto" value="<?php echo $content['msgto']; ?>">
            <div class="form-floating mb-3">
            <input type="text" name="msg" id="msg" class="form-control" placeholder="üzenet">
            <label class="form-label">Üzenet írása...</label>
            </div>
            <input type="submit" name="submit" class="btn btn-outline-light" value="küldés">
        </form>
      </div>
      <?php endforeach; ?>	
    </div>
</div>