<?php
include_once APPROOT.'inc/lib_form_util.inc';

function act_form($viewType){
	$act_form = null;
	$act_form = generate_formarray('act',$viewType);	
	
	$act_form['cancel'] = array('type'=>'submit','label'=>_t('CANCEL'));
	$act_form['previous'] = array('type'=>'submit','label'=>_t('BACK'));                     
	$act_form['continue'] = array('type'=>'submit','label'=>_t('NEXT')); 
	$act_form['update'] = array('type'=>'submit','label'=>_t('SAVE'));
	$act_form['search'] = array('type'=>'submit','label'=>_t('SEARCH')) ;
	return $act_form;
}

function person_form($viewType){
	$person_form = null;
	$person_form = generate_formarray('person',$viewType);
    if($viewType != 'search')
	$person_form['picture'] = array('type'=>'upload','label'=>_t('PICTURE'),
							  'map'=>array('field'=>'picture'),
	                          'extra_opt'=>array('value'=>''));
	
//	'document_title' => array('type'=>'text', 'label'=>'Document Title', 
//	'map'=>array('entity'=>'supporting_docs_meta', 'field'=>'title')),
	$person_form['save'] = array('type'=>'submit','label'=>_t('NEXT'));
	$person_form['update'] = array('type'=>'submit','label'=>_t('SAVE'));
	$person_form['cancel'] = array('type'=>'submit','label'=>_t('CANCEL'));
	$person_form['previous'] = array('type'=>'submit','label'=>_t('BACK'));
	$person_form['search'] = array('type'=>'submit','label'=>_t('SEARCH')) ;
	
	return $person_form;
}

function victim_form($viewType){
    $person_form = null;
    $person_form = generate_formarray('victim',$viewType);
    $person_form['search'] = array('type'=>'submit','label'=>_t('SEARCH')) ;
    
    return $person_form;
}

function perpetrator_form($viewType){
    $person_form = null;
    $person_form = generate_formarray('perpetrator',$viewType);
    $person_form['search'] = array('type'=>'submit','label'=>_t('SEARCH')) ;
    
    return $person_form;
}

function source_form($viewType){
    $person_form = null;
    $person_form = generate_formarray('source',$viewType);
    $person_form['search'] = array('type'=>'submit','label'=>_t('SEARCH')) ;
    
    return $person_form;
}

function intervening_party_form($viewType){
    $person_form = null;
    $person_form = generate_formarray('intervening_party',$viewType);
    $person_form['search'] = array('type'=>'submit','label'=>_t('SEARCH')) ;
    
    return $person_form;
}


function biographic_form($viewType)
{
	$biography_form = null;
	$biography_form = generate_formarray('biographic_details',$viewType);	
	$biography_form['save'] = array('type'=>'submit','label'=>_t('SAVE'));
	$biography_form['cancel'] = array('type'=>'submit','label'=>_t('CANCEL'));

	return $biography_form;
}
function arrest_form($viewType){
	$act_form = generate_formarray('arrest',$viewType);

	$act_form['cancel'] = array('type'=>'submit','label'=>_t('CANCEL'));
	$act_form['previous'] = array('type'=>'submit','label'=>_t('BACK'));                     
	$act_form['continue'] = array('type'=>'submit','label'=>_t('NEXT')); 
	$act_form['update'] = array('type'=>'submit','label'=>_t('SAVE'));
	return $act_form;
}


function chain_of_events_form($viewType){
	$chain_of_events_form = null;
	$chain_of_events_form = generate_formarray('chain_of_events',$viewType);
	$chain_of_events_form['save'] = array('type'=>'submit','label'=>_t('SAVE')) ;
	return $chain_of_events_form;
}

function destruction_form($viewType){
	$destruction_form = generate_formarray('destruction', $viewType);

	
	$destruction_form['cancel'] = array('type'=>'submit','label'=>_t('CANCEL'));
	$destruction_form['previous'] = array('type'=>'submit','label'=>_t('BACK'));                     
	$destruction_form['continue'] = array('type'=>'submit','label'=>_t('NEXT')); 
	$destruction_form['update'] = array('type'=>'submit','label'=>_t('SAVE'));
	return $destruction_form;
}

function event_form($viewType){	
	$event_form = null;
	$event_form = generate_formarray('event',$viewType);
	$event_form['save'] = array('type'=>'submit','label'=>_t('FINISH'));
	$event_form['cancel'] = array('type'=>'submit','label'=>_t('CANCEL'));
	$event_form['search'] = array('type'=>'submit','label'=>_t('SEARCH'));
	return $event_form;
}


function information_form($viewType){
	$information_form = null;
	$information_form = generate_formarray('information' , $viewType);
	
	$information_form['cancel'] = array('type'=>'submit','label'=>_t('CANCEL'));
	$information_form['previous'] = array('type'=>'submit','label'=>_t('BACK'));   
	$information_form['finish'] = array('type'=>'submit','label'=>_t('FINISH')) ;
	$information_form['search'] = array('type'=>'submit','label'=>_t('SEARCH')) ;
	return $information_form;
}


function intervention_form($viewType){
	$intervention_form = null;
	$intervention_form = generate_formarray('intervention',$viewType);
	
	$intervention_form['cancel'] = array('type'=>'submit','label'=>_t('CANCEL'));
	$intervention_form['previous'] = array('type'=>'submit','label'=>_t('BACK'));
	$intervention_form['more']=array('type'=>'submit','label'=>_t('ADD_INTERVENTIONS'));
	$intervention_form['finish'] = array('type'=>'submit','label'=>_t('FINISH')) ;
	$intervention_form['search'] = array('type'=>'submit','label'=>_t('SEARCH')) ;
	return $intervention_form;
}

function  involvement_form($viewType){
	$involvement_form = null;
	$involvement_form = generate_formarray('involvement',$viewType);
		
	$involvement_form['cancel'] = array('type'=>'submit','label'=>_t('CANCEL'));
	$involvement_form['previous'] = array('type'=>'submit','label'=>_t('BACK'));                     
	$involvement_form['more'] = array('type'=>'submit','label'=>_t('ADD_MORE_PERPETRATORS'));                     
	$involvement_form['finish'] = array('type'=>'submit','label'=>_t('FINISH'));
	$involvement_form['update'] = array('type'=>'submit','label'=>_t('SAVE'));
	$involvement_form['search'] = array('type'=>'submit','label'=>_t('SEARCH'));
	return $involvement_form;
}

function killing_form($viewType){
	$killing_form = generate_formarray('killing' , $viewType);
		
	$killing_form['cancel'] = array('type'=>'submit','label'=>_t('CANCEL'));
	$killing_form['previous'] = array('type'=>'submit','label'=>_t('BACK'));                     
	$killing_form['continue'] = array('type'=>'submit','label'=>_t('NEXT')); 
	$killing_form['update'] = array('type'=>'submit','label'=>_t('SAVE'));
	return $killing_form;
}

function torture_form($viewType)
{	
	$torture_form = generate_formarray('torture' , $viewType);
		
	$torture_form['cancel'] = array('type'=>'submit','label'=>_t('CANCEL'));
	$torture_form['previous'] = array('type'=>'submit','label'=>_t('BACK'));                     
	$torture_form['continue'] = array('type'=>'submit','label'=>_t('NEXT')); 
	$torture_form['update'] = array('type'=>'submit','label'=>_t('SAVE'));
	return $torture_form;
}

function document_form($viewType){    
    $document_form = generate_formarray('supporting_docs_meta' , $viewType);   
    $document_form['doc_language'] = $document_form['language'];
    unset($document_form['language']);
    
    $document_form['cancel'] = array('type'=>'submit', 'label'=>_t('CANCEL'));
    $document_form['save'] = array('type'=>'submit', 'label'=>_t('SAVE'));
	$document_form['save_doc'] = array('type'=>'submit', 'label'=>_t('SAVE'));
	$document_form['new_doc'] = array('type'=>'submit', 'label'=>_t('SAVE'));
	$document_form['search'] = array('type'=>'submit', 'label'=>_t('SEARCH'));
    $document_form['update'] = array('type'=>'submit', 'label'=>_t('SAVE'));
    return $document_form;
}


function address_form($viewType)
{
	$address_form = generate_formarray('address' , $viewType);
	
	$address_form['save'] = array('type'=>'submit','label'=>_t('SAVE'));
	$address_form['update'] = array('type'=>'submit','label'=>_t('SAVE'));
	return $address_form;	
}

