<?php

// Includes
require_once '../util/database.php';


// Validity functions
function is_username_valid(string $username): ?string {
	if (empty($username)) return "Gebruikersnaam mag niet leeg zijn!";
	if (!is_string($username)) return "Gebruikersnaam moet tekst zijn!";
	if (strlen($username) > 255) return "Gebruikersnaam moet korter dan 256 tekens zijn!";
	return NULL;
}

function is_password_valid(string $password): ?string {
	if (empty($password)) return "Wachtwoord mag niet leeg zijn!";
	if (!is_string($password)) return "Wachtwoord moet tekst zijn!";
	return NULL;
}

function is_first_name_valid(string $first_name): ?string {
	if (empty($first_name)) return "Voornaam mag niet leeg zijn!";
	if (!is_string($first_name)) return "Voornaam moet tekst zijn!";
	if (strlen($first_name) > 255) return "Voornaam moet korter dan 256 tekens zijn!";
	return NULL;
}

function is_last_name_valid(string $last_name): ?string {
	if (empty($last_name)) return "Achternaam mag niet leeg zijn!";
	if (!is_string($last_name)) return "Achternaam moet tekst zijn!";
	if (strlen($last_name) > 255) return "Achternaam moet korter dan 256 tekens zijn!";
	return NULL;
}

function is_address_valid(?string $address): ?string {
	if ($address == NULL) return NULL;
	if (!is_string($address)) return "Adres moet tekst zijn!";
	if (strlen($address) > 255) return "Adres moet korter dan 256 tekens zijn!";
	return NULL;
}


// Data fetching functions
function get_username_availability($username): bool {
	$database_connection = getDatabaseConnection();
	$username_duplicate_query = $database_connection->prepare("SELECT COUNT(*) as Count FROM \"User\" WHERE LOWER(username) = :username");
	$username_duplicate_query->execute([':username' => strtolower($username)]);
	return $username_duplicate_query->fetch()['Count'] == 0;
}


// Account functions
function register_new_client($username, $password, $first_name, $last_name, $address = NULL): ?string {
	$adress = empty($adress) ? NULL : $adress;
	
	$username_validity_error = is_username_valid($username); if ($username_validity_error != NULL) return $username_validity_error;
	if (!get_username_availability($username)) return "Gebruikersnaam al bezet!";
	$password_validity_error = is_password_valid($password); if ($password_validity_error != NULL) return $password_validity_error;
	$first_name_validity_error = is_first_name_valid($first_name); if ($first_name_validity_error != NULL) return $first_name_validity_error;
	$last_name_validity_error = is_last_name_valid($last_name); if ($last_name_validity_error != NULL) return $last_name_validity_error;
	$address_validity_error = is_address_valid($address); if ($address_validity_error != NULL) return $address_validity_error;

	$database_connection = getDatabaseConnection();
	$register_query = $database_connection->prepare("INSERT INTO \"User\" (username, password, first_name, last_name, role, address) VALUES (:username, :password, :first_name, :last_name, 'Client', :address)");
	$register_query->execute([':username' => $username, ':password' => password_hash($password, PASSWORD_DEFAULT), ':first_name' => $first_name, ':last_name' => $last_name, ':address' => $address]);
	return NULL;
}

?>

