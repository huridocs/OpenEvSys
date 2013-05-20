<h2><?php echo _t('TRANSLATE')." : {$locales[$current_locale]} ( $current_locale )" ?></h2>
<div class="form-container">
<form class="form-horizontal"  action='<?php echo get_url('admin','translate')?>' method='post'>
<fieldset><legend><?php  ?></legend>
<?php if($_SESSION['translator']){ ?>
        <input type="submit" class="btn" name='disable_translator' value="<?php echo _t('DISABLE_INTERACTIVE_TRANSLATION') ?>" style="color:red" />
<?php }else{ ?>
        <input type="submit" class="btn" name='enable_translator' value="<?php echo _t('ENABLE_INTERACTIVE_TRANSLATION') ?>" style="color:green" />
<?php }  ?> 
</fieldset>
<fieldset><legend><?php  ?></legend>
<center><textarea name="messages" rows="30" cols="107"><?php echo $messages ?></textarea></center>
<center>
    <input type="submit" class="btn" name='save_messages' value="<?php echo _t('SAVE') ?>" />
    <input type="submit" class="btn" name='download' value="<?php echo _t('DOWNLOAD') ?>" />
    <input type="file" name='upload_po'/>
    <input type="submit" class="btn" name='upload' value="<?php echo _t('UPLOAD') ?>" />
    <input type="submit" class="btn" name='compile' value="<?php echo _t('COMPILE') ?>" />
</center>
</fieldset>
</form>
</div>
