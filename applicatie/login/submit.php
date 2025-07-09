<?php

// Includes
require_once __DIR__ . "/../common/obj/Session.php";
require_once __DIR__ . "/../common/func/redirect.php";

// Method check
if ($_SERVER["REQUEST_METHOD"] != "POST") redirect("/login");

// Login
$login_error = Session::get()->login($_POST['username'], $_POST['password']);

if (!is_null($login_error)) {
	Session::get()->push_error($login_error);
	unset($login_error);
	redirect("/login");
}

unset($login_error);

// Go back to homepage
redirect("/");

?>

