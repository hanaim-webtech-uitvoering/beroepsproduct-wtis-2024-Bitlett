<?php

require_once __DIR__ . "/../common/obj/Order.php";

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
		<?php require __DIR__ . "/../common/elem/header.php" ?>

        <?php // Account system
            $session = &Session::get();
            $user = $session->get_user();

            if (is_null($user)) echo("U bent uitgelogd. Klik <a href=\"/login\">hier</a> om in te loggen.");
            else {
                echo("<h2>Algemene Gegevens</h2>");
                echo("<p>Naam: " . Session::sanitize_user_input($user->get_full_name()) . "</p>");
                echo("<p>Gebruikersnaam: " . Session::sanitize_user_input($user->get_username()) . "</p>");
                echo("<p>Rol: " . Session::sanitize_user_input($user->get_role()) . "</p>");
 
                foreach($session->get_errors() as $error) echo($error->get_element());
                $session->clear_errors();
                    
                echo("<form action=\"/profile/edit.php\" method=\"post\"> <label>Adres:</label> <input type=\"text\" name=\"address\" value=\"" . (empty($user->get_address()) ? "Onbekend" : Session::sanitize_user_input($user->get_address())) . "\"> <input type=\"submit\" value=\"Aanpassen\"> </form><br><br>");
                echo("<a href=\"/profile/delete.php\">Verwijder Account</a>");

                echo("<h2>Mijn Bestellingen</h2>");
                $orders = Order::fetch_by_username($user->get_username());

                foreach ($orders as $order) {
                    echo("<p>");
                    var_dump($order);
                    echo("</p>");
                }
            }
        ?>
        
    </body>
</html>

