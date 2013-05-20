<?php
global $global;


$field_type_options = array(
    'text'    =>_t('TEXT_FIELD_WITH_A_200_CHARACTER_LIMIT'),
    'textarea'=>_t('TEXTAREA_WITH_UNLIMITED_TEXT'),
    'date'    =>_t('DATE_FIELD_')
);

$new_field_form = array(
    'field_number' => array('type'=>'text','label'=>_t('FIELD_NUMBER'),'extra_opts'=>array('required'=>true)),
    'field_name'=>array('type'=>'text','label'=>_t('FIELD_NAME')  ,'extra_opts'=>array('required'=>true)  ),
    'field_label'=>array('type'=>'text','label'=>_t('FIELD_LABEL')  ,'extra_opts'=>array('required'=>true)  ),
    'field_type'=>array('type'=>'select','label'=>_t('FIELD_TYPE'),'extra_opts'=>array('options'=>$field_type_options)),
//    'field_mysql_type'=>array('type'=>'select','label'=>_t('MYSQL_DATA_TYPE')),
    'add_new' => array('type'=>'submit','label'=>_t('ADD_FIELD'))
);



