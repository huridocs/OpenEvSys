<?php
    global $conf;
    $username = $user->getUserName();
?>

<h2><?php echo _t('EDIT_USER') . " : <span class='red'> $username </span>" ?></h2>
<br />

<?php 
    $fields = shn_form_get_html_fields($change_password_form);
    include "act_edit_user_tabs.php";
?>

<div class='panel'>
    <div class="form-container"> 
        <form class="form-horizontal"  action='<?php echo get_url('admin', 'edit_password', null, array('uid' => $username)) ?>' method='post'>
            <?php echo $fields['password1'] ?>
            <?php echo $fields['password2'] ?>
           
            <div class="control-group">
            <div class="controls">
   <a class="btn" href="<?php get_url('admin', 'edit_user', null, array('uid' => $username)) ?> " ><i class="icon-remove-circle"></i> <?php echo _t('CANCEL') ?></a>
          
                <button type="submit" class="btn btn-primary" name="save" ><i class="icon-ok icon-white"></i> <?php echo _t('SAVE') ?></button>

           </div></div>
          
        </form>
    </div>
</div>

