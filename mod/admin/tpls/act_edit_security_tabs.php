<div id="auth-method" class="control-group">
    <p>
        2-Step Verification adds an extra layer of security to your account, 
        drastically reducing the chances of having the information in your account stolen.
    </p>

    <div class="btn-group">
      <button type="button" class="btn btn-default active" data="none"><?php echo _t('None') ?></button>
      <button type="button" class="btn btn-default" data="MGA"><?php echo _t('Google Authenticator') ?></button>
      <button type="button" class="btn btn-default" data="yubikey"><?php echo _t('YubiKey') ?></button>
    </div>
</div>

<?php 
    $userConfig = @json_decode($user->config, true);

    $currentMethod = (isset($userConfig["security"]["TSV"]["method"])) 
                ? $userConfig["security"]["TSV"]["method"] : "none";
?>

<script>
    var currentMethod = "<?php echo $currentMethod ?>";

    $('#auth-method button').click(function() {
        var authMethod = this.getAttribute('data');
        showMethod(authMethod);
    });

    function showMethod(method) {
        $('#auth-method button').removeClass('active');
        $("#auth-method button[data='"+method+"']").addClass('active');
        $(".auth-method-form").hide();
        $('#' + method).show();
    }

    $(document).ready( function() {
        showMethod(currentMethod)
    });
</script>