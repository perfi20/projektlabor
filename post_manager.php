<?php

session_start();

$GLOBALS["toastFunction"] = "";

require_once('components/curl.php');
require_once('inc/loggedInHeader.php');
require_once('components/validateInput.php');

if (empty($_SESSION['username'])) {
    header('location: index.php');

}
?>

<script src="./js/postManager.js"></script>

<?php
// create post from submit
if (isset($_POST['postSubmit'])) {

    $postTitle = $_POST['postTitle'];

    $postCategory = $_POST['postCategory'];

    $postSum = $_POST['postSum'];

    $postCoverImage = filter_var($_POST['coverImage'], FILTER_SANITIZE_URL);

    if (!empty($_POST['form'])) {
        $postInputs = $_POST['form'];
    } else {
        $GLOBALS["toastFunction"] = "showToast('false', 'Post content cannot be empty!');";
    }
    
    // 2d array
    foreach ($postInputs as $p) {

        foreach ($p as $key => $value) 
        {
            switch ($key) {

                case "pTitle": 
                    $postContent .= '<h3>'.$value.'</h3>';
                    break;

                case "pContent": 

                    $postContent .= '<p>'.$value.'</p>';
                    break;

                case "ulContent":

                    $postContent .= '<ul>';
                    $list = explode(",", $value);
                    foreach ($list as $li) {
                        $postContent .= '<li>'.$li.'</li>';
                    }
                    $postContent .= '</ul>';
                    break;

                case "olContent":

                    $postContent .= '<ol>';
                    $list = explode(",", $value);
                    foreach ($list as $li) {
                        $postContent .= '<li>'.$li.'</li>';
                    }
                    $postContent .= '</ol>';
                    break;

                case "tableHeads":
                    $postContent .= '<table class="table text-white"><thead><tr>';
                    $list = explode(",", $value);
                    foreach ($list as $heads) {
                        $postContent .= '<th>'.$heads.'</th>';
                    }
                    $postContent .= '</tr></thead>';
                    break;
                case "tableRows":
                    $postContent .= '<tbody>';
                    $rows = explode("-", $value);
                 
                    foreach ($rows as $row) {
                        $row = explode(",", $row);
                        $postContent .= '<tr>';
                        foreach ($row as $data) {
                            $postContent .= '<td>'.$data.'</td>';
                        }
                        $postContent .= '</tr>';
                    }
                    $postContent .= '</tbody></table>';
                    break;
                case "picture":
                    $postContent .= '<img class="rounded img-fluid" src="';
                    $postContent .= "$value";
                    $postContent .= '">';
                    break;
            }
        }
    }

    $postfields = json_encode([
        'title' => $postTitle,
        'category' => $postCategory,
        'summary' => $postSum,
        'cover_image' => $postCoverImage,
        'publisher' => $_SESSION['username'],
        'content' => $postContent
    ]);

    $data = curl('posts', 'PUT', $postfields);

    // redirect user to the new post
    if ($data->success == true) {
        header('location: ../index.php?post='.$data->id);
    }

    $GLOBALS["toastFunction"] = "showToast('$data->success', '$data->message');";
    
}

?>

<main class="container">

<!-- page navigation -->
<nav class="nav nav-pills nav-justified">
  <a class="nav-link <?php echo !isset($_GET["action"]) ? "active" : ""; ?>"
    href="post_manager.php">My Posts</a>
  <a class="nav-link <?php echo isset($_GET["action"]) && $_GET["action"] == "new" ? "active" : ""; ?>"
    href="post_manager.php?action=new">New Post</a>
</nav>



<?php

// delete post
if (isset($_POST["delete"])) {

    $postfield = json_encode(['delete' => 'true', 'id' => $_POST["id"]]);
    $result = curl('posts', 'DELETE', $postfield);

    $GLOBALS["toastFunction"] = "showToast('$result->success', '$result->message');";

}

// edit post
if (isset($_POST["edit"])) {
    
    $postfield = json_encode([
        'id' => $_POST["id"],
        'title' => $_POST["title"],
        'category' => $_POST["category"],
        'cover' => filter_var($_POST['cover'], FILTER_SANITIZE_URL),
        'summary' => $_POST["summary"],
        'featured' => $_POST["featured"] ? true : false
    ]);
    $result = curl('posts', 'PATCH', $postfield);

    $GLOBALS["toastFunction"] = "showToast('$result->success', '$result->message');";

}

// new post form
if (isset($_GET["action"]) && $_GET["action"] == "new") {
    include_once('./components/_create_post.php');
}

// main content - show user's posts with edit/delete options
if (!isset($_GET["action"])) {

    // pagination
    $page = isset($_GET["page"]) ? $_GET["page"] : 1;
    $category = $_GET["user"];

    $postfields = json_encode(['user' => $_SESSION["username"], 'page' => $page, 'limit' => 50]);
    $result = curl('posts', 'POST', $postfields, true);

    $content = $result["posts"];
?>

<div class="table-responsive">
    <table class="table align-middle text-light">

    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Title</th>
            <th scope="col">Category</th>
            <th scope="col">Created at</th>
            <th scope="col">Updated at</th>
            <th scope="col">Featured</th>
            <th scope="col">Actions</th>
        </tr>
    </thead>

    <?php $i = 0;
    foreach ($content as $post) : 
       $i++; 
    ?>
    
    <tbody class="table-group-divider">
        <tr>
            <th><?php echo $page == 1 ? $i : ($i + ($page * 25)); ?></th>
            <td><?php echo $post["title"] ?></td>
            <td><?php echo $post["category"] ?></td>
            <td><?php echo $post["created_at"] ?></td>
            <td><?php if (isset($post["updated_at"])) echo $post["updated_at"] ?></td>
            <td><?php echo $post["featured"] ? "yes" : "no"; ?></td>
            <td>
                <!-- view post button -->
                <a class="btn btn-outline-light" href="index.php?post=<?php echo $post["id"]; ?>">View</a>

                <!-- edit modal button -->
                <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal"
                    data-bs-target="#editModal<?php echo $post["id"]; ?>">
                    Edit
                </button>

                <!-- delete modal button -->
                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                    data-bs-target="#deleteModal<?php echo $post["id"]; ?>">
                    Delete
                </button>

                <!-- edit modal -->

                <div class="modal fade" id="editModal<?php echo $post["id"]; ?>" tabindex="-1"
                    aria-labelledby="editModalLabel" aria-hidden="true" data-bs-theme="dark">
                    <div class="modal-dialog">
                        <div class="modal-content bg-dark">

                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="editModalLabel">Edit</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                    
                                    <div class="mb-3">
                                        <label for="id" class="col-form-label">ID:</label>
                                        <input type="text" name="id" id="id" class="form-control bg-dark text-light" value="<?php echo $post["id"]; ?>" readonly>
                                    </div>

                                    <div class="mb-3">
                                        <label for="title" class="col-form-label">Title:</label>
                                        <textarea type="text" name="title" id="title" rows="3" class="form-control bg-dark text-light"><?php echo $post["title"]; ?></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="category" class="col-form-label">Category:</label>
                                        <select name="category" id="category" class="form-control bg-dark text-light">
                                            <option value="Other" <?php if ($post["category"] == "Other") echo "selected"; ?>>Other</option>
                                            <option value="World" <?php if ($post["category"] == "World") echo "selected"; ?>>World</option>
                                            <option value="U.S" <?php if ($post["category"] == "U.S") echo "selected"; ?>>U.S</option>
                                            <option value="Technology" <?php if ($post["category"] == "Technology") echo "selected"; ?>>Technology</option>
                                            <option value="Design" <?php if ($post["category"] == "Design") echo "selected"; ?>>Design</option>
                                            <option value="Culture" <?php if ($post["category"] == "Culture") echo "selected"; ?>>Culture</option>
                                            <option value="Business" <?php if ($post["category"] == "Business") echo "selected"; ?>>Business</option>
                                            <option value="Politics" <?php if ($post["category"] == "Politics") echo "selected"; ?>>Politics</option>
                                            <option value="Opinion" <?php if ($post["category"] == "Opinion") echo "selected"; ?>>Opinion</option>
                                            <option value="Science" <?php if ($post["category"] == "Science") echo "selected"; ?>>Science</option>
                                            <option value="Health" <?php if ($post["category"] == "Health") echo "selected"; ?>>Health</option>
                                            <option value="Style" <?php if ($post["category"] === "Style") echo "selected"; ?>>Style</option>
                                            <option value="Travel" <?php if ($post["category"] == "Travel") echo "selected"; ?>>Travel</option>
                                        </select>
                                    </div>

                                    <div class="mb-5">
                                        <label for="cover" class="col-form-label">Cover URL</label>
                                        <textarea name="cover" id="cover" rows="3" class="form-control bg-dark text-light"><?php echo $post["cover"]; ?></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="summary" class="col-form-label">Summary:</label>
                                        <textarea name="summary" id="summary" rows="4" class="form-control bg-dark text-light"><?php echo $post["summary"]; ?></textarea>
                                    </div>

                                    <div class="mb-3 form-check form-switch">
                                    <input class="form-check-input bg-dark" type="checkbox" role="switch" id="featuredSwitch" name="featured" value="featured" <?php echo $post["featured"] ? "checked" : "" ?> disabled="disabled">
                                    <label class="form-check-label" for="featuredSwitch">Featured</label>
                                    </div>    

                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" name="edit" id="edit" class="btn btn-outline-warning">Edit</button>
                            </div>
                                </form>
                        </div>
                    </div>
                </div>

                <!-- delete modal -->
                <div class="modal fade" id="deleteModal<?php echo $post["id"]; ?>" tabindex="-1"
                    aria-labelledby="deleteModalLabel" aria-hidden="true" data-bs-theme="dark">
                    <div class="modal-dialog">
                        <div class="modal-content bg-dark">

                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="deleteModalLabel">Delete</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

                                    <div class="mb-3">
                                        <p>Are you sure you want to delete post?</p>
                                    </div>
                                    <input type="hidden" name="id" value="<?php echo $post["id"]; ?>">
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" name="delete" class="btn btn-outline-danger">Delete</button>
                                    </div>

                                </form>
                            </div>

                        </div>
                    </div>
                </div>

            </td>
        </tr>
    </tbody>

    <?php endforeach; ?>

    </table>

</div>

<!-- pagination -->
<nav aria-label="pagination">
    <ul class="pagination justify-content-center">
    <li class="page-item  <?php echo (($page - 1) <= 0 ) ? "disabled" : ""; ?>">
        <a class="page-link bg-dark text-light" href="post_manager.php?page=<?php echo $page - 1 ; ?>">Previous</a>
    </li>
    <?php for ($pages=1;$pages<=$result["total_pages"];$pages++) : ?>
        <li class="page-item <?php echo ($pages == $page) ? "active" : ""; ?>"><a class="page-link bg-dark text-light"
        href="post_manager.php?page=<?php echo $pages; ?>"><?php echo $pages; ?></a>
        </li>
    <?php endfor; ?>
    <li class="page-item <?php echo (($page + 1) > $result["total_pages"] ) ? "disabled" : ""; ?>">
        <a class="page-link bg-dark text-light" href="post_manager.php?page=<?php echo $page + 1 ; ?>">Next</a>
    </li>
    </ul>
</nav>

</main>

<?php
}
require_once('inc/footer.php'); 
?>

<!-- toast -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
  <div id="liveToast" class="toast text-dark" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header" id="toastHeader">

      <strong class="me-auto" id="toastTitle"></strong>
      <small>11 mins ago</small>
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body" id="toastMessage">
    
    </div>
  </div>
</div>

<script src="./js/eventHandler.js"></script>

<script>

<?php
    if ($GLOBALS["toastFunction"] !== "") {
        echo $GLOBALS["toastFunction"];
    } else {
        $GLOBALS["toastFunction"] = "";
    } 
?>

// prevent form resubmission when page is refreshed
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}

</script>