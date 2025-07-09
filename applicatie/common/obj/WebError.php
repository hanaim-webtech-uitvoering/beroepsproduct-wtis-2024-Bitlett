<?php

class WebError {

	// Constructors
	function __construct(string $message) {
		$this->message = $message;
	}


	// Getters/setters
	public function get_message(): string { return $this->message; }
	public function get_element(): string { return "<p style=\"color:red;\">" . $this->message . "</p><br>"; }


	// Variables
	private string $message;
}

?>

