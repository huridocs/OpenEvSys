<?php

error_reporting(E_ALL ^ E_NOTICE);
$scanned_strings = array();
define('APPROOT', realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR);

$languages = array(2 => "en", 3 => "fr", 4 => "es", 5 => "ind", 6 => "km", 7 => "ar", 8 => "tr", 15=>"zh", 16=>"de");
$translationsFile = APPROOT . "translate" . DIRECTORY_SEPARATOR . "translations.txt";
/*
 * ////find missing
  $dirs = array(APPROOT."data",APPROOT."inc",APPROOT."inst",APPROOT."mod",APPROOT."tpls");
  foreach($dirs as $dir){
  $scan_stats_all = scan_files($dir,  0);
  //echo $scan_stats_all;
  }

  $msgids = get_existing_msgids();
  $dif1 = array_diff ($msgids,$scanned_strings);

  $dif2 = array_diff ($scanned_strings,$msgids);
  foreach($dif2 as $v){
  echo "PHP\t".$v."\t".$v."<br/>";

  } */

generate_translations();
//get_newstrings();

function get_newstrings(){
global $scanned_strings;
	$dirs = array(APPROOT."data",APPROOT."inc",APPROOT."inst",APPROOT."mod",APPROOT."tpls");
	foreach($dirs as $dir){
	$scan_stats_all = scan_files($dir,  0);
	//echo $scan_stats_all;
	}

	$msgids = get_existing_msgids();
	$dif1 = array_diff ($msgids,$scanned_strings);

	$dif2 = array_diff ($scanned_strings,$msgids);
	foreach($dif2 as $v){
		echo "PHP\t".$v."\t".$v."<br/>";
	}
}

function generate_translations() {
    global $languages;
    //$msgids = get_existing_msgids();
    $translations = get_translations_array();
    require(APPROOT . "translate" . DIRECTORY_SEPARATOR . 'php-mo.php');
    foreach ($languages as $lkey => $language) {
        $path = APPROOT . "translate" . DIRECTORY_SEPARATOR . "translated/php/" . $language . "/LC_MESSAGES" . DIRECTORY_SEPARATOR;
        $pofile = $path . $language . "_openevsys.po";
		mkdir($path ,0777 ,true);
        $fh = fopen($pofile, 'w+');
        
        $jsfile = APPROOT . "translate" . DIRECTORY_SEPARATOR . "translated/js".DIRECTORY_SEPARATOR.$language.".json";
        $jsfh = fopen($jsfile, 'w+');
        fwrite($jsfh,"{\n");
        $jsonArray = array();
        foreach ($translations as $key => $transArray) {
            $origKey = $key;
            $key = addslashes($key);
            if ($transArray[$language]) {
                $value = $transArray[$language];
            } else {
                $value = $language;
            }
            $value = str_replace('"', '\"', $value);
            fwrite($fh, "#: ----\n");
            fwrite($fh, "msgid \"$key\"\n");
            fwrite($fh, "msgstr \"$value\"\n");
            
            //fwrite($jsfh,"\"$key\": \"$value\",\n");
            $jsonArray[] = "\"$origKey\": \"$value\"";//$value;
        }
        fclose($fh);
        phpmo_convert($pofile, $path . "openevsys.mo");
        fwrite($jsfh, implode(",\n",$jsonArray));
        fwrite($jsfh,"\n}");
        fclose($jsfh);
        
    }
}

function get_translations_array() {
    global $translationsFile, $languages;
    if (($handle = fopen($translationsFile, "r")) === FALSE) {
        return array();
    }
    $results = array();
    $i = 0;
    while (($cols = fgetcsv($handle, 0, "\t")) !== FALSE) {
        if ($i && $cols) {
            foreach ($cols as $key => $val) {
                if ($languages[$key]) {
                    if (!$val) {
                        $val = $cols[1];
                    }
                    $results[$cols[1]][$languages[$key]] = $val;
                }
            }
        }
        $i++;
    }
    return $results;
}

function get_existing_msgids() {
    $path = APPROOT . "res\locale\en\LC_MESSAGES\en_openevsys.po";
    $poSrc = file_get_contents($path);
    preg_match_all('/msgid\s+\"([^\"]*)\"/', $poSrc, $matches);
    $msgids = $matches[1];
    return $msgids;
}

function scan_files($dir, $recursion = 0) {
    require_once 'potx.php';

    static $scan_stats = false;
    static $recursion, $scanned_files = array();


    if (!$scan_stats) {
        $scan_stats = sprintf('Scanning folder: %s', $dir) . PHP_EOL;
    }

    $dh = opendir($dir);
    while (false !== ($file = readdir($dh))) {
        if ($file == "." || $file == "..")
            continue;

        if (is_dir($dir . "/" . $file)) {
            $recursion++;
            $scan_stats .= str_repeat("\t", $recursion) . sprintf('Opening folder: %s', $dir . "/" . $file) . PHP_EOL;
            scan_files($dir . "/" . $file, $recursion);
            $recursion--;
        } elseif (preg_match('#(\.php|\.inc\.phtml)$#i', $file)) {
            // THE potx way
            $scan_stats .= str_repeat("\t", $recursion) . sprintf('Scanning file: %s', $dir . "/" . $file) . PHP_EOL;
            $scanned_files[] = $dir . "/" . $file;
            //_potx_find_t_calls_with_context($dir . "/" . $file, '__scan_files_store_results', '_t');
            _potx_process_file2($dir . "/" . $file, 0, '__scan_files_store_results', '_potx_save_version', POTX_API_7);
        } else {
            $scan_stats .= str_repeat("\t", $recursion) . sprintf('Skipping file: %s', $dir . "/" . $file) . PHP_EOL;
        }
    }



    if (!$recursion) {
        $scan_stats .= 'Done scanning files' . PHP_EOL;
        closedir($dh);
        $scan_stats_all = '= Your theme was scanned for texts =' . '<br />' .
                'The following files were processed:' . '<br />' .
                '<ol style="font-size:10px;"><li>' . join('</li><li>', $scanned_files) . '</li></ol>' .
                '<br /><a href="#" onclick="jQuery(this).next().toggle();return false;">' . 'More details' . '</a>' .
                '<textarea style="display:none;width:100%;height:150px;font-size:10px;">' . $scan_stats . '</textarea>';
        return $scan_stats_all;
    }
}

function __scan_files_store_results($string, $domain, $_gettext_context, $file, $line) {

    $string = str_replace(array('\"', "\\'"), array('"', "'"), $string);
    //replace extra backslashes added by _potx_process_file
    $string = str_replace(array('\\\\'), array('\\'), $string);

    global $wpdb, $__icl_registered_strings;
    global $scanned_strings;
    $context = false;

    if (!isset($__icl_registered_strings)) {
        $__icl_registered_strings = array();

        // clear existing entries (both source and page type)
        $context = $domain ? 'theme ' . $domain : 'theme';
        /* $wpdb->query("DELETE FROM {$wpdb->prefix}icl_string_positions WHERE string_id IN 
          (SELECT id FROM {$wpdb->prefix}icl_strings WHERE context = '{$context}')"); */
    }

    if (!isset($__icl_registered_strings[$domain . '||' . $string . '||' . $_gettext_context])) {
        if (!$domain) {
            $context = 'theme';
        } else {
            $context = 'theme ' . $domain;
        }

        $name = $_gettext_context ? $_gettext_context : md5($string);
        //icl_register_string($context, $name, $string);
        $scanned_strings[$file . "-" . $line] = $string;
        $__icl_registered_strings[$domain . '||' . $string . '||' . $_gettext_context] = true;
    }
}

function icl_register_string($context, $name, $value, $allow_empty_value = false) {
    global $wpdb, $sitepress, $sitepress_settings, $ICL_Pro_Translation;
    // if the default language is not set up return without doing anything
    if (
            !isset($sitepress_settings['existing_content_language_verified']) ||
            !$sitepress_settings['existing_content_language_verified']
    ) {
        return;
    }

    // Check if cached (so exists)    
    $cached = icl_t_cache_lookup($context, $name);
    if ($cached && isset($cached['original']) && $cached['original'] == $value) {
        return;
    }

    $language = $sitepress->get_default_language();
    $res = $wpdb->get_row("SELECT id, value, status, language FROM {$wpdb->prefix}icl_strings WHERE context='" . $wpdb->escape($context) . "' AND name='" . $wpdb->escape($name) . "'");
    if ($res) {
        $string_id = $res->id;
        $update_string = array();
        if ($value != $res->value) {
            $update_string['value'] = $value;
        }
        if ($language != $res->language) {
            $update_string['language'] = $language;
        }
        if (!empty($update_string)) {
            $wpdb->update($wpdb->prefix . 'icl_strings', $update_string, array('id' => $string_id));
            $wpdb->update($wpdb->prefix . 'icl_string_translations', array('status' => ICL_STRING_TRANSLATION_NEEDS_UPDATE), array('string_id' => $string_id));
            icl_update_string_status($string_id);
        }
    } else {
        if (!empty($value) && is_scalar($value) && trim($value) || $allow_empty_value) {
            $string = array(
                'language' => $language,
                'context' => $context,
                'name' => $name,
                'value' => $value,
                'status' => ICL_STRING_TRANSLATION_NOT_TRANSLATED,
            );
            $wpdb->insert($wpdb->prefix . 'icl_strings', $string);
            $string_id = $wpdb->insert_id;
        } else {
            $string_id = 0;
        }
    }
    global $WPML_Sticky_Links;
    if (!empty($WPML_Sticky_Links) && $WPML_Sticky_Links->settings['sticky_links_strings']) {
        require_once ICL_PLUGIN_PATH . '/inc/translation-management/pro-translation.class.php';
        ICL_Pro_Translation::_content_make_links_sticky($string_id, 'string', false);
    }
    return $string_id;
}

function _potx_process_file2($file_path, $strip_prefix = 0, $save_callback = '_potx_save_string', $version_callback = '_potx_save_version', $api_version = POTX_API_6) {
    global $_potx_tokens, $_potx_lookup;

    // Figure out the basename and extension to select extraction method.
    $basename = basename($file_path);
    $name_parts = pathinfo($basename);

    // Always grab the CVS version number from the code
    $code = file_get_contents($file_path);
    $file_name = $strip_prefix > 0 ? substr($file_path, $strip_prefix) : $file_path;
    _potx_find_version_number($code, $file_name, $version_callback);

    // Extract raw PHP language tokens.
    $raw_tokens = token_get_all($code);
    unset($code);

    // Remove whitespace and possible HTML (the later in templates for example),
    // count line numbers so we can include them in the output.
    $_potx_tokens = array();
    $_potx_lookup = array();
    $token_number = 0;
    $line_number = 1;
    foreach ($raw_tokens as $token) {
        if ((!is_array($token)) || (($token[0] != T_WHITESPACE) && ($token[0] != T_INLINE_HTML))) {
            if (is_array($token)) {
                $token[] = $line_number;
                // Fill array for finding token offsets quickly.         
                $src_tokens = array(
                    '_t'
                );
                if ($token[0] == T_STRING || ($token[0] == T_VARIABLE && in_array($token[1], $src_tokens))) {
                    if (!isset($_potx_lookup[$token[1]])) {
                        $_potx_lookup[$token[1]] = array();
                    }
                    $_potx_lookup[$token[1]][] = $token_number;
                }
            }
            $_potx_tokens[] = $token;
            $token_number++;
        }
        // Collect line numbers.
        if (is_array($token)) {
            $line_number += count(explode("\n", $token[1])) - 1;
        } else {
            $line_number += count(explode("\n", $token)) - 1;
        }
    }
    unset($raw_tokens);

    // Drupal 7 onwards supports context on t().
    if (!empty($src_tokens))
        foreach ($src_tokens as $tk) {
            _potx_find_t_calls_with_context($file_name, $save_callback, $tk);
        }
}

?>
