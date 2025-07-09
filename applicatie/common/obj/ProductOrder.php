<?php

// Includes
require_once __DIR__ . "/Product.php";
require_once __DIR__ . "/../func/database.php";


class ProductOrder {

	// Constructors
	function __construct(object $product, int $quantity = 1) {
		$this->product = $product;
		$this->quantity = $quantity;
    }

	public static function fetch_by_order_id(int $order_id): array {
		$database_connection = get_database_connection();

		$product_order_query = $database_connection->prepare("SELECT product_name, quantity FROM Pizza_Order_Product WHERE order_id = :order_id");
		if ($product_order_query == FALSE) return [];

		$product_order_query_status = $product_order_query->execute([":order_id" => $order_id]);
		if ($product_order_query_status == FALSE) return [];

		$product_orders = [];
		while ($product_order_row = $product_order_query->fetch()) {
			$product = Product::fetch_by_name($product_order_row["product_name"]);
			$product_orders[count($product_orders)] = new ProductOrder($product, $product_order_row["quantity"]);
		}

		return $product_orders;
	}


	// Getters/setters
	public function &get_product(): object { return $this->product; }
	public function get_quantity(): int { return $this->quantity; }
	public function add_quantity(int $quantity = 1): void { $this->quantity += $quantity; }
	public function remove_quantity(int $quantity = 1): void { if ($this->quantity < $quantity) $this->quantity = 0; else $this->quantity -= $quantity; }
	public function get_price(): float { return $this->quantity * $this->product->get_price(); }

	// Variables
	private object $product;
	private int $quantity;
}

?>

