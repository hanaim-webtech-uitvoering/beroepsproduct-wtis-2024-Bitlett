<?php

// Includes
require_once 'funcs.php';
require_once '../login/funcs.php';


// Method check
if ($_SERVER["REQUEST_METHOD"] != "POST") {
	echo("You're not supposed to be here...");
	exit();
}


// Start session
session_start();


// Register new client
$registration_error = register_new_client($_POST['username'], $_POST['password'], $_POST['first_name'], $_POST['last_name'], $_POST['address']);

if ($registration_error != NULL) {
	$_SESSION['errors'] = [$registration_error];
	header('Location: /register');
	exit();
}


// Login as well for convenience
login($_POST['username'], $_POST['password']);
header('Location: /');

?>

