<?php

require "../common/auth.php";
require "../common/errors.php";

?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="icon" type="image/x-icon" href="/assets/icons/favicon.ico">
        <title>Login</title>
    </head>
    <body>
        <h1>Inloggen</h1>
        <?php require_once '../common/header.php' ?>
        <?php echo(get_error_elements()); clear_errors(); ?>
        <form action="/login/submit.php" method="post">
			<label>Gebruikersnaam:</label> <input type="text" name="username" required><br><br>
			<label>Wachtwoord:</label> <input type="password" name="password" required><br><br>

			<input type="submit" value="Log In">
		</form>

        <p>Heeft u nog geen account? Maak er <a href="/register">hier</a> een aan.</p>
    </body>
</html>

