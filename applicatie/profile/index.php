<?php

// Includes
require_once __DIR__ . "/../common/obj/Order.php";
require_once __DIR__ . "/../common/func/redirect.php";

// Method check
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
		<?php require __DIR__ . "/../common/elem/header.php"; ?>

        <?php
            $session = &Session::get();
            $user = isset($_GET["username"]) ? User::fetch_by_username($_GET["username"]) : $session->get_user();
            $is_profile_own = !isset($_GET["username"]) || (!is_null($session->get_user()) && User::fetch_by_username($_GET["username"]) == $session->get_user());

            // Failed to load profile
            if (is_null($user)) {
                if ($is_profile_own) echo("U bent uitgelogd. Klik <a href=\"/login\">hier</a> om in te loggen.");
                else echo("Profiel niet gevonden.");
            }

            else {
                $is_user_personnel = !is_null($session->get_user()) && $session->get_user()->get_role() == "Personnel";
                $can_view_private_data = $is_profile_own || $is_user_personnel;

                echo("<h2>Algemene Gegevens</h2>");
                echo("<p>Naam: " . Session::sanitize_user_input($user->get_full_name()) . "</p>");
                echo("<p>Gebruikersnaam: " . Session::sanitize_user_input($user->get_username()) . "</p>");
                echo("<p>Rol: " . Session::sanitize_user_input($user->get_role()) . "</p>");
 
                if ($can_view_private_data) {
                    $clean_address = empty($user->get_address()) ? "Onbekend" : Session::sanitize_user_input($user->get_address());
                    if ($is_profile_own) {
                        foreach($session->get_errors() as $error) echo($error->get_element());
                        $session->clear_errors();
                        echo("<form action=\"/profile/edit_address.php\" method=\"post\"> <label>Adres:</label> <input type=\"text\" name=\"address\" value=\"" . $clean_address . "\"> <input type=\"submit\" value=\"Aanpassen\"> </form><br><br>");
                        echo("<a href=\"/profile/delete.php\">Verwijder Account</a>");
                    } else echo("<label>Adres: " . $clean_address . "</label>");
                    unset($clean_address);

                    echo("<h2>Bestellingen</h2>");
                    $orders = Order::fetch_by_username($user->get_username());
                    if (count($orders) == 0) echo("U hebt geen bestellingen.");
                    else {
                        echo("<table> <tr> <th>ID</th> <th>Product(en)</th> <th>Klant</th> <th>Adres</th> <th>Datum en Tijd</th> <th>Personeel</th> <th>Status</th> </tr>");

                        foreach ($orders as $order) {
                            echo("<tr>");

                            echo("<td>" . $order->get_id() . "</td>");

                            $products_string = "";
                            foreach($order->get_product_orders() as $product_order) {
                                if (!empty($products_string)) $products_string .= ", ";
                                $products_string .= $product_order->get_quantity() . "x " . $product_order->get_product()->get_name();
                            }
                            echo("<td>" .  $products_string . "</td>");
                            unset($products_string);

                            if (is_null($order->get_client())) echo("<td> Onbekend </td>");
                            else echo("<td><a href=\"/profile?username=" . Session::sanitize_user_input($order->get_client()->get_username()) . "\"> " . Session::sanitize_user_input($order->get_client()->get_username()) . "</a></td>");

                            echo("<td>" . Session::sanitize_user_input($order->get_address()) . "</td>");

                            echo("<td>" . date('Y/m/d H:i:s', $order->get_datetime()->getTimestamp()) . "</td>");

                            echo("<td><a href=\"/profile?username=" . $order->get_personnel()->get_username() . "\"> " . $order->get_personnel()->get_username() . "</a></td>");

                            echo("<td>" . $order->get_status()->to_string() . "</td>");

                            echo("</tr>");
                        }

                        unset($orders);
                        echo("</table>");
                    }
                }

                unset($is_user_personnel);
                unset($can_view_private_data);
            }

            unset($session);
            unset($user);
            unset($is_profile_own);
        ?>
        
    </body>
</html>

