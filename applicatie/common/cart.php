<?php

// Includes
require_once 'products.php';

// Start session
if (session_status() === PHP_SESSION_NONE) session_start();

// Initialize session cart (if not done already)
if (!isset($_SESSION["cart"])) $_SESSION["cart"] = [];


// Cart functions
function get_cart_size(): int {
	$total = 0;
	foreach ($_SESSION["cart"] as $product_order) $total += $product_order["quantity"];
	return $total;
}

function get_cart_price(): float {
	$total = 0;
	foreach ($_SESSION["cart"] as $product_order) $total += $product_order["quantity"] * $product_order["product"]["price"];
	return $total;
}

function add_cart_product(string $name): bool {
	$product = fetch_product($name);
	if (is_null($product)) return FALSE;

	foreach ($_SESSION["cart"] as &$product_order)
		if ($product_order["product"] == $product) {
			$product_order["quantity"] += 1;
			return TRUE;
		}

	$_SESSION["cart"][count($_SESSION["cart"])] = [
		"product" => $product,
		"quantity" => 1
	];

	return TRUE;
}

function remove_cart_product(string $name): bool {
	$product = fetch_product($name);
	if (is_null($product)) return FALSE;

	foreach ($_SESSION["cart"] as &$product_order)
		if ($product_order["product"] == $product) {
			if ($product_order["quantity"] == 0) return FALSE;
			$product_order["quantity"] -= 1; // This doesn't actually edit the cart??
			return TRUE;
		}

	return FALSE;
}

function clear_cart(): void {
	$_SESSION["cart"] = [];
}

function clean_cart(): void {
	$products = fetch_products();
	$cleaned_cart = [];

	foreach ($_SESSION["cart"] as $product_order) {
		// First get rid of all the empty product orders
		if ($product_order["quantity"] == 0) continue;

		// Then check for product validity on each one
		if (!in_array($product_order["product"], $products)) continue;
		
		$cleaned_cart[count($cleaned_cart)] = $product_order;
	}

	$_SESSION["cart"] = $cleaned_cart;
}

?>

