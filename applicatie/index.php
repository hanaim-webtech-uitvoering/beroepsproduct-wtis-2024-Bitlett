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
        <p><?php // Account system
            session_start();
            if (empty($_SESSION['username'])) echo("U bent uitgelogd. Klik <a href=\"/login\">hier</a> om in te loggen.");
            else echo("U bent ingelogd als " . $_SESSION['first_name'] . " " . $_SESSION['last_name'] . ". <a href=\"/logout\">Log uit</a>.")
        ?></p>
        <h1>It Works!</h1>
    </body>
</html>

