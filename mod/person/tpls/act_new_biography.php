<?php include_once('tabs.php')?>
<?php include_once('person_name.php')?>
<?php
    include_once('view_card_list.php');
    draw_card_list('bd',$pid);	
?>
<div class="panel">
<div class="form-container">
<form id="biographic_form" action='<?php echo get_url('person','new_biography',null,array('search_type'=>'person'))?>' method='post' enctype='multipart/form-data'>
		
<?php
		echo "<h3>" ._t('ADD_BIOGRAPHIC_DETAILS') . "</h3>";
		echo "<br />";		
		$fields = shn_form_get_html_fields($biography_form);
        $fields = place_form_elements($biography_form,$fields);
?>
	<center>
	<a class="but" href="<?php echo get_url('person','person'); ?>"><?php echo _t('CANCEL'); ?></a>
	<?php echo $fields['save'];?>
	</center>
</form>
</div>
<br style="clear:both" />
</div>

