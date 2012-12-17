<?php global $conf; ?>
<h2><?php echo _t('ADD_NEW_FIELD')?></h2>
<div class="form-container">
<form class="form-horizontal"  action='<?php echo get_url('admin','new_field')?>' method='post'>
<fieldset style="margin:10px"> 
<?php $fields = shn_form_get_html_fields($customization_form,false);  ?>
        <?php echo $fields['entity_select'] ?>
        <?php echo $fields['change_entity'] ?>
</fieldset>
</form>
</div>

<?php if(isset($entity_select)){ ?>
<h3><?php echo _t('ADD_FIELD_TO')." [ {$entity_select} ] "._t('FORM'); ?></h3>
<div class="form-container">
<form class="form-horizontal"  action='<?php echo get_url('admin','new_field',null)?>' method='post'>
<fieldset style="margin:10px">
<?php $fields = shn_form_get_html_fields($new_field_form); ?>
<?php place_form_elements($new_field_form , $fields); ?>
<center>
<?php echo $fields['add_new'] ?>    
<?php echo $fields['entity_select'] ?>
</center>
</fieldset>
</form>
</div>
<?php } ?>
