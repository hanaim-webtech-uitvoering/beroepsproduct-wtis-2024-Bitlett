<?php

// Includes
require_once "database.php";


// Data fetching functions
function fetch_random_personnel_username(): ?string {
	$database_connection = get_database_connection();
	$random_personnel_query = $database_connection->query("SELECT TOP 1 username FROM \"User\" WHERE role = 'Personnel' ORDER BY NEWID()");
	if ($random_personnel_query->rowCount() == 0) return NULL;
	return $random_personnel_query->fetch()["username"];
}


// Order creation
function create_new_order(string $address, ?string $username = NULL, string $full_name = "Onbekend"): int {
	$database_connection = get_database_connection();
	$order_creation_query = $database_connection->prepare("INSERT INTO Pizza_Order (client_username, client_name, personnel_username, datetime, status, address) VALUES (:client_username, :client_name, :personnel_username, GETDATE(), 1, :address)");
	$order_creation_query->execute(["client_username" => $username, ":client_name" => $full_name, ":personnel_username" => fetch_random_personnel_username(), ":address" => $address]);

	$order_id_query = $database_connection->query("SELECT TOP 1 order_id FROM Pizza_Order ORDER BY datetime DESC");
	return $order_id_query->fetch()["order_id"];
}

function create_new_order_product(int $order_id, array $product_order): void {
	$database_connection = get_database_connection();
	$order_creation_query = $database_connection->prepare("INSERT INTO Pizza_Order_Product (order_id, product_name, quantity) VALUES (:order_id, :product_name, :quantity)");
	$order_creation_query->execute([":order_id" => $order_id, ":product_name" => $product_order["product"]["name"], ":quantity" => $product_order["quantity"]]);
}

?>

