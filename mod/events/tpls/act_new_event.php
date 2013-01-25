<?php global $conf; ?>

<div class="panel">
<div class='flow'>
    <strong class='first'><?php echo _t('ADD_EVENT_INFORMATION')?></strong>
    <span><?php echo _t('FINISH')?></span>
</div>
<br />
<h3><?php echo _t('ADDING_EVENT_INFORMATION').' ...'?></h3>
<div class="form-container"> 
<form class="form-horizontal"  action='<?php echo get_url('events','new_event')?>' id="event_form" name="event_form" method='post' enctype='multipart/form-data'>
<?php $fields = shn_form_get_html_fields($event_form);  ?>
<?php $fields = place_form_elements($event_form,$fields); ?>
<center>
    <a class='btn' href="<?php get_url('events','browse') ?>"><?php echo _t('CANCEL') ?></a>
	<?php echo  $fields['save']; ?>
</center>
</form>
</div>
</div>
