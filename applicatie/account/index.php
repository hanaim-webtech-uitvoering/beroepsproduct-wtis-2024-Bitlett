<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/x-icon" href="/assets/icons/favicon.ico">
        <title>Index</title>
    </head>
    <body>
        <h1>Account</h1>
		<?php require_once '../header.php' ?>
        <p><?php // Account system
            if (empty($_SESSION['username'])) echo("U bent uitgelogd. Klik <a href=\"/login\">hier</a> om in te loggen.");
            else echo("U bent ingelogd als " . get_clean_full_name() . ". <a href=\"/logout\">Log uit</a>.")
        ?></p>
        
    </body>
</html>

