<?php

function l10n_get_locals()
{
    $locale = array();
    //iterate through the locale directory and get the locales 
    if ($handle = opendir(LOCALE_DIR)) {
    while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != ".." && $file != ".svn") {
            $name_file = LOCALE_DIR."/$file/language";
            $language = (file_exists($name_file))?file_get_contents($name_file):$file;
            $language = ($language=='')?$file:$language;
            $locales[$file]=$language;
        }
    }
    closedir($handle);
    }

    return $locales;
}


function l10n_get_messages($locale)
{
    $file_name = LOCALE_DIR."/$locale/LC_MESSAGES/openevsys.po";
    if(file_exists($file_name))
        return file_get_contents($file_name);
    return "File not found"; 
}

function l10n_set_messages($locale , $messages)
{
    $file_name = LOCALE_DIR."/$locale/LC_MESSAGES/openevsys.po";
    if(file_exists($file_name) && is_writable($file_name)){
        $return = file_put_contents($file_name, $messages);
        if($return)
            shnMessageQueue::addInformation(_t('MESSAGES_SAVED_SUCCESSFULLY_'));
        else
            shnMessageQueue::addError(_t('ERROR_ON_FILE_SAVE__UNABLE_TO_SAVE_FILE_'));
    }
    else
        shnMessageQueue::addError(_t('FILE_IS_NOT_WRITABLE_'));
}


function l10n_new_locale($language , $folder)
{
    //check if locale folder writable
    if(!is_writable(LOCALE_DIR)){
        shnMessageQueue::addError(_t('LANGUAGE_DIRECTORY_IS_NOT_WRITABLE_'));
        return;
    }
    //check if the folder exists
    $folder_name = LOCALE_DIR."/$folder";
    if(file_exists($folder_name)){
        shnMessageQueue::addError(_t('FOLDER_ALREADY_EXISTS__GIVE_IT_A_DIFFERENT_NAME_'));
        return;
    }
    else {
        //create folder
        mkdir("$folder_name/LC_MESSAGES",0777,true); 
        //create language file
        file_put_contents(LOCALE_DIR."/$folder/language",$language);
        //create openevsys.po
        file_put_contents("$folder_name/LC_MESSAGES/openevsys.po",file_get_contents(APPROOT.'res/openevsys.po'));
        shnMessageQueue::addInformation(_t('LANGUAGE_WAS_CREATED_SUCCESSFULLY_'));
    }
}


function l10n_remove_locale($locale)
{
    //check if locale folder writable
    if(!is_writable(LOCALE_DIR)){
        shnMessageQueue::addError(_t('LANGUAGE_DIRECTORY_IS_NOT_WRITABLE_'));
        return;
    }

    DELETE_RECURSIVE_DIRS(LOCALE_DIR."/$locale");
    shnMessageQueue::addInformation(_t('LANGUAGE_REMOVED'));
}

function DELETE_RECURSIVE_DIRS($dirname) 
{ // recursive function to delete 
  // all subdirectories and contents: 
  if(is_dir($dirname))$dir_handle=opendir($dirname); 
  while($file=readdir($dir_handle)) 
  { 
    if($file!="." && $file!="..") 
    { 
      if(!is_dir($dirname."/".$file))unlink ($dirname."/".$file); 
      else DELETE_RECURSIVE_DIRS($dirname."/".$file); 
    } 
  } 
  closedir($dir_handle); 
  rmdir($dirname); 
  return true; 
}


function l10n_update_po($msgid , $msgstr , $locale)
{
    $file_name = LOCALE_DIR."/$locale/LC_MESSAGES/openevsys.po";
    if(file_exists($file_name) && is_writable($file_name)){
        $messages = file_get_contents($file_name);
        //search for location to update
        $point = strpos($messages , "msgid \"$msgid\"" );
        if(!$point){ return false;}
        $point = strpos($messages , 'msgstr' , $point);
        $point = strpos($messages , "\"" , $point);
        $first = substr( $messages  , 0  , ++$point );
        $point2= strpos($messages , "\"", $point);
        $later =  substr( $messages  , $point2 , strlen($messages) );
        $new_messages = $first.$msgstr.$later;
        l10n_set_messages($locale , $new_messages );
        return true;
    }
    else
        return false;
}



function l10n_compile($locale){
	$return = array();
	$cmd = "msgfmt ".LOCALE_DIR."/$locale/LC_MESSAGES/openevsys.po --output-file=".LOCALE_DIR."/$locale/LC_MESSAGES/openevsys.mo";
    system($cmd, $return);
    if($return == 1){
    	shnMessageQueue::addError(_t('TRANSLATION_NOT_UPDATED__PLEASE_GIVE_DIRECTORY_AND_FILE_PERMISSION_TO_RES_LOCALE_FOLDER_'));
    }
    else
   		shnMessageQueue::addInformation (_t('TRANSLATION_UPDATED_SUCCESSFULLY_'));
}
