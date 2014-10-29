<div id="yubikey" class="auth-method-form control-group" style="display:none;">
	
	<?php if(!$isYubikeyAPIConfigured) { ?>
        <p class="text-warning">
            <?php echo _t("This system does not currently support Yubico authenticantion. Contact your system administrator.") ?>
        </p>
    <?php } ?>

    <?php if($currentMethod != "yubikey") { ?>
        <div class='control-group <?php if ($wrongcode) { echo ' error'; } ?>'> 
            
            <ol>
                <li>Insert your Yubikey into an empty USB slot on your computer</li>
                <li>Wait a couple of seconds until the LED on the Yubikey turns green</li>
                <li>Place your cursor in the slot below and press the button on the Yubikey firmly</li>
                <li>The Yubikey will insert a code into the slot</li>
                <li>Press "save" - thats it! </li>
            </ol>
            <label  class="control-label" for="code"><?php echo _t('Code') ?></label>

            <div class="controls">
                <input  type="text" name="YubiKeyCode" value="" class='input-large <?php if ($wrongcode) { echo ' error'; } ?>' />
                <div class="help-inline">
                    <span class="label label-important"><?php echo _t('IS_REQUIRED') ?></span>  
                    <?php if ($wrongcode) { ?> 
                        <span class="help-inline"><?php echo _t($wrongcode) ?></span>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>
    <p>
    	<?php 
    		echo _t("The Yubikey Standard is a hardware authentication device that changes passwords every time it is used. ");
    		echo _t("For further information visit: ");
    	?>
    	<a href="http://www.yubico.com/">yubico.com<a>
    </p>
</div>