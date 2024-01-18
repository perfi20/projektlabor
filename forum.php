<link href="https://fonts.googleapis.com/css?family=Playfair&#43;Display:700,900&amp;display=swap" rel="stylesheet">
<link href="forum.css" rel="stylesheet">

<?php

include('inc/config.php');
include('inc/loggedInHeader.php');

// show specific post by id
if (isset($_GET["post"]) && $_GET["post"] !== "") : 

  $id = $_GET['post'];
  $postfields = json_encode(['id' => $id]);
  $result = curl('posts', 'POST', $postfields, true);

  if (!$result["success"]) {
    header('location: forum.php');
  }

  ?>

  <main class="container">

    <div class="row g-5">
      <div class="col-md-8">
        <?php echo $result["content"]; ?>
      </div>
    </div>

    <a class="btn btn-default bg-secondary text-light mb-4" href="forum.php">Back</a>

  </main>

<?php endif;

// show posts from a sepcific user
if (isset($_GET["user"]) && $_GET["user"] !== "") :

  $user = $_GET["user"];
  $postfields = json_encode(['user' => $user]);
  $result = curl('posts', 'POST', $postfields, true);

  if (!$result["success"]) {
    header('location: forum.php');
  }

  $content = $result["posts"];

?>

<main class="container">
<div class="row g-5">
  <div class="col-md-8">
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
</div>
</main>

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

  ?>

<main class="container">
<div class="row g-5">
  <div class="col-md-8">
    <?php  foreach ($content as $post) : 
      foreach ($post as $key => $value) :
        if ($key === "content"){
          echo $value;
        }
      ?>
      <?php endforeach;
    endforeach; ?>
  </div>
</div>
</main>

  <?php endif;

// show main page
if (!isset($_GET['post']) && !isset($_GET['user']) && !isset($_GET['category'])) : ?>

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



  <div class="row g-5">
    <div class="col-md-8">
      <h3 class="pb-4 mb-4 fst-italic border-bottom">
        From the Firehose
      </h3>

      <article class="blog-post">
        <h2 class="display-5 link-body-emphasis mb-1">Sample blog post</h2>
        <p class="blog-post-meta">January 1, 2021 by <a href="#">Mark</a></p>

        <p>This blog post shows a few different types of content that's supported and styled with Bootstrap. Basic typography, lists, tables, images, code, and more are all supported as expected.</p>
        <hr>
        <p>This is some additional paragraph placeholder content. It has been written to fill the available space and show how a longer snippet of text affects the surrounding content. We'll repeat it often to keep the demonstration flowing, so be on the lookout for this exact same string of text.</p>
        <h2>Blockquotes</h2>
        <p>This is an example blockquote in action:</p>
        <blockquote class="blockquote">
          <p>Quoted text goes here.</p>
        </blockquote>
        <p>This is some additional paragraph placeholder content. It has been written to fill the available space and show how a longer snippet of text affects the surrounding content. We'll repeat it often to keep the demonstration flowing, so be on the lookout for this exact same string of text.</p>
        <h3>Example lists</h3>
        <p>This is some additional paragraph placeholder content. It's a slightly shorter version of the other highly repetitive body text used throughout. This is an example unordered list:</p>
        <ul>
          <li>First list item</li>
          <li>Second list item with a longer description</li>
          <li>Third list item to close it out</li>
        </ul>
        <p>And this is an ordered list:</p>
        <ol>
          <li>First list item</li>
          <li>Second list item with a longer description</li>
          <li>Third list item to close it out</li>
        </ol>
        <p>And this is a definition list:</p>
        <dl>
          <dt>HyperText Markup Language (HTML)</dt>
          <dd>The language used to describe and define the content of a Web page</dd>
          <dt>Cascading Style Sheets (CSS)</dt>
          <dd>Used to describe the appearance of Web content</dd>
          <dt>JavaScript (JS)</dt>
          <dd>The programming language used to build advanced Web sites and applications</dd>
        </dl>
        <h2>Inline HTML elements</h2>
        <p>HTML defines a long list of available inline tags, a complete list of which can be found on the <a href="https://developer.mozilla.org/en-US/docs/Web/HTML/Element">Mozilla Developer Network</a>.</p>
        <ul>
          <li><strong>To bold text</strong>, use <code class="language-plaintext highlighter-rouge">&lt;strong&gt;</code>.</li>
          <li><em>To italicize text</em>, use <code class="language-plaintext highlighter-rouge">&lt;em&gt;</code>.</li>
          <li>Abbreviations, like <abbr title="HyperText Markup Language">HTML</abbr> should use <code class="language-plaintext highlighter-rouge">&lt;abbr&gt;</code>, with an optional <code class="language-plaintext highlighter-rouge">title</code> attribute for the full phrase.</li>
          <li>Citations, like <cite>— Mark Otto</cite>, should use <code class="language-plaintext highlighter-rouge">&lt;cite&gt;</code>.</li>
          <li><del>Deleted</del> text should use <code class="language-plaintext highlighter-rouge">&lt;del&gt;</code> and <ins>inserted</ins> text should use <code class="language-plaintext highlighter-rouge">&lt;ins&gt;</code>.</li>
          <li>Superscript <sup>text</sup> uses <code class="language-plaintext highlighter-rouge">&lt;sup&gt;</code> and subscript <sub>text</sub> uses <code class="language-plaintext highlighter-rouge">&lt;sub&gt;</code>.</li>
        </ul>
        <p>Most of these elements are styled by browsers with few modifications on our part.</p>
        <h2>Heading</h2>
        <p>This is some additional paragraph placeholder content. It has been written to fill the available space and show how a longer snippet of text affects the surrounding content. We'll repeat it often to keep the demonstration flowing, so be on the lookout for this exact same string of text.</p>
        <h3>Sub-heading</h3>
        <p>This is some additional paragraph placeholder content. It has been written to fill the available space and show how a longer snippet of text affects the surrounding content. We'll repeat it often to keep the demonstration flowing, so be on the lookout for this exact same string of text.</p>
        <pre><code>Example code block</code></pre>
        <p>This is some additional paragraph placeholder content. It's a slightly shorter version of the other highly repetitive body text used throughout.</p>
      </article>

      <article class="blog-post">
        <h2 class="display-5 link-body-emphasis mb-1">Another blog post</h2>
        <p class="blog-post-meta">December 23, 2020 by <a href="#">Jacob</a></p>

        <p>This is some additional paragraph placeholder content. It has been written to fill the available space and show how a longer snippet of text affects the surrounding content. We'll repeat it often to keep the demonstration flowing, so be on the lookout for this exact same string of text.</p>
        <blockquote>
          <p>Longer quote goes here, maybe with some <strong>emphasized text</strong> in the middle of it.</p>
        </blockquote>
        <p>This is some additional paragraph placeholder content. It has been written to fill the available space and show how a longer snippet of text affects the surrounding content. We'll repeat it often to keep the demonstration flowing, so be on the lookout for this exact same string of text.</p>
        <h3>Example table</h3>
        <p>And don't forget about tables in these posts:</p>
        <table class="table text-white">
          <thead>
            <tr>
              <th>Name</th>
              <th>Upvotes</th>
              <th>Downvotes</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Alice</td>
              <td>10</td>
              <td>11</td>
            </tr>
            <tr>
              <td>Bob</td>
              <td>4</td>
              <td>3</td>
            </tr>
            <tr>
              <td>Charlie</td>
              <td>7</td>
              <td>9</td>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <td>Totals</td>
              <td>21</td>
              <td>23</td>
            </tr>
          </tfoot>
        </table>

        <p>This is some additional paragraph placeholder content. It's a slightly shorter version of the other highly repetitive body text used throughout.</p>
      </article>

      <article class="blog-post">
        <h2 class="display-5 link-body-emphasis mb-1">New feature</h2>
        <p class="blog-post-meta">December 14, 2020 by <a href="#">Chris</a></p>

        <p>This is some additional paragraph placeholder content. It has been written to fill the available space and show how a longer snippet of text affects the surrounding content. We'll repeat it often to keep the demonstration flowing, so be on the lookout for this exact same string of text.</p>
        <ul>
          <li>First list item</li>
          <li>Second list item with a longer description</li>
          <li>Third list item to close it out</li>
        </ul>
        <p>This is some additional paragraph placeholder content. It's a slightly shorter version of the other highly repetitive body text used throughout.</p>
      </article>

      <nav class="blog-pagination" aria-label="Pagination">
        <a class="btn btn-outline-primary rounded-pill" href="#">Older</a>
        <a class="btn btn-outline-secondary rounded-pill disabled" aria-disabled="true">Newer</a>
      </nav>

    </div>

    <div class="col-md-4">
      <div class="position-sticky" style="top: 2rem;">
        <div class="p-4 mb-3 rounded">
          <h4 class="fst-italic">About</h4>
          <p class="mb-0">Customize this section to tell your visitors a little bit about your publication, writers, content, or something else entirely. Totally up to you.</p>
        </div>

        <?php // recent posts 
        
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

<?php endif;

include('inc/footer.php'); ?>