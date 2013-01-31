<?php if( acl_i3_entity_add_is_allowed('intervention') ){ ?>
<a class="btn btn-primary" href="<?php echo get_url('events','add_intv_party',null,array('eid'=>$event_id)) ?>"><i class="icon-plus icon-white"></i><?php echo _t('ADD_INTERVENTION')?></a>
<?php } ?>
<br />
<br />
<?php if(!isset($intv_list)){ ?>
    <div class='alert alert-info'> <button type="button" class="close" data-dismiss="alert">×</button> 
    <?php echo _t('THERE_IS_NO_INFORMATION_ABOUT_INTERVENING_PARTY_AND_INTERVENTION_YET__YOU_SHOULD_ADD_SOME_') ?>
    </div>
<?php }else{ ?>
<form class="form-horizontal"  action="<?php get_url('events','delete_intervention')?>" method="post">
<table class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
			<th width='16px'><input type='checkbox' onchange='$("input.delete").attr("checked",this.checked)' /></th>
            <th><?php echo _t('DATE_OF_INTERVENTION') ?></th>
            <th><?php echo _t('INTERVENING_PARTY') ?></th>
            <th><?php echo _t('TYPE_OF_INTERVENTION') ?></th>

        </tr>
    </thead>
    <tbody>		
    <?php foreach($intv_list as $record){ ?>
        <tr class='<?php if($i++%2==1)echo "odd ";if($intervention->intervention_record_number == $record['intervention_record_number'])echo 'active'; ?>' >
			<td><input name="interventions[]" type='checkbox' value='<?php echo $record['intervention_record_number'] ?>' class='delete'/></td>
            <td><?php echo $record['date_of_intervention'] ?></td>
            <td><a href="<?php get_url('events','intv_list',null,array('type'=>'intv_party','intervention_id'=>$record['intervention_record_number'])) ?>"><?php echo $record['person_name']?></a></td>
            <td>
            <a href="<?php get_url('events','intv_list',null,array('type'=>'intv','intervention_id'=>$record['intervention_record_number'])) ?>">
            
			<?php
				echo "<ul>"; 
		        $types = Browse::getIntvTypes($record['intervention_record_number']);
		        foreach($types as $type){
		            echo "<li>".get_mt_term($type['type_of_intervention'])."</li>";
		        }
		     	echo "</ul>";
			?>
			   	
		   	</a>
        	</td>
            
        </tr>
    <?php } ?>
		<tr class='actions'>
            <td colspan='8'><button type='submit' class='btn btn-danger' name='delete' >
<i class="icon-trash icon-white"></i> <?php echo _t('DELETE') ?></button>
            </td>
        </tr>        
    </tbody>
</table>
</form>
<?php } ?>