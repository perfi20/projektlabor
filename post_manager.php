<?php

session_start();

$GLOBALS["toastFunction"] = "";

require_once('components/curl.php');
require_once('inc/loggedInHeader.php');
require_once('components/validateInput.php');

<<<<<<< HEAD
<<<<<<< HEAD
function normalize_files_array($files = []) {

    $normalized_array = [];

    foreach($files as $index => $file) {

        if (!is_array($file['name'])) {
            $normalized_array[$index][] = $file;
            continue;
        }

        foreach($file['name'] as $idx => $name) {
            $normalized_array[$index][$idx] = [
                'name' => $name,
                'type' => $file['type'][$idx],
                'tmp_name' => $file['tmp_name'][$idx],
                'error' => $file['error'][$idx],
                'size' => $file['size'][$idx]
            ];
        }

    }

    return $normalized_array;

}

=======
>>>>>>> f7bb41a4ac79e8a78d2a24c1de5b630fd6e09fa1
=======
>>>>>>> f7bb41a4ac79e8a78d2a24c1de5b630fd6e09fa1
if (empty($_SESSION['username'])) {
    header('location: /');

}
?>

<script src="/js/postManager.js"></script>

<?php
// create post from submit
if (isset($_POST['postSubmit'])) {
<<<<<<< HEAD
<<<<<<< HEAD

    $normalizedFiles = normalize_files_array($_FILES);
=======
>>>>>>> f7bb41a4ac79e8a78d2a24c1de5b630fd6e09fa1
=======
>>>>>>> f7bb41a4ac79e8a78d2a24c1de5b630fd6e09fa1
    
    $postTitle = $_POST['postTitle'];

    $postCategory = $_POST['postCategory'];

    $postSum = $_POST['postSum'];

<<<<<<< HEAD
<<<<<<< HEAD
    // cover image
        $path = 'src/posts/coverimages/';
    
        $target_dir = $path;
        $target_file = $target_dir . basename($_FILES['coverImage']['name']);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // check if image file is an actual image or fake image
        $check = getimagesize($_FILES['coverImage']['tmp_name']);
        if ($check !== false) {
            $uploadOk = 1;
        } else $uploadOk = 0;

        // check file size
        if ($_FILES['coverImage']['size'] > 500000) {
            $uploadOk = 0;
        }

        // check file format
        if ($imageFileType !== 'jpg' && $imageFileType !== 'png' && $imageFileType !== 'jpeg') {
            $uploadOk = 0;
        }

        // upload
        move_uploaded_file($_FILES['coverImage']['tmp_name'], $target_file);

        $postCoverImage = 'src/posts/coverimages/' . basename($_FILES['coverImage']['name']);

=======
    $postCoverImage = filter_var($_POST['coverImage'], FILTER_SANITIZE_URL);
>>>>>>> f7bb41a4ac79e8a78d2a24c1de5b630fd6e09fa1
=======
    $postCoverImage = filter_var($_POST['coverImage'], FILTER_SANITIZE_URL);
>>>>>>> f7bb41a4ac79e8a78d2a24c1de5b630fd6e09fa1

    if (!empty($_POST['form'])) {
        $postInputs = $_POST['form'];
    } else {
        $GLOBALS["toastFunction"] = "showToast('false', 'Post content cannot be empty!');";
    }
    
<<<<<<< HEAD
<<<<<<< HEAD
    // iterate through input fields
    foreach ($postInputs as $input) {

        if (isset($input['header'])) $postContent .= '<h3>'.$input['header'].'</h3>';

        if (isset($input['pContent'])) $postContent .= '<p>'.$input['pContent'].'</p>';

        if (isset($input['ulContent'])) {
            $postContent .= '<ul>';
                $list = explode(",", $input['ulContent']);
                foreach ($list as $li) {
                    $postContent .= '<li>'.$li.'</li>';
                }
                $postContent .= '</ul>';
        }

        if (isset($input['olContent'])) {
            $postContent .= '<ol>';
            $list = explode(",", $input['olContent']);
            foreach ($list as $li) {
                $postContent .= '<li>'.$li.'</li>';
            }
            $postContent .= '</ol>';
        }

        if (isset($input['tableHeads'])) {
            $postContent .= '<table class="table"><thead><tr>';
            $list = explode(",", $input['tableHeads']);
            foreach ($list as $heads) {
                $postContent .= '<th>'.$heads.'</th>';
            }
            $postContent .= '</tr></thead>';
        }

        if (isset($input['tableRows'])) {
            $postContent .= '<tbody>';
            $rows = explode("-", $input['tableRows']);
            
            foreach ($rows as $row) {
                $row = explode(",", $row);
                $postContent .= '<tr>';
                foreach ($row as $data) {
                    $postContent .= '<td>'.$data.'</td>';
                }
                $postContent .= '</tr>';
            }
            $postContent .= '</tbody></table>';
        }

        if (isset($input['picture'])) {
            //$path = 'src/posts/images/' . basename($input['picture']);

            //file_put_contents($path, file_get_contents(basename($input['picture'])));

            $postContent .= '<img loading="lazy" class="rounded img-fluid" src="src/posts/images/';
            $postContent .= basename($input['picture']);
            $postContent .= '">';
            
        }

    }

    // iterate trough picture file inputs
    foreach ($_FILES['picture']['error'] as $key => $error) {

        if ($error == UPLOAD_ERR_OK) {

            $path = 'src/posts/images/';

            $target_dir = $path;
            $target_file = $target_dir . basename($_FILES['picture']['name'][$key]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // check if image file is an actual image or fake image
            $check = getimagesize($_FILES['picture']['tmp_name'][$key]);
            if ($check !== false) {
                $uploadOk = 1;
            } else $uploadOk = 0;

            // check file size
            if ($_FILES['picture']['size'][$key] > 500000) {
                $uploadOk = 0;
            }

            // check file format
            if ($imageFileType !== 'jpg' && $imageFileType !== 'png' && $imageFileType !== 'jpeg') {
                $uploadOk = 0;
            }

            // upload
            move_uploaded_file($_FILES['picture']['tmp_name'][$key], $target_file);

        }

    }

    

    // 2d array
    // foreach ($postInputs as $p) {

    //     foreach ($p as $key => $value) 
    //     {
    //         switch ($key) {

    //             case "header": 

    //                 $postContent .= '<h3>'.$value.'</h3>';
    //                 break;

    //             case "pContent": 

    //                 $postContent .= '<p>'.$value.'</p>';
    //                 break;

    //             case "ulContent":

    //                 $postContent .= '<ul>';
    //                 $list = explode(",", $value);
    //                 foreach ($list as $li) {
    //                     $postContent .= '<li>'.$li.'</li>';
    //                 }
    //                 $postContent .= '</ul>';
    //                 break;

    //             case "olContent":

    //                 $postContent .= '<ol>';
    //                 $list = explode(",", $value);
    //                 foreach ($list as $li) {
    //                     $postContent .= '<li>'.$li.'</li>';
    //                 }
    //                 $postContent .= '</ol>';
    //                 break;

    //             case "tableHeads":

    //                 $postContent .= '<table class="table"><thead><tr>';
    //                 $list = explode(",", $value);
    //                 foreach ($list as $heads) {
    //                     $postContent .= '<th>'.$heads.'</th>';
    //                 }
    //                 $postContent .= '</tr></thead>';
    //                 break;

    //             case "tableRows":

    //                 $postContent .= '<tbody>';
    //                 $rows = explode("-", $value);
                 
    //                 foreach ($rows as $row) {
    //                     $row = explode(",", $row);
    //                     $postContent .= '<tr>';
    //                     foreach ($row as $data) {
    //                         $postContent .= '<td>'.$data.'</td>';
    //                     }
    //                     $postContent .= '</tr>';
    //                 }
    //                 $postContent .= '</tbody></table>';
    //                 break;

    //             case "picture":

    //                 $path = 'src/posts/images/';
                
    //                 $target_dir = $path;
    //                 $target_file = $target_dir . basename($p['picture']['name']);
    //                 $uploadOk = 1;
    //                 $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    //                 // check if image file is an actual image or fake image
    //                 $check = getimagesize($p['picture']['tmp_name']);
    //                 if ($check !== false) {
    //                     $uploadOk = 1;
    //                 } else $uploadOk = 0;

    //                 // check file size
    //                 if ($p['picture']['size'] > 500000) {
    //                     $uploadOk = 0;
    //                 }

    //                 // check file format
    //                 if ($imageFileType !== 'jpg' && $imageFileType !== 'png' && $imageFileType !== 'jpeg') {
    //                     $uploadOk = 0;
    //                 }

    //                 // upload
    //                 move_uploaded_file($p['picture']['tmp_name'], $target_file);

    //                 $postContent .= '<img loading="lazy" class="rounded img-fluid" src="src/posts/images/';
    //                 $postContent .= basename($p['picture']['name']);
    //                 $postContent .= '">';
    //                 break;
    //         }
    //     }
    // }
=======
=======
>>>>>>> f7bb41a4ac79e8a78d2a24c1de5b630fd6e09fa1
    // 2d array
    foreach ($postInputs as $p) {

        foreach ($p as $key => $value) 
        {
            switch ($key) {

                case "header": 
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
                    $postContent .= '<table class="table"><thead><tr>';
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
<<<<<<< HEAD
>>>>>>> f7bb41a4ac79e8a78d2a24c1de5b630fd6e09fa1
=======
>>>>>>> f7bb41a4ac79e8a78d2a24c1de5b630fd6e09fa1

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
        header('location: /post/'.$data->id);
    }

    $GLOBALS["toastFunction"] = "showToast('$data->success', '$data->message');";
    
}

?>

<main class="container">

<!-- page navigation -->
<nav class="nav nav-pills nav-justified">
  <a class="nav-link <?php echo !isset($view) ? "active" : ""; ?>"
    href="/user/posts">My Posts</a>
  <a class="nav-link <?php echo isset($view) && $view == "create" ? "active" : ""; ?>"
    href="/user/posts/create">New Post</a>
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
if (isset($view) && $view == "create") {
<<<<<<< HEAD
<<<<<<< HEAD
    //include_once('./components/__createPost.php');
=======
    include_once('./components/__createPost.php');
>>>>>>> f7bb41a4ac79e8a78d2a24c1de5b630fd6e09fa1
=======
    include_once('./components/__createPost.php');
>>>>>>> f7bb41a4ac79e8a78d2a24c1de5b630fd6e09fa1
    include_once('./components/_create_post.php');
}

// main content - show user's posts with edit/delete options
if (!isset($view)) {

    // pagination
    $page = isset($page) ? $page : 1;

    
    $orderBy = ($order == 'desc') ? 'asc' : 'desc';

    if (!isset($order)) {
        $orderBy = 'desc';
    }
    
    // order arrow
    if ($orderBy == 'asc') {
        if ($_COOKIE['darkmode'] === 'disabled') {
            $arrow = '<img src="/src/icons8-chevron-up-64-black.png" width="20" height="20"/>';
        } else {
            $arrow = '<img src="/src/icons8-chevron-up-64.png" width="20" height="20"/>';
        }   
    } else if ($orderBy == 'desc') {
        if ($_COOKIE['darkmode'] === 'disabled') {
            $arrow = '<img src="/src/icons8-chevron-down-64-black.png" width="20" height="20"/>';
        } else {
            $arrow = '<img src="/src/icons8-chevron-up-64.png" width="20" height="20"/>';
        }
    } 
    
    // request
    $postfields = json_encode([
        'user' => $_SESSION["username"],
        'page' => $page,
        'limit' => 50,
        'sort' => $sort,
        'order' => $orderBy
    ]);
    $result = curl('posts', 'POST', $postfields, true);

    $content = $result["posts"];
?>

<div class="table-responsive">
    <table class="table align-middle">

    <!-- filtering links styling -->
    <style>
        .filter-link {
            text-decoration: none;
        }
    </style>

    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col"><a class="filter-link" href="/user/posts/sort/title/order/<?php echo $orderBy; ?>">Title<?php if ($sort == 'title') echo $arrow; ?></a></th>
            <th scope="col"><a class="filter-link" href="/user/posts/sort/category/order/<?php echo $orderBy; ?>">Category<?php if ($sort == 'category') echo $arrow; ?></a></th>
            <th scope="col"><a class="filter-link" href="/user/posts/sort/created_at/order/<?php echo $orderBy; ?>">Created at<?php if ($sort == 'created_at') echo $arrow; ?></a></th>
            <th scope="col"><a class="filter-link" href="/user/posts/sort/updated_at/order/<?php echo $orderBy; ?>">Updated at<?php if ($sort == 'updated_at') echo $arrow; ?></a></th>
            <th scope="col"><a class="filter-link" href="/user/posts/sort/featured/order/<?php echo $orderBy; ?>">Featured<?php if ($sort == 'featured') echo $arrow; ?></a></th>
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
            <td><?php echo strlen($post["title"]) > 60 ? substr($post['title'], 0, 60).'...' : $post["title"] ?></td>
            <td><?php echo $post["category"] ?></td>
            <td><?php echo $post["created_at"] ?></td>
            <td><?php if (isset($post["updated_at"])) echo $post["updated_at"] ?></td>
            <td <?php echo $post['featured'] ? 'class="text-success"' : 'class="text-secondary"'; ?>><?php echo $post["featured"] ? 'Yes' : 'No'; ?></td>
            <td>
                <!-- view post button -->
                <a class="btn btn-outline-info" href="/post/<?php echo $post["id"]; ?>">View</a>

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
                    aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="editModalLabel">Edit</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <form method="POST" action="/user/posts">
                                    
                                    <div class="mb-3">
                                        <label for="id" class="col-form-label">ID:</label>
                                        <input type="text" name="id" id="id" class="form-control" value="<?php echo $post["id"]; ?>" readonly>
                                    </div>

                                    <div class="mb-3">
                                        <label for="title" class="col-form-label">Title:</label>
                                        <textarea type="text" name="title" id="title" rows="3" class="form-control"><?php echo $post["title"]; ?></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="category" class="col-form-label">Category:</label>
                                        <select name="category" id="category" class="form-control">
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
                                        <textarea name="cover" id="cover" rows="3" class="form-control"><?php echo $post["cover"]; ?></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="summary" class="col-form-label">Summary:</label>
                                        <textarea name="summary" id="summary" rows="4" class="form-control"><?php echo $post["summary"]; ?></textarea>
                                    </div>

                                    <div class="mb-3 form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="featuredSwitch" name="featured" value="featured" <?php echo $post["featured"] ? "checked" : "" ?> disabled="disabled">
                                    <label class="form-check-label" for="featuredSwitch">Featured</label>
                                    </div>    

                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-info" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" name="edit" id="edit" class="btn btn-outline-warning">Edit</button>
                            </div>
                                </form>
                        </div>
                    </div>
                </div>

                <!-- delete modal -->
                <div class="modal fade" id="deleteModal<?php echo $post["id"]; ?>" tabindex="-1"
                    aria-labelledby="deleteModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="deleteModalLabel">Delete</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <form method="POST" action="/user/posts">

                                    <div class="mb-3">
                                        <p>Are you sure you want to delete post?</p>
                                    </div>
                                    <input type="hidden" name="id" value="<?php echo $post["id"]; ?>">
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-info" data-bs-dismiss="modal">Cancel</button>
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
        <a class="page-link" href="/user/posts/page/<?php echo $page - 1 ; ?>">Previous</a>
    </li>
    <?php for ($pages=1;$pages<=$result["total_pages"];$pages++) : ?>
        <li class="page-item <?php echo ($pages == $page) ? "active" : ""; ?>"><a class="page-link"
        href="/user/posts/page/<?php echo $pages; ?>"><?php echo $pages; ?></a>
        </li>
    <?php endfor; ?>
    <li class="page-item <?php echo (($page + 1) > $result["total_pages"] ) ? "disabled" : ""; ?>">
        <a class="page-link" href="/user/posts/page/<?php echo $page + 1 ; ?>">Next</a>
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
  <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header" id="toastHeader">

      <strong class="me-auto" id="toastTitle"></strong>
      <small>11 mins ago</small>
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body" id="toastMessage">
    
    </div>
  </div>
</div>

<script src="/js/eventHandler.js"></script>

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