<?php

$entity_select = $_REQUEST['entity_select'];

$entity_form = array (
    'update'=>array('type'=>'submit','label'=>_t('UPDATE_FORM')), 
    'entity_select'=>array('type'=>'hidden','extra_opts'=>array('value'=>$entity_select, 'class'=>'data_save')),
    'reset' => array('type' => 'submit', 'label' => _t("RESET_ALL"),'extra_opts'=>array('optjs'=>'onclick="return confirm(\'Are you sure?\')"')),
    //OES-28 remove relet label 'reset_label' => array('type' => 'submit', 'label' => _t("RESET")),
    'reset_visible_new' => array('type' => 'submit', 'label' => _t("RESET")), 
    'reset_visible_view' => array('type' => 'submit', 'label' => _t("RESET")) ,
	'reset_visible_search' => array('type' => 'submit', 'label' => _t("RESET")) ,
	'reset_visible_search_display' => array('type' => 'submit', 'label' => _t("RESET")) ,
	'reset_visible_browse' => array('type' => 'submit', 'label' => _t("RESET")) ,
    'reset_order' => array('type' => 'submit', 'label' => _t("RESET")), 
    'reset_clari' => array('type' => 'submit', 'label' => _t("RESET")), 
    'reset_required' => array('type' => 'submit', 'label' => _t("RESET")),
    'reset_validation' => array('type' => 'submit', 'label' => _t("RESET")) 
);
