<?php

// Includes
require_once "database.php";

// Start session
if (session_status() === PHP_SESSION_NONE) session_start();


// General validity functions
function is_username_valid(mixed $username): ?string {
	if (!is_string($username)) return "Gebruikersnaam moet tekst zijn!";
	if (empty($username)) return "Gebruikersnaam mag niet leeg zijn!";
	if (strlen($username) > 255) return "Gebruikersnaam moet korter dan 256 tekens zijn!";
	return NULL;
}

function is_password_valid(mixed $password): ?string {
	if (!is_string($password)) return "Wachtwoord moet tekst zijn!";
	if (empty($password)) return "Wachtwoord mag niet leeg zijn!";
	return NULL;
}

function is_first_name_valid(mixed $first_name): ?string {
	if (!is_string($first_name)) return "Voornaam moet tekst zijn!";
	if (empty($first_name)) return "Voornaam mag niet leeg zijn!";
	if (strlen($first_name) > 255) return "Voornaam moet korter dan 256 tekens zijn!";
	return NULL;
}

function is_last_name_valid(mixed $last_name): ?string {
	if (!is_string($last_name)) return "Achternaam moet tekst zijn!";
	if (empty($last_name)) return "Achternaam mag niet leeg zijn!";
	if (strlen($last_name) > 255) return "Achternaam moet korter dan 256 tekens zijn!";
	return NULL;
}

function is_address_valid(mixed $address): ?string {
	if ($address == NULL) return NULL;
	if (!is_string($address)) return "Achternaam moet tekst of leeg zijn!";
	if (strlen($address) > 255) return "Adres moet korter dan 256 tekens zijn!";
	return NULL;
}


// Data getting functions
function get_clean_full_name(): ?string {
	if (empty($_SESSION['username'])) return NULL;
	return htmlspecialchars(strip_tags($_SESSION['first_name'])) . " " . htmlspecialchars(strip_tags($_SESSION['last_name']));
}


// Data fetching functions
function fetch_username_availability(mixed $username): bool {
	if (!is_string($username)) return FALSE;

	$database_connection = get_database_connection();

	$username_duplicate_query = $database_connection->prepare("SELECT COUNT(*) as Count FROM \"User\" WHERE LOWER(username) = :username");
	$username_duplicate_query->execute([":username" => strtolower($username)]);

	return $username_duplicate_query->fetch()["Count"] == 0;
}

function fetch_user_data($username): mixed {
	$database_connection = get_database_connection();
	$login_query = $database_connection->prepare("SELECT * FROM \"User\" WHERE (LOWER(username) = :username)");
	$login_query->execute([":username" => strtolower($username)]);
	return $login_query->fetch();
}

function fetch_login_validity($username, $password): ?string {
	$user_data = fetch_user_data($username);
	if ($user_data == FALSE) return "Ongeldige gebruikersnaam!";
	if (!password_verify($password, $user_data['password'])) return "Fout wachtwoord!";
	return NULL;
}


// Registration functions
function register_new_client(mixed $username, mixed $password, mixed $first_name, mixed $last_name, mixed $address = NULL): ?string {
	$address = empty($address) ? NULL : $address;
	
	$username_validity_error = is_username_valid($username); if ($username_validity_error != NULL) return $username_validity_error;
	if (!get_username_availability($username)) return "Gebruikersnaam is al bezet!";
	$password_validity_error = is_password_valid($password); if ($password_validity_error != NULL) return $password_validity_error;
	$first_name_validity_error = is_first_name_valid($first_name); if ($first_name_validity_error != NULL) return $first_name_validity_error;
	$last_name_validity_error = is_last_name_valid($last_name); if ($last_name_validity_error != NULL) return $last_name_validity_error;
	$address_validity_error = is_address_valid($address); if ($address_validity_error != NULL) return $address_validity_error;

	$database_connection = get_database_connection();
	$register_query = $database_connection->prepare("INSERT INTO \"User\" (username, password, first_name, last_name, role, address) VALUES (:username, :password, :first_name, :last_name, 'Client', :address)");
	$register_query->execute([":username" => $username, ":password" => password_hash($password, PASSWORD_DEFAULT), ":first_name" => $first_name, ":last_name" => $last_name, ":address" => $address]);
	
	return NULL;
}


// Login functions
function login($username, $password): ?string {
	$login_validity_error = get_login_validity($username, $password); if ($login_validity_error != NULL) return $login_validity_error;

	session_start();
	$user_data = get_user_data($username);
	$_SESSION["username"] = $user_data['username'];
	$_SESSION["first_name"] = $user_data['first_name'];
	$_SESSION["last_name"] = $user_data['last_name'];
	$_SESSION["role"] = $user_data['role'];
	$_SESSION["address"] = $user_data['address'];
	return NULL;
}

?>

