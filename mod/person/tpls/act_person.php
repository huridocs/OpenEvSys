<?php
		include_once('person_name.php');
		include_once 'view_card_list.php';
		draw_card_list('pd',$pid);
?>
<div class="panel">
        <?php if( acl_is_entity_allowed_boolean('person' , 'update')){?>
			<a class="btn btn-info" href="<?php echo get_url('person','edit_person',null) ?>"><i class="icon-edit icon-white"></i> <?php echo _t('EDIT_THIS_PERSON')?></a>
	    <?php } ?>
	    <?php if( acl_is_entity_allowed_boolean('person' , 'delete')){?>
		<a class="btn btn-danger" href="<?php echo get_url('person','delete_person',null) ?>"><i class="icon-trash icon-white"></i>  <?php echo _t('DELETE_THIS_PERSON')?></a>
		<?php } ?>
		<a class="btn" href="<?php echo get_url('person','print',null) ?>"><i class="icon-print"></i>  <?php echo _t('PRINT_THIS_PERSON')?></a>
<br />
<br />
<?php
    $person_form = person_form('view');
    unset($person_form['person_addresses']);
    popuate_formArray($person_form , $person );
    shn_form_get_html_labels($person_form , false );
    
    if(count($biographics) != 0){    	
?>
	<br />
	<br />	
	
	<table class="table table-bordered table-striped table-hover">	
	<thead>
		<tr>
			<th><?php echo _t('CONTEXTUAL_INFORMATION')?></th>			
		</tr>
	</thead>
	<tbody>		
<?php 			
       		foreach($biographics as $bio){      			
?>
			<tr class='<?php if($i++%2==1)echo "odd ";if($_GET['row']==$i)echo 'active'; ?>' >			
			<td><a href="<?php echo get_url('person','person',null,array('pid'=>$bio['person'])); ?>"><?php echo "<strong>". $bio['person_name']. "</strong>"; ?></a><?php echo " is a/an <strong>" . $bio['relationship_type']. "</strong> of this person"; ?></td>          
     		</tr>
<?php
			}	
?>   
		
	</tbody>
	</table>
	<br />
<?php 
	}	
?>
</div>
