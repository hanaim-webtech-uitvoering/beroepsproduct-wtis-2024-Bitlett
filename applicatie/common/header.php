<?php

require_once "cart.php";
require_once "auth.php";

?>


<p>
	<a href="/">Home</a> • 
	<a href="/menu">Menu (<?php echo(get_cart_size()) ?>) [€<?php echo(get_cart_price()) ?>]</a> • 
	<?php
		if (get_login_status()) echo("<a href=\"/profile\">Profiel (" . get_clean_full_name() . ")</a> • ");
		else echo("<a href=\"/login\">Log in</a> • ");
	?>
	<a href="/privacy">Privacyverklaring</a>
</p>

