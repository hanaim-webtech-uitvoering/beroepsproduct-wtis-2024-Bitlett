<?php

// Includes
require_once "../common/auth.php";
require_once "../common/redirect.php";

// Make sure the user is even logged in
if (!get_login_status()) redirect("/");

// Delete the account
delete_client($_SESSION["username"]);

// Log out
logout();

// Go back to the home page
redirect("/");

?>

