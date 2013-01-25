<?php  global $global ;?>

<form class="form-horizontal hero-unit" method="post" action="index.php" style="margin: 0 auto;width: 500px;padding:20px;">
    <h4 style="text-align: center;"><?php echo _t('SIGN_IN_TO_OPENEVSYS') ?></h4>
     <?php  if($global['loginerror'] != null) {?>
    <div class="alert alert-error">
        <?php echo $global['loginerror']?>  
    </div>
  <?php }elseif( $global['timeOut']!=""){?>
    <div class="alert alert-error">
        <?php echo $global['timeOut']?>  
    </div>
    
	<?php $global['timeOut']="" ?>
  <?php } ?>
  <div class="control-group">
      
      <input type="hidden" value="login" name="login"/>
    <label class="control-label" for="username"><?php echo _t('USERNAME')?></label>
    <div class="controls">
      <input type="text" id="username" name="username" placeholder="<?php echo _t('USERNAME')?>" autocomplete="off">
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="password"><?php echo _t('PASSWORD')?></label>
    <div class="controls">
      <input type="password" id="password" placeholder="<?php echo _t('PASSWORD')?>" name="password" autocomplete="off">
    </div>
  </div>
    
    
  <div class="control-group">
    <div class="controls">
       <button type="submit" class="btn">Sign in</button>
    </div>
  </div>
   
</form>
