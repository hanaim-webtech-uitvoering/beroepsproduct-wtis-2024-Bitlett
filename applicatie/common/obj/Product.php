<?php

// Includes
require_once __DIR__ . "/../func/database.php";


class Product {
	// Constructors
	function __construct(string $name, float $price, string $type_id, array $ingredients) {
		$this->name = $name;
		$this->price = $price;
		$this->type_id = $type_id;
		$this->ingredients = $ingredients;
	}

	public static function fetch_all(): array {
		$database_connection = get_database_connection();

		$products_query = $database_connection->query("SELECT name, price, type_id FROM Product");
		if ($products_query == FALSE) return [];

		$products = [];
		while ($product_row = $products_query->fetch())
			$products[count($products)] = new Product($product_row["name"], $product_row["price"], $product_row["type_id"], Product::fetch_ingredients($product_row["name"]));

		return $products;
	}

	public static function fetch_by_type_id(mixed $type_id): array {
		$database_connection = get_database_connection();

		$products_query = $database_connection->prepare("SELECT name, price, type_id FROM Product WHERE type_id = :type_id");
		if ($products_query == FALSE) return [];

		$products_status = $products_query->execute([":type_id" => $type_id]);
		if ($products_status == FALSE) return [];

		$products = [];
		while ($product_row = $products_query->fetch())
			$products[count($products)] = new Product($product_row["name"], $product_row["price"], $product_row["type_id"], Product::fetch_ingredients($product_row["name"]));

		return $products;
	}

	public static function fetch_by_name(mixed $name): ?object {
		$database_connection = get_database_connection();

		$products_query = $database_connection->prepare("SELECT TOP 1 name, price, type_id FROM Product WHERE name = :name");
		if ($products_query == FALSE) return NULL;

		$products_status = $products_query->execute([":name" => $name]);
		if ($products_status == FALSE) return NULL;
		if ($products_query->rowCount() == 0) return NULL;

		$product_row = $products_query->fetch();
		return new Product($product_row["name"], $product_row["price"], $product_row["type_id"], Product::fetch_ingredients($product_row["name"]));;
	}

	private static function fetch_ingredients(string $name): array {
		$database_connection = get_database_connection();
		$ingredients_query = $database_connection->prepare("SELECT ingredient_name FROM Product_Ingredient WHERE product_name = :product_name");
		$ingredients_query->execute([":product_name" => $name]);

		$ingredients = [];
		while ($ingredient_row = $ingredients_query->fetch()) $ingredients[count($ingredients)] = $ingredient_row["ingredient_name"];

		return $ingredients;
	}


	// Getters/setters
	public function get_name(): string { return $this->name; }
	public function get_price(): float { return $this->price; }
	public function get_type_id(): string { return $this->type_id; }
	public function get_ingredients(): array { return $this->ingredients; }


	// Variables
	private string $name;
	private float $price;
	private string $type_id;
	private array $ingredients;
}

?>

