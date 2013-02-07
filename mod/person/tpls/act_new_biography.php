<?php include_once('tabs.php')?>
<?php include_once('person_name.php')?>
<?php
    include_once('view_card_list.php');
    draw_card_list('bd',$pid);	
?>
<div class="panel">
<div class="form-container">
<form class="form-horizontal"  id="biographic_form" action='<?php echo get_url('person','new_biography',null,array('search_type'=>'person'))?>' method='post' enctype='multipart/form-data'>
	<div class="control-group">
                <div class="controls">

                   
       <a class="btn" href="<?php echo get_url('person','person'); ?>"><i class="icon-remove-circle"></i> <?php echo _t('CANCEL'); ?></a>
	 <button type="submit" class="btn btn-primary" name="save" ><i class="icon-ok icon-white"></i> <?php echo _t('SAVE') ?></button>
</div>
      </div>	
<?php
		echo "<h3>" ._t('ADD_BIOGRAPHIC_DETAILS') . "</h3>";
		echo "<br />";		
		$fields = shn_form_get_html_fields($biography_form);
        $fields = place_form_elements($biography_form,$fields);
?>
      <div class="control-group">
                <div class="controls">

                   
       <a class="btn" href="<?php echo get_url('person','person'); ?>"><i class="icon-remove-circle"></i> <?php echo _t('CANCEL'); ?></a>
	 <button type="submit" class="btn btn-primary" name="save" ><i class="icon-ok icon-white"></i> <?php echo _t('SAVE') ?></button>
</div>
      </div>
	
</form>
</div>
<br style="clear:both" />
</div>

