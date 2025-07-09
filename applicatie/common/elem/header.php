<?php

// Includes
require_once __DIR__ . "/../obj/Session.php";

?>


<p>
	<a href="/">Home</a> • 

	<a href="/menu">Menu (<?php echo(Session::get()->get_cart()->get_size()) ?>) [€<?php echo(Session::get()->get_cart()->get_price()) ?>]</a> • 

	<?php
		$user =& Session::get()->get_user();
		if (!is_null($user)) echo("<a href=\"/profile\">Profiel (" . Session::sanitize_user_input($user->get_full_name()) . ")</a> • ");
	?>

	<?php
		$user = Session::get()->get_user();
		if (is_null($user)) echo("<a href=\"/login\">Log in</a> • ");
		else echo("<a href=\"/logout\">Log uit</a> • ");
	?>

	<a href="/privacy">Privacyverklaring</a>
</p>

