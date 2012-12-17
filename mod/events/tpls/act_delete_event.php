<?php 
include_once('event_title.php');
	if(count($events) != null || count($acts) != null || count($involvements) != null || count($informations) != null || count($interventions) != null){
	?>
	<div class="panel">
	<?php
	if($del_confirm){ ?>
    <div class="alert alert-block" style="text-align:center">
<h4><?php echo _t('YOU_ARE_ABOUT_TO_DELETE_AN_EVENT__THIS_MEANS_YOU_WILL_DELETE_THE_RECORDS_LISTED_BELOW__DO_YOU_WANT_TO_CONTINUE_')?></h4>
    <form class="form-horizontal"  action="<?php get_url('events','delete_event')?>" method="post" >
        <br/><center>
        <input type='submit' class='btn' name='yes' value='<? echo _t('FORCE_DELETE') ?>' />
        <input type='submit' class='btn' name='no' value='<? echo _t('CANCEL') ?>' />
        </center>        
    </form>
    </div>
<?php } ?>
    <div class='view'>
    <?php
    if(count($events) != null){
    	echo "<h4>" . _t('CHAIN_OF_EVENT_S_') . "</h4>"; 
    	$i = 0;
    ?>
    <table class='table table-bordered table-striped table-hover'>
        <thead>
            <tr>
                <th><?php echo _t('REC_NO')?></th>
                <th><?php echo _t('EVENT_TITLE')?></th>
                <th width='80px'><?php echo _t('INITIAL_DATE')?></th>
                <th width='80px'><?php echo _t('FINAL_DATE')?></th>         
            </tr>
        </thead>
        <tbody>
    <?php $i=0;
           foreach($events as $event){ ?>
            <tr <?php echo ($i++%2==1)?'class="odd"':''; ?>>
                <td><?php echo $event['related_event']?></td>
                <td><?php echo $event['event_title'] ?></td>
                <td><?php echo $event['initial_date'] ?> </td>
                <td><?php echo $event['final_date'] ?></td>
            </tr>
    <?php } ?>
        </tbody>
    </table>
    <br />
    <br />
    <?php } ?>
    
    <?php
    if(count($acts) != null){
    	echo "<h4>" . _t('ACT_S_') . "</h4>";
    	$i = 0;
    ?>
    <table class="table table-bordered table-striped table-hover">
    <thead>
        <tr>			
            <th><?php echo _t('REC_NO') ?></th>
            <th><?php echo _t('TYPE_OF_ACT') ?></th>            
            <th><?php echo _t('INITIAL_DATE') ?></th>            			
            <th><?php echo _t('FINAL_DATE') ?></th>            
        </tr>
    </thead>
    <tbody>	
    <?php foreach($acts as $record){            
    ?>
        <tr <?php echo ($i++%2==1)?'class="odd"':''; ?>>
            <td><?php echo $record['act_record_number']?></td>
            <td><?php echo get_mt_term($record['type_of_act'])?></td>            
            <td><?php echo $record['initial_date'] ?></td>
            <td><?php echo $record['final_date'] ?></td>			
        </tr>
    <?php } ?>		
    </tbody>
	</table>
	<br />
    <br />
    <?php
    } 
    ?>
    
    <?php
    if(count($involvements) != null){
    	echo "<h4>" . _t('INVOLVEMENT_S_') . "</h4>";
    	$i = 0;
    ?>
    <table class='table table-bordered table-striped table-hover'>
    <thead>
        <tr>			
            <th class="title"><?php echo _t('REC_NO')?></th>
            <th class="title"><?php echo _t('DEGREE_OF_INVOLVEMENT')?></th>
            <th class="title"><?php echo _t('STATUS')?></th>
            <th class="title"><?php echo _t('REMARKS')?></th>
        </tr>
    </thead>
    <tbody>		
<?php 			
       		foreach($involvements as $record){ ?>
        	<tr <?php echo ($i++%2==1)?'class="odd"':''; ?>>			
            <td><?php echo $record['involvement_record_number']; ?></td>
            <td><?php echo get_mt_term($record['degree_of_involvement'])?></td>
            <td><?php echo get_mt_term($record['latest_status_as_perpetrator_in_the_act'])?></td>
            <td><?php echo get_mt_term($record['remarks'])?></td>            
        	</tr>		
<?php 	
			}		
?>   		
	</tbody>
	</table>
	<br />
    <br /> 
    <?php
    } 
    ?>
    
    <?php
    if(count($informations) != null){ 
    	echo "<h4>" . _t('INFORMATION_S_') . "</h4>";
    	$i = 0;
    ?>
    <table class='table table-bordered table-striped table-hover'>
    <thead>
        <tr>			
            <th><?php echo _t('REC_NO') ?></th>
            <th><?php echo _t('INFORMATION') ?></th>
            <th><?php echo _t('DATE_OF_SOURCE_MATERIAL') ?></th>
            <th><?php echo _t('REMARKS') ?></th>
        </tr>
    </thead>
    <tbody>		
    <?php foreach($informations as $record){ ?>
        <tr <?php echo ($i++%2==1)?'class="odd"':''; ?>>			
            <td><?php echo $record['information_record_number']; ?></td>
            <td><?php echo get_mt_term($record['source_connection_to_information'])?></td>
            <td><?php echo $record['date_of_source_material']; ?></td>
            <td><?php echo $record['remarks']; ?></td>        
        </tr>
    <?php } ?>		
    </tbody>
	</table>
	<br />
    <br />
    <?php
    } 
    ?>
    
    <?php
    if(count($interventions) != null){
    	echo "<h4>" . _t('INTERVENTION_S_') . "</h4>";
    	$i = 0;
    ?>
    <table class='table table-bordered table-striped table-hover'>
    <thead>
        <tr>
            <th><?php echo _t('REC_NO') ?></th>
            <th><?php echo _t('IMPACT') ?></th>
            <th><?php echo _t('DATE_OF_INTERVENTION') ?></th>
            <th><?php echo _t('REMARKS') ?></th>
        </tr>
    </thead>
    <tbody>		
    <?php foreach($interventions as $record){ ?>
        <tr <?php echo ($i++%2==1)?'class="odd"':''; ?>>			
            <td><?php echo $record['intervention_record_number']; ?></td>
            <td><?php echo get_mt_term($record['impact_on_the_situation'])?></td>
            <td><?php echo $record['date_of_intervention']; ?></td>
            <td><?php echo $record['remarks']; ?></td>        
        </tr>
    <?php } ?>		
    </tbody>
	</table>
    <?php
    } 
    ?>
    
    </div>
<br />
<?php
	}
	else{
?>
	<div class='dialog confirm'>
    <h3><?php echo _t('DO_YOU_WANT_TO_DELETE_THIS_EVENT_')?></h3>
    <form class="form-horizontal"  action="<?php get_url('events','delete_event')?>" method="post">
        <br />
        <center>
        <input type='submit' class='btn' name='yes' value='<? echo _t('DELETE') ?>' />
        <input type='submit' class='btn' name='no' value='<? echo _t('CANCEL') ?>' />
        </center>        
    </form>
    </div>
    </div>
<?php
	}
?>
