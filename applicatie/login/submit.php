<?php

// Includes
require_once 'funcs.php';


// Method check
if ($_SERVER["REQUEST_METHOD"] != "POST") {
	echo("You're not supposed to be here...");
	exit();
}


// Start session
session_start();


// Login
$login_error = login($_POST['username'], $_POST['password']);

if ($login_error != NULL) {
	$_SESSION['errors'] = [$login_error];
	header('Location: /login');
	exit();
}


// Login as well for convenience
login($_POST['username'], $_POST['password']);
header('Location: /');

?>

