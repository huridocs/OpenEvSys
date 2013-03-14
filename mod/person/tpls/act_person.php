<?php
		include_once('person_name.php');
		include_once 'view_card_list.php';
		draw_card_list('pd',$pid);
?>
<div class="panel">
        <?php if( acl_is_entity_allowed_boolean('person' , 'update')){?>
			<a class="but" href="<?php echo get_url('person','edit_person',null) ?>"><img src="<?php echo data_uri(APPROOT.'www/res/img/gtk-edit.png','image/png') ?>"> <?php echo _t('EDIT_THIS_PERSON')?></a>
	    <?php } ?>
	    <?php if( acl_is_entity_allowed_boolean('person' , 'delete')){?>
		<a class="but" href="<?php echo get_url('person','delete_person',null) ?>"><img src="<?php echo data_uri(APPROOT.'www/res/img/edit-delete.png','image/png') ?>"> <?php echo _t('DELETE_THIS_PERSON')?></a>
		<?php } ?>
		<a class="but" href="<?php echo get_url('person','print',null) ?>"><img src="<?php echo data_uri(APPROOT.'www/res/img/document-print.png','image/png') ?>"> <?php echo _t('PRINT_THIS_PERSON')?></a>
<br />
<br />
<?php
    $person_form = person_form('view');
    unset($person_form['person_addresses']);
    popuate_formArray($person_form , $person );
    shn_form_get_html_labels($person_form , false );
    
    if(count($biographics) != 0 || count($biographics_reverse) != 0){    	
?>
	<br />
	<br />	
	
	<table class="browse">	
	<thead>
		<tr>
			<td><?php echo _t('CONTEXTUAL_INFORMATION')?></td>			
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
                        foreach($biographics_reverse as $bio){      			
?>
			<tr class='<?php if($i++%2==1)echo "odd ";if($_GET['row']==$i)echo 'active'; ?>' >			
			<td><a href="<?php echo get_url('person','person',null,array('pid'=>$bio['related_person'])); ?>"><?php echo "<strong>". $bio['person_name']. "</strong>"; ?></a><?php echo " is a/an <strong>" . get_mt_term(get_biography_reverse($bio['relationship_type'])). "</strong> of this person"; ?></td>          
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
