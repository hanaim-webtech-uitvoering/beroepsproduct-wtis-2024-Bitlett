<?php

// Includes
require_once __DIR__ . "/../func/database.php";
require_once __DIR__ . "/User.php";
require_once __DIR__ . "/Cart.php";
require_once __DIR__ . "/WebError.php";


// Always have an active session
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION["data"])) $_SESSION["data"] = new Session();


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

		$order_edit_query = $database_connection->prepare("UPDATE \"Pizza_Order\" SET client_username = NULL WHERE client_username = :username");
		if ($order_edit_query == FALSE) return "Bestelling prepared statement aanmaken mislukt!";

		$order_edit_status = $order_edit_query->execute([":username" => $this->user->get_username()]);
		if ($order_edit_status == FALSE) return "Bestelling prepared statement uitvoeren mislukt!";

		$delete_query = $database_connection->prepare("DELETE FROM \"User\" WHERE username = :username");
		if ($delete_query == FALSE) return "Account prepared statement aanmaken mislukt!";

		$delete_status = $delete_query->execute([":username" => $this->user->get_username()]);
		if ($delete_status == FALSE) return "Account prepared statement uitvoeren mislukt!";

		$this->logout();
		return NULL;
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

