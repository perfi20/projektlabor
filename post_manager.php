<?php

session_start();

require_once('components/curl.php');
require_once('inc/loggedInHeader.php');
require_once('components/validateInput.php');

if (empty($_SESSION['username'])) {
    header('location: index.php');
}

?>

<script src="./js/postManager.js"></script>

<main class="container">

<nav class="nav nav-pills nav-justified">
  <a class="nav-link active" aria-current="page" href="post_manager.php">My Posts</a>
  <a class="nav-link" href="post_manager.php?action=new">New Post</a>
  <a class="nav-link" href="#">Link</a>
  <a class="nav-link" aria-disabled="true">Disabled</a>
</nav>



<?php

// delete post with ?delete=id
if (isset($_POST["delete"])) {

    $postfield = json_encode(['delete' => 'true', 'id' => $_POST["id"]]);
    $result = curl('posts', 'DELETE', $postfield);

    header('location: post_manager.php');

    //  TODO: toast

}

// edit post with ?edit=id
if (isset($_POST["edit"])) {

    $postfield = json_encode([
        'id' => $_POST["id"],
        'title' => $_POST["title"],
        'category' => $_POST["category"],
        'summary' => $_POST["summary"],
        'featured' => $_POST["featured"] ? true : false
    ]);
    $result = curl('posts', 'PATCH', $postfield);

    header('location: post_manager.php');

}

// new post form
if (isset($_GET["action"]) && $_GET["action"] == "new") {
    include_once('./components/_create_post.php');
}

// main content - show user's posts with edit/delete options
if (!isset($_GET["action"])) {

    $postfields = json_encode(['user' => $_SESSION["username"]]);
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
            <th scope="col">Publisher</th>
            <th scope="col">Created at</th>
            <th scope="col">Updated at</th>
            <th scope="col">Featured</th>
            <th scope="col">Actions</th>
        </tr>
    </thead>

    <?php foreach ($content as $post) : ?>

    <tbody class="table-group-divider">
        <tr>
            <th><?php echo $post["id"]; ?></th>
            <td><?php echo $post["title"] ?></td>
            <td><?php echo $post["category"] ?></td>
            <td><?php echo $post["publisher"] ?></td>
            <td><?php echo $post["created_at"] ?></td>
            <td><?php if (isset($post["updated_at"])) echo $post["updated_at"] ?></td>
            <td><?php echo $post["featured"] ? "yes" : "no"; ?></td>
            <td>
                <!-- view post button -->
                <a class="btn btn-outline-secondary" href="index.php?post=<?php echo $post["id"]; ?>">View</a>

                <!-- edit modal button -->
                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
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
                    aria-labelledby="editModalLabel" aria-hidden="true">
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
                                        <input type="text" name="title" id="title" class="form-control bg-dark text-light" value="<?php echo $post["title"]; ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label for="category" class="col-form-label">Category:</label>
                                        <select name="category" id="category" class="form-control bg-dark text-light">
                                            <option value="other" <?php if ($post["category"] == "other") echo "selected"; ?>>Other</option>
                                            <option value="world" <?php if ($post["category"] == "world") echo "selected"; ?>>World</option>
                                            <option value="u.S" <?php if ($post["category"] == "u.S") echo "selected"; ?>>U.S</option>
                                            <option value="technology" <?php if ($post["category"] == "technology") echo "selected"; ?>>Technology</option>
                                            <option value="design" <?php if ($post["category"] == "design") echo "selected"; ?>>Design</option>
                                            <option value="culture" <?php if ($post["category"] == "culture") echo "selected"; ?>>Culture</option>
                                            <option value="business" <?php if ($post["category"] == "business") echo "selected"; ?>>Business</option>
                                            <option value="politics" <?php if ($post["category"] == "politics") echo "selected"; ?>>Politics</option>
                                            <option value="opinion" <?php if ($post["category"] == "opinion") echo "selected"; ?>>Opinion</option>
                                            <option value="science" <?php if ($post["category"] == "science") echo "selected"; ?>>Science</option>
                                            <option value="health" <?php if ($post["category"] == "health") echo "selected"; ?>>Health</option>
                                            <option value="style" <?php if ($post["category"] === "style") echo "selected"; ?>>Style</option>
                                            <option value="travel" <?php if ($post["category"] == "travel") echo "selected"; ?>>Travel</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="summary" class="col-form-label">Summary:</label>
                                        <textarea name="summary" id="summary" rows="3" class="form-control bg-dark text-light"><?php echo $post["summary"];?></textarea>
                                    </div>

                                    <div class="mb-3 form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="featuredSwitch" name="featured" value="featured">
                                    <label class="form-check-label" for="featuredSwitch">Featured</label>
                                    </div>    

                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" name="edit" id="edit" class="btn btn-primary">Edit</button>
                            </div>
                                </form>
                        </div>
                    </div>
                </div>

                <!-- delete modal -->
                <div class="modal fade" id="deleteModal<?php echo $post["id"]; ?>" tabindex="-1"
                    aria-labelledby="deleteModalLabel" aria-hidden="true">
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
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" name="delete" class="btn btn-danger">Delete</button>
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

</main>
<?php
}
require_once('inc/footer.php'); 
?>