<?php global $conf; ?>
<h2><?php echo _t('MY_PREFERENCES') ." : $username " ?></h2>
<br />
<div class="card_list">
    <a class="active" href="<?php get_url('home','edit_user');?> " ><?php echo _t('EDIT_PROFILE') ?></a>
    <a href="<?php get_url('home','edit_password' );?> " ><?php echo _t('CHANGE_PASSWORD') ?></a>
</div> 
<div class='panel'>
<?php $fields = shn_form_get_html_fields($user_form);?>
<div class="form-container"> 
<form action='<?php echo get_url('home','edit_user')?>' method='post'>
        <?php  echo $fields['first_name']  ?>
        <?php  echo $fields['last_name']  ?>
        <?php  echo $fields['organization']  ?>
        <?php  echo $fields['designation']  ?>
        <?php  echo $fields['email']  ?>
        <?php  echo $fields['address']  ?>
<center> 
        <?php  echo $fields['save']  ?>
        <a class="but" href="<?php get_url('home' ) ?> " ><?php echo _t('CANCEL') ?></a>
</center>
</form>
</div>
</div>
