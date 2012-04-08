<?php include_once('tabs.php')?>
<?php include_once('person_name.php')?>
<?php
    include_once('view_card_list.php');
    draw_card_list('pa',$pid);	
?>
<div class="panel">
<a class="but" href="<?php echo get_url('person','new_address',null, null) ?>"><?php echo _t('ADD_ADDRESS')?></a>
<br />
<br />    
<?php	
	if((is_array($addresses) && count($addresses) != 0)){
?>
		<form action="<?php get_url('person','delete_address')?>" method="post">
		<table class="view">
		<thead>
			<tr>
				<td width='16px'><input type='checkbox' onchange='$("input.delete").attr("checked",this.checked)' /></td>
				<td><?php echo _t('ADDRESS_TYPE')?></td>
				<td><?php echo _t('ADDRESS')?></td>
				<td><?php echo _t('COUNTRY')?></td>
				<td><?php echo _t('PHONE')?></td>
				<td><?php echo _t('CELLULAR')?></td>
				<td><?php echo _t('FAX')?></td>
				<td><?php echo _t('EMAIL')?></td>
				<td><?php echo _t('WEBSITE')?></td>
				<td><?php echo _t('START_DATE')?></td>
				<td><?php echo _t('END_DATE')?></td>				
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
            <td colspan='11'><input type='submit' name='delete' value='<?php echo _t('DELETE') ?>' /></td>
        	</tr>
		</tbody>
		</table>
		</form>
	<?php
	}
	else{
		echo "<div class='notice'>";
    	echo _t('THERE_IS_NO_ADDRESS_ADDED_TO_THIS_PERSON_YET__YOU_SHOULD_ADD_SOME_');
    	echo "</div>";
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
		<a class="but" href="<?php echo get_url('person','edit_address',null,array('address_id'=>$_GET['address_id'])) ?>"><img src="<?php echo data_uri(APPROOT.'www/res/img/gtk-edit.png','image/png') ?>"> <?php echo _t('EDIT_THIS_ADDRESS')?></a>
		<br />
	    <br />
	    <form action='<?php echo get_url('person','edit_address')?>' method='post' enctype='multipart/form-data'>
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