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

// Register new client
register_new_client($_POST['username'], $_POST['password'], $_POST['first_name'], $_POST['last_name'], $_POST['address']);

// Login as well for convenience
login($_POST['username'], $_POST['password']);

?>

