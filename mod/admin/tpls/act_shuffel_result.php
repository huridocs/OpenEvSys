<?php global $conf; ?>
<h2><?php echo _t('COMBINED_SEARCH_FORMS')?></h2>
<div class="form-container">
<form class="form-horizontal"  action='<?php echo get_url('admin','shuffel_result')?>' method='get'>
<fieldset style="margin:10px">
<input type='hidden' name='mod' value='admin' /> 
<input type='hidden' name='act' value='shuffel_result' /> 
<?php 
    shn_form_select(_t('SELECT_SECONDARY_ENTITY'),'secondary_entity',array('options'=>$sec_entity_list,'value'=>$secondary_entity)) ;
    shn_form_submit(_t('SELECT'),'');
?>
</fieldset>
</form>
</div>

<?php if(isset($secondary_entity)){ ?>
<h3><?php echo _t('CHANGE')." [ {$sec_entity_list[$secondary_entity]} ] "._t('FILTER_OPTIONS_AND_RESULTS'); ?></h3>
<br />
<div class="panel">
<form class="form-horizontal"  action='<?php echo get_url('admin','shuffel_result',null,array('sub_act'=>$_REQUEST['sub_act']))?>' method='post'>
<?php include_once('shuffel_result_search.php');?>
<input type="hidden" name='secondary_entity' value="<?php echo $secondary_entity ?>" />
</form>
</div>
<?php } ?>
