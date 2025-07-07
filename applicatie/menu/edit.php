<?php

// Includes
require_once 'funcs.php';
require_once '../util/cart.php';


// Start session
session_start();


// Do the thing (im tired okay give me a break)
$origin = isset($_GET['origin']) ? $_GET['origin'] : "/";
header("Location: " . $origin);

if (!isset($_GET['product'])) exit();
if (!isset($_GET['action'])) exit();

if ($_GET['action'] == "add") add_cart_product($_GET['product']);
else if ($_GET['action'] == "remove") remove_cart_product($_GET['product']);

?>

