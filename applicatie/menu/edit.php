<?php

// Includes
require_once "../common/cart.php";

// Start session
if (session_status() === PHP_SESSION_NONE) session_start();


// Do the thing (im tired okay give me a break)
$origin = isset($_GET["origin"]) ? $_GET["origin"] : "/";
header("Location: " . $origin);

if (!isset($_GET["product_name"])) exit();
if (!isset($_GET["action"])) exit();

if ($_GET["action"] == "add") add_cart_product($_GET["product_name"]);
else if ($_GET["action"] == "remove") remove_cart_product($_GET["product_name"]);

?>

