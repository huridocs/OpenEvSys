<?php 
/**
 *
 * This is the library that generates global unique ids.
 * Each "Sahana" instance will be given a unique id(40) which will be prefixed
 * to the primary keys.
 *
 *
 * PHP version 4 and 5
 *
 * LICENSE: This source file is subject to LGPL license
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 *
 * @package    moduleAPI
 * @subpackage UUID
 * @author     Janaka Wickramasinghe <janaka@opensource.lk>
 * @copyright  Lanka Software Foundation - http://www.opensource.lk
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 *
 * @todo       define a subpackage
 */


/**
 * shn_create_uuid
 *
 * @param string $type
 * @access public
 * @return void
 */
function shn_create_uuid($type='person')
{
	global $conf;

	switch ($type){
		case 'event':
			$gen_id = 'event/'._shn_gen_id('event');
			break;

		case 'person':
			$gen_id = 'person/'._shn_gen_id('person');
			break;
        case 'act':
            $gen_id = 'act/'._shn_gen_id('act');
            break;
		default :
		    $gen_id = $type.'/'._shn_gen_id($type);
			break;
	}

	return $conf['base_uuid'].'/'.$gen_id;
}

/**
 * _shn_gen_id
 *
 * @param string $type
 * @access protected
 * @return void
 */
function _shn_gen_id($type='person')
{
	global $global;
	global $conf;

	if($conf['db_engine'] == 'mysql'){
		if(isset($conf['storage_engine']) && $conf['storage_engine']!='' && strlen(trim($conf['storage_engine']))>0){
			$engine = $conf['storage_engine'];
			$global['db']->Execute("set storage_engine ='{$engine}'");
		}
	}
    global $global;
    global $conf;
    
   /* if($conf['db_engine'] == 'mysql'){
    $engine = $conf['storage_engine'];

    $global['db']->Execute("set storage_engine ='{$engine}'");
    
    }*/
	$tables=$global['db']->MetaTables("TABLE",false,false);
	$type.="_seq";
	if(array_search($type,$tables)==false){
		$global['db']->CreateSequence($type,1);
	}
	 
	return $global['db']->GenID($type);
}
?>
