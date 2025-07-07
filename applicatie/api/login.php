<?php

// Start session
session_start();

// Includes
require_once 'account/funcs.php';

// Method check
if ($_SERVER["REQUEST_METHOD"] != "POST") {
	echo "Request type must be POST!";
	exit();
}

// Login
login($_POST['username'], $_POST['password']);

?>

