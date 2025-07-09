<?php

// Includes
require_once __DIR__ . "/../obj/ProductOrder.php";
require_once __DIR__ . "/../obj/User.php";


class Cart {

	// Ordering
	public function order(mixed $address, ?string $username = NULL, string $full_name = "Onbekend"): ?string {
		// First make sure everything is good to go
		$this->clean(); if (count($this->product_orders) == 0) return "Winkelmandje is leeg!";
		if (is_null($address)) return "Adres mag niet leeg zijn!";
		$address_validity_error = User::is_address_valid($address); if ($address_validity_error != NULL) return $address_validity_error;

		$full_name = substr($full_name, 0, 255);

		$database_connection = get_database_connection();

		$random_personnel_query = $database_connection->query("SELECT TOP 1 username FROM \"User\" WHERE role = 'Personnel' ORDER BY NEWID()");
		if ($random_personnel_query == FALSE) return "Willekeurig personeel aanvragen mislukt!";
		if ($random_personnel_query->rowCount() == 0) return "Er is geen personeel beschikbaar!";
		$random_personnel_username = $random_personnel_query->fetch()["username"];

		// Then create the order
		$order_creation_query = $database_connection->prepare("INSERT INTO Pizza_Order (client_username, client_name, personnel_username, datetime, status, address) VALUES (:client_username, :client_name, :personnel_username, GETDATE(), 1, :address)");
		if ($order_creation_query == FALSE) return "Bestelling prepared statement maken mislukt!";
		
		$order_creation_status = $order_creation_query->execute(["client_username" => $username, ":client_name" => $full_name, ":personnel_username" => $random_personnel_username, ":address" => $address]);
		if ($order_creation_status == FALSE) return "Bestelling prepared statement uitvoeren mislukt!";

		$order_id_query = $database_connection->query("SELECT TOP 1 order_id FROM Pizza_Order ORDER BY datetime DESC");
		if ($order_id_query == FALSE) return "Bestelling ID opvangen mislukt!";
		if ($order_id_query->rowCount() == 0) return "Bestelling plaatsen mislukt!";
		$order_id = $order_id_query->fetch()["order_id"];

		// Then create all the order products
		foreach ($this->product_orders as $product_order) {
			$order_product_creation_query = $database_connection->prepare("INSERT INTO Pizza_Order_Product (order_id, product_name, quantity) VALUES (:order_id, :product_name, :quantity)");
			if ($order_product_creation_query == FALSE) return "Product bestelling prepared statement maken mislukt!";

			$order_product_creation_status = $order_product_creation_query->execute([":order_id" => $order_id, ":product_name" => $product_order->get_product()->get_name(), ":quantity" => $product_order->get_quantity()]);
			if ($order_product_creation_status == FALSE) return "Product bestelling prepared statement uitvoeren mislukt!";
		}

		// And then clear the cart
		$this->clear();

		return NULL;
	}
	

	// Getters/setters
	public function get_size(): int {
		$total = 0;
		foreach ($this->product_orders as $product_order) $total += $product_order->get_quantity();
		return $total;
	}

	public function get_product_quantity(object $product): int {
		foreach ($this->product_orders as $product_order) if ($product_order->get_product() == $product) return $product_order->get_quantity();
		return 0;
	}

	public function get_price(): float {
		$total = 0;
		foreach ($this->product_orders as $product_order) $total += $product_order->get_price();
		return $total;
	}

	public function add(mixed $product_name, int $quantity = 1): void {
		$product = Product::fetch_by_name($product_name);
		if (is_null($product)) return;

		foreach ($this->product_orders as &$product_order)
			if ($product_order->get_product() == $product) {
				$product_order->add_quantity($quantity);
				return;
			}
		$this->product_orders[count($this->product_orders)] = new ProductOrder($product, $quantity);
	}

	public function remove(mixed $product_name, int $quantity = 1): void {
		$product = Product::fetch_by_name($product_name);
		if (is_null($product)) return;

		foreach ($this->product_orders as &$product_order)
			if ($product_order->get_product() == $product) {
				$product_order->remove_quantity($quantity);
				$this->clean();
				return;
			}
	}

	public function clear(): void {
		$this->product_orders = [];
	}

	public function clean(): void {
		$clean_product_orders = [];

		foreach ($this->product_orders as $product_order)
			if ($product_order->get_quantity() != 0)
				$clean_product_orders[count($clean_product_orders)] = $product_order;

		$this->product_orders = $clean_product_orders;
	}


	// Variables
	private array $product_orders = [];
}

?>

