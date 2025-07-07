<?php session_start(); ?>
<?php require_once 'funcs.php' ?>

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
		<?php require_once '../header.php' ?>

		<?php
			foreach (get_menu_categories() as $category) {
				echo("<h2>" . $category . "</h2>
					<table>
						<tr> <th>Product</th> <th>Ingrediënten</th> <th>Prijs</th> <th>Aantal</th> </tr>");

				foreach (get_menu_products($category) as $product) {
					$ingredients_string = "";
					foreach ($product['ingredients'] as $ingredient) {
						if (!empty($ingredients_string)) $ingredients_string .= ", ";
						$ingredients_string .= $ingredient;
					}

					echo("<tr> <td>" . $product['name'] . "</td> <td>" . $ingredients_string . "</td> <td>€" . $product['price'] . "</td> <td><a href=\"/menu/edit.php?origin=/menu&product=" . $product['name'] . "&action=remove\">-</a> " . get_cart_product_count($product['name']) . " <a href=\"/menu/edit.php?origin=/menu&product=" . $product['name'] . "&action=add\">+</a></td> </tr>");
				}

				echo("</table>");
			}
		?>
		
    </body>
</html>

