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
		<?php require __DIR__ . "/../common/elem/header.php" ?>

		<?php
			$products = Product::fetch_all();

			$products_by_type_id = [];
			foreach ($products as $product) {
				if (!isset($products_by_type_id[$product->get_type_id()])) $products_by_type_id[$product->get_type_id()] = [];
				$products_by_type_id[$product->get_type_id()][count($products_by_type_id[$product->get_type_id()])] = $product;
			}

			unset($products);

			foreach ($products_by_type_id as $type_id => $products) {
				echo("<h2>" . $type_id . "</h2>");
				echo("<table> <tr> <th>Product</th> <th>Ingrediënten</th> <th>Prijs</th> <th>Aantal</th> </tr>");

				foreach ($products as $product) {
					$ingredients_string = "";
					foreach ($product->get_ingredients() as $ingredient) {
						if (!empty($ingredients_string)) $ingredients_string .= ", ";
						$ingredients_string .= $ingredient;
					}

					echo("<tr>");

					echo("<td>" . $product->get_name() . "</td>");
					echo("<td>" . $ingredients_string . "</td>");
					echo("<td>€" . $product->get_price() . "</td>");

					echo("<td>");
					echo("<a href=\"/menu/edit.php?origin=/menu&product_name=" . $product->get_name() . "&action=remove\">-</a> ");
					echo(Session::get()->get_cart()->get_product_quantity($product));
					echo(" <a href=\"/menu/edit.php?origin=/menu&product_name=" . $product->get_name() . "&action=add\">+</a>");
					echo("</td>");

					echo("</tr>");
				}

				echo("</table>");
			}

			unset($products_by_type_id);
		?>

		<h2>Bestellen</h2>
		<?php foreach (Session::get()->get_errors() as $error) echo($error->get_element()); Session::get()->clear_errors(); ?>

		<form action="/menu/order.php" method="post">
			<label>Adres:</label> <input type="text" name="address" value="<?php if (!is_null(Session::get()->get_user()) && !is_null(Session::get()->get_user()->get_address())) echo(Session::sanitize_user_input(Session::get()->get_user()->get_address())); ?>" required> <br><br>
			<input type="submit" value="Bestellen">
		</form>
		
    </body>
</html>

