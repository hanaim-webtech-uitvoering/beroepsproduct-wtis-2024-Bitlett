<?php

// Includes
require_once "database.php";


// Product functions
function fetch_products(): array {
	$database_connection = get_database_connection();
	$products_query = $database_connection->query("SELECT name, price, type_id FROM Product");

	$products = [];
	while ($product_row = $products_query->fetch())
		$products[count($products)] = [
			"name" => $product_row["name"],
			"price" => $product_row["price"],
			"ingredients" => fetch_product_ingredients($product_row["name"]),
			"type_id" => $product_row["type_id"]
		];

	return $products;
}

function fetch_product(string $name): ?array {
	$database_connection = get_database_connection();
	$product_query = $database_connection->prepare("SELECT name, price, type_id FROM Product WHERE name = :name");
	$product_query->execute([":name" => $name]);

	if ($product_query->rowCount() == 0) return NULL;

	$product_row = $product_query->fetch();

	return [
		"name" => $product_row["name"],
		"price" => $product_row["price"],
		"ingredients" => fetch_product_ingredients($product_row["name"]),
		"type_id" => $product_row["type_id"]
	];
}

function fetch_product_ingredients(string $product_name): array {
	$database_connection = get_database_connection();
	$ingredients_query = $database_connection->prepare("SELECT ingredient_name FROM Product_Ingredient WHERE product_name = :product_name");
	$ingredients_query->execute([":product_name" => $product_name]);

	$ingredients = [];
	while ($ingredient_row = $ingredients_query->fetch()) $ingredients[count($ingredients)] = $ingredient_row["ingredient_name"];

	return $ingredients;
}

?>

