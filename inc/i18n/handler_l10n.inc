<?php
global $conf;
global $global;

// define constants

define(LOCALE_DIR, APPROOT.'res/locale');
define(DEFAULT_LOCALE, $conf['locale']);

require_once(APPROOT.'/3rd/php-gettext/gettext.inc');

$encoding = 'UTF-8';

header("Content-Type: text/html; charset=$encoding");

// Set the text domain as 'openevsys'
$domain = 'openevsys';
$locale = (isset($_GET['lang']))? $_GET['lang'] : DEFAULT_LOCALE;

// gettext setup
T_setlocale(LC_MESSAGES, $locale);
T_bindtextdomain($domain, LOCALE_DIR);
T_bind_textdomain_codeset($domain, $encoding);
T_textdomain($domain);

function _t($string)
{
	/*
	 * individual strings customisation
	 * like when someone want's "biographic details" will be "case details"
	 * 
	 */
	global $conf;
	if(isset($conf['string-'.$string])){
		return $conf['string-'.$string];
	}
	/*
	 * standard translation
	 */
    return T_($string);
}
