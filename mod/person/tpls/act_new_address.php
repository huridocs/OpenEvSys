<?php include_once('tabs.php')?>
<?php include_once('person_name.php')?>
<?php
    include_once('view_card_list.php');
    draw_card_list('pa',$pid);	
?>
<div class="panel">
    <h3><?php echo _t('ADDING_PERSON_ADDRESS____') ?></h3>
    <div class="form-container"> 
    <form class="form-horizontal"  action='<?php echo get_url('person','new_address')?>' name="person_form" id="person_address" method='post' enctype='multipart/form-data'>    	
       <div class="control-group">
                <div class="controls">

                  <a class='btn' href='<?php get_url('person','address_list') ?>' ><i class="icon-remove-circle"></i> <?php echo _t('CANCEL') ?></a>
       
                    <button type="submit" class="btn btn-primary" name="save" ><i class="icon-ok icon-white"></i> <?php echo _t('SAVE') ?></button>
 </div>
      </div>
        <?php        		
		$fields = shn_form_get_html_fields($address_form);		
		$fields = place_form_elements($address_form,$fields);
		?>
         <div class="control-group">
                <div class="controls">

                  <a class='btn' href='<?php get_url('person','address_list') ?>' ><i class="icon-remove-circle"></i> <?php echo _t('CANCEL') ?></a>
       
                    <button type="submit" class="btn btn-primary" name="save" ><i class="icon-ok icon-white"></i> <?php echo _t('SAVE') ?></button>
 </div>
      </div>
       
    </form>
    </div>
</div>