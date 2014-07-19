<div id="none" class="auth-method-form control-group" style="display:none;">
	
	<?php if($currentMethod == "none") { ?>
		<p class="text-info">
			<?php echo _t("Currently your account is not configured to use any 2-Step authentication method.") ?>
		</p>
	<?php } ?>

    <p>
    	<?php echo _t("Choosing none and saving will deactivate any previously configured 2-Step authentication methods.") ?>
    </p>
</div>