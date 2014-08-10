<?php 

class ArgumentEncoder {

	public $whiteList;

	public function __construct($whiteList) {
		$this->whiteList = $whiteList;
	}

	public function encode($arguments) {
		$encodedArguments = Array();

		foreach($arguments as $argument => $value) {
			if(in_array($argument, $this->whiteList))
				$encodedArguments[$argument] = Reform::HtmlEncode($value);
		}

		return $encodedArguments;
	}	
}