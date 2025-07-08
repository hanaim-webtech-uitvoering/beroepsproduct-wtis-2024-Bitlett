<?php

// Start session
if (session_status() === PHP_SESSION_NONE) session_start();

// Includes
require 'funcs.php';
require '../login/funcs.php';
require '../util/errors.php';
require '../util/redirect.php';

// Method check
if ($_SERVER["REQUEST_METHOD"] != "POST") redirect("/register");

// Register new client
$registration_error = register_new_client($_POST['username'], $_POST['password'], $_POST['first_name'], $_POST['last_name'], $_POST['address']);
if ($registration_error != NULL) {
	push_error($registration_error);
	redirect("/register");
}

// Login as well for convenience
login($_POST['username'], $_POST['password']);
redirect("/");

?>

