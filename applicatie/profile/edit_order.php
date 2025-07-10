<?php

// Includes
require_once __DIR__ . "/../common/obj/Session.php";
require_once __DIR__ . "/../common/obj/Order.php";
require_once __DIR__ . "/../common/func/redirect.php";

// Method check
if ($_SERVER["REQUEST_METHOD"] != "POST") redirect("/");

// Login check
if (is_null(Session::get()->get_user())) redirect("/login");

// Status validity check
if (!isset($_POST["status"])) redirect("/profile");
$status = OrderStatus::from_string($_POST["status"]);
if (is_null($status)) {
	unset($status);
	redirect("/profile");
}

// Order check
if (!isset($_POST["order_id"])) redirect("/profile");
$order_id = filter_var($_POST["order_id"], FILTER_VALIDATE_INT);
if ($order_id === FALSE) {
	unset($order_id);
	unset($status);
	redirect("/profile");
}

$order = Order::fetch_by_id($order_id);
unset($order_id);
if (is_null($order)) {
	unset($order);
	unset($status);
	redirect("/profile");
}

// Authority check
if (Session::get()->get_user() != $order->get_personnel()) {
	unset($order);
	unset($status);
	redirect("/profile");
}

// Update the order status
$order->set_status($status);
unset($order);
unset($status);

// Redirect back to profile
redirect("/profile");

?>

