<?php

/**
 *
 * This is the validation library. A vital component of the framework.
 * It is hoped that the module writers add or change functions here rather 
 * than writing with in the module. 
 * 
 * 
 *
 * PHP version 4 and 5
 *
 * LICENSE: This source file is subject to LGPL license
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 *
 * @package    moduleAPI
 * @subpackage validation
 * @author     Ravindra De Silva <ravindra@opensource.lk><ravidesilva@iee.org>
 * @author     Janaka Wickramasinghe <janaka@opensource.lk>
 * @copyright  Lanka Software Foundation - http://www.opensource.lk
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 * 
 * @todo       password validation
 * @todo       username validation
 */
/**
 * shn_valid_phone 
 * 
 * @param mixed $value 
 * @access public
 * @return void
 */
global $validationMessage;

function shn_valid_get_error() {
    global $validationMessage;
    //var_dump($validationMessage);
    return $validationMessage;
}

function shn_valid_set_error($msg) {
    global $validationMessage;
    $validationMessage = $msg;
}

function shn_valid_phone($value) {
    if(!$value){
        return true;
    }
    if (ereg("[0-9]+", $value))
        $ok = true;
    else
        $ok = false;

    if ($ok == false) {
        $errorMsg = _t("PHONE_NUMBER_REQUIRED__INVAILD_PHONE_NUMBER");
        shn_valid_set_error($errorMsg);   // set the first array message as the validation error message        
    }
    return $ok;
}

function shn_valid_upload($value) {
    if (!checkFileExtension($value)) {
        $errorMsg = _t('FILE_UPLOAD_STOPPED_BY_EXTENSION_');
        shn_valid_set_error($errorMsg);   // set the first array message as the validation error message        
        return false;
    }
    return true;
}

/**
 * shn_valid_email 
 * 
 * @param mixed $value 
 * @access public
 * @return void
 */
function shn_valid_email($value) {
    if(!$value){
        return true;
    }
    if (ereg("^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$", $value))
        $ok = true;
    else
        $ok = false;

    if ($ok == false) {
        $errorMsg = _t("EMAIL_ADDRESS_REQUIRED__INVAILD_EMAIL");
        shn_valid_set_error($errorMsg);   // set the first array message as the validation error message        
    }

    return $ok;
}

/**
 * shn_valid_date 
 * 
 * @param mixed $value 
 * @access public
 * @return void
 */
function shn_valid_date($value) {
    if(!$value){
        return true;
    }
    if (ereg("(19|20)\d\d([- /.])(0[1-9]|1[012])\2(0[1-9]|[12][0-9]|3[01])", $value))
        $ok = true;
    else
        $ok = false;

    if ($ok == false) {
        $errorMsg = _t("DATE_REQUIRED__INVALID_DATE");
        shn_valid_set_error($errorMsg);   // set the first array message as the validation error message        
    }

    return $ok;
}

/**
 * shn_valid_number 
 * 
 * @param mixed $value 
 * @access public
 * @return void
 */
function shn_valid_number($value) {
    if(!$value){
        return true;
    }
    if (ereg("[0-9]+", $value))
        $ok = true;
    else
        $ok = false;

    if ($ok == false) {
        $errorMsg = _t("NUMBER_REQUIRED__INVALID_NUMBER");
        shn_valid_set_error($errorMsg);   // set the first array message as the validation error message        
    }

    return $ok;
}

/**
 * shn_valid_user 
 * 
 * @param mixed $value 
 * @access public
 * @return void
 */
function shn_valid_user($value) {
    if (ereg("^[a-zA-Z0-9_]{3,16}$", $value))
        return true;
    else
        return false;
}

/**
 * Validates a Longitude/Easting (X coordinate) 
 * 
 * @param mixed $field 
 * @access public
 * @return bool
 */
function shn_valid_x_coordinate($field) {
    //  $LONG_ERROR="Longitude/Easting should be between -180 & 180";
    if (!preg_match("/^([0-9.,-]+)$/", $field)) {
        // add_error($LONG_ERROR);
        return false;
    }

    if (($field > -180) && ($field < 180)) {
        return true;
    }
    //add_error($LONG_ERROR);
    return false;
}

/**
 * Validates a Latitude/Northing (Y coordinate) 
 * 
 * @param mixed $field 
 * @access public
 * @return bool
 */
function shn_valid_y_coordinate($field) {
    //$LAT_ERROR="Latitude/Northing should be between -90 & 90";
    if (!preg_match("/^([0-9.,-]+)$/", $field)) {
        // add_error($LAT_ERROR);
        return false;
    }

    if (($field > -90) && ($field < 90)) {
        return true;
    }
    //add_error($LAT_ERROR);
    return false;
}

function shn_valid_notnull($value) {
    $ok = true;
    if (is_string($value) && (('' === $value) || preg_match('/^\s+$/s', $value))) {
        $ok = false;
    } elseif (!is_string($value) && empty($value)) {
        $ok = false;
    }
    if ($ok == false) {
        $errorMsg = _t("YOU_MUST_FILL_IN_THIS_FIELD");
        shn_valid_set_error($errorMsg);   // set the first array message as the validation error message        
    }
    //var_dump($valObject->getMessages());

    return $ok;
}
