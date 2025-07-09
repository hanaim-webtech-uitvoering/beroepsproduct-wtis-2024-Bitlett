<?php

// Includes
require_once __DIR__ . "/../common/obj/Session.php";
require_once __DIR__ . "/../common/func/redirect.php";

// Delete the account
Session::get()->delete_account();

// Go back to the home page
redirect("/");

?>

