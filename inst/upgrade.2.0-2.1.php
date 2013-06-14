<?php

global $conf;
global $global;
error_reporting(E_ALL ^ E_NOTICE);
define('APPROOT', realpath(dirname(__FILE__) . '/../') . '/');
$_SESSION['username'] = 'admin';

require_once(APPROOT . 'conf/sysconf.php');

require_once(APPROOT.'3rd/adodb5/adodb-active-record.inc.php');
require_once(APPROOT.'inc/lib_uuid.inc');
//load db handler
require_once(APPROOT.'inc/handler_db.inc');
ADOdb_Active_Record::SetDatabaseAdapter($global['db']);
//overide conf values from db
require_once(APPROOT.'inc/handler_config.inc');

include_once APPROOT.'inc/lib_entity_forms.inc';
//include_once APPROOT.'3rd/phpgacl/gacl.class.php';


include_once APPROOT.'inc/lib_form_util.inc';

include_once APPROOT.'inc/lib_files.inc';
include_once APPROOT.'inc/security/lib_acl.inc';
include_once APPROOT.'inc/security/handler_acl.inc';

include_once APPROOT . 'inc/lib_form.inc';
include_once APPROOT . 'inc/lib_form_util.inc';

require_once ( APPROOT . 'data/Browse.php');
require_once ( APPROOT . 'data/MtField.php');
require_once ( APPROOT . 'data/MtFieldWrapper.php');

require_once ( APPROOT . 'data/MtIndex.php');
require_once ( APPROOT . 'data/MtTerms.php');

$mtIndex = new MtIndex();
$index_terms = $mtIndex->Find('');
$options = array();
$options[''] = '';
foreach ($index_terms as $index_term) {
    
    $data_array = MtFieldWrapper::getMTList($index_term->no);
    $count = count($data_array);
    $level = 0;
    $levelsarray = array();
    $term_order = 1;
    
    for ($i = 0; $i < $count;) {
        $element1 = $data_array[$i];
        $element2 = $data_array[++$i];

        $h1 = strlen(rtrim($element1['huri_code'], '0'));
        $h2 = strlen(rtrim($element2['huri_code'], '0'));

        if ($h1 % 2 == 1)
            $h1++;
        if ($h2 % 2 == 1)
            $h2++;


        $levelsarray[$level] = $element1['vocab_number'];
        $parent_vocub_number = 0;
        if(isset($levelsarray[$level-1])){
            $parent_vocub_number = $levelsarray[$level-1];
        }
         $sql = "UPDATE mt_vocab SET term_level='$level',term_order='$term_order',parent_vocab_number='$parent_vocub_number' WHERE vocab_number = '".$element1['vocab_number']."'";
    //var_dump( $sql);
    $res = $global['db']->Execute($sql);

        //echo $level."-".$element1['vocab_number']."-".$parent_vocub_number."\r\n";
        
        
        if ($h1 < $h2) {
            $level++;
            $h2 = $h2 - 2;
            while ($h1 < $h2) {
                $level++;
                $h2 = $h2 - 2;
            }
        }
        if ($h2 < $h1 && isset($element2)) {
            while ($h2 < $h1) {
                $level--;
                $h1 = $h1 - 2;
            }
        }
        
        $term_order++;
    }
}

$sql = "update data_dict set enabled='y' where essential='y';";
$res = $global['db']->Execute($sql);
$sql = "update data_dict set visible_new='y' where field_name='person_record_number';";
$res = $global['db']->Execute($sql);
    