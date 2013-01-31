<?php if( acl_i3_entity_add_is_allowed('information') ){ ?> 
<a class="btn btn-primary" href="<?php echo get_url('events','add_source',null,array('eid'=>$event_id)) ?>"><i class="icon-plus icon-white"></i><?php echo _t('ADD_SOURCE')?></a>
<?php } ?>
<br />
<br />
<?php
	if(is_array($sources) && count($sources) !=0 ){
?>
<form class="form-horizontal"  action="<?php get_url('events','delete_information')?>" method="post">
<table class='table table-bordered table-striped table-hover'>
    <thead>
        <tr>
			<th width='16px'><input type='checkbox' onchange='$("input.delete").attr("checked",this.checked)' /></th>
            <th class="title"><?php echo _t('DATE_OF_SOURCE_MATERIAL')?></th>
            <th class="title"><?php echo _t('SOURCE_NAME')?></th>
            <th class="title"><?php echo _t('INFORMATION')?></th>
        </tr>
    </thead>
    <tbody>		
<?php 			
       		foreach($sources as $record){ ?>
        <tr class='<?php if($i++%2==1)echo "odd ";if($information->information_record_number==$record['information_record_number'])echo 'active'; ?>' >
			<td><input name="informations[]" type='checkbox' value='<?php echo $record['information_record_number'] ?>' class='delete'/></td>
            <td><?php echo $record['date_of_source_material']; ?></td>
            <td><a href="<?php echo get_url('events','src_list',null,array('type'=>'person','information_id'=>$record['information_record_number'])) ?>"><?php echo $record['person_name']; ?></a></td>
            <td><a href="<?php echo get_url('events','src_list',null,array('type'=>'information','information_id'=>$record['information_record_number'])) ?>"><?php echo $record['connection']; ?></a> </td>            
        </tr>
		
<?php 	
			}		
?>   
		<tr class='actions'>
            <td colspan='8'><button type='submit' class='btn btn-danger' name='delete' >
<i class="icon-trash icon-white"></i> <?php echo _t('DELETE') ?></button></td>
        </tr>        
	</tbody>
</table>
</form>
<?php
	}
	else{
		echo '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">×</button>';
    	echo _t('THERE_IS_NO_INFORMATION_ABOUT_SOURCE_AND_INFORMATION_YET__YOU_SHOULD_ADD_SOME_');
    	echo "</div>";
	}
?>
