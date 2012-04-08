<?php include_once('tabs.php')?>
<?php include_once('person_name.php')?>
<?php
    include_once('view_card_list.php');
    draw_card_list('rl',$pid);	
?>
<div class="panel">
<?php
	if($victim_records->RecordCount() || $perpetrator_records->RecordCount() || $source_records->RecordCount() || $intervening_party_records->RecordCount()){
?>
	<table class='browse'>
        <thead>		                     
            <tr>                
                <td><?php echo _t('ROLE')?></td>
                <td width="120px"><?php echo _t('EVENT_TITLE')?></td>
                <td><?php echo _t('FURTHER_INFORMATION')?></td>                
                <td width='80px'><?php echo _t('INITIAL_DATE')?></td>
                <td width="100px"><?php echo _t('FINAL_DATE')?></td>                          
            </tr>           
        </thead>
		<tbody> 
<?php
	include_once('person_role_list.php');		
	draw_person_role_list($victim_records,$person_victim,$further_victim_msg,$person_victim_type);	
	draw_person_role_list($perpetrator_records,$person_perpetrator,$further_perpetrator_msg,$person_perpetrator_type);	
	draw_person_role_list($source_records,$person_source,$further_source_msg,$person_source_type);	
	draw_person_role_list($intervening_party_records,$person_intervening_party,$further_intervening_party_msg,$person_intervening_party_type);
	
?>
	</tbody> 
	</table>
<?php
	}
	else{
?>
		<div class='notice'>
    	<?php echo _t('THE_PERSON_DOES_NOT_YET_HAVE_ANY_ROLES_'); ?>
		<br />		
		<br />
    	</div>
<?php
	}
?>
</div>
