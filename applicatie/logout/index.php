<?php

// Includes
require __DIR__ . "/../common/obj/Session.php";
require __DIR__ . "/../common/func/redirect.php";

// Log out
Session::get()->logout();

// Back to home page
redirect("/");

?>

