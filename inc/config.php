<?php
// define('DB_HOST', 'mysql.rackhost.hu');
// define('DB_USER', 'c22654perfi');
// define('DB_PASS', 'wegojim');
// define('DB_NAME', 'c22654drupal');

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'labor');

error_reporting(0);
mysqli_report(MYSQLI_REPORT_OFF);

$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
// if (mysqli_connect_errno()) {
// 	header('location: nemjo.php');
// }
// // $db = mysqli_connect("localhost", "bugerweb", "654321", "bugerweb") or exit(header('location: nemjo.php'));
if ($db) {
	mysqli_query($db, "SET CHARACTER SET 'utf8'");
	mysqli_query($db, "SET SESSION collation_connection = 'utf8mb4_unicode_ci'");
}

?>

<style>
	@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;400&display=swap');
</style>
