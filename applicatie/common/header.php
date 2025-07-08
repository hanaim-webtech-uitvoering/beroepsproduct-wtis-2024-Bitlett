<?php

require_once "cart.php";
require_once "auth.php";

?>


<p>
	<a href="/">Home</a> • 
	<a href="/menu">Menu</a> • 
	<a href="/cart">Winkelmandje (<?php echo(get_cart_size()) ?>) [€<?php echo(get_cart_price()) ?>]</a> • 
	<a href="/account">Account<?php echo(get_clean_full_name() ? " (" . get_clean_full_name() . ")" : ""); ?></a> • 
	<a href="/privacy">Privacyverklaring</a>
</p>

