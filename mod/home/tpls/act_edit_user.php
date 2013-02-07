<?php global $conf; ?>

<div class='panel'>
    <h2><?php echo _t('MY_PREFERENCES') ." : $username " ?></h2>

<?php $fields = shn_form_get_html_fields($user_form);?>
<div class="form-container"> 
<form class="form-horizontal"  action='<?php echo get_url('home','edit_user')?>' method='post'>
        <?php  echo $fields['first_name']  ?>
        <?php  echo $fields['last_name']  ?>
        <?php  echo $fields['organization']  ?>
        <?php  echo $fields['designation']  ?>
        <?php  echo $fields['email']  ?>
        <?php  echo $fields['address']  ?>

      <div class="control-group">
                <div class="controls">

                    
                    <a class="btn" href="<?php get_url('home' ) ?> " ><i class="icon-remove-circle"></i> <?php echo _t('CANCEL') ?></a>
          <button type="submit" class="btn  btn-primary" name="save" ><i class="icon-ok icon-white"></i> <?php echo _t('SAVE') ?></button>
      </div></div>

</form>
</div>
</div>
