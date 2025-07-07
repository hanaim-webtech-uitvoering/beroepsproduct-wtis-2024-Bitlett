<?php

// Includes
require_once '../util/database.php';


// Data fetching functions
function get_user_data($username): mixed {
	$database_connection = getDatabaseConnection();
	$login_query = $database_connection->prepare("SELECT * FROM \"User\" WHERE (LOWER(username) = :username)");
	$login_query->execute([':username' => strtolower($username)]);
	return $login_query->fetch();
}

function get_login_validity($username, $password): ?string {
	$user_data = get_user_data($username);
	if ($user_data == FALSE) return "Ongeldige gebruikersnaam!";
	if (!password_verify($password, $user_data['password'])) return "Fout wachtwoord!";
	return NULL;
}


// Account functions
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

