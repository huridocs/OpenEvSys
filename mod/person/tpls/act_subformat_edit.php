<?php
		include_once 'new_card_list.php';
		draw_card_list();
?>
<div class="panel">
    <div class="form-container">
      <form class="form-horizontal"  action='<?php echo get_url('person','subformat_edit', null, array('subformat' => $subformat_name, 'subid' => $subid))?>' name="subformat_form" method='post' enctype='multipart/form-data'>
        <div class="control-group">
          <div>
            <a class='btn' href='<?php get_url('person','subformat_list', null, array('subformat' => $subformat_name)) ?>' ><i class="icon-remove-circle"></i> <?php echo _t('CANCEL') ?></a>
            <button type="submit" class="btn  btn-primary" name="save" ><i class="icon-ok icon-white"></i> <?php echo _t('SAVE') ?></button>
          </div>
      	</div>
				<?php
          $html_fields = shn_form_get_html_fields($fields);
          place_form_elements($fields, $html_fields);
        ?>
        <div class="control-group">
          <div >
 						<a class='btn' href='<?php get_url('person','subformat_list', null, array('subformat' => $subformat_name)) ?>' ><i class="icon-remove-circle"></i> <?php echo _t('CANCEL') ?></a>
            <button type="submit" class="btn  btn-primary" name="save" ><i class="icon-ok icon-white"></i> <?php echo _t('SAVE') ?></button>
      		</div>
      	</div>
    	</form>
    </div>
</div>
