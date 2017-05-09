<?php
class EntityRelations{

	private $entityRelasionships = array(
	'event' => array(
						array('to'=>'act' , 'via'=>null) ,
						array('to'=>'information' , 'via' => null),
						array('to'=>'intervention' , 'via' => null),
						array('to'=>'involvement' , 'via' => 'act'),
						array('to'=>'source' , 'via' => 'information'),
						array('to'=>'perpetrator' , 'via' => 'act'),
						array('to'=>'victim' , 'via' => 'act'),
						array('to'=>'invervening_party' , 'via' => 'intervention'),
						array('to'=>'chain_of_events' , 'via' => null)

						),
	'act' => array(
						array('to'=>'event' , 'via'=>null) ,
						array('to'=>'victim' , 'via' => null),
						array('to'=>'involvement' , 'via' => null),
						array('to'=>'perpetrator' , 'via' => 'involvement'),
						array('to'=>'victim' , 'via' => null),
						array('to'=>'killing' ,'via' => null ),
						array('to'=>'destruction' ,'via' => null ),
						array('to'=>'arrest' ,'via' => null ),
						array('to'=>'torture' ,'via' => null ),
						),
	'information' => array(
						array('to'=>'event' , 'via'=>null) ,
						array('to'=>'victim' , 'via' => null),
						array('to'=>'source' , 'via' => null)
						),
	'intervention' => array(
						array('to'=>'event' , 'via'=>null) ,
						array('to'=>'intervening_party' , 'via' => null),
						array('to'=>'victim' , 'via' => null)
						),
	'involvement' => array(
						array('to'=>'event' , 'via'=>'act') ,
						array('to'=>'victim' , 'via' => 'act'),
						array('to'=>'act' , 'via' => null),
						array('to'=>'perpetrator' , 'via' => null)
						),
	'person' => array(
						array('to'=>'address' , 'via'=>null),
						array('to'=>'credit_card' , 'via'=>null),
						array('to'=>'biographic_details' , 'via'=>null  ),
						array('to'=>'related_person' , 'via'=>'biographic_details' )
						),
	'victim' => array(
						array('to'=>'involvement' , 'via'=>'act') ,
						array('to'=>'perpetrator' , 'via' => 'act'),
						array('to'=>'event' , 'via' => 'act'),
						array('to'=>'act' , 'via' => null)
						)
	);

	private $entityJoinFields = array(
		'event'=>array( 'act'=> array('event' , 'event_record_number' , 'act' , 'event'),
						'information' => array ('event' , 'event_record_number' , 'information' , 'event' ),
						'intervention'=> array ('event' , 'event_record_number' , 'intervention' , 'event' ) ,
						'chain_of_events'=> array ('event' , 'event_record_number' , 'chain_of_events' , 'event' )
		),
		'act' => array(
						'event'=>array('act' , 'event' , 'event' , 'event_record_number'),
						'involvement'=>array('act' , 'act_record_number' , 'involvement' , 'act') ,
						'victim'=>array('act' , 'victim' , 'person' , 'person_record_number') ,
						'killing' =>array('act' , 'act_record_number' , 'killing' , 'killing_record_number') ,
						'destruction' =>array('act' , 'act_record_number' , 'destruction' , 'destruction_record_number') ,
						'arrest' =>array('act' , 'act_record_number' , 'arrest' , 'arrest_record_number') ,
						'torture' =>array('act' , 'act_record_number' , 'torture' , 'torture_record_number')

		),
		'information' => array(
						'event'=>array('information' , 'event' , 'event' , 'event_record_number') ,
						'source'=>array('information' , 'source' , 'person' , 'person_record_number') ,
						'victim'=>null // to be filled
		),
		'intervention' => array(
						'event'=>array('intervention','event','event','event_record_number'),
						'intervening_party'=>array('intervention','intervening_party','person' , 'person_record_number'),
						'victim'=>array('intervention','victim','person' , 'person_record_number')
		),
		'involvement' => array(
						'act'=> array('involvement' , 'act' , 'act' , 'act_record_number') ,
						'perpetrator'=> array('involvement' , 'perpetrator' , 'person' , 'person_record_number')
		),
		'person' => array(
						'address'=> array( 'person' , 'person_record_number' ,'address' , 'person'),
						'credit_card'=> array( 'person' , 'person_record_number' ,'credit_card' , 'record_number'),
						'biographic_details' => array('person','person_record_number' , 'biographic_details','person' )
		),
		'biographic_details' => array(
						'person' => array('biographic_details', 'person', 'person', 'person_record_number')
		),
		'chain_of_events' => array(
						'event' => array('chain_of_events', 'event', 'event', 'event_record_number')
		)
	);


	public function getEntitiesForPath($entitySource , $entityDestination, &$reverse=false ){
		$pathArray = array();
		static $end ;  // used to ensure that the recurssion stops

		$currentEntity = $this->entityRelasionships[$entitySource];

		if($currentEntity == null && $end!=true){ // if not found,  try the other way around
			$end = true;
			$pathArray = $this->getEntitiesForPath($entityDestination , $entitySource);

			$reverse = true;
			if(count($pathArray) > 0){
				return array_reverse( $pathArray) ;
			}else{
				return null;
			}

		}else{
			while( $currentEntity != null ){
				foreach($currentEntity as $pathEntity){
					if($pathEntity['to'] == $entityDestination ){
						if($pathEntity['via'] != null){
							$pathArray[] =  $pathEntity['via']  ;
						}
						$currentEntity = $this->entityRelasionships[ $pathEntity['via'] ];
					}
				}
			}
			if(count($pathArray) > 0){
				return $pathArray;
			}else{
				return null;
			}
		}
	}

	public function getJoins($entitySource , $entityDestination ){
		$joins = array();
		$entityPath = $this->getEntitiesForPath($entitySource, $entityDestination , $reverse);

		$entityList = array();
		$entityList[] = $entitySource;
		foreach($entityPath as $entityNode){
			$entityList[] = $entityNode;
		}
		$entityList[] = $entityDestination;
		//var_dump('entityList',$entityList);
		for( $i=0 ; $i<count($entityList) - 1; $i++){
			$joins[] = $this->getJoinArray($entityList[$i],$entityList[$i+1]);
		}

		return $joins;


	}

	private function getJoinArray($entity1 , $entity2){
		$entity1Array = $this->entityJoinFields[$entity1];
		foreach($entity1Array as $entityKey=>$nextEntity){
			if($entityKey == $entity2){
				$nextEntity['as'] = $entity2;
				//var_dump('nextentity',$nextEntity);
				return $nextEntity;
			}
		}
	}


}
