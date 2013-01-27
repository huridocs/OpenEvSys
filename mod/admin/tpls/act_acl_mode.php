<h2><?php echo _t('IF_AN_EVENT_PERSON_IS_CONFIDENTIAL__') ?></h2>
<h3><?php echo _('CURRENT_SETTING').' : '.$modes[$current_acl_mode] ?></h3>

<div class="form-container">
<form class="form-horizontal"  action='<?php echo get_url('admin','acl_mode')?>' method='post'>
<fieldset><legend></legend>
<?php
    shn_form_select(_t('SET_ACL_MODE'),'acl_mode',array('options'=>$modes, 'value'=>$current_acl_mode));
?>
    <button type="submit" class="btn" name='update_acl_mode' ><i class="icon-ok"></i> <?php echo _t('SAVE') ?></button>
</fieldset>
</form>
</div>
