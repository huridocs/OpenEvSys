<?php
/**
 * Class for retriving data for listing purposes.
 *
 * This file is part of OpenEvsys.
 *
 * Copyright (C) 2009  Human Rights Information and Documentation Systems,
 *                     HURIDOCnameS), http://www.huridocs.org/, info@huridocs.org
 *
 * OpenEvsys is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OpenEvsys is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author	J P Fonseka <jo@respere.com>
 * @author	Kethees S <ks@respere.com>
 * @author	Nilushan Silva <nilushan@respere.com>
 * @author	Mahesh K K S <jo@respere.com>>
 * 
 * @package	OpenEvsys
 * @subpackage	DataModel
 *
 */

interface BrowseStrategy
{
    public function ExecuteQuery($query);
}


class Browse implements BrowseStrategy
{
    public function __construct($table = false, $pkeyarr=false, $db=false, $options=array())
    {
        global $global;
        $this->db = $global['db']; 
    }

    public static function setPager(shnPager &$pager)
    {
        $this->pager = $pager;
    }

    function ExecuteQuery($qry)
    {		
        $db = $this->db; if (!$db) return false;
        $save = $db->SetFetchMode(ADODB_FETCH_ASSOC);
        $rows = $db->GetAll($qry);
        $db->SetFetchMode($save);        
        if(sizeof($rows) > 0)
            return $rows;
            else
            return null;
    }	
    
    function ExecuteNonQuery($qry)
    {		
        $db = $this->db; if (!$db) return false;
        //$save = $db->SetFetchMode(ADODB_FETCH_ASSOC);
        $ret = $db->Execute($qry);
        return $ret;
    }
    
    function AutoExecute($table, $array , $option)
    {       
        $db = $this->db; if (!$db) return false;
        
        return $db->AutoExecute($table,$array,$option);
    }   
    
    function GetInsertID()
    {       
        $db = $this->db; if (!$db) return false;
        
        return $db->Insert_ID();
    }

	public static function getExecuteSql($sql)
	{		
		$pager = new shnPager($sql);
        return $pager;
	}

	public static function getSaveQueryList($options=null)
	{
        global $global;
		$sql = "SELECT * FROM save_query WHERE 1=1 ";
        if(isset($options['save_query_record_number'])){
            $name = $global['db']->qstr("%{$options['save_query_record_number']}%");
            $where .="AND name LIKE $name";
        }
        if(isset($options['name'])){
            $name = $global['db']->qstr("%{$options['name']}%");
            $where .="AND name LIKE $name";
        }
        if(isset($options['description'])){
            $description = $global['db']->qstr("%{$options['description']}%");
            $where .="AND description LIKE $description";
        }
        if(isset($options['created_date'])){
            $created_date = $global['db']->qstr("%{$options['created_date']}%");
            $where .="AND created_date LIKE $created_date";
        }
        ;
        if(isset($options['created_by'])){
            $created_by = $global['db']->qstr("%{$options['created_by']}%");
            $where .="AND created_by LIKE $created_by";
        }
        if(isset($options['query_type'])){
            $created_by = $global['db']->qstr("%{$options['query_type']}%");
            $where .="AND query_type LIKE $created_by";
        }
        $sql = $sql . $where;
		
		$pager = new shnPager($sql);
        return $pager;
	}

	public static function getPersonVictimRoleList($person_id)
	{
        global $conf;
		$sql = "SELECT  a.act_record_number as record_number, 
                        IFNULL(l.msgstr , v.english) as 'further_infor', 
						a.event, 
						e.event_title, 
						e.initial_date, 
						e.final_date 
				FROM person p 
				INNER JOIN act a ON p.person_record_number = a.victim
				INNER JOIN event e ON e.event_record_number = a.event
				INNER JOIN mt_vocab v ON a.type_of_act = v.vocab_number
                LEFT  JOIN mt_vocab_l10n l ON ( l.msgid = v.vocab_number AND l.locale = '{$conf['locale']}' )";

		$where = " WHERE p.person_record_number = '$person_id'";
		
		$sql .= $where;
		
		$pager = new shnPager($sql);
        return $pager;
	}

	public static function getPersonSourceRoleList($person_id)
	{
        global $conf;
		$sql = "SELECT  i.information_record_number as record_number, 
                        IFNULL(l.msgstr , v.english) as 'further_infor', 
						i.event, 
						e.event_title,
						e.initial_date,
						e.final_date 
				FROM person p
				INNER JOIN information i ON p.person_record_number = i.source
				INNER JOIN event e ON e.event_record_number = i.event
				INNER JOIN mt_vocab v ON i.source_connection_to_information = v.vocab_number
                LEFT  JOIN mt_vocab_l10n l ON ( l.msgid = v.vocab_number AND l.locale = '{$conf['locale']}' )";
		
		$where = " WHERE p.person_record_number = '$person_id'";
		
		$sql .= $where;
		
		$pager = new shnPager($sql);
        return $pager;
	}

	public static function getPersonPerpetratorRoleList($person_id)
	{
        global $conf;
		$sql = "SELECT 	i.involvement_record_number as record_number, 
                        IFNULL(l.msgstr , v.english) as 'further_infor', 
						i.event, 
						e.event_title, 
						e.initial_date, 
						e.final_date 
				FROM person p
				INNER JOIN involvement i ON p.person_record_number = i.perpetrator
				INNER JOIN event e ON e.event_record_number = i.event
				INNER JOIN mt_vocab v ON i.degree_of_involvement = v.vocab_number
                LEFT  JOIN mt_vocab_l10n l ON ( l.msgid = v.vocab_number AND l.locale = '{$conf['locale']}' )";

		$where = " WHERE p.person_record_number = '$person_id'";
		
		$sql .= $where;
		
		$pager = new shnPager($sql);
        return $pager;
	}

	public static function getPersonInterveningPartyRoleList($person_id)
	{
        global $conf;
		$sql = "SELECT 	i.intervention_record_number as record_number, 
                        IFNULL(l.msgstr , v.english) as 'further_infor', 
						i.event, 
						e.event_title, 
						e.initial_date, 
						e.final_date 
				FROM person p
				INNER JOIN intervention i ON p.person_record_number = i.intervening_party
				INNER JOIN event e ON e.event_record_number = i.event
				INNER JOIN mlt_intervention_type_of_intervention  mlt 
				ON mlt.record_number=i.intervention_record_number
				INNER JOIN mt_vocab v ON mlt.vocab_number = v.vocab_number
                LEFT  JOIN mt_vocab_l10n l ON ( l.msgid = v.vocab_number AND l.locale = '{$conf['locale']}' )";

		$where = " WHERE p.person_record_number = '$person_id'";
		
		$sql .= $where;
		
		$pager = new shnPager($sql);
        return $pager;
	}

	public static function getBiographyList($person_id)
    {
        global $conf;
		$sql = "SELECT 
                    bd.biographic_details_record_number, 
                    bd.related_person, 
                    rp.person_name, 
                    IFNULL(l.msgstr , mt.english) as 'relationship_type', 
                    bd.initial_date, 
                    bd.final_date 
				FROM biographic_details AS bd
				INNER JOIN person p ON bd.person = p.person_record_number
				LEFT JOIN person rp ON bd.related_person = rp.person_record_number
				LEFT JOIN mt_vocab mt ON bd.type_of_relationship = mt.vocab_number
                LEFT JOIN mt_vocab_l10n l ON ( l.msgid = mt.vocab_number AND l.locale = '{$conf['locale']}' ) 
				WHERE person = '$person_id'";
	
		$browse = new Browse();
		$rows = $browse->ExecuteQuery($sql);
        return $rows;
	}
	
	public static function getRelativeInfo($person_id)
	{
        global $conf;
		$sql = "SELECT 
                    bd.biographic_details_record_number, 
                    bd.related_person, 
                    p.person_name, 
                    IFNULL(l.msgstr , mt.english) AS 'relationship_type', 
                    bd.initial_date, 
                    bd.final_date ,
                    bd.person
				FROM biographic_details AS bd
				INNER JOIN person p ON bd.person = p.person_record_number
				LEFT JOIN person rp ON bd.related_person = rp.person_record_number
				LEFT JOIN mt_vocab mt ON bd.type_of_relationship = mt.vocab_number
                LEFT  JOIN mt_vocab_l10n l ON ( l.msgid = mt.vocab_number AND l.locale = '{$conf['locale']}' ) 
				WHERE bd.related_person = '$person_id'";
		
		$browse = new Browse();
		$rows = $browse->ExecuteQuery($sql);
        return $rows;
	}

	public static function getBiographyListArray($biographics)
    {
        global $conf;
		$sql = "SELECT 
                    bd.biographic_details_record_number, 
                    bd.related_person, 
                    rp.person_name, 
                    IFNULL(l.msgstr , mt.english) AS 'relationship_type', 
                    bd.initial_date, 
                    bd.final_date 
				FROM biographic_details AS bd
				INNER JOIN person p ON bd.person = p.person_record_number
				LEFT JOIN person rp ON bd.related_person = rp.person_record_number
				LEFT JOIN mt_vocab mt ON bd.type_of_relationship = mt.vocab_number
                LEFT  JOIN mt_vocab_l10n l ON ( l.msgid = mt.vocab_number AND l.locale = '{$conf['locale']}' )"; 
	
		$where =' WHERE bd.biographic_details_record_number IN (';

		if($biographics != null){
			foreach($biographics as $biographic)
				$where .= " '$biographic' ,";

			$sql = $sql . rtrim($where,',') . " )";
			
        	$browse = new Browse();
			$rows = $browse->ExecuteQuery($sql);
		}
		else{
			$rows = null;
		}
        
        return $rows;  
	}

	public static function getPersonVictimList($data)
	{
		$sql = "SELECT DISTINCT  p.* 
				FROM person AS p 
				INNER JOIN act AS a ON p.person_record_number = a.victim
				INNER JOIN involvement as i ON a.act_record_number = i.act 
				INNER JOIN person AS p2 ON i.perpetrator = p2.person_record_number 
				WHERE a.event='{$data['event_id']}'";
		$pager = new shnPager($sql);
        return $pager;
	}
  
	public static function getImportErrorLogList()
	{
		$sql = "SELECT  i.* 
				FROM import_log_report AS i ";
		$pager = new shnPager($sql);
        return $pager;
	}


    public static function getUserListArray($users){
        
        $sql = "SELECT * , u.username as username FROM user AS u 
                LEFT JOIN user_profile AS up ON u.username=up.username";      

        $where =' WHERE u.username IN (';
        foreach($users as $user)
            $where .= " '$user' ,";
        $sql = $sql . rtrim($where,',') . " )"; 
        $browse = new Browse();
        $rows = $browse->ExecuteQuery($sql);
        return $rows;
    }    
    
    public static function getSourceListforEvent($event = null, $person = null)
    {
        global $conf;
        $browse = new Browse();
		$sql = "SELECT  i.source, 
                        i.related_person, 
                        i.related_event, 
                        i.event, 
                        i.information_record_number, 
                        i.date_of_source_material, 
                        p.person_name, 
                        p.reliability_as_source,
                        IFNULL(l.msgstr , v.english) AS 'connection' 
                FROM event e 
                INNER JOIN information i ON e.event_record_number = i.event 
                INNER JOIN person p ON i.source = p.person_record_number 
                INNER JOIN mt_vocab v ON i.source_connection_to_information = v.vocab_number 
                LEFT  JOIN mt_vocab_l10n l ON ( l.msgid = v.vocab_number AND l.locale = '{$conf['locale']}' ) ";
		
		if($event != null && $person != null){
			$where = "WHERE event = '$event' AND i.source = '$person' ";
		}
		else if($event != null){
			$where = "WHERE event = '$event'";
		}
		
		$sql .= $where;

        $rows = $browse->ExecuteQuery($sql);
        return $rows;     
    }

	public static function getSourceListArray($informations = null)
    {        
        global $conf;
		$sql = "SELECT  i.source, 
                        i.related_person, 
                        i.related_event, 
                        i.event, 
                        i.information_record_number, 
                        i.date_of_source_material, 
                        p.person_name, 
                        p. reliability_as_source, 
                        IFNULL(l.msgstr , v.english) AS 'connection' 
                FROM event e 
                INNER JOIN information i ON e.event_record_number = i.event 
                INNER JOIN person p ON i.source = p.person_record_number 
                INNER JOIN mt_vocab v ON i.source_connection_to_information = v.vocab_number 
                LEFT  JOIN mt_vocab_l10n l ON ( l.msgid = v.vocab_number AND l.locale = '{$conf['locale']}' ) ";
		
		$where =' WHERE i.information_record_number IN (';

		if($informations != null){
			foreach($informations as $information)
				$where .= " '$information' ,";

			$sql = $sql . rtrim($where,',') . " )";

			$browse = new Browse();
        	$rows = $browse->ExecuteQuery($sql);
		}
		else{
			$rows = null;
		}
        
        return $rows;     
    }

    public static function getVpList($event)
    {
        $browse = new Browse();
        $res = $browse->ExecuteQuery("
        SELECT  
                (SELECT count(*) FROM involvement WHERE act = act_record_number) AS inv_count,
                initial_date , 
                p.person_name AS vname ,
                p.person_record_number AS victim_record_number, 
                a.victim ,
                type_of_act, 
                act_record_number, 
                i.perpetrator,
                p2.person_name AS pname, 
                p2.person_record_number AS perpetrator_record_number,
                i.involvement_record_number,
                i.degree_of_involvement 
        FROM act AS a 
        INNER JOIN person AS p ON a.victim = p.person_record_number 
        LEFT JOIN involvement as i ON a.act_record_number = i.act 
        LEFT JOIN person AS p2 ON i.perpetrator = p2.person_record_number
        WHERE a.event='$event' ORDER BY act_record_number");
        return $res;
    }    

	public static function getVpListArray($acts)
    {
        $sql = "SELECT  initial_date , 
                		p.person_name AS vname ,
                		a.victim ,
                		type_of_act, 
                		act_record_number, 
                		i.perpetrator,
                		p2.person_name AS pname, 
                		i.involvement_record_number,
                		i.degree_of_involvement 
        		FROM act AS a 
        		INNER JOIN person AS p ON a.victim = p.person_record_number 
        		LEFT JOIN involvement as i ON a.act_record_number = i.act 
        		LEFT JOIN person AS p2 ON i.perpetrator = p2.person_record_number";

        $where =' WHERE a.act_record_number IN (';

		if($acts != null){
			foreach($acts as $act)
				$where .= " '$act' ,";

			$sql = $sql . rtrim($where,',') . " )";

			$browse = new Browse();
        	$rows = $browse->ExecuteQuery($sql);
		}
		else{
			$rows = null;
		}
        
        return $rows;
    }
    
	public static function getVpListInvArray($invs)
    {
        $sql = "SELECT  initial_date , 
                		p.person_name AS vname ,
                		a.victim ,
                		type_of_act, 
                		act_record_number, 
                		i.perpetrator,
                		p2.person_name AS pname, 
                		i.involvement_record_number,
                		i.degree_of_involvement 
        		FROM act AS a 
        		INNER JOIN person AS p ON a.victim = p.person_record_number 
        		LEFT JOIN involvement as i ON a.act_record_number = i.act 
        		LEFT JOIN person AS p2 ON i.perpetrator = p2.person_record_number";

        $where =' WHERE i.involvement_record_number IN (';

		if($invs != null){
			foreach($invs as $inv)
				$where .= " '$inv' ,";

			$sql = $sql . rtrim($where,',') . " )";

			$browse = new Browse();
        	$rows = $browse->ExecuteQuery($sql);
		}
		else{
			$rows = null;
		}
        
        return $rows;
    }

    public static function getIntvList($event)
    {		
        $browse = new Browse();
        $res = $browse->ExecuteQuery("
        Select  i.date_of_intervention, 
				ip.person_name,  
				i.intervening_party, 
				i.intervention_record_number 
		From intervention  AS i 
		LEFT JOIN person AS ip ON i.intervening_party=ip.person_record_number
        WHERE i.event='$event'");
        return $res;
    }

    public static function getIntvTypes($intervention)
    {
        $browse = new Browse();
        $res = $browse->ExecuteQuery("
        Select  
			    mlt.vocab_number AS type_of_intervention 
		From intervention  AS i 
		LEFT JOIN mlt_intervention_type_of_intervention AS mlt 
		ON mlt.record_number=i.intervention_record_number
        WHERE i.intervention_record_number='$intervention'");
        return $res;       
    }

	public static function getIntvListArray($interventions)
    {		
        $sql = "Select  i.date_of_intervention, 
						ip.person_name,  
						mlt.vocab_number AS type_of_intervention, 
						i.intervening_party, 
						i.intervention_record_number 
				From intervention  AS i 
				LEFT JOIN person AS ip ON i.intervening_party=ip.person_record_number
				LEFT JOIN mlt_intervention_type_of_intervention AS mlt 
				ON mlt.record_number=i.intervention_record_number";
        		
        $where =' WHERE i.intervention_record_number IN (';

		if($interventions != null){
			foreach($interventions as $intervention)
				$where .= " '$intervention' ,";

			$sql = $sql . rtrim($where,',') . " )";

			$browse = new Browse();
        	$rows = $browse->ExecuteQuery($sql);
		}
		else{
			$rows = null;
		}
        
        return $rows;
    }
    
    public static function getUserList()
    {
        $sql = "SELECT U.username, first_name, last_name, organization, designation, email, address , role ,status FROM user_profile AS UP RIGHT JOIN user AS U ON U.username = UP.username";
        //filter options

        $pager = new shnPager($sql);
        return $pager;
    }
    
    public static function getPerpetrator($id)
    {
        $browse = new Browse();
        $res = $browse->ExecuteQuery("Select person_name from person where person_record_number = '$id'");
        return $res;
    }
    
    public static function getEntityFields($entity)
    {
        global $conf;
        $browse = new Browse();
        $sql = "Select d.* , 
                    IFNULL(l.msgstr , d.field_label) AS 'field_label_l10n' 
                FROM data_dict d
                LEFT  JOIN data_dict_l10n l ON ( l.msgid = d.field_number AND l.locale = '{$conf['locale']}' ) 
                WHERE entity = '$entity' ORDER BY field_number";
        $res = $browse->ExecuteQuery($sql);
        return $res;
    }

    public static function getEntityLocationFields($entity)
    {
        global $conf;
        $browse = new Browse();
        $sql = "Select d.* , 
                    IFNULL(l.msgstr , d.field_label) AS 'field_label_l10n' 
                FROM data_dict d
                LEFT  JOIN data_dict_l10n l ON ( l.msgid = d.field_number AND l.locale = '{$conf['locale']}' ) 
                WHERE entity = '$entity' and field_type='location' and enabled='y' ORDER BY field_number";
        $res = $browse->ExecuteQuery($sql);
        return $res;
    }
    
    public static function getSecondaryEntityFields($entity,$secondary_entity)
    {
        global $conf;
        $sql = " SELECT 
                    d.*, 
                    ss.field_option AS shuffel_search, 
                    sr.field_option AS shuffel_search_view 
                FROM data_dict AS d 
                INNER JOIN sec_entity_entities AS se ON (d.entity=se.entity ) 
                LEFT JOIN sec_entity_fields AS ss ON ( se.entity_key = ss.entity_key AND ss.field_name = d.field_name AND ss.field_option='search') 
                LEFT JOIN sec_entity_fields AS sr ON ( se.entity_key = sr.entity_key AND sr.field_name = d.field_name AND sr.field_option='search_view')
                WHERE d.entity='$entity' AND se.sec_entity='$secondary_entity' ORDER BY CAST(label_number as UNSIGNED)";
        $browse = new Browse();
        $res = $browse->ExecuteQuery($sql);
        return $res;
    }


    public static function getChainOfEvents($eid)
    {
		$sql = "SELECT  coe.chain_of_events_record_number as coe_id,
						coe.related_event,
						coe.type_of_chain_of_events,
						e.initial_date, 
						e.event_title	
				FROM chain_of_events as coe
				INNER JOIN event as e ON e.event_record_number = coe.related_event";	
        
		$where = " WHERE coe.event = '$eid'";

		$sql .= $where; 

		$browse = new Browse();
        $res = $browse->ExecuteQuery($sql);
		
        return $res;

    }
    public static function getChainOfEventsReverse($eid)
    {
		$sql = "SELECT  coe.chain_of_events_record_number as coe_id,
						coe.related_event,
                                                coe.event,
						coe.type_of_chain_of_events,
						e.initial_date, 
						e.event_title	
				FROM chain_of_events as coe
				INNER JOIN event as e ON e.event_record_number = coe.event";	
        
		$where = " WHERE coe.related_event = '$eid'";

		$sql .= $where; 

		$browse = new Browse();
        $res = $browse->ExecuteQuery($sql);
		
        return $res;

    }
    
	public static function getActsOfEvents($eid)
    {
		$sql = "SELECT act_record_number, event, initial_date, final_date, type_of_act, exact_location 
				FROM act ";	
        
		$where = " WHERE event = '$eid'";

		$sql .= $where; 

		$browse = new Browse();
        $res = $browse->ExecuteQuery($sql);
		
        return $res;

    }
    
	public static function getInvolvementsOfEvents($eid)
    {
		$sql = "SELECT involvement_record_number, act, event, degree_of_involvement, latest_status_as_perpetrator_in_the_act, remarks 
				FROM involvement";	
        
		$where = " WHERE event = '$eid'";

		$sql .= $where; 

		$browse = new Browse();
        $res = $browse->ExecuteQuery($sql);
		
        return $res;

    }
    
	public static function getInformationsOfEvents($eid)
    {
		$sql = "SELECT information_record_number, source_connection_to_information, date_of_source_material, remarks, reliability_of_information 
				FROM information";	
        
		$where = " WHERE event = '$eid'";

		$sql .= $where; 

		$browse = new Browse();
        $res = $browse->ExecuteQuery($sql);
		
        return $res;

    }
    
	public static function getInterventionsOfEvents($eid)
    {
		$sql = "SELECT intervention_record_number,impact_on_the_situation, date_of_intervention, remarks, intervention_status 
				FROM intervention";	
        
		$where = " WHERE event = '$eid'";

		$sql .= $where; 

		$browse = new Browse();
        $res = $browse->ExecuteQuery($sql);
		
        return $res;

    }
        
	public static function getEventCOE($eid)
    {
		$sql = "SELECT coe.chain_of_events_record_number as coe_id, coe.event, 
				coe.type_of_chain_of_events, e.initial_date, e.event_title 
				FROM chain_of_events as coe 
				INNER JOIN event as e ON e.event_record_number = coe.event";	
        
		$where = " WHERE coe.related_event = '$eid'";

		$sql .= $where; 

		$browse = new Browse();
        $res = $browse->ExecuteQuery($sql);
		
        return $res;

    }

	public static function getCOEListArray($coes)
    {
		$sql = "SELECT  coe.chain_of_events_record_number as coe_id,
						coe.related_event,
						coe.type_of_chain_of_events,
						e.initial_date, 
						e.event_title	
				FROM chain_of_events as coe
				INNER JOIN event as e ON e.event_record_number = coe.related_event";	
        
		 $where =' WHERE coe.chain_of_events_record_number IN (';

		if($coes != null){
			foreach($coes as $coe)
				$where .= " '$coe' ,";

			$sql = $sql . rtrim($where,',') . " )";

			$browse = new Browse();
        	$rows = $browse->ExecuteQuery($sql);
		}
		else{
			$rows = null;
		}
        
        return $rows;

    }

    public static function getEvent($eid)
    {
        $browse = new Browse();
        $res = $browse->ExecuteQuery("SELECT * FROM event WHERE event_record_number='$eid'");
        return $res;

    }

    public static function getFields($entity)
    {
        global $conf;
        $browse = new Browse();
        $sql = "SELECT 
                    field_number, 
                    field_label, 
                    field_name, 
                    field_type, 
                    label_number, 
                    clar_note, 
                    enabled , 
                    visible_new , 
                    visible_edit , 
                    visible_view, 
                    visible_browse, 
                    visible_browse_editable , 
                    visible_search , 
                    visible_search_display, 
                    required , 
                    validation , 
                    essential,
                    l.msgstr AS field_label_l10n
                FROM data_dict 
                LEFT JOIN data_dict_l10n as l ON ( l.msgid = field_number AND l.locale = '{$conf['locale']}' ) 
                WHERE entity='$entity' ORDER BY CAST(label_number as UNSIGNED)";
        $res = $browse->ExecuteQuery($sql);
        return $res;
    }
    
    public static function getFieldsIds($entity)
    {
        $browse = new Browse();
        $res = $browse->ExecuteQuery("SELECT field_number,field_name , essential FROM data_dict WHERE entity='$entity' ORDER BY CAST(label_number as UNSIGNED)");
        return $res;
    }
    public static function getFieldByName($entity,$field_name)
    {
        $browse = new Browse();
        $res = $browse->ExecuteQuery("SELECT field_number,field_name , essential FROM data_dict WHERE entity='$entity' and field_name='$field_name'");
        return $res;
    }
    
    public static function getAuditLogForEvent($event_record_number){
        $browse = new Browse();        
        $sql = "select * from (SELECT audit_log.* FROM audit_log WHERE module='events' AND module_record_number='$event_record_number'  
		UNION 
		SELECT audit_log.* FROM audit_log
        inner JOIN (SELECT distinct(`record_number`) FROM audit_log WHERE module='events' AND module_record_number='$event_record_number' and `entity`='person') as p 
        ON p.record_number = audit_log.record_number) t
		ORDER BY timestamp desc" ;

        $pager = new shnPager($sql);
        return $pager;
    }
    
    public static function getAuditLogForPerson($person_record_number){
        $browse = new Browse();        
        $res = $browse->ExecuteQuery("select * from(SELECT * FROM audit_log WHERE module='person' AND module_record_number='$person_record_number'
        UNION
		SELECT * FROM audit_log WHERE entity='person' AND module = 'events' AND record_number='$person_record_number')
                    t ORDER BY timestamp desc
        ");        
        return $res;
    }
    
	public static function getAuditLogForDocument($document_record_number){
        $browse = new Browse();
              
        $sql = "select * from (SELECT * FROM audit_log WHERE module='docu' AND module_record_number='$document_record_number' 
		UNION 
		SELECT * FROM audit_log WHERE (entity LIKE '%_doc' OR entity ='supporting_docs_meta') AND record_number='$document_record_number' )
                    t ORDER BY timestamp desc"; 
        $res = $browse->ExecuteQuery($sql);        
        return $res;
    }

    public static function gethelp($id){
        global $global;global $conf;
		$browse= new Browse();
        $id = $global['db']->qstr($id);
//        $sql = "SELECT * FROM help WHERE field_number = $id ";
        $sql = "SELECT 
                    d.field_number ,
                    IFNULL(dl.msgstr , d.field_label) as 'field_label', 
                    IFNULL(hl.definition , h.definition) as 'definition', 
                    IFNULL(hl.guidelines , h.guidelines) as 'guidelines', 
                    IFNULL(hl.entry , h.entry) as 'entry', 
                    IFNULL(hl.examples , h.examples) as 'examples'
                FROM data_dict AS d
                LEFT JOIN help AS h ON h.field_number = d.field_number
                LEFT JOIN help_l10n AS hl ON (hl.field_number = d.field_number AND hl.locale = '{$conf['locale']}')
                LEFT JOIN data_dict_l10n AS dl ON (d.field_number = dl.msgid AND dl.locale = '{$conf['locale']}' )
                WHERE d.field_number = $id ";
		$res = $browse->ExecuteQuery($sql);
		return $res;
    }

    public static function getAllDocuments(BrowseStrategy $browse , $data = null)
    {
        $sql = "SELECT docs.doc_id as doc_id, docs.uri as uri, meta.title as title, meta.creator as creator, meta.description as description, meta.dateCreated as dateCreated, meta.dateSubmitted as dateSubmitted, meta.format as format, meta.language as language, meta.subject as subject FROM supporting_docs as docs, supporting_docs_meta as meta WHERE docs.doc_id=meta.doc_id";
        //filter options
        if($data['title']!='')
            $and = " AND meta.title LIKE '%{$data['title']}%'";
        if($data['creator']!='')
            $and = " AND meta.creator LIKE '%{$data['creator']}%'";
        if($data['dateCreated']!='')
            $and = " AND meta.dateCreated LIKE '%{$data['dateCreated']}%'";
		if($data['dateSubmitted']!='')
            $and = " AND meta.dateSubmitted LIKE '%{$data['dateSubmitted']}%'";
        if($data['Description']!='')
            $and = " AND meta.description LIKE '%{$data['Description']}%'";

        $sql = $sql.$and;
        return $browse->ExecuteQuery($sql);
    }

    public static function getDocumentLinks($doc_id , $entity = null)
    {
        if(!isset($entity)) return null;
        $sql = "SELECT * FROM {$entity}_doc WHERE doc_id = '$doc_id'";
        $browse = new Browse();
        return $browse->ExecuteQuery($sql);
    }


    public static function getHelpText($entity,$filter = null)
    {
        global $global;global $conf;
        $entity = $global['db']->qstr($entity);
        $sql = "SELECT 
                    IFNULL(dl.msgstr , d.field_label) as 'field_label', 
                    d.field_number,
                    h.definition,
                    h.guidelines,
                    h.entry,
                    h.examples,
                    l.definition as l10n_definition,
                    l.guidelines as l10n_guidelines,
                    l.entry as l10n_entry,
                    l.examples as l10n_examples
                FROM data_dict AS d
                LEFT JOIN help AS h ON d.field_number = h.field_number
                LEFT JOIN help_l10n AS l ON (d.field_number = l.field_number AND l.locale = '{$conf['locale']}') 
                LEFT JOIN data_dict_l10n AS dl ON (d.field_number = dl.msgid AND dl.locale = '{$conf['locale']}' )
                WHERE entity=$entity ";
        if(isset($filter))
            $sql = $sql . " AND huri_code LIKE '$filter'  ";

//        $sql = $sql." ORDER BY huri_code";
       	$pager = new shnPager($sql);
        return $pager;
    }

    public static function getFieldHelpText($entity,$filter )
    {
        global $global;global $conf;
        $entity = $global['db']->qstr($entity);
        $filter = $global['db']->qstr($filter);
        $sql = "SELECT 
                    IFNULL(dl.msgstr , d.field_label) as 'field_label', 
                    d.field_number,
                    h.definition,
                    h.guidelines,
                    h.entry,
                    h.examples,
                    l.definition as l10n_definition,
                    l.guidelines as l10n_guidelines,
                    l.entry as l10n_entry,
                    l.examples as l10n_examples
                FROM data_dict AS d
                LEFT JOIN help AS h ON d.field_number = h.field_number
                LEFT JOIN help_l10n AS l ON (d.field_number = l.field_number AND l.locale = '{$conf['locale']}') 
                LEFT JOIN data_dict_l10n AS dl ON (d.field_number = dl.msgid AND dl.locale = '{$conf['locale']}' )
                WHERE d.entity=$entity  ";
        $sql = $sql . " AND d.field_number = $filter ";
        $res = $global['db']->GetRow($sql);
        if(count($res)>0)
            return $res;
        else
            return false;
    }

    public static function getHuriTermsForListCodePaged($fieldName,$filter = null , $filterValues=null ){
        global $global;global $conf;
    	if(  is_numeric($fieldName) ){
            $listCode = (int)$fieldName;
        }
        
        if(is_array($filterValues)){
            $huricode = $filterValues['huricode'];
            $mt_term = $filterValues['mt_term'];
            if(!( $mt_term=='' ||$mt_term == null) ){
                $whereMt = " (english LIKE '%$mt_term%' OR l.msgstr LIKE '%$mt_term%') ";
            }
            if(!( $huricode=='' ||$huricode == null) ){
                $whereHuri = " huri_code LIKE '$huricode%' ";
            }
            if($whereMt != null && $huricode!= null){
                $filterWhere = " AND $whereHuri AND $whereMt ";;
            }else if($whereMt == null && $huricode == null){
                $filterWhere = null ;   
            }
            else{
                $filterWhere = " AND $whereHuri $whereMt ";;
            }
        }
        
        $sql = "SELECT 
                    vocab_number , 
                    huri_code , 
                    english AS 'mt_term' , 
                    list_code ,
                    l.msgstr AS 'mt_term_l10n' ,
                    visible
                FROM mt_vocab 
                LEFT JOIN mt_vocab_l10n as l ON (l.msgid = vocab_number AND l.locale = '{$conf['locale']}' ) 
                WHERE TRIM(list_code)='$listCode' $filterWhere  ";
        if(isset($filter))
            $sql = $sql . " AND huri_code LIKE '$filter'  ";

          $sql = $sql." ORDER BY huri_code";
       	$pager = new shnPager($sql);
        return $pager;
    }
    
	public static function getHuriTermsForListCodePaged2($fieldName,$filter){
        
		global $global;global $conf;
    	if(  is_numeric($fieldName) ){
            $listCode = (int)$fieldName;
        }
        $sql = "SELECT 
                    count(vocab_number)                  
                FROM mt_vocab 
                LEFT JOIN mt_vocab_l10n as l ON (l.msgid = vocab_number AND l.locale = '{$conf['locale']}' ) 
                WHERE TRIM(list_code)='$listCode' ";
        if(isset($filter))
            $sql = $sql . " AND huri_code LIKE '$filter'  ";
       		$sql = $sql." ORDER BY huri_code";
       		$res = $global['db']->getOne($sql);
       		if($res > 1){
       			return true;
       		}
       		else{
       			return false;       		
       		}
    }

	public static function getTheChildren($fieldName,$filter){
        
		global $global;global $conf;
    	if(  is_numeric($fieldName) ){
            $listCode = (int)$fieldName;
        }
                
        $sql = "SELECT 
                    vocab_number                    
                FROM mt_vocab 
                LEFT JOIN mt_vocab_l10n as l ON (l.msgid = vocab_number AND l.locale = '{$conf['locale']}' ) 
                WHERE TRIM(list_code)='$listCode' ";
        if(isset($filter))
            $sql = $sql . " AND huri_code LIKE '$filter'  ";
       		$sql = $sql." ORDER BY huri_code";
       		$res = $global['db']->getAll($sql);
       		
       	return $res;       		
       	
    }
	public static function getHuriTermsForListArray($vocab_numbers)
    {		
        $sql = "select * from mt_vocab";
        		
        $where =' WHERE vocab_number IN (';

		if($vocab_numbers != null){
			foreach($vocab_numbers as $vocab_number)
				$where .= " '$vocab_number' ,";

			$sql = $sql . rtrim($where,',') . " )";

			$browse = new Browse();
        	$rows = $browse->ExecuteQuery($sql);
		}
		else{
			$rows = null;
		}
        
        return $rows;
    }

    public static function getIncidentRecords($eid)
    {
        $browse = new Browse();
        $res = $browse->ExecuteQuery("SELECT act.victim, act.act_record_number, act.type_of_act, invo.perpetrator FROM act as act, involvement as invo WHERE act.act_record_number = invo.act AND act.event='$eid' AND invo.event='$eid' ");
        return $res;
        
    }

    public static function getSourceRecords($eid)
    {
        $browse = new Browse();
        $res = $browse->ExecuteQuery("SELECT info.source, inte.intervening_party, inte.date_of_intervention FROM intervention as inte, information as info WHERE info.event = inte.event AND info.event = '$eid' AND inte.event = '$eid' ORDER BY info.source");
        return $res;
    }

    public static function getChainOfEventsRecords($eid)
    {
        $browse = new Browse();
        $res = $browse->ExecuteQuery("SELECT * FROM chain_of_events WHERE event='$eid'");
        return $res;
    }

    public static function getAllEntityFields()
    {
    	global $conf;
        $browse = new Browse();
        $sql = "SELECT 
                    field_number ,
                    field_name ,
                    datatype ,
                    IFNULL(dl.msgstr , d.field_label) as 'field_label',                      
                    LOWER(entity) as entity ,
                    field_type ,
                    visible_adv_search_display as in_results,
                    list_code 	
                FROM data_dict as d 
                LEFT JOIN data_dict_l10n AS dl ON (d.field_number = dl.msgid AND dl.locale = '{$conf['locale']}' )
                WHERE  d.entity IS NOT NULL AND visible_adv_search = 'y'  ORDER BY entity, field_number";
        $res = $browse->ExecuteQuery($sql);
        return $res;
    }
    

/*{{{ Functions related to data export */
    public static function getEvents()
    {
        $browse = new Browse();
        $res = $browse->ExecuteQuery("SELECT event_record_number FROM event");
        return $res;
    } 

    public static function getActs($event)
    {
        $browse = new Browse();
        $res = $browse->ExecuteQuery("
        SELECT act_record_number 
        FROM act AS a 
        WHERE a.event='$event' ORDER BY act_record_number");
        return $res;
    } 
    public static function getInvolments($act){
        $sql = "SELECT involvement_record_number FROM involvement WHERE act='$act'";
        $browse = new Browse();
        $rows = $browse->ExecuteQuery($sql);
        return $rows;
    }
    public static function getCOEs($event)
    {
        $browse = new Browse();
        $res = $browse->ExecuteQuery("
        SELECT chain_of_events_record_number
        FROM chain_of_events AS c 
        WHERE c.event='$event'");
        return $res;
    }


    public static function getPersons(){
        $sql = "SELECT person_record_number FROM person";
        $browse = new Browse();
        $rows = $browse->ExecuteQuery($sql);
        return $rows;
    }
    
    public static function getPersonConf(){
        $sql = "SELECT person_record_number , confidentiality FROM person";
        $browse = new Browse();
        $rows = $browse->ExecuteQuery($sql);
        return $rows;
    }

    public static function getBiographicDetails($person){
        global $global;
        $person = $global['db']->qstr($person);
        $sql = "SELECT biographic_details_record_number FROM biographic_details WHERE person=$person";
        $browse = new Browse();
        $rows = $browse->ExecuteQuery($sql);
        return $rows;
    }


    public static function getAddress($person){
        $sql = "SELECT address_record_number FROM address WHERE person='$person'";
        $browse = new Browse();
        $rows = $browse->ExecuteQuery($sql);
        return $rows;
    }

    public static function getInterventions(){
        $sql = "SELECT intervention_record_number FROM intervention";
        $browse = new Browse();
        $rows = $browse->ExecuteQuery($sql);
        return $rows;
    }
	public static function getManagement(){
        $sql = "SELECT entity_id FROM management";
        $browse = new Browse();
        $rows = $browse->ExecuteQuery($sql);
        return $rows;
    }
	
	public static function getAdditionalData($data_type,$act_rec_num){
        $sql = "SELECT ".$data_type."_record_number FROM ".$data_type." WHERE ".$data_type."_record_number = '$act_rec_num'" ;
        $browse = new Browse();
        $rows = $browse->ExecuteQuery($sql);
        return $rows;
    }
	    
    public static function getInformation(){
        $sql = "SELECT information_record_number FROM information";
        $browse = new Browse();
        $rows = $browse->ExecuteQuery($sql);
        return $rows;
    }
     
    public static function getEventsDocList($event_record_number, $get_data = null)
    {
    	if($get_data['filter'] != null){
    		$filter_where = '';
    		$filter_where .= ($get_data['doc_id'] != null) ? ' AND sdm.doc_id LIKE ' . "'%{$get_data['doc_id']}%'" : null;
    		//$filter_where .= ($get_data['entity_type'] != null) ? ' AND entity_type LIKE ' . "'%{$get_data['entity_type']}%'" : null;
    		$filter_where .= ($get_data['title'] != null) ? ' AND sdm.title LIKE ' . "'%{$get_data['title']}%'" : null;
    		$filter_where .= ($get_data['type'] != null) ? ' AND sdm.type LIKE ' . "'%{$get_data['type']}%'": null;
    		$filter_where .= ($get_data['format'] != null) ? ' AND sdm.format LIKE ' . "'%{$get_data['format']}%'" : null;
    	}

    	$order_by = ($get_data['sort'] != null) ? "ORDER BY " .$get_data['sort'] .' ' . $get_data['sortorder'] : '';
    	
    	$sql = "(SELECT 'event' AS 'entity_type', e.event_record_number AS 'record_number', sdm.*, sd.uri FROM supporting_docs_meta AS sdm
				INNER JOIN supporting_docs AS sd ON sdm.doc_id = sd.doc_id
				INNER JOIN event_doc AS ed ON sdm.doc_id = ed.doc_id
				INNER JOIN event AS e ON  ed.record_number = e.event_record_number
				WHERE e.event_record_number = '$event_record_number' $filter_where)
				UNION
				(SELECT 'act' AS 'entity_type', a.act_record_number AS 'record_number', sdm.*, sd.uri FROM supporting_docs_meta AS sdm
				INNER JOIN supporting_docs AS sd ON sdm.doc_id = sd.doc_id
				INNER JOIN act_doc AS ad ON sdm.doc_id = ad.doc_id
				INNER JOIN act AS a ON a.act_record_number = ad.record_number
				INNER JOIN event AS e ON  a.event = e.event_record_number
				WHERE e.event_record_number = '$event_record_number' $filter_where)
				UNION
				(SELECT 'information' AS 'entity_type', info.information_record_number AS 'record_number', sdm.*, sd.uri FROM supporting_docs_meta AS sdm
				INNER JOIN supporting_docs AS sd ON sdm.doc_id = sd.doc_id
				INNER JOIN information_doc AS infod ON sdm.doc_id = infod.doc_id
				INNER JOIN information AS info ON info.information_record_number = infod.record_number
				INNER JOIN event AS e ON  info.event = e.event_record_number
				WHERE e.event_record_number = '$event_record_number' $filter_where)
				UNION
				(SELECT 'intervention' AS 'entity_type', intv.intervention_record_number AS 'record_number', sdm.*, sd.uri FROM supporting_docs_meta AS sdm
				INNER JOIN supporting_docs AS sd ON sdm.doc_id = sd.doc_id
				INNER JOIN intervention_doc AS intvd ON sdm.doc_id = intvd.doc_id
				INNER JOIN intervention AS intv ON intv.intervention_record_number = intvd.record_number
				INNER JOIN event AS e ON  intv.event = e.event_record_number
				WHERE e.event_record_number = '$event_record_number' $filter_where)
				UNION
				(SELECT 'involvement' AS 'entity_type', inv.involvement_record_number AS 'record_number', sdm.*, sd.uri FROM supporting_docs_meta AS sdm
				INNER JOIN supporting_docs AS sd ON sdm.doc_id = sd.doc_id
				INNER JOIN involvement_doc AS invd ON sdm.doc_id = invd.doc_id
				INNER JOIN involvement AS inv ON inv.involvement_record_number = invd.record_number
				INNER JOIN event AS e ON  inv.event = e.event_record_number
				WHERE e.event_record_number = '$event_record_number' $filter_where)
				UNION
				(SELECT 'victim' AS 'entity_type', a.victim AS 'record_number', sdm.*, sd.uri FROM supporting_docs_meta AS sdm
				INNER JOIN supporting_docs AS sd ON sdm.doc_id = sd.doc_id
				INNER JOIN person_doc AS pd ON sdm.doc_id = pd.doc_id
				INNER JOIN act AS a ON a.victim = pd.record_number
				INNER JOIN event AS e ON  a.event = e.event_record_number
				WHERE e.event_record_number = '$event_record_number' $filter_where)
				UNION
				(SELECT 'source' AS 'entity_type', info.source AS 'record_number', sdm.*, sd.uri FROM supporting_docs_meta AS sdm
				INNER JOIN supporting_docs AS sd ON sdm.doc_id = sd.doc_id
				INNER JOIN person_doc AS pd ON sdm.doc_id = pd.doc_id
				INNER JOIN information AS info ON info.source = pd.record_number
				INNER JOIN event AS e ON  info.event = e.event_record_number
				WHERE e.event_record_number = '$event_record_number' $filter_where)
				UNION
				(SELECT 'intervening_party' AS 'entity_type', intv.intervening_party AS 'record_number', sdm.*, sd.uri FROM supporting_docs_meta AS sdm
				INNER JOIN supporting_docs AS sd ON sdm.doc_id = sd.doc_id
				INNER JOIN person_doc AS pd ON sdm.doc_id = pd.doc_id
				INNER JOIN intervention AS intv ON intv.intervening_party = pd.record_number
				INNER JOIN event AS e ON  intv.event = e.event_record_number
				WHERE e.event_record_number = '$event_record_number' $filter_where)
				UNION
				(SELECT 'perpetrator' AS 'entity_type', inv.perpetrator AS 'record_number', sdm.*, sd.uri FROM supporting_docs_meta AS sdm
				INNER JOIN supporting_docs AS sd ON sdm.doc_id = sd.doc_id
				INNER JOIN person_doc AS pd ON sdm.doc_id = pd.doc_id
				INNER JOIN involvement AS inv ON inv.perpetrator = pd.record_number
				INNER JOIN event AS e ON  inv.event = e.event_record_number
				WHERE e.event_record_number = '$event_record_number' $filter_where) $order_by";
    	
    	$browse = new Browse();
        $results = $browse->ExecuteQuery($sql);
        
        return $sql;
    }

/*}}}*/
}
