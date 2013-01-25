<?php global $conf; ?>
<h2><?php echo _t('MY_PREFERENCES') ." : $username " ?></h2>

<div class='panel'>
<?php $fields = shn_form_get_html_fields($change_password_form);?>
<div class="form-container"> 
<form class="form-horizontal"  action='<?php echo get_url('home','edit_password' ) ?>' method='post'>
        <?php  echo $fields['password_current']  ?>
        <br />
        <?php  echo $fields['password1']  ?>
        <?php  echo $fields['password2']  ?>
        
        <br />
        
<center> 
        <?php  echo $fields['save']  ?>
        <a class="btn" href="<?php get_url('home','edit_user'  ) ?> " ><?php echo _t('CANCEL') ?></a>
</center>
</form>
</div>
</div>
