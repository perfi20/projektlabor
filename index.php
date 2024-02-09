<?php

session_start();
//error_reporting(0);

include_once('components/curl.php');

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
  $result = curl('posts', 'POST', $postfields, true);

  if ($result["success"] != true) {
    header('location: /');
  }

  $title = $result["title"];
  $category = $result["category"];
  $cover = $result["cover"];
  $publisher = $result["publisher"];
  $date = date("M d, Y", strtotime($result["created_at"]));
  $content = $result["content"];

  ?>

  <?php // display post
    echo '
      <article class="blog-post">
      <h2 class="display-5 link-body-emphasis mb-1">'.$title.'</h2>
      <img class="rounded img-fluid" src="'.$cover.'">
      <div class="position-relative">
        <p class="blog-post-meta postition-absolute top-0 start-0"><a href="/posts/category/'.$category.'"><strong class="d-inline-block mb-2 text-primary-emphasis">'.$category.'</strong></a></p>
        <p class="position-absolute top-0 end-0">'.$date.' by <a href="/posts/from/'.$publisher.'">'.$publisher.'</a></p>
      </div>
      '.$content.'
      </article>
    '; 
  ?>

    <a class="btn btn-outline-light mb-4" href="/">Back</a>
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

  $date = date("M d, Y", strtotime($post["created_at"]));

  echo '
  <article class="blog-post">
  <h2 class="display-5 link-body-emphasis mb-1">'.$post["title"].'</h2>
  <img class="rounded img-fluid" src="'.$post["cover"].'">
  <div class="position-relative">
    <p class="blog-post-meta postition-absolute top-0 start-0"><a href="/posts/category/'.$post["category"].'"><strong class="d-inline-block mb-2 text-primary-emphasis">'.$post["category"].'</strong></a></p>
    <p class="position-absolute top-0 end-0">'.$date.' by <a href="/posts/from/'.$post["username"].'">'.$post["username"].'</a></p>
  </div>
  '.$post["content"].'
  </article>
  ';

  endforeach; ?>

  <!-- pagination -->
  <nav aria-label="pagination">
      <ul class="pagination justify-content-center">
        <li class="page-item  <?php echo (($page - 1) <= 0 ) ? "disabled" : ""; ?>">
          <a class="page-link" href="/posts/from/<?php echo $postsByUser; ?>/page/<?php echo $page - 1 ; ?>">Previous</a>
        </li>
        <?php for ($pages=1;$pages<=$result["total_pages"];$pages++) : ?>
          <li class="page-item <?php echo ($pages == $page) ? "active" : ""; ?>"><a class="page-link"
            href="/posts/from/<?php echo $postsByUser; ?>/page/<?php echo $pages; ?>"><?php echo $pages; ?></a>
          </li>
        <?php endfor; ?>
        <li class="page-item <?php echo (($page + 1) > $result["total_pages"] ) ? "disabled" : ""; ?>">
          <a class="page-link" href="/posts/from/<?php echo $postsByUser; ?>/page/<?php echo $page + 1 ; ?>">Next</a>
        </li>
      </ul>
    </nav>

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

      $date = date("M d, Y", strtotime($post["created_at"]));

      echo '
      <article class="blog-post">
      <h2 class="display-5 link-body-emphasis mb-1">'.$post["title"].'</h2>
      <img class="rounded img-fluid" src="'.$post["cover"].'">
      <div class="position-relative">
        <p class="blog-post-meta postition-absolute top-0 start-0"><a href="/posts/category/'.$post["category"].'"><strong class="d-inline-block mb-2 text-primary-emphasis">'.$post["category"].'</strong></a></p>
        <p class="position-absolute top-0 end-0">'.$date.' by <a href="/posts/from/'.$post["username"].'">'.$post["username"].'</a></p>
      </div>
      '.$post["content"].'
      </article>
      ';

    endforeach; ?>
    
    <!-- pagination -->
    <nav aria-label="pagination">
      <ul class="pagination justify-content-center">
        <li class="page-item  <?php echo (($page - 1) <= 0 ) ? "disabled" : ""; ?>">
          <a class="page-link" href="/posts/category/<?php echo $postsByCategory; ?>/page/<?php echo $page - 1 ; ?>">Previous</a>
        </li>
        <?php for ($pages=1;$pages<=$result["total_pages"];$pages++) : ?>
          <li class="page-item <?php echo ($pages == $page) ? "active" : ""; ?>"><a class="page-link"
            href="/posts/category/<?php echo $postsByCategory; ?>/page/<?php echo $pages; ?>"><?php echo $pages; ?></a>
          </li>
        <?php endfor; ?>
        <li class="page-item <?php echo (($page + 1) > $result["total_pages"] ) ? "disabled" : ""; ?>">
          <a class="page-link" href="/posts/category/<?php echo $postsByCategory; ?>/page/<?php echo $page + 1 ; ?>">Next</a>
        </li>
      </ul>
    </nav>

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

    $date = date("M d, Y", strtotime($post["created_at"]));

    echo '
    <article class="blog-post">
    <h2 class="display-5 link-body-emphasis mb-1">'.$post["title"].'</h2>
    <img class="rounded img-fluid" src="'.$post["cover"].'">
    <div class="position-relative">
        <p class="blog-post-meta postition-absolute top-0 start-0"><a href="/posts/category/'.$post["category"].'"><strong class="d-inline-block mb-2 text-primary-emphasis">'.$post["category"].'</strong></a></p>
        <p class="position-absolute top-0 end-0">'.$date.' by <a href="/posts/from/'.$post["username"].'">'.$post["username"].'</a></p>
    </div>
    '.$post["content"].'
    </article>
    ';

  endforeach;?>

  <!-- pagination -->
    <nav aria-label="pagination">
      <ul class="pagination justify-content-center">
        <li class="page-item  <?php echo (($page - 1) <= 0 ) ? "disabled" : ""; ?>">
          <a class="page-link" href="/posts/date/<?php echo $postsByYear; ?>/<?php echo $postsByMonth; ?>/page/<?php echo $page - 1 ; ?>">Previous</a>
        </li>
        <?php for ($pages=1;$pages<=$result["total_pages"];$pages++) : ?>
          <li class="page-item <?php echo ($pages == $page) ? "active" : ""; ?>"><a class="page-link"
            href="/posts/date/<?php echo $postsByYear; ?>/<?php echo $postsByMonth; ?>/page/<?php echo $pages; ?>"><?php echo $pages; ?></a>
          </li>
        <?php endfor; ?>
        <li class="page-item <?php echo (($page + 1) > $result["total_pages"] ) ? "disabled" : ""; ?>">
          <a class="page-link" href="/posts/date/<?php echo $postsByYear; ?>/<?php echo $postsByMonth; ?>/page/<?php echo $page + 1 ; ?>">Next</a>
        </li>
      </ul>
    </nav>

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

      $date = date("M d, Y", strtotime($post["created_at"]));

      echo '
      <article class="blog-post">
      <h2 class="display-5 link-body-emphasis mb-1">'.$post["title"].'</h2>
      <img class="rounded img-fluid" src="'.$post["cover"].'">
      <div class="position-relative">
        <p class="blog-post-meta postition-absolute top-0 start-0"><a href="/posts/category/'.$post["category"].'"><strong class="d-inline-block mb-2 text-primary-emphasis">'.$post["category"].'</strong></a></p>
        <p class="position-absolute top-0 end-0">'.$date.' by <a href="/posts/from/'.$post["username"].'">'.$post["username"].'</a></p>
      </div>
      
      '.$post["content"].'
      </article>
      ';

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