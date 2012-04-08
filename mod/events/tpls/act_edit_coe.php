<?php include_once('tabs.php')?>
<?php include_once('event_title.php')?>
<?php
    include_once('card_list.php');
    draw_card_list('coe',$event_id);
?>
<div class="panel">

<br />
<div class="form-container"> 
<form action='<?php echo get_url('events','edit_coe',null,array('eid'=>$_GET['eid'],'coeid'=>$_GET['coeid'],'search_type'=>'event'))?>' method='post' enctype='multipart/form-data'>
<?php 		 
		$chain_of_events_form['update'] = array('type'=>'submit','label'=>_t('UPDATE'));
		$fields = shn_form_get_html_fields($chain_of_events_form);
		include 'chain_of_event_form.php';		
?>  
		<center>
		<?php echo $fields['update']; ?>
		<a class="but" href="<?php echo get_url('events','coe_list'); ?>"><?php echo _t('CANCEL'); ?></a>
		</center>
</form> 
<br />
</div>
</div>
