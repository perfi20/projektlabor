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
      <a class="nav-item nav-link link-body-emphasis active" href="index.php?category=World">World</a>
      <a class="nav-item nav-link link-body-emphasis" href="index.php?category=U.S">U.S.</a>
      <a class="nav-item nav-link link-body-emphasis" href="index.php?category=Technology">Technology</a>
      <a class="nav-item nav-link link-body-emphasis" href="index.php?category=Design">Design</a>
      <a class="nav-item nav-link link-body-emphasis" href="index.php?category=Culture">Culture</a>
      <a class="nav-item nav-link link-body-emphasis" href="index.php?category=Business">Business</a>
      <a class="nav-item nav-link link-body-emphasis" href="index.php?category=Politics">Politics</a>
      <a class="nav-item nav-link link-body-emphasis" href="index.php?category=Opinion">Opinion</a>
      <a class="nav-item nav-link link-body-emphasis" href="index.php?category=Science">Science</a>
      <a class="nav-item nav-link link-body-emphasis" href="index.php?category=Health">Health</a>
      <a class="nav-item nav-link link-body-emphasis" href="index.php?category=Style">Style</a>
      <a class="nav-item nav-link link-body-emphasis" href="index.php?category=Travel">Travel</a>
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

  $id = $_GET["post"];
  $ip = $_SERVER["REMOTE_ADDR"];

  $postfields = json_encode(['id' => $id, 'ip' => $ip, 'userID' => $_SESSION["userID"]]);
  $result = curl('posts', 'POST', $postfields, true);

  if ($result["success"] != true) {
    header('location: index.php');
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
      <p class="blog-post-meta">'.$date.' by <a href="../index.php?user='.$publisher.'">'.$publisher.'</a>
      </p>
      <a href="index.php?category='.$category.'"><strong class="d-inline-block mb-2 text-primary-emphasis">'.$category.'</strong></a>
      '.$content.'
      </article>
    '; 
  ?>

    <a class="btn btn-outline-light mb-4" href="index.php">Back</a>
    </div>

<?php endif;

// show posts from a sepcific user
if (isset($_GET["user"]) && $_GET["user"] !== "") :

  // pagination
  $page = isset($_GET["page"]) ? $_GET["page"] : 1;
  $user = $_GET["user"];

  $postfields = json_encode(['user' => $user, 'page' => $page, 'limit' => 5]);
  $result = curl('posts', 'POST', $postfields, true);

  if (!$result["success"]) {
    header('location: index.php');
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
  <p class="blog-post-meta">'.$date.' by <a href="../index.php?user='.$post["username"].'">'.$post["username"].'</a>
  </p>
  <a href="index.php?category='.$post["category"].'"><strong class="d-inline-block mb-2 text-primary-emphasis">'.$post["category"].'</strong></a>
  '.$post["content"].'
  </article>
  ';

  endforeach; ?>

  <!-- pagination -->
  <nav aria-label="pagination">
      <ul class="pagination justify-content-center">
        <li class="page-item  <?php echo (($page - 1) <= 0 ) ? "disabled" : ""; ?>">
          <a class="page-link bg-dark text-light" href="index.php?user=<?php echo $user; ?>&page=<?php echo $page - 1 ; ?>">Previous</a>
        </li>
        <?php for ($pages=1;$pages<=$result["total_pages"];$pages++) : ?>
          <li class="page-item <?php echo ($pages == $page) ? "active" : ""; ?>"><a class="page-link bg-dark text-light"
            href="index.php?user=<?php echo $user; ?>&page=<?php echo $pages; ?>"><?php echo $pages; ?></a>
          </li>
        <?php endfor; ?>
        <li class="page-item <?php echo (($page + 1) > $result["total_pages"] ) ? "disabled" : ""; ?>">
          <a class="page-link bg-dark text-light" href="index.php?user=<?php echo $user; ?>&page=<?php echo $page + 1 ; ?>">Next</a>
        </li>
      </ul>
    </nav>

</div>

<?php endif;

// show posts by category
if (isset($_GET["category"]) && $_GET["category"] !== "") :

  // pagination
  $page = isset($_GET["page"]) ? $_GET["page"] : 1;
  $category = $_GET["category"];

  $postfields = json_encode(['category' => $category, 'page' => $page, 'limit' => 3]);
  $result = curl('posts', 'POST', $postfields, true);

  if (!$result["success"]) {
    header('location: index.php');
  }

  $content = $result["posts"];

    foreach ($content as $post) : 

      $date = date("M d, Y", strtotime($post["created_at"]));

      echo '
      <article class="blog-post">
      <h2 class="display-5 link-body-emphasis mb-1">'.$post["title"].'</h2>
      <img class="rounded img-fluid" src="'.$post["cover"].'">
      <p class="blog-post-meta">'.$date.' by <a href="../index.php?user='.$post["username"].'">'.$post["username"].'</a>
      </p>
      <a href="index.php?category='.$post["category"].'"><strong class="d-inline-block mb-2 text-primary-emphasis">'.$post["category"].'</strong></a>
      '.$post["content"].'
      </article>
      ';

    endforeach; ?>
    
    <!-- pagination -->
    <nav aria-label="pagination">
      <ul class="pagination justify-content-center">
        <li class="page-item  <?php echo (($page - 1) <= 0 ) ? "disabled" : ""; ?>">
          <a class="page-link bg-dark text-light" href="index.php?category=<?php echo $category; ?>&page=<?php echo $page - 1 ; ?>">Previous</a>
        </li>
        <?php for ($pages=1;$pages<=$result["total_pages"];$pages++) : ?>
          <li class="page-item <?php echo ($pages == $page) ? "active" : ""; ?>"><a class="page-link bg-dark text-light"
            href="index.php?category=<?php echo $category; ?>&page=<?php echo $pages; ?>"><?php echo $pages; ?></a>
          </li>
        <?php endfor; ?>
        <li class="page-item <?php echo (($page + 1) > $result["total_pages"] ) ? "disabled" : ""; ?>">
          <a class="page-link bg-dark text-light" href="index.php?category=<?php echo $category; ?>&page=<?php echo $page + 1 ; ?>">Next</a>
        </li>
      </ul>
    </nav>

  </div>

<?php endif;

// show posts by date - archive
if (isset($_GET['year']) || isset($_GET['month'])) {

  $page = isset($_GET["page"]) ? $_GET["page"] : 1;
  $year = $_GET['year'];
  $month = $_GET['month'];

  $postfields = json_encode(['year' => $year, 'month' => $month, 'page' => $page, 'limit' => 3]);
  $result = curl('posts', 'POST', $postfields, true);
  if (!$result["success"]) {
    header('location: index.php');
  }

  $content = $result["posts"];

  foreach ($content as $post) : 

    $date = date("M d, Y", strtotime($post["created_at"]));

    echo '
    <article class="blog-post">
    <h2 class="display-5 link-body-emphasis mb-1">'.$post["title"].'</h2>
    <img class="rounded img-fluid" src="'.$post["cover"].'">
    <p class="blog-post-meta">'.$date.' by <a href="../index.php?user='.$post["username"].'">'.$post["username"].'</a>
    </p>
    <a href="index.php?category='.$post["category"].'"><strong class="d-inline-block mb-2 text-primary-emphasis">'.$post["category"].'</strong></a>
    '.$post["content"].'
    </article>
    ';

  endforeach;?>

  <!-- pagination -->
    <nav aria-label="pagination">
      <ul class="pagination justify-content-center">
        <li class="page-item  <?php echo (($page - 1) <= 0 ) ? "disabled" : ""; ?>">
          <a class="page-link bg-dark text-light" href="index.php?year=<?php echo $year; ?>&month=<?php echo $month; ?>&page=<?php echo $page - 1 ; ?>">Previous</a>
        </li>
        <?php for ($pages=1;$pages<=$result["total_pages"];$pages++) : ?>
          <li class="page-item <?php echo ($pages == $page) ? "active" : ""; ?>"><a class="page-link bg-dark text-light"
            href="index.php?year=<?php echo $year; ?>&month=<?php echo $month; ?>&page=<?php echo $pages; ?>"><?php echo $pages; ?></a>
          </li>
        <?php endfor; ?>
        <li class="page-item <?php echo (($page + 1) > $result["total_pages"] ) ? "disabled" : ""; ?>">
          <a class="page-link bg-dark text-light" href="index.php?year=<?php echo $year; ?>&month=<?php echo $month; ?>&page=<?php echo $page + 1 ; ?>">Next</a>
        </li>
      </ul>
    </nav>

  </div>

<?php
}

// main page content - 3 random post
if (!isset($_GET['post']) && !isset($_GET['user']) && !isset($_GET['category']) && !isset($_GET['year']) && !isset($_GET['month'])) : 
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
      <p class="blog-post-meta">'.$date.' by <a href="../index.php?user='.$post["username"].'">'.$post["username"].'</a>
      </p>
      <a href="index.php?category='.$post["category"].'"><strong class="d-inline-block mb-2 text-primary-emphasis">'.$post["category"].'</strong></a>
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
            Feel free to explore and try out anything. The Archives is under construction.
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
//include_once('inc/event.php');
include_once('inc/footer.php');
?>