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
        <div class="alert alert-info">
                <?php echo _t('Minimum 8 characters. Combining upper and lower case letters, numbers and symbols increases password complexity. Using a password vault is recommended.'); ?>
        </div>

        <?php  echo $fields['password1']  ?>
        <div class='controls'>
            <div class="progressbar">
                <div id="progress1" class="progress-percent"></div>
            </div>
        </div>
        
        <?php  echo $fields['password2']  ?>
        <div class='controls'>
            <div class="progressbar">
                <div id="progress2" class="progress-percent"></div>
            </div>
        </div>

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
          
                <button type="submit" class="btn btn-primary" name="save" id="submit"><i class="icon-ok icon-white"></i> <?php echo _t('SAVE') ?></button>

            </div></div>
    
       
</form>
</div>

<script src="res/js/jquery.complexify.js"></script>
<script src="res/js/password-complexify.js"></script>
