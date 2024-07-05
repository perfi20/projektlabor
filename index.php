<?php

session_start();
//error_reporting(0);

include_once('components/curl.php');
require_once('components/post.php');
require_once('components/pagination.php');

if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
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
      <a class="nav-item nav-link link-body-emphasis active" href="/posts/category/world">World</a>
      <a class="nav-item nav-link link-body-emphasis" href="/posts/category/u.s">U.S.</a>
      <a class="nav-item nav-link link-body-emphasis" href="/posts/category/technology">Technology</a>
      <a class="nav-item nav-link link-body-emphasis" href="/posts/category/design">Design</a>
      <a class="nav-item nav-link link-body-emphasis" href="/posts/category/culture">Culture</a>
      <a class="nav-item nav-link link-body-emphasis" href="/posts/category/business">Business</a>
      <a class="nav-item nav-link link-body-emphasis" href="/posts/category/politics">Politics</a>
      <a class="nav-item nav-link link-body-emphasis" href="/posts/category/opinion">Opinion</a>
      <a class="nav-item nav-link link-body-emphasis" href="/posts/category/science">Science</a>
      <a class="nav-item nav-link link-body-emphasis" href="/posts/category/health">Health</a>
      <a class="nav-item nav-link link-body-emphasis" href="/posts/category/style">Style</a>
      <a class="nav-item nav-link link-body-emphasis" href="/posts/category/travel">Travel</a>
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
if (isset($postByID) && $postID !== "") : 

  $ip = $_SERVER["REMOTE_ADDR"];

  $postfields = json_encode(['id' => $postByID, 'ip' => $ip, 'userID' => $_SESSION["userID"]]);
  $post = curl('posts', 'POST', $postfields, true);

  if ($post["success"] != true) {
    header('location: /');
  }

  // display post
  $posts = new Post(
    $post['created_at'],
    $post['title'],
    $post['cover'],
    $post['category'],
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
    $post['publisher'],
=======
    $post['username'],
>>>>>>> f7bb41a4ac79e8a78d2a24c1de5b630fd6e09fa1
=======
    $post['username'],
>>>>>>> f7bb41a4ac79e8a78d2a24c1de5b630fd6e09fa1
=======
    $post['username'],
>>>>>>> refs/remotes/origin/2024
    $post['content']
  );

  $posts->display();

?>

<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
    <a class="btn btn-outline-warning mb-4" href="/">Back</a>
=======
    <a class="btn btn-outline-light mb-4" href="/">Back</a>
>>>>>>> f7bb41a4ac79e8a78d2a24c1de5b630fd6e09fa1
=======
    <a class="btn btn-outline-light mb-4" href="/">Back</a>
>>>>>>> f7bb41a4ac79e8a78d2a24c1de5b630fd6e09fa1
=======
    <a class="btn btn-outline-light mb-4" href="/">Back</a>
>>>>>>> refs/remotes/origin/2024
    </div>

<?php endif;

// show posts from a sepcific user
if (isset($postsByUser) && $postsByUser !== "") :

  // pagination
  $page = isset($page) ? $page : 1;

  $postfields = json_encode(['user' => $postsByUser, 'page' => $page, 'limit' => 5]);
  $result = curl('posts', 'POST', $postfields, true);

  if (!$result["success"]) {
    header('location: /');
  }

  $content = $result["posts"];

?>
  <h3 class="pb-4 mb-4 fst-italic border-bottom">From <?php echo $result["publisher"]; ?></h3>
  <?php  foreach ($content as $post) : 

  $posts = new Post(
    $post['created_at'],
    $post['title'],
    $post['cover'],
    $post['category'],
    $post['username'],
    $post['content']
  );

  $posts->display();

  endforeach;

  // pagination
  $pagination = new Pagination($page, $postsByUser, '/posts/from/', $result['total_pages']);
  $pagination->display();

  ?>
  
</div>

<?php endif;

// show posts by category
if (isset($postsByCategory) && $postsByCategory !== "") :

  // pagination
  $page = isset($page) ? $page : 1;

  $postfields = json_encode(['category' => $postsByCategory, 'page' => $page, 'limit' => 3]);
  $result = curl('posts', 'POST', $postfields, true);

  if (!$result["success"]) {
    header('location: /');
  }

  $content = $result["posts"];

    foreach ($content as $post) : 

      $posts = new Post(
        $post['created_at'],
        $post['title'],
        $post['cover'],
        $post['category'],
        $post['username'],
        $post['content']
      );
      $posts->display();

    endforeach; 

    $pagination = new Pagination($page, $postsByCategory, '/posts/category/', $result['total_pages']);
    $pagination->display();
    
   ?>

  </div>

<?php endif;

// show posts by date - archive
if (isset($postsByYear) || isset($postsByMonth)) {

  $page = isset($page) ? $page : 1;

  $postfields = json_encode(['year' => $postsByYear, 'month' => $postsByMonth, 'page' => $page, 'limit' => 3]);
  $result = curl('posts', 'POST', $postfields, true);
  if (!$result["success"]) {
    header('location: /');
  }

  $content = $result["posts"];

  foreach ($content as $post) : 

    $posts = new Post(
      $post['created_at'],
      $post['title'],
      $post['cover'],
      $post['category'],
      $post['username'],
      $post['content']
    );
    $posts->display();

  endforeach;
  
  // pagination TODO: $postsByDate ???
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
  $pagination = new Pagination($page, $postsByDate, '/posts/date/'.$postsByYear.'/'.$postsByMonth, $result['total_pages']);
=======
  $pagination = new Pagination($page, $postsByDate, '/posts/date/', $result['total_pages']);
>>>>>>> f7bb41a4ac79e8a78d2a24c1de5b630fd6e09fa1
=======
  $pagination = new Pagination($page, $postsByDate, '/posts/date/', $result['total_pages']);
>>>>>>> f7bb41a4ac79e8a78d2a24c1de5b630fd6e09fa1
=======
  $pagination = new Pagination($page, $postsByDate, '/posts/date/', $result['total_pages']);
>>>>>>> refs/remotes/origin/2024
  $pagination->display();
  
  ?>

  </div>

<?php
}

// main page content - 3 random post
if (!isset($postByID) && !isset($postsByUser) && !isset($postsByCategory) && !isset($postsByYear) && !isset($postsByMonth)) : 
?>
    
  <h3 class="pb-4 mb-4 fst-italic border-bottom">
    From the Firehose
  </h3>

  <?php
  
    $postfields = json_encode(['main' => 'yes']);
    $result = curl('posts', 'POST', $postfields, true);

    $content = $result["posts"];

    foreach ($content as $post) : 

      $posts = new Post(
        $post['created_at'],
        $post['title'],
        $post['cover'],
        $post['category'],
        $post['username'],
        $post['content']
      );
      $posts->display();

    endforeach; ?>
  
</div>
    
<?php // end of main content 
endif;
?>

    <div class="col-md-4">
      <div class="position-sticky" style="top: 2rem;">
        <div class="p-4 mb-3 rounded">
          <h4 class="fst-italic">About</h4>
          <p class="mb-0">
            Circle is a university project where users can view and create blog posts in various themes.
            Feel free to explore and try out anything.
          </p>
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
include_once('inc/footer.php');
?>