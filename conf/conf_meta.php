<?php
# set the changeable $conf
$alt_conf=array(/*"base_uuid","acl_base","root_name",
"root_email","root_tel","db_host","db_port",*/ /*"theme"=>_t("Theme"),*/
/*"db_name","db_user","db_pass","debug" ,"session_writer"=>_t("Session writer"),
"session_name"=>_t("Session name"),*/"session_timeout"=>_t("Session timeout"),/*"dbal_lib_name","db_engine","storage_engine",
"enable_cache","cache_dir","locale","locale_lib"=>_t("Locale lib"),
"fb_locale","custom_form","related_search", "media_dir",
"media_mode" */
        "use_recaptcha"=>_t("Use reCAPTCHA "),"recaptcha_public_key"=>_t("reCAPTCHA Public Key"),
    "recaptcha_private_key"=>_t("reCAPTCHA Private Key"));

$alt_conf_check=array("acl_base"=>"t","debug"=>"t","enable_cache"=>"t","custom_form"=>"t",
    "related_search"=>"t","use_recaptcha"=>"t");


$alt_conf['hide_person_format'] = _t('Hide Person Format');
$alt_conf['hide_event_format'] = _t('Hide Event Format');
$alt_conf['hide_act_format'] = _t('Hide Act Format');
$alt_conf['hide_supporting_docs_meta_format'] = _t('Hide Document Format');
$alt_conf['hide_arrest_format'] = _t('Hide Arrest Format');
$alt_conf['hide_destruction_format'] = _t('Hide Destruction Format');
$alt_conf['hide_killing_format'] = _t('Hide Killing Format');
$alt_conf['hide_torture_format'] = _t('Hide Tortur Format');
$alt_conf['hide_chain_of_events_format'] = _t('Hide Chain of Events Format');
$alt_conf['hide_involvement_format'] = _t('Hide Involvement Format');
$alt_conf['hide_information_format'] = _t('Hide Information Format');
$alt_conf['hide_intervention_format'] = _t('Hide Intervention Format');
$alt_conf['hide_biographic_details_format'] = _t('Hide Biographic Details Format');
$alt_conf['hide_address_format'] = _t('Hide Address Format');


$alt_conf_check['hide_person_format'] = "t";
$alt_conf_check['hide_event_format'] = "t";
$alt_conf_check['hide_act_format'] = "t";
$alt_conf_check['hide_supporting_docs_meta_format'] = "t";
$alt_conf_check['hide_arrest_format'] = "t";
$alt_conf_check['hide_destruction_format'] = "t";
$alt_conf_check['hide_killing_format'] = "t";
$alt_conf_check['hide_torture_format'] = "t";
$alt_conf_check['hide_chain_of_events_format'] = "t";
$alt_conf_check['hide_involvement_format'] = "t";
$alt_conf_check['hide_information_format'] = "t";
$alt_conf_check['hide_intervention_format'] = "t";
$alt_conf_check['hide_biographic_details_format'] = "t";
$alt_conf_check['hide_address_format'] = "t";
