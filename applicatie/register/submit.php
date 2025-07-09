<?php

// Includes
require_once __DIR__ . "/../common/obj/Session.php";
require_once __DIR__ . "/../common/func/redirect.php";

// Method check
if ($_SERVER["REQUEST_METHOD"] != "POST") redirect("/register");

// Globals
$session = &Session::get();

// Register new client
$registration_error = $session->register($_POST["username"], $_POST["password"], $_POST["first_name"], $_POST["last_name"], $_POST["address"]);

if ($registration_error != NULL) {
	$session->push_error($registration_error);
	unset($session);
	unset($registration_error);
	redirect("/register");
}

unset($registration_error);

// Login as well for convenience
$session->login($_POST["username"], $_POST["password"]);

unset($session);

// Go back to homepage
redirect("/");

?>

