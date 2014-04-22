<?php global $conf; ?>
<h2><?php echo _t('ADD_NEW_USER')  ?></h2>



<?php $fields = shn_form_get_html_fields($user_form);?>

<div class="form-container"> 

<form class="form-horizontal"  action='<?php echo get_url('admin','add_user')?>' method='post'>
<div class="control-group">
            <div class="controls">
  <a class="btn" href="<?php get_url('admin','user_management' ) ?> " ><i class="icon-remove-circle"></i> <?php echo _t('CANCEL') ?></a>
          
                <button type="submit" class="btn btn-primary" name="save" ><i class="icon-ok icon-white"></i> <?php echo _t('SAVE') ?></button>

            </div></div>
<fieldset>
    <legend><?php echo _t('Login Information')?></legend>

        <?php  echo $fields['username']  ?>
        <?php  echo $fields['password1']  ?>
        <?php  echo $fields['password2']  ?>
        

</fieldset>
<fieldset>
    <legend><?php echo _t('Profile Information')?></legend>
        <?php  echo $fields['first_name']  ?>
        <?php  echo $fields['last_name']  ?>
        <?php  echo $fields['organization']  ?>
        <?php  echo $fields['designation']  ?>
        <?php  echo $fields['email']  ?>
        <?php  echo $fields['address']  ?>
    <?php echo $fields['locale'] ?>
        <?php  echo $fields['role']  ?>
        <?php  echo $fields['status']  ?>      
              
 
  </fieldset>  
         <div class="control-group">
            <div class="controls">
  <a class="btn" href="<?php get_url('admin','user_management' ) ?> " ><i class="icon-remove-circle"></i> <?php echo _t('CANCEL') ?></a>
          
                <button type="submit" class="btn btn-primary" name="save" ><i class="icon-ok icon-white"></i> <?php echo _t('SAVE') ?></button>

            </div></div>
    
       
</form>
</div>

