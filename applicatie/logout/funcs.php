<?php

// Account functions
function logout(): void {
	session_start();
	unset($_SESSION["username"]);
	unset($_SESSION["first_name"]);
	unset($_SESSION["last_name"]);
	unset($_SESSION["role"]);
	unset($_SESSION["address"]);
}

?>

