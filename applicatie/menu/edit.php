<?php

// Includes
require_once "../common/cart.php";
require_once "../common/redirect.php";

// Do the thing (im tired okay give me a break)
if (!isset($_GET["product_name"])) exit();
if (!isset($_GET["action"])) exit();

if ($_GET["action"] == "add") add_cart_product($_GET["product_name"]);
else if ($_GET["action"] == "remove") remove_cart_product($_GET["product_name"]);

redirect("/menu")

?>

