<?php
    
    include_once('event_title.php'); 
    
?>
<div class="panel">
    <div class='flow'>
        <strong class="first"><?php echo _t('ADD_CHAIN_OF_EVENTS')?></strong>
        <span><?php echo _t('FINISH')?></span>
    </div>
    <br />    
    <div class="form-container"> 
        <form class="form-horizontal"  action='<?php echo get_url('events','add_coe',null,array('eid'=>$_GET['eid'],'search_type'=>'event'))?>' method='post' enctype='multipart/form-data'>            
        <?php 
			$fields = shn_form_get_html_fields($chain_of_events_form);
			$fields = place_form_elements($chain_of_events_form,$fields);
		?>			
			<center>
			<a class="btn" href="<?php echo get_url('events','coe_list'); ?>"><?php echo _t('CANCEL'); ?></a>
			<?php echo $fields['save']; ?>
			</center>		 
		</form>       
    </div>
</div>
