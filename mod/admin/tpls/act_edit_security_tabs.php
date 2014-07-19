<?php 
    $userConfig = $user->getConfig();

    $currentMethod = (isset($userConfig["security"]["TSV"]["method"])) 
                ? $userConfig["security"]["TSV"]["method"] : "none";
?>

<div id="auth-method" class="control-group">
    <p>
        <?php echo _t("2-Step Verification adds an extra layer of security to your account, drastically reducing the chances of having the information in your account stolen.") ?>
    </p>

    <?php 
        $currentMethodFeedback = "This account is not currently configured to use any 2-Step authentication method.";

        if($currentMethod == "MGA") { 
            $currentMethodFeedback = "Google Authenticator is currently enabled for this account.";
        }

        if($currentMethod == "yubikey") {
            $currentMethodFeedback = "YubiKey is currently enabled for this account.";
        }
    ?>

    <p class="text-info"> <?php echo _t($currentMethodFeedback) ?> </p>

    <div class="btn-group">
      <button type="button" class="btn btn-default active" data="none"><?php echo _t('None') ?></button>
      <button type="button" class="btn btn-default" data="MGA"><?php echo _t('Google Authenticator') ?></button>
      <button type="button" class="btn btn-default" data="yubikey"><?php echo _t('YubiKey') ?></button>
    </div>

    <input type="hidden" name="desiredMethod" />
</div>

<script>
    var currentMethod = "<?php echo $currentMethod ?>";
    var desiredMethod = "<?php echo $_POST['desiredMethod'] ?>";

    $('#auth-method button').click(function() {
        var authMethod = this.getAttribute('data');
        showMethod(authMethod);
    });

    function showMethod(method) {
        $('#auth-method button').removeClass('active');
        $("#auth-method button[data='"+method+"']").addClass('active');
        $(".auth-method-form").hide();
        $('#' + method).show();
        $("input[name='desiredMethod']").val(method);
    }

    $(document).ready( function() {
        var defaultMethod = desiredMethod != "" ? desiredMethod : currentMethod;
        showMethod(defaultMethod)
    });
</script>