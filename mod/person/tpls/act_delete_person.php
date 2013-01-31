<?php
	include_once('person_name.php');
	include_once 'view_card_list.php';
	
	draw_card_list('pd',$pid);	
?>
<div class="panel">
<?php
	if($victim_records->RecordCount() || $perpetrator_records->RecordCount() || $source_records->RecordCount() || $intervening_party_records->RecordCount()){ 
?>
	<div class="alert alert-error">
     
    <h3><?php echo _t('DO_YOU_WANT_TO_DELETE_THIS_PERSON_')?></h3>
    <form class="form-horizontal"  action="<?php get_url('person','delete_person')?>" method="post">
        <br />
        <center>
             <button type='submit' class='btn btn-danger' name='yes' ><i class="icon-trash icon-white"></i> <?php echo _t('DELETE') ?></button>
        <button type='submit' class='btn' name='no' ><i class="icon-stop"></i> <?php echo _t('CANCEL') ?></button>
      </center>        
    </form>
    </div>
	<table class='table table-bordered table-striped table-hover'>
        <thead>		                     
            <tr>                
                <th><?php echo _t('ROLE')?></th>
                <th width="120px"><?php echo _t('EVENT_TITLE')?></th>
                <th><?php echo _t('FURTHER_INFORMATION')?></th>                
                <th width='80px'><?php echo _t('INITIAL_DATE')?></th>
                <th width="100px"><?php echo _t('FINAL_DATE')?></th>                          
            </tr>           
        </thead>
		<tbody> 
<?php
	include_once('person_role_list.php');	
	draw_person_role_list($victim_records,$person_victim,$further_victim_msg,$person_victim_type);
	echo "<br />";
	draw_person_role_list($perpetrator_records,$person_perpetrator,$further_perpetrator_msg,$person_perpetrator_type);
	echo "<br />";
	draw_person_role_list($source_records,$person_source,$further_source_msg,$person_source_type);
	echo "<br />";
	draw_person_role_list($intervening_party_records,$person_intervening_party,$further_intervening_party_msg,$person_intervening_party_type);
	
?>
	</tbody> 
	</table>
<?php 
	}
	else{
?>
	<div class="alert alert-error">
     
    <h3><?php echo _t('DO_YOU_WANT_TO_DELETE_THIS_PERSON_')?></h3>
    <form class="form-horizontal"  action="<?php get_url('person','delete_person')?>" method="post">
        <br />
        <center>
             <button type='submit' class='btn btn-danger' name='yes' ><i class="icon-trash icon-white"></i> <?php echo _t('DELETE') ?></button>
        <button type='submit' class='btn' name='no' ><i class="icon-stop"></i> <?php echo _t('CANCEL') ?></button>
       
        </center>        
    </form>
    </div>
<?php	
	}
?>
</div>
