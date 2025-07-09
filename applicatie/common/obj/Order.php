<?php

// Includes
require_once __DIR__ . "/ProductOrder.php";
require_once __DIR__ . "/User.php";
require_once __DIR__ . "/OrderStatus.php";


class Order {

	// Constructors
	function __construct(int $id, ?object $client, object $personnel, object $datetime, OrderStatus $status, string $address, array $product_orders) {
		$this->id = $id;
		$this->client = $client;
		$this->personnel = $personnel;
		$this->datetime = $datetime;
		$this->status = $status;
		$this->address = $address;
		$this->product_orders = $product_orders;
	}

	public static function fetch_by_id(int $id): ?object {
		$database_connection = get_database_connection();

		$order_query = $database_connection->prepare("SELECT client_username, personnel_username, datetime, status, address FROM Pizza_Order WHERE order_id = :order_id");
		if ($order_query == FALSE) return NULL;

		$order_query_status = $order_query->execute([":order_id" => $id]);
		if ($order_query_status == FALSE) return NULL;
		if ($order_query->rowCount() == 0) return NULL;

		$order_row = $order_query->fetch();

		$status = OrderStatus::from_int($order_row["status"]);
		if (is_null($status)) return NULL;

		return new Order($id, User::fetch_by_username($order_row["client_username"]), User::fetch_by_username($order_row["personnel_username"]), DateTime::createFromFormat("Y-m-d H:i:s.u", $order_row["datetime"]), $status, $order_row["address"], ProductOrder::fetch_by_order_id($id));
	}

	public static function fetch_by_username(string $username): array {
		$database_connection = get_database_connection();

		$order_query = $database_connection->prepare("SELECT * FROM Pizza_Order WHERE (client_username = :client_username) OR (personnel_username = :personnel_username) ORDER BY datetime DESC");
		if ($order_query == FALSE) return [];

		$order_query_status = $order_query->execute([":client_username" => $username, ":personnel_username" => $username]);
		if ($order_query_status == FALSE) return [];

		$orders = [];
		while ($order_row = $order_query->fetch()) {
			$status = OrderStatus::from_int($order_row["status"]); if (is_null($status)) continue;
			$orders[count($orders)] = new Order($order_row["order_id"], User::fetch_by_username($order_row["client_username"]), User::fetch_by_username($order_row["personnel_username"]), DateTime::createFromFormat("Y-m-d H:i:s.u", $order_row["datetime"]), $status, $order_row["address"], ProductOrder::fetch_by_order_id($order_row["order_id"]));
		}

		return $orders;
	}


	// Getters/setters
	public function get_id(): int { return $this->id; }
	public function get_client(): ?object { return $this->client; }
	public function get_personnel(): object { return $this->personnel; }
	public function get_datetime(): object { return $this->datetime; }
	public function get_status(): OrderStatus { return $this->status; }
	public function get_address(): string { return $this->address; }
	public function get_product_orders(): array { return $this->product_orders; }
	

	// Variables
	private int $id;
	private ?object $client;
	private object $personnel;
	private object $datetime;
	private OrderStatus $status;
	private string $address;
	private array $product_orders;
}

?>

