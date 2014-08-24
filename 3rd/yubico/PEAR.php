<?php

//
// This is a very basic PEAR_Error lib for Yubikey used to avoid depeding on PEAR 
//

class PEAR {

	function raiseError($message) {
		return new PEAR_Error($message);
	}

	function isError($object) {
		return is_a($object, 'PEAR_Error');
	}
}

class PEAR_Error {

	public $message;

	function __construct($message) {
		$this->message = $message;
	}
}

?>
