<?php include_once('tabs.php')?>
<?php include_once('person_name.php')?>
<?php
    include_once('view_card_list.php');
    draw_card_list('pa',$pid);	
?>
<div class="panel">
<a class="btn" href="<?php echo get_url('person','new_address',null, null) ?>"><?php echo _t('ADD_ADDRESS')?></a>
<br />
<br />    
<?php	
	if((is_array($addresses) && count($addresses) != 0)){
?>
		<form class="form-horizontal"  action="<?php get_url('person','delete_address')?>" method="post">
		<table class="table table-bordered table-striped table-hover">
		<thead>
			<tr>
				<th width='16px'><input type='checkbox' onchange='$("input.delete").attr("checked",this.checked)' /></th>
				<th><?php echo _t('ADDRESS_TYPE')?></th>
				<th><?php echo _t('ADDRESS')?></th>
				<th><?php echo _t('COUNTRY')?></th>
				<th><?php echo _t('PHONE')?></th>
				<th><?php echo _t('CELLULAR')?></th>
				<th><?php echo _t('FAX')?></th>
				<th><?php echo _t('EMAIL')?></th>
				<th><?php echo _t('WEBSITE')?></th>
				<th><?php echo _t('START_DATE')?></th>
				<th><?php echo _t('END_DATE')?></th>				
			</tr>
		</thead>
		<tbody>
		<?php
		$count = 0;		
		foreach($addresses as $add){				
			$address->LoadfromRecordNumber($add);
			$odd = ($i++%2==1) ? "odd " : '' ;
		?>
			<tr id="<?php echo $count; ?>" class="<?php echo  $odd; ?>">
			<td><input name="addresses[]" type='checkbox' value='<?php echo $add ?>' class='delete'/></td>
			<td><a href="<?php echo get_url('person','address_list',null,array('address_id'=>$add)) ?>"><?php echo get_mt_term($address->address_type); ?></a></td>
			<td><?php echo $address->address; ?></td>
			<td><?php echo get_mt_term($address->country); ?></td>
			<td><?php echo $address->phone; ?></td>
			<td><?php echo $address->cellular; ?></td>
			<td><?php echo $address->fax; ?></td>
			<td><?php echo $address->email; ?></td>
			<td><?php echo $address->web; ?></td>
			<td><?php echo $address->start_date; ?></td>
			<td><?php echo $address->end_date; ?></td>				
			</tr>
		<?php	
			$count++;
		}
		?>
			<tr class='actions'>
            <td colspan='11'><input type='submit' class='btn' name='delete' value='<?php echo _t('DELETE') ?>' /></td>
        	</tr>
		</tbody>
		</table>
		</form>
	<?php
	}
	else{
		echo '<div class="alert alert-info spanauto"><button type="button" class="close" data-dismiss="alert">×</button>';
    	echo _t('THERE_IS_NO_ADDRESS_ADDED_TO_THIS_PERSON_YET__YOU_SHOULD_ADD_SOME_');
    	echo "</div><br/>";
	}
	if(isset($_GET['address_id'])){ 
	?>			
		<div class="form-container">
		<br />
		<br />
		<?php
			echo "<h3>" ._t('VIEW_ADDRESS') . "</h3>";
			echo "<br />";
		?>
		<a class="btn" href="<?php echo get_url('person','edit_address',null,array('address_id'=>$_GET['address_id'])) ?>"><i class="icon-edit"></i> <?php echo _t('EDIT_THIS_ADDRESS')?></a>
		<br />
	    <br />
	    <form class="form-horizontal"  action='<?php echo get_url('person','edit_address')?>' method='post' enctype='multipart/form-data'>
		<?php		
	    shn_form_get_html_labels($address_form , false );	
		$fields['save'] = null;
		?>
		</form>
		</div>
	<?php
	} 
	?>
		
</div>