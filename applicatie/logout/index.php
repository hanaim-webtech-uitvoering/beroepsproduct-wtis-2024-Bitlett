<?php

// Includes
require_once 'funcs.php';


// Start session
session_start();


// Log out
logout();
header('Location: /');

?>

