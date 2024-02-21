<link href="https://fonts.googleapis.com/css?family=Playfair&#43;Display:700,900&amp;display=swap" rel="stylesheet">
<link href="forum.css" rel="stylesheet">

<main class="container">
<div class="row g-5">
<!-- form -->
<div class="col-md-8">
    <style>
        textarea {
            border: none;
            resize: none;
            outline: none;
        }
    </style>
    <form
            action="/users/post" class="was-validated p-5"
            style="--bs-border-opacity: .5;" method="POST"
        >
        <article class="blog-post">
            <h2 class="display-5 link-body-emphasis mb-1">
                <textarea type="text" name="postTitle" id="postTitle" cols="30" required placeholder="Title"></textarea>
            </h2>
            <input type="file" id="coverImageInput" hidden>
            <img src="/src/profile_pic.jpg" id="coverImage" class="rounded img-fluid">
            <script>
                document.getElementById('coverImage').addEventListener('click', () => {
                    document.getElementById('coverImageInput').click();
                });
                let rows = 1;
                document.getElementById('postTitle').addEventListener('input', () => {
                    let lines = document.getElementById('postTitle').value.split(/\r\n|\r|\n/).length;
                    document.getElementById('postTitle').setAttribute('rows', `${lines}`);
                    
                    //https://stackoverflow.com/questions/17109702/remove-all-stylings-border-glow-from-textarea
                });
            </script>
            <select name="postCategory" id="postCategory">
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
            <h4 class="display-10 link-body-emphasis mb-1">
                <textarea name="postSummary" id="postSummary" placeholder="Summary"></textarea>
            </h4>
        </article>
    </form>
</div>

<!-- form components -->
<div class="col md-4">
    <p>Header</p>
    <p>Paragraph</p>
    <p>Paragraph</p>
    <p>Table</p>
    <p>Unordered List</p>
    <p>Ordered List</p>
    <p>Picture</p>
</div>

</div>
</main>