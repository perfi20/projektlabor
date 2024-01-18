<?php

$postfields = json_encode(['recent' => "3"]); 
$result = curl('posts', 'POST', $postfields, true);

// if (!$result["success"]) {

// }

$recentPosts = $result["posts"];
//var_dump($recentPosts);
?>


<div>
    <h4 class="fst-italic">Recent posts</h4>
    <ul class="list-unstyled">

        <?php foreach ($recentPosts as $post) : 
        //foreach ($post as $key => $value) : ?>
            
            <li>
                <a class="d-flex flex-column flex-lg-row gap-3 align-items-start align-items-lg-center py-3 link-body-emphasis text-decoration-none border-top"
                    href="index.php?post=<?php  echo $post["id"]; ?>">

                <svg class="bd-placeholder-img" width="100%" height="96" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false"><rect width="100%" height="100%" fill="#777"/></svg>
                <div class="col-lg-8">
                    <h6 class="mb-0"><?php echo $post["title"]; ?></h6>
                </div>
                </a>
            </li>

        <?php //endforeach; 
        endforeach; ?>

    </ul>
</div>