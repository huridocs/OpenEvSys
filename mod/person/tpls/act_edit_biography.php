<?php include_once('tabs.php')?>
<?php include_once('person_name.php')?>
<?php
    include_once('view_card_list.php');
    draw_card_list('bd',$pid);	
?>
<div class="panel">
<div class="form-container">
<form class="form-horizontal"  action='<?php echo get_url('person','edit_biography',null,array('search_type'=>'person'))?>' method='post' enctype='multipart/form-data'>
<?php
	echo "<h3>" ._t('EDIT_BIOGRAPHIC_DETAILS') . "</h3>";
	echo "<br />";		
	$fields = shn_form_get_html_fields($biography_form);
    $fields = place_form_elements($biography_form,$fields);
?>
       <div class="control-group">
                <div class="controls">

                    <button type="submit" class="btn" name="save" ><i class="icon-ok"></i> <?php echo _t('SAVE') ?></button>

        <a class="btn" href="<?php echo get_url('person','person'); ?>"><i class="icon-stop"></i> <?php echo _t('CANCEL'); ?></a>
	</div>
      </div>
	
</form>
</div>
<br style="clear:both" />
</div>