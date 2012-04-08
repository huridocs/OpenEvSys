<?php
    include_once('tabs.php');
    include_once('event_title.php'); 
    include_once('card_list.php');
    draw_card_list('coe',$event_id);
?>
<div class="panel">
    <div class='flow'>
        <strong class="first"><?php echo _t('ADD_CHAIN_OF_EVENTS')?></strong>
        <span><?php echo _t('FINISH')?></span>
    </div>
    <br />    
    <div class="form-container"> 
        <form action='<?php echo get_url('events','add_coe',null,array('eid'=>$_GET['eid'],'search_type'=>'event'))?>' method='post' enctype='multipart/form-data'>            
        <?php 
			$fields = shn_form_get_html_fields($chain_of_events_form);
			$fields = place_form_elements($chain_of_events_form,$fields);
		?>			
			<center>
			<a class="but" href="<?php echo get_url('events','coe_list'); ?>"><?php echo _t('CANCEL'); ?></a>
			<?php echo $fields['save']; ?>
			</center>		 
		</form>       
    </div>
</div>
