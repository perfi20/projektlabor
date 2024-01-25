<?php 

$postfields = json_encode(['archive' => "yes"]); 
$result = curl('posts', 'POST', $postfields, true);

// if (!$result["success"]) {

// }

?>

<div class="p-4">
    <h4 class="fst-italic">Archives</h4>
    <ol class="list-unstyled mb-0">

    <?php foreach ($result as $archive) : ?>

        <li><a href="index.php?date=<?php echo $archive["month_year"]; ?>"><?php echo $archive["month_year"]; ?></a></li>

    <?php endforeach; ?>
    </ol>
</div>