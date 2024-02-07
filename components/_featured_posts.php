<?php 

$postfields = json_encode(['featured' => 'yes']);
$result = curl('posts', 'POST', $postfields, true);

$content = $result["posts"];

for ($i=0; $i<3; $i++) {
  if ($i == 0) $first = $content[$i];
  if ($i == 1) $second = $content[$i];
  if ($i == 2) $third = $content[$i];
}

?>

<div class="p-4 p-md-5 mb-4 rounded">
    <div class="col-lg-6 px-0">
      <h1 class="display-4 fst-italic"><?php echo $first["title"]; ?></h1>
      <p class="lead my-3"><?php echo $first["summary"]; ?></p>
      <p class="lead mb-0"><a href="/post/<?php echo $first["id"]; ?>" class="fw-bold">Continue reading...</a></p>
    </div>
  </div>

  <div class="row md-2">
    <div class="col-md-6">
      <div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
        <div class="col p-4 d-flex flex-column position-static">
          <strong class="d-inline-block mb-2 text-primary-emphasis"><?php echo $second["category"]; ?></strong>
          <h3 class="mb-0"><?php echo $second["title"]; ?></h3>
          <div class="mb-1 text-body-secondary">Nov 12</div>
          <p class="card-text mb-auto"><?php echo $second["summary"]; ?></p>
          <a href="/post/<?php echo $second["id"]; ?>" class="icon-link gap-1 icon-link-hover stretched-link">
            Continue reading
            <svg class="bi"><use xlink:href="#chevron-right"/></svg>
          </a>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
        <div class="col p-4 d-flex flex-column position-static">
          <strong class="d-inline-block mb-2 text-success-emphasis"><?php echo $third["category"] ?></strong>
          <h3 class="mb-0"><?php echo $third["title"]; ?></h3>
          <div class="mb-1 text-body-secondary">Nov 11</div>
          <p class="mb-auto"><?php echo $third["summary"]; ?></p>
          <a href="/post/<?php echo $third["id"]; ?>" class="icon-link gap-1 icon-link-hover stretched-link">
            Continue reading
            <svg class="bi"><use xlink:href="#chevron-right"/></svg>
          </a>
        </div>
      </div>
    </div>
  </div>