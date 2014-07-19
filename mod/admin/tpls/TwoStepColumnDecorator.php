<?php
class TwoStepColumnDecorator {
	
	protected static $TSVMethods = array(
		"MGA" => "Google Auth", 
		"yubikey" => "YubiKey"
	);

	public static function decorate($config) {
        $result = "None";
        
        $config = @json_decode($config, true);
        if(isset($config['security']['TSV']['method']))
            $result = self::$TSVMethods[$config['security']['TSV']['method']];

        return $result;
	}
}