<?php

require('components/curl.php');
require('inc/loggedInHeader.php');

?>

	<div class="container">
		<!-- admin panel list group -->
		<div class="container d-flex align-items-start">
			<div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
				<button
					class="btn btn-lg btn-light m-2 p-2 active" id="v-pills-events-tab"
					data-bs-toggle="pill"data-bs-target="#v-pills-events" type="button"
					role="tab" aria-controls="v-pills-events" aria-selected="true">Események
				</button>
				<button
					class="btn btn-lg btn-light m-2 p-2" id="v-pills-forum-tab"
					data-bs-toggle="pill"data-bs-target="#v-pills-forum" type="button"
					role="tab" aria-controls="v-pills-forum" aria-selected="false">Fórum
				</button>
				<button
					class="btn btn-lg btn-light m-2 p-2" id="v-pills-users-tab"
					data-bs-toggle="pill"data-bs-target="#v-pills-users"
					type="button" role="tab" aria-controls="v-pills-users" aria-selected="false">Felhasználók
				</button>
				<button
					class="btn btn-lg btn-light m-2 p-2" id="v-pills-statistics-tab"
					data-bs-toggle="pill"data-bs-target="#v-pills-statistics" type="button"
					role="tab" aria-controls="v-pills-statistics" aria-selected="false">Statisztikák
				</button>
				<button
					class="btn btn-lg btn-light m-2 p-2" id="v-pills-other-tab"
					data-bs-toggle="pill"data-bs-target="#v-pills-other" type="button"
					role="tab" aria-controls="v-pills-other" aria-selected="false">Egyéb
				</button>
			</div>
			
			<div class="container tab-content m-3" id="v-pills-tabContent">
					<!-- LIST POSTS -->
					<div class="tab-pane fade show active" id="v-pills-events" role="tabpanel" aria-labelledby="v-pills-events-tab" tabindex="0">
						<?php include('components/_posts.php'); ?>
					</div>

					<!-- TODO: FORUM -->
					<div class="tab-pane fade" id="v-pills-forum" role="tabpanel" aria-labelledby="v-pills-forum-tab" tabindex="1">
						Some placeholder content in a paragraph relating to "Profile". And some more content, used here just
						to pad out and fill this tab panel. In production, you would obviously have more real content here.
						And not just text. It could be anything, really. Text, images, forms.
					</div>

					<!-- LIST USERS -->
					<div class="tab-pane fade" id="v-pills-users" role="tabpanel" aria-labelledby="v-pills-users-tab" tabindex="2">
						<?php include('components/_users.php'); ?>
					</div>

					<!-- STATS -->
					<div class="tab-pane fade" id="v-pills-statistics" role="tabpanel" aria-labelledby="v-pills-statistics-tab" tabindex="3">
						<?php include('components/_stats.php'); ?>
					</div>

					<!-- TODO: OTHER -->
					<div class="tab-pane fade" id="v-pills-other" role="tabpanel" aria-labelledby="v-pills-other-tab" tabindex="3">
						Some placeholder content in a paragraph relating to "asd". And some more content, used here just
						to pad out and fill this tab panel. In production, you would obviously have more real content here.
						And not just text. It could be anything, really. Text, images, forms.
					</div>	
			</div>
		</div>
	</div>
	
<?php require('inc/footer.php'); ?>