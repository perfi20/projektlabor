<?php

session_start();
$_SESSION['username'] = "";
$_SESSION['access_level'] = "";
$_SESSION['token'] = "";
session_destroy();

header('location: ../index.php');