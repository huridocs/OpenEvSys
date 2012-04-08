<?php include_once('tabs.php')?>
<?php include_once('person_name.php')?>
<?php
    include_once('view_card_list.php');
    draw_card_list('pa',$pid);	
?>
<div class="panel">
    <h3><?php echo _t('ADDING_PERSON_ADDRESS____') ?></h3>
    <div class="form-container"> 
    <form action='<?php echo get_url('person','new_address')?>' name="person_form" id="person_address" method='post' enctype='multipart/form-data'>    	
        <?php        		
		$fields = shn_form_get_html_fields($address_form);		
		$fields = place_form_elements($address_form,$fields);
		?>
        <center>        
        <a class='but' href='<?php get_url('person','address_list') ?>' ><?php echo _t('CANCEL') ?></a>
        <?php echo $fields['save'];?>
        </center>
    </form>
    </div>
</div>