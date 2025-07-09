<?php

function get_database_connection(): object {
	$host = 'database_server';
	$name = 'pizzeria';
	$username = 'sa';
	$password = 'abc123!@#';

	static $database_connection;

	if (!isset($database_connection)) {
		$database_connection = new PDO('sqlsrv:Server=' . $host . ';Database=' . $name . ';ConnectionPooling=0;TrustServerCertificate=1', $username, $password);
		$database_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	
	return $database_connection;
}

?>

