<?php

  $data = curl('posts', 'GET', NULL, true);

  foreach ($data as $post) : ?>
    <div class="container">
      <div class="content">
        <div class="card text-center border border-2 shadow mb-4 rounded">
          <!-- <div class="card-header">card header</div> -->
          <div class="card-body bg-dark">
            <h2 class="card-title text-light"><?php echo $post['title']; ?></h2>
            <h6 class="card-subtitle mb-2 text-muted"><small>Létrehozva <?php echo $post['published']; ?></small></h6>
            <p class="card-text mx-auto col-8 text-truncate text-light"><?php echo $post['body']; ?></p>
            <br>
            <a class="card-link btn btn-secondary rounded-pill mb-2" href="/post.php?id=<?php echo $post['id']; ?>">Olvasd tovább</a>
            <div class="card-footer text-muted"><?php echo round((time() - strtotime($post['published'])) / (60 * 60 * 24)); ?> napja</div>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach;?>