<?php

require_once "../common/products.php";
require_once "../common/cart.php";
require_once "../common/auth.php";
require_once "../common/errors.php";

?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/x-icon" href="/assets/icons/favicon.ico">
        <title>Menu</title>
    </head>
    <body>
        <h1>Menu</h1>
		<?php require "../common/header.php" ?>

		<?php
			$products = fetch_products();

			$products_by_type_id = [];
			foreach ($products as $product) {
				if (!isset($products_by_type_id[$product["type_id"]])) $products_by_type_id[$product["type_id"]] = [];
				$products_by_type_id[$product["type_id"]][count($products_by_type_id[$product["type_id"]])] = $product;
			}

			$cart_product_order_quantity_by_name = [];
			foreach ($products as $product)
				$cart_product_order_quantity_by_name[$product["name"]] = 0;
			foreach ($_SESSION["cart"] as $product_order)
				$cart_product_order_quantity_by_name[$product_order["product"]["name"]] = $product_order["quantity"];

			foreach ($products_by_type_id as $type_id => $products) {
				echo("<h2>" . $type_id . "</h2>
					<table>
						<tr> <th>Product</th> <th>Ingrediënten</th> <th>Prijs</th> <th>Aantal</th> </tr>");

				foreach ($products as $product) {
					$ingredients_string = "";
					foreach ($product['ingredients'] as $ingredient) {
						if (!empty($ingredients_string)) $ingredients_string .= ", ";
						$ingredients_string .= $ingredient;
					}

					echo("<tr> <td>" . $product['name'] . "</td> <td>" . $ingredients_string . "</td> <td>€" . $product['price'] . "</td> <td><a href=\"/menu/edit.php?origin=/menu&product_name=" . $product['name'] . "&action=remove\">-</a> " . $cart_product_order_quantity_by_name[$product['name']] . " <a href=\"/menu/edit.php?origin=/menu&product_name=" . $product['name'] . "&action=add\">+</a></td> </tr>");
				}

				echo("</table>");
			}
		?>

		<h2>Bestellen</h2>
		<?php echo(get_error_elements()); clear_errors(); ?>
		<form action="/menu/order.php" method="post">
			<label>Adres:</label> <input type="text" name="address" value="<?php if (get_login_status()) echo(sanitize_user_input($_SESSION["address"])) ?>"> <br><br>
			<input type="submit" value="Bestellen">
		</form>
		
    </body>
</html>

