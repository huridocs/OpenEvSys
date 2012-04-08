<?php 

include_once(APPROOT.'inc/lib_config.inc');
global $global;
global $conf;
// load database overides of config
$sql = "SELECT confkey, value FROM config";

if($results = $global['db']->GetAll($sql)){
    foreach($results as $result) {
        // translate the boolean strings to actual boolean values
        if ($result['value'] === 'true') $result['value'] = true;
        if ($result['value'] === 'false') $result['value'] = false;

        $conf[$result['confkey']] = $result['value'];
    }
}
