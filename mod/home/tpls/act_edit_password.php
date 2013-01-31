<?php global $conf; ?>

<div class='panel'>
    <h2><?php echo _t('MY_PREFERENCES') ." : $username " ?></h2>

<?php $fields = shn_form_get_html_fields($change_password_form);?>
<div class="form-container"> 
<form class="form-horizontal"  action='<?php echo get_url('home','edit_password' ) ?>' method='post'>
        <?php  echo $fields['password_current']  ?>
        <br />
        <?php  echo $fields['password1']  ?>
        <?php  echo $fields['password2']  ?>
        
  <div class="control-group">
                <div class="controls">

                    <button type="submit" class="btn" name="save" ><i class="icon-ok"></i> <?php echo _t('SAVE') ?></button>

                    <a class="btn" href="<?php get_url('home','edit_user'  ) ?> " ><i class="icon-stop"></i> <?php echo _t('CANCEL') ?></a>
                </div></div>
      
</form>
</div>
</div>
