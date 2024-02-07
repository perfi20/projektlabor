<?php
$postContent = '';
$error = "";


?>

<div class="container">
    <form id="create_post" class="was-validated border border-secondary p-5 rounded" style="--bs-border-opacity: .5;" method="POST" action="/user/posts">
    

        <h1>Create a new post</h1>
        <p class="text-light text-center bg-danger mt-5"><?php echo $error; ?></p>
        
        <div class="mb-5">
            <label for="postTitle">Title</label>
            <input type="text" name="postTitle" id="postTitle" class="form-control bg-dark text-light" required>
        </div>

        <div class="mb-5">
            <label for="postCategory">Category</label>
            <select class="form-control bg-dark text-light" name="postCategory" id="postCategory">
                <option default value="Other">Other</option>
                <option value="World">World</option>
                <option value="U.S">U.S</option>
                <option value="Technology">Technology</option>
                <option value="Design">Design</option>
                <option value="Culture">Culture</option>
                <option value="Business">Business</option>
                <option value="Politics">Politics</option>
                <option value="Opinion">Opinion</option>
                <option value="Science">Science</option>
                <option value="Health">Health</option>
                <option value="Style">Style</option>
                <option value="Travel">Travel</option>
            </select>
        </div>

        <div class="mb-5">
            <label for="postSum">Summary</label>
            <textarea type="text" name="postSum" id="postSum" class="form-control bg-dark text-light" rows="3" required></textarea>
        </div>

        <div class="mb-5">
            <label for="coverImage">Cover Image URL</label>
            <input type="url" name="coverImage" id="coverImage" class="form-control bg-dark text-light" required>
        </div>

        <div id="form_content" data-bs-theme="dark"></div>

        <div class="mb-5">
            <label for="newContent">New...</label>
            <select class="form-control bg-dark text-light" oninput="addNewContent(this.value)" name="newContent" id="newContentType">
                <option value="">Select an element</option>
                <option value="h">New Header</option>
                <option value="p">New Paragraph</option>
                <option value="q" disabled>New Quote</option>
                <option value="t">New Table</option>
                <option value="ul">New Unordered List</option>
                <option value="ol">New Ordered List</option>
                <option value="pic">New Picture</option>
            </select>
        </div>
        
        <button type="submit" name="postSubmit" class="btn btn-outline-success align-middle">Submit</button>
        <button
            type="button" id="deleteContent" name="deleteContent" class="btn btn-outline-danger" 
            onclick="removeFormContent()">Clear
        </button>

    </form>
</div>