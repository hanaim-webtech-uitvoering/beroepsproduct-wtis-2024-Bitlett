<?php

// Cart stuff
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

function get_cart_total_size(): int {
	$total = 0;
	foreach ($_SESSION['cart'] as $product_name => $product_count) $total += $product_count;
	return $total;
}

function get_cart_product_count(?string $product = NULL): int {
	if ($product == NULL) return get_cart_total_size();
	return isset($_SESSION['cart'][$product]) ? $_SESSION['cart'][$product] : 0;
}

function add_cart_product(string $product): void {
	if (!isset($_SESSION['cart'][$product])) $_SESSION['cart'][$product] = 0;
	$_SESSION['cart'][$product] += 1;
}

function remove_cart_product(string $product): bool {
	if (!isset($_SESSION['cart'][$product])) $_SESSION['cart'][$product] = 0;
	if ($_SESSION['cart'][$product] == 0) return FALSE;
	$_SESSION['cart'][$product] -= 1; return TRUE;
}

?>

