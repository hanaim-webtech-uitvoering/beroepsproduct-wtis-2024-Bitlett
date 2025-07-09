<?php

// Includes
require_once __DIR__ . "/../obj/Product.php";


class ProductOrder {

	// Constructors
	function __construct(object $product, int $quantity = 1) {
		$this->product = $product;
		$this->quantity = $quantity;
    }


	// Getters/setters
	public function &get_product(): object { return $this->product; }
	public function get_quantity(): int { return $this->quantity; }
	public function add_quantity(int $quantity = 1): void { $this->quantity += $quantity; }
	public function remove_quantity(int $quantity = 1): void { if ($this->quantity < $quantity) $this->quantity = 0; else $this->quantity -= $quantity; }
	public function get_price(): float { return $this->quantity * $this->product->get_price(); }

	// Variables
	private object $product;
	private int $quantity;
}

?>

