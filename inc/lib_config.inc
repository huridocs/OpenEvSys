<?php

// update a conf key/value pair in the database
function shn_config_database_update($key, $value)
{
	global $global;
	$sql = "SELECT confkey, value FROM config
                WHERE confkey = '$key'";

	if($results = $global['db']->GetALL($sql)) {
		$sql = "UPDATE config SET value = '$value'
                WHERE confkey = '$key' ";
		$global['db']->Execute($sql);

	} else {
		$sql = "INSERT INTO config VALUES (NULL, '$key', '$value')";
		$global['db']->Execute($sql);
	}
}
