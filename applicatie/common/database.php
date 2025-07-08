<?php

// defined in 'variables.env'
$host = 'database_server'; // de database server 
$name = 'pizzeria';                    // naam van database

// defined in sql-script 'movies.sql'
$username = 'sa';                 // db user
$password = 'abc123!@#';  // wachtwoord db user

// Het 'ssl certificate' wordt altijd geaccepteerd (niet overnemen op productie, verder dan altijd "TrustServerCertificate=1"!!!)
$connection = new PDO('sqlsrv:Server=' . $host . ';Database=' . $name . ';ConnectionPooling=0;TrustServerCertificate=1', $username, $password);

// Bewaar het wachtwoord niet langer onnodig in het geheugen van PHP.
unset($password);

// Zorg ervoor dat eventuele fouttoestanden ook echt als fouten (exceptions) gesignaleerd worden door PHP.
$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Functie om in andere files toegang te krijgen tot de verbinding.
function get_database_connection() {
	global $connection;
	return $connection;
}

?>

