<?php

session_start();
error_reporting(0);

include_once('components/curl.php');

if (isset($_SESSION['username']) && $_SESSION['username'] != "") {
  include('inc/loggedInHeader.php');
} else {
  include('inc/notLoggedInHeader.php');
}

?>

<link href="https://fonts.googleapis.com/css?family=Playfair&#43;Display:700,900&amp;display=swap" rel="stylesheet">
<link href="forum.css" rel="stylesheet">

<main class="container">

<div class="nav-scroller py-1 mb-3 border-bottom">
    <nav class="nav nav-underline justify-content-between">
      <a class="nav-item nav-link link-body-emphasis active" href="index.php?category=world">World</a>
      <a class="nav-item nav-link link-body-emphasis" href="index.php?category=u.S">U.S.</a>
      <a class="nav-item nav-link link-body-emphasis" href="index.php?category=technology">Technology</a>
      <a class="nav-item nav-link link-body-emphasis" href="index.php?category=design">Design</a>
      <a class="nav-item nav-link link-body-emphasis" href="index.php?category=culture">Culture</a>
      <a class="nav-item nav-link link-body-emphasis" href="index.php?category=business">Business</a>
      <a class="nav-item nav-link link-body-emphasis" href="index.php?category=politics">Politics</a>
      <a class="nav-item nav-link link-body-emphasis" href="index.php?category=opinion">Opinion</a>
      <a class="nav-item nav-link link-body-emphasis" href="index.php?category=science">Science</a>
      <a class="nav-item nav-link link-body-emphasis" href="index.php?category=health">Health</a>
      <a class="nav-item nav-link link-body-emphasis" href="index.php?category=style">Style</a>
      <a class="nav-item nav-link link-body-emphasis" href="index.php?category=travel">Travel</a>
    </nav>
  </div>

  <?php 
   // main content
  
  // display featured segment 
  include_once("components/_featured_posts.php"); 
  ?>

  <div class="row g-5">
    <div class="col-md-8">

<?php

// show specific post by id
if (isset($_GET["post"]) && $_GET["post"] !== "") : 

  $id = $_GET['post'];
  $ip = $_SERVER["REMOTE_ADDR"];

  $postfields = json_encode(['id' => $id, 'ip' => $ip, 'userID' => $_SESSION["userID"]]);
  $result = curl('posts', 'POST', $postfields, true);

  if (!$result["success"]) {
    header('location: index.php');
  }

  ?>

    <?php echo $result["content"]; ?>

    <a class="btn btn-default bg-secondary text-light mb-4" href="index.php">Back</a>
    </div>

<?php endif;

// show posts from a sepcific user
if (isset($_GET["user"]) && $_GET["user"] !== "") :

  $user = $_GET["user"];
  $postfields = json_encode(['user' => $user]);
  $result = curl('posts', 'POST', $postfields, true);

  if (!$result["success"]) {
    header('location: index.php');
  }

  $content = $result["posts"];

?>
  <h3 class="pb-4 mb-4 fst-italic border-bottom">From <?php echo $result["publisher"]; ?></h3>
  <?php  foreach ($content as $post) : 
    foreach ($post as $key => $value) :
      if ($key === "content"){
        echo $value;
      }
    ?>
  <?php endforeach;
  endforeach; ?>
</div>

<?php endif;

if (isset($_GET["category"]) && $_GET["category"] !== "") :

  $category = $_GET["category"];
  $postfields = json_encode(['category' => $category]);
  $result = curl('posts', 'POST', $postfields, true);

  if (!$result["success"]) {
    //header('location: forum.php');
    echo $result["success"];
  }

  $content = $result["posts"];

    foreach ($content as $post) : 
      foreach ($post as $key => $value) :
        if ($key === "content"){
          echo $value;
        }
      ?>
      <?php endforeach;
    endforeach; ?>
  </div>

<?php endif;

// main page content - 3 random post
if (!isset($_GET['post']) && !isset($_GET['user']) && !isset($_GET['category'])) : 
?>
    
  <h3 class="pb-4 mb-4 fst-italic border-bottom">
    From the Firehose
  </h3>

  <?php
  
    $postfields = json_encode(['main' => 'yes']);
    $result = curl('posts', 'POST', $postfields, true);

    $content = $result["posts"];

    foreach ($content as $post) : 
      foreach ($post as $key => $value) :
        if ($key === "content"){
          echo $value;
        }
      ?>
      <?php endforeach;
    endforeach; ?>
  
</div>
    
<?php // end of main content 
endif;
?>

    <div class="col-md-4">
      <div class="position-sticky" style="top: 2rem;">
        <div class="p-4 mb-3 rounded">
          <h4 class="fst-italic">About</h4>
          <p class="mb-0">Customize this section to tell your visitors a little bit about your publication, writers, content, or something else entirely. Totally up to you.</p>
        </div>

        <?php
        
        // display recent posts
        include_once('components/_recent_posts.php');

        // display archives
        include_once('components/_archives.php');
        
        ?>

        <div class="p-4">
          <h4 class="fst-italic">Elsewhere</h4>
          <ol class="list-unstyled">
          <li><a href="https://github.com/perfi20/projektlabor" target="_blank">GitHub</a></li>
          </ol>
        </div>
      </div>
    </div>
  </div>

</main>

<?php
//include_once('inc/event.php');
include_once('inc/footer.php');
?>