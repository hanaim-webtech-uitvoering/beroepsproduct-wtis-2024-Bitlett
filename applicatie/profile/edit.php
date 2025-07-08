<?php

// Includes
require "../common/auth.php";
require "../common/errors.php";
require "../common/redirect.php";

// Method check
if ($_SERVER["REQUEST_METHOD"] != "POST") redirect("/login");

// Make sure the user is logged in
if (!get_login_status()) redirect("/");

// Edit the address
$edit_error = set_user_address($_SESSION["username"], $_POST["address"]);

if ($edit_error != NULL) push_error($edit_error);
else $_SESSION["address"] = $_POST["address"];
redirect("/profile");

?>

