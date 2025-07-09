<?php

// Includes
require_once __DIR__ . "/../func/database.php";
require_once __DIR__ . "/../obj/User.php";
require_once __DIR__ . "/../obj/Cart.php";
require_once __DIR__ . "/../obj/WebError.php";


// Always have an active session
if (session_status() === PHP_SESSION_NONE) session_start();
if (is_null($_SESSION["data"])) $_SESSION["data"] = new Session();


class Session {

	// Constructors
	function __construct() {
		$this->user = NULL;
		$this->cart = new Cart();
		$this->errors = [];
	}

	public static function &get(): object { return $_SESSION["data"]; }
	
	
	// User functions
	public static function register(mixed $username, mixed $password, mixed $first_name, mixed $last_name, mixed $address = NULL): ?string {
		if (empty($address)) $address = NULL;

		$validity_error = User::is_registration_valid($username, $password, $first_name, $last_name, $address);
		if (!is_null($validity_error)) return $validity_error;

		$database_connection = get_database_connection();

		$register_query = $database_connection->prepare("INSERT INTO \"User\" (username, password, first_name, last_name, role, address) VALUES (:username, :password, :first_name, :last_name, 'Client', :address)");
		if ($register_query == FALSE) return "Prepared statement aanmaken mislukt!";
		
		$registration_status = $register_query->execute([":username" => $username, ":password" => password_hash($password, PASSWORD_DEFAULT), ":first_name" => $first_name, ":last_name" => $last_name, ":address" => $address]);
		if ($registration_status == FALSE) return "Prepared statement uitvoeren mislukt!";

		return NULL;
	}

	public function login(mixed $username, mixed $password): ?string {
		$validity_error = User::is_login_valid($username, $password);
		if (!is_null($validity_error)) return $validity_error;

		$user = User::fetch_by_username($username);
		if (!password_verify($password, $user->get_password())) return "Fout wachtwoord!";

		$this->user = $user;
		return NULL;
	}

	public function logout(): void { $this->user = NULL; }

	public function delete_account(): ?string {
		if (is_null($this->user)) return "U bent niet ingelogd!";

		$database_connection = get_database_connection();

		$delete_query = $database_connection->prepare("DELETE FROM \"User\" WHERE username = :username");
		if ($delete_query == FALSE) return "Prepared statement aanmaken mislukt!";

		$delete_status = $delete_query->execute([":username" => $this->user->get_username()]);
		if ($delete_status == FALSE) return "Prepared statement uitvoeren mislukt!";

		$this->logout();
		return NULL;
	}


	// Order functions
	function place_order(): int {
		$database_connection = get_database_connection();
		$order_creation_query = $database_connection->prepare("INSERT INTO Pizza_Order (client_username, client_name, personnel_username, datetime, status, address) VALUES (:client_username, :client_name, :personnel_username, GETDATE(), 1, :address)");
		$order_creation_query->execute(["client_username" => $username, ":client_name" => $full_name, ":personnel_username" => fetch_random_personnel_username(), ":address" => $address]);

		$order_id_query = $database_connection->query("SELECT TOP 1 order_id FROM Pizza_Order ORDER BY datetime DESC");
		return $order_id_query->fetch()["order_id"];
	}


	// Convenience functions
	public static function sanitize_user_input(string $input): string {
		return htmlspecialchars(strip_tags($input));
	}


	// Getters/setters
	public function &get_user(): ?object { return $this->user; }
	public function &get_cart(): object { return $this->cart; }
	public function get_errors(): array { return $this->errors; }
	public function push_error(string $error): void { $this->errors[count($this->errors)] = new WebError($error); }
	public function clear_errors(): void { $this->errors = []; }
	

	// Variables
	private ?object $user;
	private object $cart;
	private array $errors;
}


?>

