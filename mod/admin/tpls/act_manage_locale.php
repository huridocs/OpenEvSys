<h2><?php echo _t('MANAGE_LANGUAGES') ?></h2>
<div class="form-container">
<form class="form-horizontal"  action='<?php echo get_url('admin','manage_locale')?>' method='post'>
<fieldset><legend><?php echo _t('ADD_LANGUAGE')?></legend>
<?php
    echo "<div class='field'>";
    shn_form_text(_t('LANGUAGE'),'new_locale',array('req'=>true));
    echo "</div>";
    echo "<div class='field'>";
    shn_form_text(_t('FOLDER_NAME'),'locale_folder',array('req'=>true));
    echo "</div>";
?>
<input type="submit" class="btn" name='add_locale' value="<?php echo _t('ADD_NEW') ?>" />
</fieldset>
<fieldset><legend><?php echo _t('REMOVE_LANGUAGE')?></legend>
<?php
    shn_form_select('Select language','select_locale',array('options'=>$locales, 'value'=>$current_locale));
?>
<input type="submit" class="btn" name='remove_locale' value="<?php echo _t('REMOVE') ?>" />
</fieldset>
</form>
</div>
