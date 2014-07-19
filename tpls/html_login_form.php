<?php global $global, $conf; ?>

<form class="form-horizontal hero-unit" method="post" action="index.php" style="margin: 0 auto;width: 500px;padding:20px;">
    <h4 style="text-align: center;"><?php echo _t('SIGN_IN_TO_OPENEVSYS') ?></h4>
    <?php if ($global['loginerror'] != null) { ?>
        <div class="alert alert-error">
            <?php echo $global['loginerror'] ?>  
        </div>
    <?php } elseif ($global['timeOut'] != "") { ?>
        <div class="alert alert-error">
            <?php echo $global['timeOut'] ?>  
        </div>

        <?php $global['timeOut'] = "" ?>
    <?php } ?>
    <?php
    if (!isset($_SESSION['username'])) {
        ?>

        <div class="control-group">

            <input type="hidden" value="login" name="login"/>
            <label class="control-label" for="username"><?php echo _t('USERNAME') ?></label>
            <div class="controls">
                <input type="text" id="username" name="username" placeholder="<?php echo _t('USERNAME') ?>" autocomplete="off">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="password"><?php echo _t('PASSWORD') ?></label>
            <div class="controls">
                <input type="password" id="password" placeholder="<?php echo _t('PASSWORD') ?>" name="password" autocomplete="off">
            </div>
        </div>
        <?php
        if ($conf['use_recaptcha']) {
            //require_once(APPROOT . '3rd/recaptcha/recaptchalib.php');
            $publickey = $conf['recaptcha_public_key']; // you got this from the signup page

            if ($publickey) {
                ?>
                <style>.input-recaptcha{
                        width:172px;   
                    }
                </style>
                <script type="text/javascript">
                    var RecaptchaOptions = {
                        theme : 'custom',
                        custom_theme_widget: 'recaptcha_widget'
                    };
                </script>               

                <div id="recaptcha_widget" style="display:none">

                    <div class="control-group">
                        <label class="control-label">reCAPTCHA</label>
                        <div class="controls">
                            <a id="recaptcha_image" href="#" class="thumbnail"></a>
                            <div class="recaptcha_only_if_incorrect_sol" style="color:red">Incorrect please try again</div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="recaptcha_only_if_image control-label">Enter the words above:</label>
                        <label class="recaptcha_only_if_audio control-label">Enter the numbers you hear:</label>

                        <div class="controls">
                            <div class="input-append">
                                <input type="text" id="recaptcha_response_field" name="recaptcha_response_field" class="input-recaptcha" />
                                <a class="btn" href="javascript:Recaptcha.reload()"><i class="icon-refresh"></i></a>
                                <a class="btn recaptcha_only_if_image" href="javascript:Recaptcha.switch_type('audio')"><i title="Get an audio CAPTCHA" class="icon-headphones"></i></a>
                                <a class="btn recaptcha_only_if_audio" href="javascript:Recaptcha.switch_type('image')"><i title="Get an image CAPTCHA" class="icon-picture"></i></a>
                                <a class="btn" href="javascript:Recaptcha.showhelp()"><i class="icon-question-sign"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <script type="text/javascript" src="https://www.google.com/recaptcha/api/challenge?k=<?php echo $publickey ?>"></script>

                <?php
            }
        }
    } elseif(!empty($_SESSION['check_TSV'])) {
        $message = "Your account is configured to use Google Authentication";
        if($_SESSION['check_TSV'] == "yubikey")
            $message = "Your account is configured to use YubiKey authentication";
        ?>
        <div class="control-group">
            <p style="text-align: center;"><?php echo $message ?></p>
            <label class="control-label" for="code"><?php echo _t('Code') ?></label>
            <div class="controls">
                <input type="text" id="code" name="code" autocomplete="off">
            </div>
        </div>
        <?php
    }
    ?>
        <div class="control-group">
            <div class="controls">
                <button type="submit" class="btn"><?php echo _t('SIGN_IN') ?></button>
            </div>
        </div>
</form>
