<?php

// Includes
require_once "../common/orders.php";
require_once "../common/cart.php";
require_once "../common/errors.php";
require_once "../common/redirect.php";
require_once "../common/auth.php";

// Method check
if ($_SERVER["REQUEST_METHOD"] != "POST") redirect("/login");

// Make sure the supplied address is valid
$address_validity_error = is_address_valid($_POST["address"]);
if (!isset($_POST["address"]) || !is_string($_POST["address"]) || empty($_POST["address"]) || strlen($_POST["address"]) >= 256 || !is_null($address_validity_error)) {
	push_error("Ongeldig adres!");
	redirect("/menu");
}

// Make sure the cart actually has at least 1 product
clean_cart();
if (get_cart_size() == 0) {
	push_error("Uw winkelmandje mag niet leeg zijn!");
	redirect("/menu");
}

// Place the order
$address = $_POST["address"];
$username = get_login_status() ? $_SESSION["username"] : NULL;
$full_name = get_login_status() ? substr($_SESSION["first_name"] . " " . $_SESSION["last_name"], 0, 255) : "Onbekend";
$order_id = create_new_order($address, $username, $full_name);
foreach ($_SESSION["cart"] as $product_order) create_new_order_product($order_id, $product_order);

// Clear the cart
clear_cart();

// And go back to the menu
redirect("/menu/order_success.php");

?>

