<?php

// Includes
require_once __DIR__ . "/../common/obj/Session.php";
require_once __DIR__ . "/../common/func/redirect.php";

// Method check
if ($_SERVER["REQUEST_METHOD"] != "POST") redirect("/login");

// Globals
$user = &Session::get()->get_user();

// Make sure the user is logged in
if (is_null($user)) {
	unset($user);
	redirect("/");
}

// Edit the address
$edit_error = $user->set_address($_POST["address"]);

unset($user);

if ($edit_error != NULL) push_error($edit_error);

unset($edit_error);

redirect("/profile");

?>

