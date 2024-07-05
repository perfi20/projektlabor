<?php

session_start();

$GLOBALS["toastFunction"] = "";

require_once('components/curl.php');
require_once('components/pagination.php');
include('inc/loggedInHeader.php');

// check for authorization
if (!empty($_SESSION["username"]) && !empty($_SESSION["userID"]) && $_SESSION["access_level"] !== 1) {
	header('location: /');
}
?>

<main class="container">

<!-- page navigation -->
<nav class="nav nav-pills nav-justified">
	<a class="nav-link <?php echo $view === 'statistics' ? 'active' : ''; ?>"
    	href="/admin/statistics">Statistics</a>
	<a class="nav-link <?php echo $view === 'posts' ? 'active' : ''; ?>"
    	href="/admin/posts">Posts</a>
	<a class="nav-link <?php echo $view === 'users' ? 'active' : ''; ?>"
    	href="/admin/users">Users</a>
</nav>

<?php 
// MAIN CONTENT - website statistics
if (isset($view) && $view == 'statistics') {
	
	$postfields = json_encode(['user' => $_SESSION["username"], 'view' => 'stats']);
	$result = curl('stats', 'POST', $postfields, true);

	?>

	<div class="table-responsive">
		<table class="table align-middle">

		<thead>
			<th scope="col">Users</th>
			<th scope="col">Posts</th>
            <th scope="col">Views</th>
		</thead>

		<tbody class="table-group-divider">
			<tr>
				<th><?php echo $result["users"]; ?></th>
				<th><?php echo $result["posts"]; ?></th>
                <th><?php echo $result["views"]; ?></th>
			</tr>
		</tbody>

		</table>
	</div>

	<?php

}

// POSTS VIEW

// delete post
    if (isset($_POST['deletePost'])) {

        $postfield = json_encode(['delete' => 'true', 'id' => $_POST["id"]]);
        $result = curl('posts', 'DELETE', $postfield);

        $GLOBALS["toastFunction"] = "showToast('$result->success', '$result->message');";

    }

    // edit post
    if (isset($_POST['editPost'])) {
    
        $postfield = json_encode([
            'id' => $_POST["id"],
            'title' => $_POST["title"],
            'category' => $_POST["category"],
            'cover' => $_POST["cover"],
            'summary' => $_POST["summary"],
            'featured' => $_POST["featured"] ? true : false
        ]);
        $result = curl('posts', 'PATCH', $postfield);

        $GLOBALS["toastFunction"] = "showToast('$result->success', '$result->message');";

    }

// POSTS MAIN CONTENT
if (isset($view) && $view == "posts") {

	// pagination
    $page = isset($page) ? $page : 1;

    $order = ($order == 'desc') ? 'asc' : 'desc';
    if (!isset($order)) {
        $order = 'desc';
    }

    // order arrow
    if ($order === 'asc') {
        if ($_COOKIE['darkmode'] === 'disabled') {
            $arrow = '<img src="/src/icons8-chevron-up-64-black.png" width="20" height="20"/>';
        } else {
            $arrow = '<img src="/src/icons8-chevron-up-64.png" width="20" height="20"/>';
        }   
    } else if ($order === 'desc') {
        if ($_COOKIE['darkmode'] === 'disabled') {
            $arrow = '<img src="/src/icons8-chevron-down-64-black.png" width="20" height="20"/>';
        } else {
            $arrow = '<img src="/src/icons8-chevron-down-64.png" width="20" height="20"/>';
        }
    } 

    // request
	$postfields = json_encode([
        'user' => $_SESSION["username"],
        'view' => 'posts',
        'page' => $page,
        'limit' => 50,
        'sort' => $sort,
        'order' => $order
    ]);
	$result = curl('stats', 'POST', $postfields, true);
    $content = $result["posts"];

	?>

<!-- collapsable admin search form -->
<button type="button" class="btn btn-outline-success" data-bs-toggle="collapse"
    data-bs-target="#searchFormCollapse" aria-expanded="false">
    Filters
</button>

<div class="collapse" id="searchFormCollapse">
<div class="card card-body">
    <form method="GET" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">

        <label for="filterTitle" class="col-form-label">Title</label>
        <input type="text" name="filterTitle" id="filterTitle" class="form-control">

        <label for="filterCategory" class="col-form-label">Category</label>
        <select name="filterCategory" id="filterCategory" class="form-control">
            <option value="world">World</option>
            <option value="us">U.S</option>
            <option value="technology">Technology</option>
            <option value="design">Design</option>
            <option value="culture">Culture</option>
            <option value="business">Business</option>
            <option value="politics">Politics</option>
            <option value="opinion">Opinion</option>
            <option value="science">Science</option>
            <option value="health">Health</option>
            <option value="style">Style</option>
            <option value="travel">Travel</option>
            <option value="other">Other</option>
        </select>

        <label for="filterPublisher" class="col-form-label">Publisher</label>
        <input type="text" name="filterPublisher" id="filterPublisher" class="form-control">

        <label for="filterFeatured" class="col-form-label">Featured</label>
        <input type="radio" name="filterFeatured" id="filterFeatured" class="form-control">

        <input type="submit" value="Search" name="filterSubmit" id="filterSubmit" class="btn btn-outline-success">

    </form>
</div>
</div>



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
            <th scope="col"><a class="filter-link" href="/admin/posts/sort/title/order/<?php echo $order; ?>">Title<?php if ($sort == 'title') echo $arrow; ?></a></th>
            <th scope="col"><a class="filter-link" href="/admin/posts/sort/category/order/<?php echo $order; ?>">Category<?php if ($sort == 'category') echo $arrow; ?></a></th>
            <th scope="col"><a class="filter-link" href="/admin/posts/sort/publisher/order/<?php echo $order; ?>">Publisher<?php if ($sort == 'publisher') echo $arrow; ?></a></th>
            <th scope="col"><a class="filter-link" href="/admin/posts/sort/created_at/order/<?php echo $order; ?>">Created at<?php if ($sort == 'created_at') echo $arrow; ?></a></th>
            <th scope="col"><a class="filter-link" href="/admin/posts/sort/updated_at/order/<?php echo $order; ?>">Updated at<?php if ($sort == 'updated_at') echo $arrow; ?></a></th>
            <th scope="col"><a class="filter-link" href="/admin/posts/sort/featured/order/<?php echo $order; ?>">Featured<?php if ($sort == 'featured') echo $arrow; ?></a></th>
            <th scope="col"><a class="filter-link" href="/admin/posts/sort/views/order/<?php echo $order; ?>">Views<?php if ($sort == 'views') echo $arrow; ?></a></th>
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
            <td><?php echo strlen($post["title"]) > 60 ? substr($post['title'], 0, 60).'...' : $post["title"]; ?></td>
            <td><?php echo $post["category"] ?></td>
            <td><?php echo $post["username"] ?></td>
            <td><?php echo $post["created_at"] ?></td>
            <td><?php if (isset($post["updated_at"])) echo $post["updated_at"] ?></td>
            <td <?php echo $post['featured'] ? 'class="text-success"' : 'class="text-secondary"'; ?>><?php echo $post["featured"] ? 'Yes' : 'No'; ?></td>
            <td><?php echo $post["views"]; ?></td>
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
                                <form method="POST" action="/admin/posts">
                                    
                                    <div class="mb-3">
                                        <label for="id" class="col-form-label">ID:</label>
                                        <input type="text" name="id" id="id" class="form-control" value="<?php echo $post["id"]; ?>" readonly>
                                    </div>

                                    <div class="mb-3">
                                        <label for="title" class="col-form-label">Title:</label>
                                        <textarea name="title" id="title" rows="2" class="form-control"><?php echo $post["title"]; ?></textarea>
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
                                        <textarea name="summary" id="summary" rows="3" class="form-control"><?php echo $post["summary"];?></textarea>
                                    </div>

                                    <div class="mb-3 form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="featuredSwitch" name="featured" value="featured" <?php echo $post["featured"] ? "checked" : "" ?>>
                                    <label class="form-check-label" for="featuredSwitch">Featured</label>
                                    </div>    

                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-info" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" name="editPost" class="btn btn-outline-warning">Edit</button>
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
                                <form method="POST" action="/admin/posts">

                                    <div class="mb-3">
                                        <p>Are you sure you want to delete post?</p>
                                    </div>
                                    <input type="hidden" name="id" value="<?php echo $post["id"]; ?>">
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-info" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" name="deletePost" class="btn btn-outline-danger">Delete</button>
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


<?php
    // pagination
    $pagination = new Pagination($page, null, '/admin/posts', $result['total_pages']);
    $pagination->display();

}


// USERS VIEW

// delete user
if (isset($_POST["deleteUser"])) {

    $postfield = json_encode(['delete' => 'true', 'id' => $_POST["id"]]);
    $result = curl('user', 'DELETE', $postfield);

    $GLOBALS["toastFunction"] = "showToast('$result->success', '$result->message');";

}

// admin edit user
if (isset($_POST["editUser"])) {
    
    $postfield = json_encode([
        'id' => $_POST["id"],
        'newUsername' => $_POST["username"],
        'newEmail' => $_POST["email"],
        'newAccessLevel' => $_POST["role"] ? true : false
    ]);
    $result = curl('stats', 'PATCH', $postfield);

    $GLOBALS["toastFunction"] = "showToast('$result->success', '$result->message');";

}

// USERS MAIN CONTENT
if (isset($view) && $view == "users") {

    // pagination
    $page = isset($page) ? $page : 1;

    $orderBy = ($order == 'desc') ? 'asc' : 'desc';
    if (!isset($order)) {
        $orderBy = 'desc';
    }
    
    // order arrow
    if ($orderBy === 'asc') {
        if ($_COOKIE['darkmode'] === 'disabled') {
            $arrow = '<img src="/src/icons8-chevron-up-64-black.png" width="20" height="20"/>';
        } else {
            $arrow = '<img src="/src/icons8-chevron-up-64.png" width="20" height="20"/>';
        }   
    } else if ($orderBy === 'desc') {
        if ($_COOKIE['darkmode'] === 'disabled') {
            $arrow = '<img src="/src/icons8-chevron-down-64-black.png" width="20" height="20"/>';
        } else {
            $arrow = '<img src="/src/icons8-chevron-down-64.png" width="20" height="20"/>';
        }
    } 

    // request
	$postfields = json_encode([
        'user' => $_SESSION['username'],
        'view' => 'users',
        'page' => $page,
        'limit' => 50,
        'sort' => $sort,
        'order' => $orderBy
    ]);
	$result = curl('stats', 'POST', $postfields, true);

    $content = $result["users"];

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
            <th scope="col"><a class="filter-link" href="/admin/users/sort/username/order/<?php echo $orderBy; ?>">Username<?php if ($sort == 'username') echo $arrow; ?></a></th>
            <th scope="col"><a class="filter-link" href="/admin/users/sort/email/order/<?php echo $orderBy; ?>">Email<?php if ($sort == 'email') echo $arrow; ?></a></th>
            <th scope="col"><a class="filter-link" href="/admin/users/sort/created_at/order/<?php echo $orderBy; ?>">Created at<?php if ($sort == 'created_at') echo $arrow; ?></a></th>
            <th scope="col"><a class="filter-link" href="/admin/users/sort/updated_at/order/<?php echo $orderBy; ?>">Updated at<?php if ($sort == 'updated_at') echo $arrow; ?></a></th>
            <th scope="col"><a class="filter-link" href="/admin/users/sort/access_level/order/<?php echo $orderBy; ?>">Role<?php if ($sort == 'access_level') echo $arrow; ?></a></th>
            <th scope="col"><a class="filter-link" href="/admin/users/sort/postNumber/order/<?php echo $orderBy; ?>">Posts<?php if ($sort == 'postNumber') echo $arrow; ?></a></th>
            <th scope="col"><a class="filter-link" href="/admin/users/sort/totalViews/order/<?php echo $orderBy; ?>">Views<?php if ($sort == 'totalViews') echo $arrow; ?></a></th>
            <th scope="col">Actions</th>
        </tr>
    </thead>

    <?php $i = 0;
    foreach ($content as $user) : 
       $i++; 
    ?>
    
    <tbody class="table-group-divider">
        <tr>
            <th><?php echo $page == 1 ? $i : ($i + ($page * 25)); ?></th>
            <td><?php echo $user["username"] ?></td>
            <td><?php echo $user["email"] ?></td>
            <td><?php echo $user["created_at"] ?></td>
            <td><?php if (isset($user["updated_at"])) echo $user["updated_at"] ?></td>
            <td><?php echo ($user["access_level"] === 1) ? "Admin" : "User"; ?></td>
            <td><?php echo $user["postNumber"]; ?></td>
            <td><?php echo $user["totalViews"]; ?></td>

            <td>
                <!-- view posts button -->
                <a class="btn btn-outline-info" href="/posts/from/<?php echo $user["username"]; ?>">View Posts</a>

                <!-- edit modal button -->
                <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal"
                    data-bs-target="#editModal<?php echo $user["id"]; ?>">
                    Edit
                </button>

                <!-- delete modal button -->
                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                    data-bs-target="#deleteModal<?php echo $user["id"]; ?>">
                    Delete
                </button>

                <!-- edit modal -->

                <div class="modal fade" id="editModal<?php echo $user["id"]; ?>" tabindex="-1"
                    aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="editModalLabel">Edit</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <form method="POST" action="/admin/users">
                                    
                                    <div class="mb-3">
                                        <label for="id" class="col-form-label">ID:</label>
                                        <input type="text" name="id" id="id" class="form-control" value="<?php echo $user["id"]; ?>" readonly>
                                    </div>

                                    <div class="mb-3">
                                        <label for="username" class="col-form-label">Username:</label>
                                        <input type="text" name="username" id="username" class="form-control" value="<?php echo $user["username"]; ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="col-form-label">Email:</label>
                                        <input type="text" name="email" id="email" class="form-control" value="<?php echo $user["email"]; ?>">
                                    </div>


                                    <div class="mb-3 form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="roleSwitch" name="role" value="role" <?php echo ($user["access_level"] === 1 ) ? "checked" : "" ?>>
                                        <label class="form-check-label" for="roleSwitch">Admin</label>
                                    </div>    

                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-info" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" name="editUser" class="btn btn-outline-warning">Edit</button>
                            </div>
                                </form>
                        </div>
                    </div>
                </div>

                <!-- delete modal -->
                <div class="modal fade" id="deleteModal<?php echo $user["id"]; ?>" tabindex="-1"
                    aria-labelledby="deleteModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="deleteModalLabel">Delete</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <form method="POST" action="/admin/users">

                                    <div class="mb-3">
                                        <p>Are you sure you want to delete User?</p>
                                    </div>
                                    <input type="hidden" name="id" value="<?php echo $user["id"]; ?>">
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-info" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" name="deleteUser" class="btn btn-outline-danger">Delete</button>
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

<?php 
    // pagination
    $pagination = new Pagination($page, NULL, '/admin/users', $result['total_pages']);
    $pagination->display();

}

?>

</main>

<?php include_once('inc/footer.php'); ?>

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