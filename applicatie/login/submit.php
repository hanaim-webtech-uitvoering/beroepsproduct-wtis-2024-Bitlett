<?php

// Includes
require "../common/auth.php";
require "../common/errors.php";
require "../common/redirect.php";

// Method check
if ($_SERVER["REQUEST_METHOD"] != "POST") redirect("/login");

// Login
$login_error = login($_POST['username'], $_POST['password']);

if ($login_error != NULL) {
	push_error($login_error);
	redirect("/login");
}

redirect("/");

?>

