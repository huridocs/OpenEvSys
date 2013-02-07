<?php
    $document_form = array(
            //'event' => array('type'=>'radio_button', 'label'=>'document', 'map'=>array()),
            //'person' => array('type'=>'radio_button', 'label'=>'document', 'map'=>array()),
            //'act' => array('type'=>'radio_button', 'label'=>'document', 'map'=>array()),
            'document_title' => array('type'=>'text', 'label'=>_t('DOCUMENT_TITLE'), 'map'=>array('entity'=>'supporting_docs_meta', 'field'=>'title')),
            'creator' => array('type'=>'text', 'label'=>_t('CREATOR'), 'map'=>array('entity'=>'supporting_docs_meta', 'field'=>'creator')),
            'description' => array('type'=>'textarea', 'label'=>_t('DESCRIPTION'), 'map'=>array('entity'=>'supporting_docs_meta', 'field'=>'description')),
            'date_created' => array('type'=>'date', 'label'=>_t('DATE_CREATED'), 'map'=>array('entity'=>'supporting_docs_meta', 'field'=>'datecreated')),
            'date_submitted' => array('type'=>'date', 'label'=>_t('DATE_SUBMITTED'), 'map'=>array('entity'=>'supporting_docs_meta', 'field'=>'datesubmitted')),
            'type' => array('type'=>'text', 'label'=>_t('TYPE'), 'map'=>array('entity'=>'supporting_docs_meta', 'field'=>'format')),
            'language' => array('type'=>'text', 'label'=>_t('LANGUAGE'), 'map'=>array('entity'=>'supporting_docs_meta', 'field'=>'language')),
            'choose_file_upload' => array('type'=>'upload', 'label'=>_t('CHOOSE_FILE_UPLOAD'), 'map'=>array('entity'=>'document', 'field'=>'choose_file_upload')),
            'cancel' => array('type'=>'submit', 'label'=>_t('CANCEL')),
            'save' => array('type'=>'submit', 'label'=>_t('SAVE')),
            'update' => array('type'=>'submit', 'label'=>_t('SAVE'))
);
?>
