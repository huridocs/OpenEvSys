<?php
global $conf;

$entity_select_options = array(
                    ''=>'',
                    'person'=>_t('PERSON'),
                    'event'=>_t('EVENT'), 
                    'act'=>_t('ACT'), 
                    'arrest'=>_t('ARREST'), 
                    'destruction'=>_t('DESTRUCTION'), 
                    'killing'=>_t('KILLING') , 
                    'torture'=>_t('TORTURE') ,  
                    'chain_of_events'=>_t('CHAIN_OF_EVENT'), 
                    'involvement' => _t('INVOLVEMENT'), 
                    'information' => _t('INFORMATION'), 
                    'intervention' => _t('INTERVENTION'), 
                    'biographic_details'=>_t('BIOGRAPHIC_DETAILS') , 
                    'address'=>_t('ADDRESS'), 
                    'supporting_docs_meta'=>_t('DOCUMENT')
                    );

$customization_form = array(
    'entity_select'=>array(
         'type'      =>'select',
         'label'     =>_t('SELECT_A_FORMAT') ,
         'extra_opts'=>array(  
               'options' =>$entity_select_options ,
                'value'=>$_REQUEST['entity_select']
                )
         ),
    'change_entity' => array('type'=>'submit','label'=>_t('SELECT'))
);


