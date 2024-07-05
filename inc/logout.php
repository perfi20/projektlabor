<?php

session_start();
$_SESSION['username'] = "";
$_SESSION['userID'] = "";
$_SESSION['email'] = "";
$_SESSION['access_level'] = "";
$_SESSION['token'] = "";
session_destroy();

header('location: ../');