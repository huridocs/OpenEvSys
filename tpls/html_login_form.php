<?php  global $global ;?>

<form  method="post" action="index.php" id="loginform">
    <input type="hidden" value="login" name="login"/>
    <h2><?php echo _t('SIGN_IN_TO_OPENEVSYS') ?></h2>
<!-- using a table to make the login look nicer in ie -->
    <table class="center">
    <tr>
    <td><label class="ul"><?php echo _t('USERNAME')?> :</label></td>
    <td><input type="text" value="" style=" width: 150px" id="username" name="username" id="username" autocomplete="off"/></td>
    </tr>
    <tr>
    <td><label class="ul pass"><?php echo _t('PASSWORD')?> :</label></td>
    <td><input type="password" value="" style= " width: 150px" id="password" name="password" autocomplete="off"/></td>
    </tr>
    </table>
  <?php  if($global['loginerror'] != null) {?>
    <div class="center">
    <label class="error">  <?php echo $global['loginerror']?>  </label> 
    </div>
    <br />
  <?php }elseif( $global['timeOut']!=""){?>
    <div class="center">
    <label class="error"><?php echo$global['timeOut'] ?>  </label> 
    </div>
    <br />
	<?php $global['timeOut']="" ?>
  <?php } ?>

    <input type="submit" value="<?php echo _t('SIGN_IN')?>" name="submit" id="submit"/>
</form>
