<div id="yubikey" class="auth-method-form control-group" style="display:none;">
	
	<?php if(!$isYubikeyAPIConfigured) { ?>
        <p class="text-warning">
            <?php echo _t("This system does not currently support Yubico authenticantion. Contact your system administrator.") ?>
        </p>
    <?php } ?>

    <?php if($currentMethod == "yubikey") { ?>
		<p class="text-info">
			<?php echo _t("YubiKey is currently enabled for your account.") ?>
		</p>
	<?php } ?>

    <p>
    	<?php 
    		echo _t("The Yubikey Standard is a hardware authentication device that changes passwords every time it is used. ");
    		echo _t("For further information visit: ");
    	?>
    	<a href="http://www.yubico.com/">yubico.com<a>
    </p>
</div>