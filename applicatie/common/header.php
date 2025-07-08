<?php

require_once "cart.php";
require_once "auth.php";

?>

<?php

function sanitize_user_input(string $input): string {
	return htmlspecialchars(strip_tags($input));
}

?>


<p>
	<a href="/">Home</a> • 
	<a href="/menu">Menu (<?php echo(get_cart_size()) ?>) [€<?php echo(get_cart_price()) ?>]</a> • 
	<?php
		if (get_login_status()) echo("<a href=\"/profile\">Profiel (" . sanitize_user_input($_SESSION["first_name"] . " " . $_SESSION["last_name"]) . ")</a> • <a href=\"/logout\">Log uit</a> • ");
		else echo("<a href=\"/login\">Log in</a> • ");
	?>
	<a href="/privacy">Privacyverklaring</a>
</p>

