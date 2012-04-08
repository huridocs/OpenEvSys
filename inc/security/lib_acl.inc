<?php
function acl_get_gacl_api()
{
    global $global;
    include_once(APPROOT.'3rd/phpgacl/gacl_api.class.php');
    $gacl = new gacl_api(array('db'=>$global['db'] , 'db_table_prefix'=>'gacl_'));
    return $gacl;
}


function acl_is_mod_allowed($module)
{
    global $global;
    if($global['gacl']->acl_check('access', 'access', 'users' , $_SESSION['username'], 'modules' , $module ))
        return true;
    return false;    
}

function acl_mod_allowed($module){
    global $global;

	if(!$global['gacl']->acl_check('access', 'access', 'users' , $_SESSION['username'], 'modules' , $module )){
		throw new shnAccessDeny();
	}
}

function acl_is_entity_allowed($entity , $crud , $record_number = null ){
	global $global;

//var_dump($entity, $crud );
//var_dump('record_number' , $record_number );

	$entity_allow = $global['gacl']->acl_check('crud', $crud , 'users' , $_SESSION['username'], 'entities' , $entity );
//var_dump('entity_allow' , $entity_allow);

	if( $entity=='event' && $record_number != null ){
//		$event_allow = $global['gacl']->acl_check('crud', $crud , 'users' , $_SESSION['username'], 'events' , $record_number );
//var_dump('event_allow' , $event_allow);
		$event_allow = $global['gacl']->acl_check('access', 'access', 'users' , $_SESSION['username'], 'events' , $record_number );
		if( !$event_allow ){
				throw new shnAccessDeny();
		}

	}
	else if( $entity=='person' && $record_number != null ){
		$event_allow = $global['gacl']->acl_check('access', 'access', 'users' , $_SESSION['username'], 'person' , $record_number );
		if( !$event_allow ){
				throw new shnAccessDeny();
		}

	}
	if(!$entity_allow){
		throw new shnAccessDeny();
	}
}

function acl_is_entity_allowed_boolean($entity , $crud , $record_number = null ){
    try{
        $allowed = acl_is_entity_allowed($entity, $crud , $record_number);
        //var_dump('allowed' , $allowed);
        return true;
    }catch ( shnAccessDeny $e){
        return false;
    }
}

function acl_i3_entity_add_is_allowed($i3){ // $i3 is on of the following - intervention, involvement, information
    $person_new = acl_is_entity_allowed_boolean('person','create');
    $person_search = acl_is_entity_allowed_boolean('person','read');
    $i = acl_is_entity_allowed_boolean($i3,'create');
    //var_dump('person_new', $person_new , 'person_read' , $person_search );
    if( $i == true && ($person_new==true || $person_search==true)){
        return true;
    }else{
        return false;
    }    
}

function acl_vp_entity_add_is_allowed(){ 
    $person_new = acl_is_entity_allowed_boolean('person','create');
    $person_search = acl_is_entity_allowed_boolean('person','read');
    $i = acl_is_entity_allowed_boolean('involvement','create');
    $act = acl_is_entity_allowed_boolean('act','create');
    //var_dump('person_new', $person_new , 'person_read' , $person_search );
    if( $i == true && $act == true && ($person_new==true || $person_search==true)){
        return true;
    }else{
        return false;
    }    
}


function acl_get_roles()
{
    global $global;
    include_once(APPROOT.'3rd/phpgacl/gacl_api.class.php');
    $gacl = new gacl_api(array('db'=>$global['db'] , 'db_table_prefix'=>'gacl_'));
    $user_group_id = $gacl->get_group_id('users');
    $role_ids = $gacl->get_group_children($user_group_id);
    $options =array();
    foreach($role_ids as $role_id){
        $role = $gacl->get_group_data($role_id , 'ARO');
        $options[$role[2]] = $role[3];
    }
    return $options;
}

function acl_change_user_roles($user , $role)
{
    global $global;
    include_once(APPROOT.'3rd/phpgacl/gacl_api.class.php');
    $gacl = new gacl_api(array('db'=>$global['db'] , 'db_table_prefix'=>'gacl_'));
    $user_id = $gacl->get_object_id('users', $user,'ARO'); 
    //remove user from all the groups
    $roles =  $gacl->get_object_groups($user_id);
    foreach($roles as $r){
        $gacl->del_group_object($r,'users', $user);
    }
    //assign new roles
    $role_id = $gacl->get_group_id($role);
    $gacl->add_group_object($role_id, 'users', $user) ;
}

function acl_get_user_roles($user)
{
    global $global;
    include_once(APPROOT.'3rd/phpgacl/gacl_api.class.php');
    $gacl = new gacl_api(array('db'=>$global['db'] , 'db_table_prefix'=>'gacl_'));
    $user_id = $gacl->get_object_id('users', $user,'ARO'); 
    $roles =  $gacl->get_object_groups($user_id);
    $options =array();
    foreach($roles as $role_id){
        $role = $gacl->get_group_data($role_id , 'ARO');
        $options[$role[2]] = $role[3];
    }
    return $options;
}


function acl_get_modules()
{
    global $global;
    include_once(APPROOT.'3rd/phpgacl/gacl_api.class.php');
    $gacl = new gacl_api(array('db'=>$global['db'] , 'db_table_prefix'=>'gacl_'));
    $array =  $gacl->get_objects('modules', 0 , 'AXO'); 
    return $array['modules'];
}

function acl_get_role_modules($role)
{
    global $global;
    include_once(APPROOT.'3rd/phpgacl/gacl_api.class.php');
    $gacl = new gacl_api(array('db'=>$global['db'] , 'db_table_prefix'=>'gacl_'));
    $acl_ids = $gacl->search_acl(FALSE, FALSE, FALSE,FALSE, $role , FALSE,'modules',FALSE , FALSE);
    $select = array();
    foreach($acl_ids as $acl_id){
        $acl = $gacl->get_acl($acl_id);
        $select = array_merge($select,$acl['axo']['modules']);
    }
    return $select;
}



/*{{{  Event Related ACL */
function acl_add_event($event_record_number)
{
    $gacl_api = acl_get_gacl_api();
	$gacl_api->add_object('events', $event_record_number , $event_record_number ,0 , 0 ,'AXO');		

	$g_events = $gacl_api->get_group_id('events' , 'Events' , 'AXO' );
	$gacl_api->add_group_object($g_events , 'events' , $event_record_number , 'AXO' );

	$g_root_user = $gacl_api->get_group_id('root' , 'OpenEvSysUser' , 'ARO' );
}


function acl_set_event_permissions($event_record_number)
{
    global $conf;
    $gacl_api = acl_get_gacl_api();
    //remove this event from users
    $root_aro =  $gacl_api->get_group_id('root', 'OpenEvSysUser', 'ARO');
    $return = $gacl_api->add_acl( array( 'access'=>array('access')), null , array($root_aro), array( 'events'=>array($event_record_number)), null , 0 );
    //allow it for admin group
    $admin_aro =  $gacl_api->get_group_id('admin', 'Admin', 'ARO');
    $return = $gacl_api->add_acl( array( 'access'=>array('access')), null , array($admin_aro), array( 'events'=>array($event_record_number)), null , 1 );
    if($conf['acl_mode']=='role'){
        //allow it for created user group
        $user_id =  $gacl_api->get_object_id('users', $_SESSION['username'],'ARO'); 
        //remove user from all the groups
        $roles   =  $gacl_api->get_object_groups($user_id);
        foreach($roles as $r){
            if($r==$admin_aro)continue;
            $return = $gacl_api->add_acl( array( 'access'=>array('access')), null , array($r), array( 'events'=>array($event_record_number)), null , 1 );
        }
    }
    else{
        //only allow for that perticuler user
        $return = $gacl_api->add_acl( array( 'access'=>array('access')), array('users'=>array($_SESSION['username'])) , null, array( 'events'=>array($event_record_number)), null , 1 );
    }
}

function acl_set_event_permissions_for_user($event_record_number,$username){
	global $conf;
    $gacl_api = acl_get_gacl_api();
	$return = $gacl_api->add_acl( array( 'access'=>array('access')), array('users'=>array($username)) , null, array( 'events'=>array($event_record_number)), null , 1 );
}

function acl_unset_event_permissions($event_record_number)
{
    $gacl_api = acl_get_gacl_api();
    $acl_id = $gacl_api->search_acl('access','access', FALSE, FALSE, FALSE ,'events', $event_record_number, FALSE, FALSE);
    foreach($acl_id as $id)
        $gacl_api->del_acl($id);
}

function acl_delete_event($event_record_number)
{
    $gacl_api = acl_get_gacl_api();
    $id = $gacl_api->get_object_id('events', $event_record_number , 'AXO'); 
    $gacl_api->del_object( $id, 'AXO', TRUE ); 
}
/*}}}*/


function acl_add_user($username)
{
    $gacl_api = acl_get_gacl_api();
    $gacl_api->add_object('users', $username , $username , 5 , 0 , 'ARO');
}

function acl_delete_user($username)
{
    $gacl_api = acl_get_gacl_api();
    $id = $gacl_api->get_object_id('users', $username , 'ARO'); 
    $gacl_api->del_object( $id, 'ARO', TRUE ); 

}


function acl_get_allowed_users($event_record_number, $type = 'events'){
    global $global;
    $gacl_api = acl_get_gacl_api();
    $users = array();
    $event_record_number = $global['db']->qstr($event_record_number); 
    $type = $global['db']->qstr($type); 
    $sql = "SELECT DISTINCT a.id FROM gacl_acl a LEFT JOIN gacl_aco_map ac ON ac.acl_id=a.id LEFT JOIN gacl_aro_map ar ON ar.acl_id=a.id LEFT JOIN gacl_axo_map ax ON ax.acl_id=a.id LEFT JOIN gacl_aco c ON (c.section_value=ac.section_value AND c.value=ac.value) LEFT JOIN gacl_axo x ON (x.section_value=ax.section_value AND x.value=ax.value) WHERE ac.section_value='access' AND (lower(c.value) LIKE 'access' OR lower(c.name) LIKE 'access') AND ar.section_value='users' AND ax.section_value=$type AND (lower(x.value) LIKE $event_record_number OR lower(x.name) LIKE $event_record_number) ORDER BY a.id ASC";
    $acl_list = $global['db']->GetCol($sql);
    foreach($acl_list as $acl_id){
        $acl = $gacl_api->get_acl($acl_id);
        if($acl){
            $users =array_merge($users, $acl['aro']['users']);
        }
    }
    return $users;
}

/*{{{  Person Related ACL */
function acl_add_person($person_record_number)
{
    $gacl_api = acl_get_gacl_api();
	$gacl_api->add_object('person', $person_record_number , $person_record_number ,0 , 0 ,'AXO');		

	$g_person = $gacl_api->get_group_id('person' , 'Person' , 'AXO' );
	$gacl_api->add_group_object($g_person , 'person' , $person_record_number , 'AXO' );

	$g_root_user = $gacl_api->get_group_id('root' , 'OpenEvSysUser' , 'ARO' );
}


function acl_set_person_permissions($person_record_number)
{
    global $conf;
    $gacl_api = acl_get_gacl_api();
    //remove this person from users
    $root_aro =  $gacl_api->get_group_id('root', 'OpenEvSysUser', 'ARO');
    $return = $gacl_api->add_acl( array( 'access'=>array('access')), null , array($root_aro), array( 'person'=>array($person_record_number)), null , 0 );
    //allow it for admin group
    $admin_aro =  $gacl_api->get_group_id('admin', 'Admin', 'ARO');
    $return = $gacl_api->add_acl( array( 'access'=>array('access')), null , array($admin_aro), array( 'person'=>array($person_record_number)), null , 1 );
    if($conf['acl_mode']=='role'){
        //allow it for created user group
        $user_id =  $gacl_api->get_object_id('users', $_SESSION['username'],'ARO'); 
        //remove user from all the groups
        $roles   =  $gacl_api->get_object_groups($user_id);
        foreach($roles as $r){
            if($r==$admin_aro)continue;
            $return = $gacl_api->add_acl( array( 'access'=>array('access')), null , array($r), array( 'person'=>array($person_record_number)), null , 1 );
        }
    }
    else{
        //only allow for that perticuler user
        $return = $gacl_api->add_acl( array( 'access'=>array('access')), array('users'=>array($_SESSION['username'])) , null, array( 'person'=>array($person_record_number)), null , 1 );
    }
}

function acl_set_person_permissions_for_user($person_record_number,$username){
	global $conf;
    $gacl_api = acl_get_gacl_api();
	$return = $gacl_api->add_acl( array( 'access'=>array('access')), array('users'=>array($username)) , null, array( 'person'=>array($person_record_number)), null , 1 );
}


function acl_unset_person_permissions($person_record_number)
{
    $gacl_api = acl_get_gacl_api();
    $acl_id = $gacl_api->search_acl('access','access', FALSE, FALSE, FALSE ,'person', $person_record_number, FALSE, FALSE);
    foreach($acl_id as $id)
        $gacl_api->del_acl($id);
}

function acl_delete_person($person_record_number)
{
    $gacl_api = acl_get_gacl_api();
    $id = $gacl_api->get_object_id('person', $person_record_number , 'AXO'); 
    $gacl_api->del_object( $id, 'AXO', TRUE ); 
}
/*}}}*/


//list SQL

function acl_list_events_permissons(){
    $dataArray = acl_list_allowed_data('access', 'access', 'users' , $_SESSION['username'], 'events' );
    
    return acl_process_entity_access_array($dataArray);

}

function acl_list_person_permissons(){
    $dataArray = acl_list_allowed_data('access', 'access', 'users' , $_SESSION['username'], 'person' );
    
    return acl_process_entity_access_array($dataArray);

}

function acl_process_entity_access_array($dataArray){
    $entityIds = array();
    if( is_array( $dataArray))
    foreach($dataArray as $entry){
        if( !isset($entityIds[ $entry['value'] ] )){
            $entityIds[ $entry['value'] ] =  $entry['allow'];
        }
    }
    //var_dump($entityIds);
    $notAllowedEntitiesArray = array();
    foreach($entityIds as $id=> $entityId){
        if($entityId=='0')
        $notAllowedEntitiesArray[] = $id; 
    }
    //var_dump($notAllowedEntitiesArray);
    return $notAllowedEntitiesArray;
}

function acl_list_allowed_data($aco_section_value, $aco_value, $aro_section_value, $aro_value, $axo_section_value=NULL, $axo_value=NULL, $root_aro_group=NULL, $root_axo_group=NULL, $debug=NULL) {
	$gacl_api = acl_get_gacl_api();
    
	
	$cache_id = 'acl_query_'.$aco_section_value.'-'.$aco_value.'-'.$aro_section_value.'-'.$aro_value.'-'.$axo_section_value.'-'.$axo_value.'-'.$root_aro_group.'-'.$root_axo_group.'-'.$debug;

	$retarr = $gacl_api->get_cache($cache_id);

	if (!$retarr) {
		/*
		 * Grab all groups mapped to this ARO/AXO
		 */
		$aro_group_ids = $gacl_api->acl_get_groups($aro_section_value, $aro_value, $root_aro_group, 'ARO');
		
		if (is_array($aro_group_ids) AND !empty($aro_group_ids)) {
			$sql_aro_group_ids = implode(',', $aro_group_ids);
		}

		if ($axo_section_value != '' AND $axo_value != '') {
			$axo_group_ids = $gacl_api->acl_get_groups($axo_section_value, $axo_value, $root_axo_group, 'AXO');

			if (is_array($axo_group_ids) AND !empty($axo_group_ids)) {
				$sql_axo_group_ids = implode(',', $axo_group_ids);
			}
		}

		/*
		 * This query is where all the magic happens.
		 * The ordering is very important here, as well very tricky to get correct.
		 * Currently there can be  duplicate ACLs, or ones that step on each other toes. In this case, the ACL that was last updated/created
		 * is used.
		 *
		 * This is probably where the most optimizations can be made.
		 */

		$order_by = array();

		$query = '
				SELECT		a.allow, ax.value
				FROM		'. $gacl_api->_db_table_prefix .'acl a
				LEFT JOIN 	'. $gacl_api->_db_table_prefix .'aco_map ac ON ac.acl_id=a.id';

		if ($aro_section_value != $gacl_api->_group_switch) {
			$query .= '
				LEFT JOIN	'. $gacl_api->_db_table_prefix .'aro_map ar ON ar.acl_id=a.id';
		}

		if ($axo_section_value != $gacl_api->_group_switch) {
			$query .= '
				LEFT JOIN	'. $gacl_api->_db_table_prefix .'axo_map ax ON ax.acl_id=a.id';
		}

		/*
		 * if there are no aro groups, don't bother doing the join.
		 */
		if (isset($sql_aro_group_ids)) {
			$query .= '
				LEFT JOIN	'. $gacl_api->_db_table_prefix .'aro_groups_map arg ON arg.acl_id=a.id
				LEFT JOIN	'. $gacl_api->_db_table_prefix .'aro_groups rg ON rg.id=arg.group_id';
		}

		// this join is necessary to weed out rules associated with axo groups
		$query .= '
				LEFT JOIN	'. $gacl_api->_db_table_prefix .'axo_groups_map axg ON axg.acl_id=a.id';

		/*
		 * if there are no axo groups, don't bother doing the join.
		 * it is only used to rank by the level of the group.
		 */
		if (isset($sql_axo_group_ids)) {
			$query .= '
				LEFT JOIN	'. $gacl_api->_db_table_prefix .'axo_groups xg ON xg.id=axg.group_id';
		}

		//Move the below line to the LEFT JOIN above for PostgreSQL's sake.
		//AND	ac.acl_id=a.id
		$query .= '
				WHERE		a.enabled=1
					AND		(ac.section_value='. $gacl_api->db->quote($aco_section_value) .' AND ac.value='. $gacl_api->db->quote($aco_value) .')';

		// if we are querying an aro group
		if ($aro_section_value == $gacl_api->_group_switch) {
			// if acl_get_groups did not return an array
			if ( !isset ($sql_aro_group_ids) ) {
				$gacl_api->debug_text ('acl_query(): Invalid ARO Group: '. $aro_value);
				return FALSE;
			}

			$query .= '
					AND		rg.id IN ('. $sql_aro_group_ids .') ';

			$order_by[] = '(rg.rgt-rg.lft) ASC';
		} else {
			$query .= '
					AND		((ar.section_value='. $gacl_api->db->quote($aro_section_value) .' AND ar.value='. $gacl_api->db->quote($aro_value) .')';

			if ( isset ($sql_aro_group_ids) ) {
				$query .= ' OR rg.id IN ('. $sql_aro_group_ids .') ' ;

				$order_by[] = '(CASE WHEN ar.value IS NULL THEN 0 ELSE 1 END) DESC';
				$order_by[] = '(rg.rgt-rg.lft) ASC';
			}

			$query .= ')';
		}


		// if we are querying an axo group
		if ($axo_section_value == $gacl_api->_group_switch) {
			// if acl_get_groups did not return an array
			if ( !isset ($sql_axo_group_ids) ) {
				$gacl_api->debug_text ('acl_query(): Invalid AXO Group: '. $axo_value);
				return FALSE;
			}

			$query .= '
					AND		xg.id IN ('. $sql_axo_group_ids .')';

			$order_by[] = '(xg.rgt-xg.lft) ASC';
		} else {
			$query .= '
					AND		(';

			if ($axo_section_value == '' AND $axo_value == '') {
				$query .= '(ax.section_value IS NULL AND ax.value IS NULL)';
			} else {
				$query .= '(ax.section_value='. $gacl_api->db->quote($axo_section_value) .' )';
			}

			if (isset($sql_axo_group_ids)) {
				$query .= ' OR xg.id IN ('. $sql_axo_group_ids .')';

				$order_by[] = '(CASE WHEN ax.value IS NULL THEN 0 ELSE 1 END) DESC';
				$order_by[] = '(xg.rgt-xg.lft) ASC';
			} else {
				$query .= ' AND axg.group_id IS NULL';
			}

			$query .= ')';
			
			//$query .= ' AND rg.value=' ."'$aro_value' ";
		}

		/*
		 * The ordering is always very tricky and makes all the difference in the world.
		 * Order (ar.value IS NOT NULL) DESC should put ACLs given to specific AROs
		 * ahead of any ACLs given to groups. This works well for exceptions to groups.
		 */

		$order_by[] = 'a.updated_date DESC';

		$query .= '
				ORDER BY	'. implode (',', $order_by) . '
				';
		
        $tempTable = "DROP TABLE  IF EXISTS allowed_records;";
        $tempTable2 = "CREATE TEMPORARY TABLE IF NOT EXISTS allowed_records( order_id INT NOT NULL AUTO_INCREMENT , id VARCHAR(45) , allowed int, PRIMARY KEY (order_id) );"; 
        $tempTable3 = "INSERT INTO allowed_records ( allowed , id )  $query ; ";
//var_dump($tempTable2);
		$browse = new Browse();
		$browse->ExecuteNonQuery($tempTable);
		$browse->ExecuteNonQuery($tempTable2);
		$browse->ExecuteNonQuery($tempTable3);
//	    $browse = new Browse();		
		//$data = $browse->ExecuteQuery($query);
        //var_dump($data);
		//return $data;


	}
}
