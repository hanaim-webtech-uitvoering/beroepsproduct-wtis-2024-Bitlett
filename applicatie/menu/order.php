<?php

// Includes
require_once __DIR__ . "/../common/obj/Session.php";
require_once __DIR__ . "/../common/func/redirect.php";

// Method check
if ($_SERVER["REQUEST_METHOD"] != "POST") redirect("/login");

// Globals
$session = &Session::get();
$cart = &$session->get_cart();
$user = $session->get_user();

// Place the order
$order_error = $cart->order($_POST["address"], is_null($user) ? NULL : $user->get_username(), is_null($user) ? "Onbekend" : $user->get_username());

unset($cart);
unset($user);

if (!is_null($order_error)) {
	$session->push_error($order_error);
	unset($session);
	unset($order_error);
	redirect("/menu");
}

unset($session);
unset($order_error);

// And go back to the menu
redirect("/menu/order_success.php");

?>

