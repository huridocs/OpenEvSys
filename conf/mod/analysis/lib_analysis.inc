<?php

function analysis_get_query()
{
    unset($_GET['mod']);
    unset($_GET['act']);
    unset($_GET['actions']);
    unset($_GET['query_save']);
    unset($_GET['query_name']);
    unset($_GET['query_desc']);
    return serialize($_GET);
}

function analysis_get_actions()
{
	$actions = array(
        'none'=>_t('CHOOSE_AN_ACTION'),
        'csv'=>_t('CSV'),
        'report'=>_t('CREATE_REPORT_FROM_THESE_RESULTS'),
        'export_spreadsheet'=>_t('EXPORT_THIS_TABLE_TO_A_SPREADSHEET'),
        'save_org_sql'=>_t('SAVE_THIS_QUERY'),
        'save_shuffle_sql'=>_t('SAVE_THIS_QUERY_WITH_THIS_SHUFFLE')
        );
    return $actions;
}


function analysis_get_search_entities()
{
    $search_entities = array(
        'event'=>array(
            'type'=>'event',							
            'title'=>_t('EVENTS'),
            'desc'=>_t('SEARCH_INFORMATION_ABOUT_EVENTS_')
        ),
        'person'=>array(
            'type'=>'person',									
            'title'=>_t('PERSONS'),
            'desc'=>_t('SEARCH_INFORMATION_ON_PERSONS__WHETHER_THEY_ARE_INDIVIDUALS__GROUPS_OR_ORGANISATIONS_')
        ),
        'supporting_docs_meta'=>array(
            'type'=>'supporting_docs_meta',										
            'title'=>_t('DOCUMENTS'),
            'desc'=>_t('FIND_DOCUMENTS_WHICH_HAVE_BEEN_UPLOADED_TO_OPENEVSYS__AND_SEE_HOW_THEY_HAVE_BEEN_LINKED_IN_SUPPORT_OF_OTHER_FORMATS')
        ),
        'victim'=>array(
            'type'=>'victim',							
            'ac_type'=>'person',							
            'title'=>_t('VICTIMS'),
            'desc'=>_t('SEARCH_INFORMATION_ON_PERSONS_WHO_HAVE_BEEN_VICTIMS_')
        ),
        'perpetrator'=>array(
            'type'=>'perpetrator',							
            'ac_type'=>'person',							
            'title'=>_t('PERPETRATORS'),
            'desc'=>_t('SEARCH_INFORMATION_ABOUT_PERSONS_WHO_HAVE_BEEN_PERPETRATORS_OF_ACTS_AGAINST_VICTIMS_')
        ),			
        'source'=>array(
            'type'=>'source',							
            'ac_type'=>'person',							
            'title'=>_t('SOURCES'),
            'desc'=>_t('SEARCH_INFORMATION_ABOUT_PERSONS_WHO_HAVE_PROVIDED_INFORMATION_')
        ),
        'intervening_party'=>array(
            'type'=>'intervening_party',					
            'ac_type'=>'person',							
            'title'=>_t('INTERVENING_PARTIES'),
            'desc'=>_t('SEARCH_INFORMATION_ABOUT_PERSONS_WHO_HAVE_INTERVENED_IN_EVENTS_IN_SOME_WAY_')
        ),
        'act'=>array(
            'type'=>'act',						
            'title'=>_t('ACTS'),
            'desc'=>_t('SEARCH_INFORMATION_ON_THE_ACTS_COMMITTED_AGAINST_VICTIMS_')
        ),
        'involvement'=>array(
            'type'=>'involvement',						
            'title'=>_t('INVOLVEMENTS'),
            'desc'=>_t('SEARCH_INFORMATION_ON_THE_WAYS_IN_WHICH_PERPETRATORS_HAVE_BEEN_INVOLVED_IN_SPECIFIC_ACTS_')
        ),
        'information'=>array(
            'type'=>'information',						
            'title'=>_t('INFORMATION'),
            'desc'=>_t('SEARCH_INFORMATION_ON_THE_DATA_PROVIDED_BY_SOURCES_')
        ),
        'intervention'=>array(
            'type'=>'intervention',						
            'title'=>_t('INTERVENTIONS'),
            'desc'=>_t('SEARCH_RECORDS_ABOUT_HOW_PERSONS_HAVE_INTERVENED_IN_EVENTS_OR_ON_BEHALF_OF_VICTIMS_')
        ),

      /*'additional_detail'=>array(
            'type'=>'additional_detail',				
            'title'=>'Additional Details',
            'desc'=>_t('SEARCH_ADDITIONAL_INFORMATION_RELEVANT_TO_SPECIFIC_TYPES_OF_ACTS_')
        )*/	
	);
    return $search_entities;
}

function analysis_get_advance_search_entities(){
    $entities = analysis_get_search_entities();
    $entities['address']=array(
        'type'=>'address',						
        'title'=>_t('ADDRESS'),
        'desc'=>_t('ADDRESS_OF_A_PERSON_')
    );
    $entities['biographic_details']= array(
        'type'=>'biographic_details',						
        'title'=>_t('BIOGRAPHIC_DETAILS'),
        'desc'=>_t('BIOGRAPHIC_DETAILS__')
    );
    $entities['torture']= array(
        'type'=>'torture',						
        'title'=>_t('TORTURE'),
        'desc'=>_t('TORTURE')
    );
    $entities['killing']= array(
        'type'=>'killing',						
        'title'=>_t('KILLING'),
        'desc'=>_t('KILLING')
    );
    $entities['destruction']= array(
        'type'=>'destruction',						
        'title'=>_t('DESTRUCTION'),
        'desc'=>_t('DESTRUCTION')
    );
    $entities['arrest']= array(
        'type'=>'arrest',						
        'title'=>_t('ARREST'),
        'desc'=>_t('ARREST')
    );

    $entities['chain_of_events']= array(
        'type'=>'chain_of_events',						
        'title'=>_t('CHAIN_OF_EVENTS'),
        'desc'=>_t('CHAIN_OF_EVENTS')
    );
    return $entities;
}


function analysis_get_relationship_array(){
    $rela = array( 
        'event' => array(
            'all' => array('label' => _t('ORIGINAL_SEARCH_RESULTS')),
            'victim' =>  array(
                'rel'=> array(
                    array('event' , 'event_record_number' , 'act' , 'event'),
                    array('act' , 'victim' , 'person' , 'person_record_number')
                    ),	
                'label' => _t('VICTIMS')
                ),
            'source' => array(
                'rel' => array(
                    array ('event' , 'event_record_number' , 'information' , 'event' ),
                    array ('information' , 'source' , 'person' , 'person_record_number' )
                    ),
                'label' => _t('SOURCES')
                ),
            'intervening_party' => 	array( 
                'rel' =>	array(
                    array ('event' , 'event_record_number' , 'intervention' , 'event' ) ,
                    array ('intervention' , 'intervening_party' , 'person' , 'person_record_number' )
                    ),
                'label' => _t('INTERVENING_PARTIES')
                ),
            'perpetrator' => array( 
                    'rel'=>	array(
                        array('event' , 'event_record_number' , 'act' , 'event') ,
                        array(  'act' , 'act_record_number' , 'involvement' , 'act'),
                        array(  'involvement' , 'perpetrator' , 'person' , 'person_record_number')
                        ),
                    'label' => _t('PERPETRATOR')				     							
                    )	 	 
        ),
        'person' => array( 	
            'all' => array('label' => _t('ORIGINAL_SEARCH_RESULTS')), 
            'victim' =>  array( 
                'rel'=> array( array('person' , 'person_record_number' , 'act' , 'victim') ),	
                'label' => _t('VICTIMS')
                ),
            'source' => array( 	
                'rel' => array(	array ('person' , 'person_record_number' , 'information' , 'source' ) ),
                'label' => _t('SOURCES')
            ),
            'intervening_party' => 	array( 
                'rel' => array( array ('person' , 'person_record_number' , 'intervention' , 'intervening_party' ) ),
                'label' => _t('INTERVENING_PARTIES')
            ),
            'perpetrator' => array( 
                'rel'=>	array(
                        array('person' , 'person_record_number' , 'involvement' , 'perpetrator') 
                ),
                'label' => _t('PERPETRATORS')
            )	 	 
        ),

        'act' 	=> 	array( 	'all' =>	array('label' => _t('ORIGINAL_SEARCH_RESULTS')), 
            'victim' =>  array( 
                'rel'=> array(
                    array('act' , 'victim' , 'person' , 'person_record_number') 
                ),	
                'label' => _t('VICTIMS')
            ),
            'perpetrator' => array( 
            	'rel'=>	array(
                    array('act' , 'act_record_number' , 'involvement' , 'act') ,
                    array('involvement' , 'perpetrator' , 'person' , 'person_record_number') 
                ),
                'label' => _t('PERPETRATORS')
            ),
            'event' => array(
                'rel'=>	array(
                    array('act' , 'event' , 'event' , 'event_record_number') 
                ),
                'label' => _t('EVENTS')
            )
        ),

        'information' => array( 
            'all' =>	array('label' => _t('ORIGINAL_SEARCH_RESULTS')), 
            'victim' =>  array( 
                'rel'=> array(
                     array('information' , 'event' , 'event' , 'event_record_number') ,
                     array('event' , 'event_record_number' , 'act' , 'event'), 
                     array('act' , 'victim' , 'person' , 'person_record_number') 
                ),	
                'label' => _t('VICTIMS')
            ),
            'perpetrator' =>  array( 
                'rel'=> array(
                     array('information' , 'event' , 'event' , 'event_record_number') ,
                     array('event' , 'event_record_number' , 'act' , 'event'),
                     array('act' , 'act_record_number' , 'involvement' , 'act'),
                     array('involvement' , 'perpetrator' , 'person' , 'person_record_number') 
                 ),	
                 'label' => _t('PERPETRATORS')
            ),	 									     					
            'event' => array( 	
                'rel'=>	array(
                     array('information' , 'event' , 'event' , 'event_record_number') 
                ),
                'label' => _t('EVENTS')
            ),
            'act' => array( 
                'rel'=>	array(
                     array('information' , 'event' , 'event' , 'event_record_number') ,
                     array('event' , 'event_record_number' , 'act' , 'event') 
                 ),
                'label' => _t('ACTS')
            ),
            'involvement' => array( 
                'rel'=>	array(
                    array('information' , 'event' , 'event' , 'event_record_number') ,
                    array('event' , 'event_record_number' , 'act' , 'event'),
                    array('act' , 'act_record_number' , 'involvement' , 'act') 
                ),
                'label' => _t('INVOLVEMENTS')
            )	
        ),

        'victim' => array( 	
            'all' => array('label' => _t('ORIGINAL_SEARCH_RESULTS')), 
            'act' => array( 
                'rel'=>	array(
                    //array('person' , 'person_record_number' , 'act' , 'victim')  
                ),
                'label' => _t('ACTS')
            ),
            'event' => array( 	
                'rel'=>	array(
                    //array('person' , 'person_record_number' , 'act' , 'victim')  ,
                    array('act' , 'event' , 'event' , 'event_record_number') 
                    ),
                'label' => _t('EVENTS')
                )
               /* ,
            'perpetrator' =>  array( 
                'rel'=> array(
                    //array('person' , 'person_record_number' , 'act' , 'victim')  ,
                    array('act' , 'act_record_number' , 'involvement' , 'act'),
                    array('involvement' , 'perpetrator' , 'person' , 'person_record_number') 
                ),	
                'label' => 'Perpetrators'
            )	 							*/		     				
        ),

        'intervention'=>array(
            'all'=>array('label'=>_t('ORIGINAL_SEARCH_RESULTS')),
            'event'=>array(
                'rel'=>array(
                    array('intervention','event','event','event_record_number')
                ),
                'label'=>_t('EVENTS')),
            'victim'=>array(
                'rel'=>array(
                    array('intervention','event','event','event_record_number'),
                    array('event','event_record_number','act','event'),
                    array('act','victim','person','person_record_number')
                ),
                'label'=>_t('VICTIMS')
            )
        ),

        'intervening_party' => 	array( 	
            'all' =>	array('label' => ''), 
            'intervention' => array(
                'rel'=>	array(/*array('person' , 'person_record_number' , 'intervention' , 'intervening_party') */ ),
                'label' => _t('INTERVENTIONS')
            ),
            'victim' => array( 	
                'rel'=>	array(
                    //array('person' , 'person_record_number' , 'intervention' , 'intervening_party')  ,
                    array('intervention' , 'victim' , 'person' , 'person_record_number') 
                ),
                'label' => _t('VICTIMS')
            ),
            'event' =>  array( 
                'rel'=> array(
                    //array('person' , 'person_record_number' , 'intervention' , 'intervening_party')  ,
                    array('intervention' , 'event' , 'event' , 'event_record_number')	 									     								),	
                'label' => _t('EVENTS')
            ) 				
        ),								 										 									 	 										
        'perpetrator' => array(
            'all' => array('label' => _t('ORIGINAL_SEARCH_RESULTS')), 
            'involvement' => array(
                'rel'=>	array( /* array('person' , 'person_record_number' , 'involvement' , 'perpetrator'),*/ ),
                'label' => _t('INVOLVEMENTS')
            ),
            'act' => array( 
            	'rel'=>	array(
                    //array('person' , 'person_record_number' , 'involvement' , 'perpetrator' )  ,
                    array('involvement' , 'act' , 'act' , 'act_record_number') 
                ),
                'label' => _t('ACTS')
            ),
/*            'victim' =>  array( 
                'rel'=> array(
                    //array('person' , 'person_record_number' , 'involvement' , 'perpetrator' )  ,
                    array('involvement' , 'act' , 'act' , 'act_record_number'), 
                    array('act' , 'victim' , 'person' , 'person_record_number') 
                ),	
                'label' => 'Victims'
            ),	 */									     				
            'event' => array( 	
                'rel'=>	array(
                    //array('person' , 'person_record_number' , 'involvement' , 'perpetrator' )  ,
                    array('involvement' , 'act' , 'act' , 'act_record_number'),
                    array('act' , 'event' , 'event' , 'event_record_number') 
                ),
                'label' => _t('EVENTS')
            ) 				
        )
    );
    return $rela;	 	
}



