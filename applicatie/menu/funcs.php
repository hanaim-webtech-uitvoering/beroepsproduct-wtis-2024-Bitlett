<?php

// Includes
require_once '../util/database.php';


// Data fetching functions
function get_menu_categories(): array {
	$database_connection = getDatabaseConnection();
	$category_rows = $database_connection->query("SELECT name FROM ProductType");

	$categories = []; $i = 0;
	while ($category_row = $category_rows->fetch()) {
		$categories[$i] = $category_row['name'];
		$i += 1;
	}

	return $categories;
}

function get_menu_products(string $category): array {
	$database_connection = getDatabaseConnection();
	$products_query = $database_connection->prepare("SELECT name, price FROM Product WHERE type_id = :category");
	$products_query->execute([':category' => $category]);

	$products = []; $i = 0;
	while ($product_row = $products_query->fetch()) {
		$products[$i] = ['name' => $product_row['name'], 'price' => $product_row['price'], 'ingredients' => get_menu_product_ingredients($product_row['name'])];
		$i += 1;
	}

	return $products;
}

function get_menu_product_ingredients(string $product_name): array {
	$database_connection = getDatabaseConnection();
	$ingredients_query = $database_connection->prepare("SELECT ingredient_name FROM Product_Ingredient WHERE product_name = :product_name");
	$ingredients_query->execute([':product_name' => $product_name]);

	$ingredients = []; $i = 0;
	while ($ingredient_row = $ingredients_query->fetch()) {
		$ingredients[$i] = $ingredient_row['ingredient_name'];
		$i += 1;
	}

	return $ingredients;
}

?>