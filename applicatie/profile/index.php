<?php

require "../common/auth.php";
require "../common/errors.php";

?>

<?php

if ($_SERVER["REQUEST_METHOD"] != "GET") redirect("/");

?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/x-icon" href="/assets/icons/favicon.ico">
        <title>Profiel</title>
    </head>
    <body>
        <h1>Profiel</h1>
		<?php require '../common/header.php' ?>
        <p><?php // Account system
            if (!get_login_status()) echo("U bent uitgelogd. Klik <a href=\"/login\">hier</a> om in te loggen.");
            else {
                echo("
                    <h2>Algemene Gegevens</h2>
                    <p>Naam: " . sanitize_user_input($_SESSION["first_name"] . " " . $_SESSION["last_name"]) . "</p>
                    <p>Gebruikersnaam: " . sanitize_user_input($_SESSION["username"]) . "</p>
                    <p>Rol: " . sanitize_user_input($_SESSION["role"]) . "</p>
                    " . get_error_elements() . "
                    <form action=\"/profile/edit.php\" method=\"post\"> <label>Adres:</label> <input type=\"text\" name=\"address\" value=\"" . (empty($_SESSION["address"]) ? "Onbekend" : sanitize_user_input($_SESSION["address"])) . "\"> <input type=\"submit\" value=\"Aanpassen\"><br><br>
                    <a href=\"/profile/delete_account.php\">Verwijder Account</a>

                    <h2>Mijn Bestellingen</h2>
                ");

                clear_errors();
            }
        ?></p>
        
    </body>
</html>

