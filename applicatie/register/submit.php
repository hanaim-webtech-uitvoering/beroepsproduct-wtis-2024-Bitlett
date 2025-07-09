<?php

// Includes
require_once __DIR__ . "/../common/obj/Session.php";
require_once __DIR__ . "/../common/func/redirect.php";

// Method check
if ($_SERVER["REQUEST_METHOD"] != "POST") redirect("/register");

// Register new client
$registration_error = Session::get()->register($_POST["username"], $_POST["password"], $_POST["first_name"], $_POST["last_name"], $_POST["address"]);

if ($registration_error != NULL) {
	Session::get()->push_error($registration_error);
	redirect("/register");
}

// Login as well for convenience
Session::get()->login($_POST["username"], $_POST["password"]);

// Go back to homepage
redirect("/");

?>

