<?php if( acl_i3_entity_add_is_allowed('information') ){ ?> 
<a class="but" href="<?php echo get_url('events','add_source',null,array('eid'=>$event_id)) ?>"><?php echo _t('ADD_SOURCE')?></a>
<?php } ?>
<br />
<br />
<?php
	if(is_array($sources) && count($sources) !=0 ){
?>
<form action="<?php get_url('events','delete_information')?>" method="post">
<table class='view'>
    <thead>
        <tr>
			<td width='16px'><input type='checkbox' onchange='$("input.delete").attr("checked",this.checked)' /></td>
            <td class="title"><?php echo _t('DATE_OF_SOURCE_MATERIAL')?></td>
            <td class="title"><?php echo _t('SOURCE_NAME')?></td>
            <td class="title"><?php echo _t('INFORMATION')?></td>
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
            <td colspan='8'><input type='submit' name='delete' value='<?php echo _t('DELETE') ?>' /></td>
        </tr>        
	</tbody>
</table>
</form>
<?php
	}
	else{
		echo "<div class='notice'>";
    	echo _t('THERE_IS_NO_INFORMATION_ABOUT_SOURCE_AND_INFORMATION_YET__YOU_SHOULD_ADD_SOME_');
    	echo "</div>";
	}
?>
