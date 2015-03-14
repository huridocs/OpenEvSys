<?php include_once('tabs.php')?>
<?php include_once('person_name.php')?>
<?php
    include_once('view_card_list.php');
    draw_card_list('pa',$pid);
?>
<div class="panel">
<a class="btn btn-primary" href="<?php echo get_url('person','subformat_new', null, array('subformat' => $subformat_entity)) ?>"><i class="icon-plus icon-white"></i><?php echo _t('ADD')?></a>
<br />
<br />
<?php
	if((is_array($list) && count($list) != 0)){
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
            <td colspan='11'><button type='submit' class='btn btn-grey' name='delete' >
<i class="icon-trash"></i> <?php echo _t('DELETE') ?></button>
            </td>
        	</tr>
		</tbody>
		</table>
		</form>
	<?php
	}else{
			echo '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">×</button>';
    	echo _t('NO_RESULTS_FOUND');
    	echo "</div>";
	}
	?>
</div>
