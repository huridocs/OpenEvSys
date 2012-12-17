<?php include_once('person_name.php')?>
<?php if($del_confirm){ ?>
    <div class='dialog confirm'>
    <h3><?php echo _t('DO_YOU_WANT_TO_DELETE_THE_SELECTED_ADDRESS_ES__')?></h3>
    <form class="form-horizontal"  action="<?php get_url('person','delete_address')?>" method="post">
        <br />
        <center>
        <input type='submit' class='btn' name='yes' value='<? echo _t('YES') ?>' />
        <input type='submit' class='btn' name='no' value='<? echo _t('NO') ?>' />
        </center>
        <?php foreach($_POST['addresses'] as $val){ ?>
        <input type='hidden' name='addresses[]' value='<?php echo $val ?>' />
        <?php } ?>
    </form>
    </div>
<?php }?>

<?php	
	if((is_array($addresses) && count($addresses) != 0)){
?>		<div id="browse">		
		<table class="table table-bordered table-striped table-hover">
		<thead>
			<tr>				
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
			<td><?php echo get_mt_term($address->address_type); ?></td>
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
		</tbody>
		</table>
		</div>
	<?php
	}