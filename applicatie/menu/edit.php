<?php

// Includes
require_once __DIR__ . "/../common/obj/Session.php";
require_once __DIR__ . "/../common/func/redirect.php";

// Edit the cart
$cart = &Session::get()->get_cart();

if ($_GET["action"] == "add") $cart->add($_GET["product_name"]);
else if ($_GET["action"] == "remove") $cart->remove($_GET["product_name"]);

unset($cart);

// Go back to the menu
redirect("/menu");

?>

