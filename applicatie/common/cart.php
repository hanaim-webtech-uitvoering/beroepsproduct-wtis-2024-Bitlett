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
	foreach ($_SESSION["cart"] as $product_name => $product_quantity) $total += $product_quantity;
	return $total;
}

function get_cart_cost(): float {
	$products = fetch_products();

	$product_prices_by_name = [];
	foreach ($products as $product) $product_prices_by_name[$product["name"]] = $product["price"];

	$total = 0;
	foreach ($_SESSION["cart"] as $product_name => $product_quantity) $total += $product_quantity * $product_prices_by_name[$product_name];

	return $total;
}

function get_cart_product_quantity(string $product): int {
	return isset($_SESSION["cart"][$product]) ? $_SESSION["cart"][$product] : 0;
}

function add_cart_product(string $name): bool {
	if (fetch_product($name) == NULL) {
		unset($_SESSION["cart"][$name]);
		return FALSE;
	}

	if (!isset($_SESSION["cart"][$name])) $_SESSION["cart"][$name] = 0;

	$_SESSION["cart"][$name] += 1;
	return TRUE;
}

function remove_cart_product(string $name): bool {
	if (!isset($_SESSION["cart"][$name])) return FALSE;
	if ($_SESSION["cart"][$name] == 0) return FALSE;
	$_SESSION["cart"][$name] -= 1; return TRUE;
}

function clear_cart(): void {
	$_SESSION["cart"] = [];
}

function clean_cart(): void {
	$products = fetch_products();
	$product_names = []; foreach ($products as $product) $product_names[count($product_names)] = $product["name"];

	foreach ($_SESSION["cart"] as $cart_product_name => $cart_product_quantity)
		if (!in_array($cart_product_name, $product_names)) unset($_SESSION["cart"][$cart_product_name]);
}

?>

