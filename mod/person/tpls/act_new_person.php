<?php
		include_once 'new_card_list.php';
		draw_card_list();
?>
<div class="panel">
    <h3><?php echo _t('ADDING_PERSON_INFORMATION____') ?></h3>
    <div class="form-container"> 
    <form class="form-horizontal"  action='<?php echo get_url('person','new_person')?>' name="person_form" id="person_form" method='post' enctype='multipart/form-data'>
        <?php $fields = shn_form_get_html_fields($person_form);  ?>
        <?php $fields = place_form_elements($person_form,$fields); ?>
        <center>
        <a class='btn' href='<?php get_url('person','browse') ?>' ><?php echo _t('CANCEL') ?></a>
 		<?php echo $fields['save'] ?>
        </center>        
    </form>
    </div>
</div>
