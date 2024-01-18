<?php
$postContent = '';
$error = "";

if (isset($_POST['postSubmit'])) {

    $postContent = '<article class="blog-post">';

    $postTitle = $_POST['postTitle'];
    $postContent .= '<h2 class="display-5 link-body-emphasis mb-1">'.$postTitle.'</h2>';

    $date = date("M d, Y");
    $postContent .= '<p class="blog-post-meta">
        '.$date.' by
        <a href="../index.php?user='.$_SESSION['username'].'">'.$_SESSION['username'].'</a>
    </p>';

    $postCategory = $_POST['postCategory'];
    $postContent .= '<a href="index.php?category='.$postCategory.'"><strong class="d-inline-block mb-2 text-primary-emphasis">'.$postCategory.'</strong></a>';

    $postSum = $_POST['postSum'];

    $postInputs = $_POST['form'];

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
            }
        }
    }

    $postContent .= '</article>';

    $postfields = json_encode([
        'title' => $postTitle,
        'category' => $postCategory,
        'summary' => $postSum,
        'publisher' => $_SESSION['username'],
        'content' => $postContent
    ]);

    $data = curl('posts', 'PUT', $postfields);

    // redirect user to the new post
    if ($data->success == true) {
        header('location: ../forum.php?post='.$data->id);
    }

    $error = $data->error;
    
    //echo $postContent;
}

?>

<div class="container">
    <form id="create_post" class="was-validated border border-secondary p-5 rounded" style="--bs-border-opacity: .5;" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" onsubmit="generatePost(<?php echo $_POST['form']; ?>)">
    

        <h1>Create a new post</h1>
        <p class="text-light text-center bg-danger mt-5"><?php echo $error; ?></p>
        
        <div class="mb-5">
            <label for="postTitle">Title</label>
            <input type="text" name="postTitle" id="postTitle" class="form-control bg-dark text-light" required>
        </div>

        <div class="mb-5">
            <label for="postCategory">Category</label>
            <select class="form-control bg-dark text-light" name="postCategory" id="postCategory">
                <option default value="other">Other</option>
                <option value="world">World</option>
                <option value="u.S">U.S</option>
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
            </select>
        </div>

        <div class="mb-5">
            <label for="postSum">Summary</label>
            <textarea type="text" name="postSum" id="postSum" class="form-control bg-dark text-light" rows="3" required></textarea>
        </div>
        
        <div class="mb-5">
            <label for="newContent">New...</label>
            <select class="form-control bg-dark text-light" oninput="addNewContent()" name="newContent" id="newContentType">
                <option value="">Select an element</option>
                <option value="p">New Paragraph</option>
                <option value="q" disabled>New Quote</option>
                <option value="t">New Table</option>
                <option value="ul">New Unordered List</option>
                <option value="ol">New Ordered List</option>
            </select>
        </div>
        <div id="form_content"></div>
        
        <button type="submit" name="postSubmit" class="btn btn-outline-success align-middle">Submit</button>
        <button type="button" id="deleteContent" name="deleteContent" class="btn btn-outline-danger" onclick="removeFormContent()">Clear</button>

    </form>
</div>