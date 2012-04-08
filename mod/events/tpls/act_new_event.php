<?php global $conf; ?>
<br />
<div class="card_list">
    <a href="<?php get_url('events','new_event')?>" class="active first"><?php echo _t('EVENT_DESCRIPTION') ?></a>
    <a class='inactive' ><?php echo _t('VICTIMS_AND_PERPETRATORS') ?></a>
    <a class='inactive'><?php echo _t('SOURCE') ?></a>
    <a class='inactive'><?php echo _t('INTERVENTIONS') ?></a>
    <a class='inactive'><?php echo _t('CHAIN_OF_EVENTS') ?></a>
    <a class='inactive'><?php echo _t('SUPPORTING_DOCUMENTS') ?></a>
    <a class="last inactive" ><?php echo _t('AUDIT_LOG') ?></a>
</div>
<div class="panel">
<div class='flow'>
    <strong class='first'><?php echo _t('ADD_EVENT_INFORMATION')?></strong>
    <span><?php echo _t('FINISH')?></span>
</div>
<br />
<h3><?php echo _t('ADDING_EVENT_INFORMATION').' ...'?></h3>
<div class="form-container"> 
<form action='<?php echo get_url('events','new_event')?>' id="event_form" name="event_form" method='post' enctype='multipart/form-data'>
<?php $fields = shn_form_get_html_fields($event_form);  ?>
<?php $fields = place_form_elements($event_form,$fields); ?>
<center>
    <a class='but' href="<?php get_url('events','browse') ?>"><?php echo _t('CANCEL') ?></a>
	<?php echo  $fields['save']; ?>
</center>
</form>
</div>
</div>
