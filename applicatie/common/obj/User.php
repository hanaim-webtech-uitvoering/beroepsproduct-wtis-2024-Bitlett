<?php

// Includes
require_once __DIR__ . "/../func/database.php";


class User {

	// Constructors
	function __construct(string $username, string $password, string $first_name, string $last_name, ?string $address = NULL, string $role = "Client") {
		if (empty($address)) $address = NULL;

		$this->username = $username;
		$this->password = $password;
		$this->first_name = $first_name;
		$this->last_name = $last_name;
		$this->role = $role;
		$this->address = $address;
    }

	public static function fetch_by_username(mixed $username): ?object {
		if (!is_null(User::is_username_valid($username))) return NULL;

		$database_connection = get_database_connection();

		$user_query = $database_connection->prepare("SELECT * FROM \"User\" WHERE (LOWER(username) = :username)");
		if ($user_query == FALSE) return NULL;

		$user_query_status = $user_query->execute([":username" => strtolower($username)]);
		if ($user_query_status == FALSE) return NULL;
		if ($user_query->rowCount() == 0) return NULL;

		$user_row = $user_query->fetch();
		return new User($user_row["username"], $user_row["password"], $user_row["first_name"], $user_row["last_name"], $user_row["address"], $user_row["role"],);
	}


	// Registration
	public static function is_registration_valid(mixed $username, mixed $password, mixed $first_name, mixed $last_name, mixed $address = NULL): ?string {
		if (empty($address)) $address = NULL;

		$username_validity_error = User::is_username_valid($username); if ($username_validity_error != NULL) return $username_validity_error;
		if (!is_null(User::fetch_by_username($username))) return "Gebruikersnaam is al bezet!";
		$password_validity_error = User::is_password_valid($password); if ($password_validity_error != NULL) return $password_validity_error;
		$first_name_validity_error = User::is_first_name_valid($first_name); if ($first_name_validity_error != NULL) return $first_name_validity_error;
		$last_name_validity_error = User::is_last_name_valid($last_name); if ($last_name_validity_error != NULL) return $last_name_validity_error;
		$address_validity_error = User::is_address_valid($address); if ($address_validity_error != NULL) return $address_validity_error;

		return NULL;
	}


	// Login
	public static function is_login_valid(mixed $username, mixed $password): ?string {
		$username_validity_error = User::is_username_valid($username); if ($username_validity_error != NULL) return $username_validity_error;
		if (is_null(User::fetch_by_username($username))) return "Gebruiker bestaat niet!";
		$password_validity_error = User::is_password_valid($password); if ($password_validity_error != NULL) return $password_validity_error;
		return NULL;
	}


	// Data Validity
	public static function is_username_valid(mixed $username): ?string {
		if (!is_string($username)) return "Gebruikersnaam moet tekst zijn!";
		if (empty($username)) return "Gebruikersnaam mag niet leeg zijn!";
		if (strlen($username) > 255) return "Gebruikersnaam moet korter dan 256 tekens zijn!";
		return NULL;
	}

	public static function is_password_valid(mixed $password): ?string {
		if (!is_string($password)) return "Wachtwoord moet tekst zijn!";
		if (empty($password)) return "Wachtwoord mag niet leeg zijn!";
		return NULL;
	}

	public static function is_first_name_valid(mixed $first_name): ?string {
		if (!is_string($first_name)) return "Voornaam moet tekst zijn!";
		if (empty($first_name)) return "Voornaam mag niet leeg zijn!";
		if (strlen($first_name) > 255) return "Voornaam moet korter dan 256 tekens zijn!";
		return NULL;
	}

	public static function is_last_name_valid(mixed $last_name): ?string {
		if (!is_string($last_name)) return "Achternaam moet tekst zijn!";
		if (empty($last_name)) return "Achternaam mag niet leeg zijn!";
		if (strlen($last_name) > 255) return "Achternaam moet korter dan 256 tekens zijn!";
		return NULL;
	}

	public static function is_address_valid(mixed $address): ?string {
		if ($address == NULL) return NULL;
		if (!is_string($address)) return "Achternaam moet tekst of leeg zijn!";
		if (strlen($address) > 255) return "Adres moet korter dan 256 tekens zijn!";
		return NULL;
	}


	// Getters/setters
	public function get_username(): string { return $this->username; }

	public function get_password(): string { return $this->password; }

	public function get_first_name(): string { return $this->first_name; }
	public function get_last_name(): string { return $this->last_name; }
	public function get_full_name(): string { return $this->get_first_name() . " " . $this->get_last_name(); }

	public function get_role(): string { return $this->role; }

	public function get_address(): ?string { return $this->address; }
	public function set_address(mixed $address): ?string {
		$address = empty($address) ? NULL : $address;

		$address_validity_error = User::is_address_valid($address); if ($address_validity_error != NULL) return $address_validity_error;

		$database_connection = get_database_connection();

		$address_edit_query = $database_connection->prepare("UPDATE \"User\" SET address = :address WHERE username = :username");
		if ($address_edit_query == FALSE) return "Prepared statement aanmaken mislukt!";

		$address_edit_status = $address_edit_query->execute([":address" => $address, ":username" => $this->username]);
		if ($address_edit_status == FALSE) return "Prepared statement uitvoeren mislukt!";

		$this->address = $address;
		return NULL;
	}

	
	// Variables
	private string $username;
	private string $password;
	private string $first_name;
	private string $last_name;
	private string $role;
	private ?string $address;
}

?>

