<h2><?php echo _t('SET_SYSTEM_LANGUAGE') ?></h2>
<div class="form-container">
<form action='<?php echo get_url('admin','set_locale')?>' method='post'>
<fieldset><legend></legend>
<?php
    shn_form_select('Select language','locale',array('options'=>$locales, 'value'=>$current_locale));
?>
<input type="submit" name='update_locale' value="<?php echo _t('SAVE') ?>" />
</fieldset>
</form>
</div>
