<?php
		include_once 'new_card_list.php';
		draw_card_list();
?>
<div class="panel">
    <h3><?php echo _t('ADDING_PERSON_INFORMATION____') ?></h3>
    <div class="form-container"> 
    <form class="form-horizontal"  action='<?php echo get_url('person','new_person')?>' name="person_form" id="person_form" method='post' enctype='multipart/form-data'>
        <div class="control-group">
                <div class="controls">
 <a class='btn' href='<?php get_url('person','browse') ?>' ><i class="icon-remove-circle"></i> <?php echo _t('CANCEL') ?></a>
 	
                    <button type="submit" class="btn  btn-primary" name="save" ><i class="icon-ok icon-white"></i> <?php echo _t('SAVE') ?></button>

      </div>
      </div>   <?php $fields = shn_form_get_html_fields($person_form);  ?>
        <?php $fields = place_form_elements($person_form,$fields); ?>
             <div class="control-group">
                <div class="controls">
 <a class='btn' href='<?php get_url('person','browse') ?>' ><i class="icon-remove-circle"></i> <?php echo _t('CANCEL') ?></a>
 	
                    <button type="submit" class="btn  btn-primary" name="save" ><i class="icon-ok icon-white"></i> <?php echo _t('SAVE') ?></button>

      </div>
      </div>      
    </form>
    </div>
</div>
