<?php

// Includes
require_once '../../util/database.php';


// Validity functions
function is_username_valid($username): bool {
	return !empty($username) && is_string($username) && strlen($username) <= 255;
}

function is_password_valid($password): bool {
	return !empty($password) && is_string($password);
}

function is_first_name_valid($first_name): bool {
	return !empty($first_name) && is_string($first_name) && strlen($first_name) <= 255;
}

function is_last_name_valid($last_name): bool {
	return !empty($last_name) && is_string($last_name) && strlen($last_name) <= 255;
}

function is_address_valid($address): bool {
	if ($address == NULL) return TRUE;
	return is_string($address) && strlen($address) <= 255;
}


// Data fetching functions
function get_username_availability($username): bool {
	$database_connection = getConnection();
	$username_duplicate_query = $database_connection->prepare("SELECT COUNT(*) as Count FROM \"User\" WHERE LOWER(username) = :username");
	$username_duplicate_query->execute([':username' => strtolower($username)]);
	return $username_duplicate_data->fetch()['Count'] == 0;
}

function get_user_data($username): mixed {
	$database_connection = getConnection();
	$login_query = $database_connection->prepare("SELECT * FROM \"User\" WHERE (LOWER(username) = :username)");
	$login_query->execute([':username' => strtolower($username)]);
	return $login_query->fetch();
}

function get_login_validity($username, $password): bool {
	$user_data = get_user_data($username);
	return $user_data && password_verify($password, $user_data['password']);
}


// Account functions
function register_new_client($username, $password, $first_name, $last_name, $address = NULL): bool {
	if (!is_username_valid($username)) return FALSE;
	if (!get_username_availability($username)) return FALSE;
	if (!is_password_valid($password)) return FALSE;
	if (!is_first_name_valid($first_name)) return FALSE;
	if (!is_last_name_valid($last_name)) return FALSE;
	if (!is_address_valid($address)) return FALSE;

	$register_query = $database_connection->prepare("INSERT INTO \"User\" (username, password, first_name, last_name, role, address) VALUES (:username, :password, :first_name, :last_name, 'Client', :address)");
	$register_query->execute([':username' => $username, ':password' => password_hash($password, PASSWORD_DEFAULT), ':first_name' => $first_name, ':last_name' => $last_name, ':address' => $address]);
	
	return TRUE;
}

function login($username, $password): bool {
	if (!get_login_validity($username, $password)) return FALSE;

	session_start();
	$user_data = get_user_data($username);
	$_SESSION["username"] = $user_data['username'];
	$_SESSION["first_name"] = $user_data['first_name'];
	$_SESSION["last_name"] = $user_data['last_name'];
	$_SESSION["role"] = $user_data['role'];
	$_SESSION["address"] = $user_data['address'];
	return TRUE;
}

?>

