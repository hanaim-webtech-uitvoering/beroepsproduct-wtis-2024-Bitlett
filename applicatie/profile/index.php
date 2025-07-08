<?php

require "../common/auth.php";

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
            if (get_login_status()) echo("U bent ingelogd als " . get_clean_full_name() . ". <a href=\"/logout\">Log uit</a>.");
            else echo("U bent uitgelogd. Klik <a href=\"/login\">hier</a> om in te loggen.");
        ?></p>
        
    </body>
</html>

