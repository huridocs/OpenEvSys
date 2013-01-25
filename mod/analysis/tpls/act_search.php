<?php
	global $conf;
?>
<div id="browse">
	<table class='table table-bordered table-striped table-hover'>
		<thead>
		<tr>
			<th colspan="2"><?php echo _t('CHOSE_STARTING_POINT'); ?></th>
		</tr>
		</thead>
		<tbody>
<?php
	if(is_array($search_entities) && count($search_entities) != 0){	
		$i=0;	
		foreach($search_entities as $entity=>$search_entity){			
?>
			<tr <?php echo ($i++%2==1)?'class="odd"':''; ?>>
				<td><a href="<?php echo  get_url('analysis','search_form',null,array('main_entity'=>$search_entities[$entity]['type']))?>"><?php echo $search_entities[$entity]['title']?></a></td>
				<td><?php echo $search_entities[$entity]['desc']?></td>
			</tr>
<?php			
		}
	}
?>
		</tbody>
	</table>
</div>
