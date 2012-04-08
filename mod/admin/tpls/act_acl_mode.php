<h2><?php echo _t('IF_AN_EVENT_PERSON_IS_CONFIDENTIAL__') ?></h2>
<h3><?php echo _('CURRENT_SETTING').' : '.$modes[$current_acl_mode] ?></h3>

<div class="form-container">
<form action='<?php echo get_url('admin','acl_mode')?>' method='post'>
<fieldset><legend></legend>
<?php
    shn_form_select(_t('SET_ACL_MODE'),'acl_mode',array('options'=>$modes, 'value'=>$current_acl_mode));
?>
<input type="submit" name='update_acl_mode' value="<?php echo _t('SAVE') ?>" />
</fieldset>
</form>
</div>
