<?php

// Start session
if (session_status() === PHP_SESSION_NONE) session_start();

// Initialize session errors (if not done already)
if (!isset($_SESSION["errors"])) $_SESSION["errors"] = [];


// Error functions
function get_errors(): array {
	return $_SESSION["errors"];
}

function push_error(string $error): void {
	$_SESSION["errors"][count($_SESSION["errors"])] = $error;
}

function clear_errors(): void {
	$_SESSION["errors"] = [];
}

function get_error_element(string $error): string {
	return "<p style=\"color:red;\">" . $error . "</p><br>";
}

function get_error_elements(): string {
	$result = "";
	foreach (get_errors() as $error) $result .= get_error_element($error);
	return $result;
}

?>

