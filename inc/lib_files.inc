<?php
/**
 *
 * This is the library will manage media files used in the system
 *
 *
 * PHP version  5
 *
 * LICENSE: This source file is subject to LGPL license
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 *
 * @package    framework
 * @author     H J P Fonseka <jo@respere.com>
 * @copyright  Respere http://respere.com
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 *
 */

function checkFileExtension($filename){
    $path_parts = pathinfo($filename);
    $ext = $path_parts['extension'];
    $notallowedexts = array("php","html","htm","inc","exe","bat","msi","cgi","bin");
    if(in_array($ext, $notallowedexts)){
        return false;
    }
    return true;
}
function shn_files_store($name , $dir= 'documents' , &$file_type,$uploaddir = ""){
    global $global;
    global $conf;
    
    switch ($_FILES[$name]['error']) {
        case UPLOAD_ERR_OK:
            include_once APPROOT.'inc/lib_uuid.inc';
            $file_id = shn_create_uuid('file');
            $file_name = basename($_FILES[$name]['name']);
            if(!checkFileExtension($file_name)){
                $error = _t('FILE_UPLOAD_STOPPED_BY_EXTENSION_');
            }else{
                //print $file_name;
                $file_size = $_FILES[$name]['size'];
                $file_type = $_FILES[$name]['type'];

                $file_tmp_name = $_FILES[$name]['tmp_name'];
                $dir = $dir;
                if(!$uploaddir){
                    $uploaddir = $conf['media_dir'];
                }
                _shn_files_create_dir($uploaddir . $dir , $conf['media_mode']);

                $file_path = $uploaddir . $dir . str_replace('/', '_', $file_id)."_". $file_name;

                if (move_uploaded_file($_FILES[$name]['tmp_name'], $file_path)) {
                    return $file_path;
                }
            }
            break; 
        case UPLOAD_ERR_INI_SIZE:
            $error = _t('THE_UPLOADED_FILE_EXCEEDS_THE_UPLOAD_MAX_FILESIZE_DIRECTIVE_IN_PHP_INI_');
            break; 
        case UPLOAD_ERR_FORM_SIZE:
            $error = _t('THE_UPLOADED_FILE_EXCEEDS_THE_MAX_FILE_SIZE_DIRECTIVE_THAT_WAS_SPECIFIED_IN_THE_HTML_FORM_');
            break; 
        case UPLOAD_ERR_PARTIAL:
            $error = _t('THE_UPLOADED_FILE_WAS_ONLY_PARTIALLY_UPLOADED_');
            break; 
        case UPLOAD_ERR_NO_FILE:
            return null;
            break; 
        case UPLOAD_ERR_NO_TMP_DIR:
            $error = _t('MISSING_A_TEMPORARY_FOLDER_');
            break; 
        case UPLOAD_ERR_CANT_WRITE:
            $error = _t('FAILED_TO_WRITE_FILE_TO_DISK_');
            break; 
        case UPLOAD_ERR_EXTENSION:
            $error = _t('FILE_UPLOAD_STOPPED_BY_EXTENSION_');
            break; 
        default:
            $error = _t('UNKNOWN_UPLOAD_ERROR_');
    }
    shnMessageQueue::addError($error);
    return null;
}


function _shn_files_create_dir($pathname,$mode){
    is_dir(dirname($pathname)) || mkdir(dirname($pathname), $mode, true);
    return is_dir($pathname) || @mkdir($pathname, $mode);
}

function shn_file_findexts($filename){
    $filename = strtolower($filename) ;
    $exts = split("[/\\.]", $filename) ;
    $n = count($exts)-1;
    $exts = $exts[$n];
    return $exts;
}

