<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="icon" type="image/x-icon" href="/assets/icons/favicon.ico">
        <title>Register</title>
    </head>
    <body>
        <h1>Account Registreren</h1>
        <?php include '../header.php'; ?>
        <?php include '../util/errors.php'; foreach (get_errors() as $error) echo(get_error_element($error)); clear_errors(); ?>
        <form action="/register/submit.php" method="post">
			<label>Gebruikersnaam:</label> <input type="text" name="username" required><br><br>
			<label>Wachtwoord:</label> <input type="password" name="password" required><br><br>
			<label>Voornaam:</label> <input type="text" name="first_name" required><br><br>
			<label>Achternaam (incl. tussenvoegsels):</label> <input type="text" name="last_name" required><br><br>
			<label>Address (Optioneel):</label> <input type="text" name="address"></textarea><br><br>

			<input type="submit" value="Registreren">
		</form>
    </body>
</html>

