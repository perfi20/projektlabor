<?php

class Post {
    
    private $date;
    private $title;
    private $cover;
    private $category;
    private $author;
    private $content;

    public function __construct($_date, $_title, $_cover, $_category, $_author, $_content) {
        $this->date =  date("M d, Y", strtotime($_date));
        $this->title = $_title;
        $this->cover = $_cover;
        $this->category = $_category;
        $this->author = $_author;
        $this->content = $_content;
    }

    public function display() {
        echo '
            <article class="blog-post">
            <h2 class="display-5 link-body-emphasis mb-1">'.$this->title.'</h2>
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
            <img loading="lazy" class="rounded img-fluid" src="'.$this->cover.'">
=======
            <img class="rounded img-fluid" src="'.$this->cover.'">
>>>>>>> f7bb41a4ac79e8a78d2a24c1de5b630fd6e09fa1
=======
            <img class="rounded img-fluid" src="'.$this->cover.'">
>>>>>>> f7bb41a4ac79e8a78d2a24c1de5b630fd6e09fa1
=======
            <img class="rounded img-fluid" src="'.$this->cover.'">
>>>>>>> refs/remotes/origin/2024
            <div class="position-relative">
            <p class="blog-post-meta postition-absolute top-0 start-0">
                <a href="/posts/category/'.$this->category.'">
                    <strong class="d-inline-block mb-2 text-primary-emphasis">
                        '.$this->category.'
                    </strong>
                </a>
            </p>
            <p class="position-absolute top-0 end-0">
                '.$this->date.' by
                <a href="/posts/from/'.$this->author.'">'.$this->author.'</a>
            </p>
            </div>
            '.$this->content.'
            </article>
        ';
    }
}

?>