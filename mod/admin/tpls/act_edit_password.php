<?php
    global $conf;
    $username = $user->getUserName();
?>

<h2><?php echo _t('EDIT_USER') . " : <span class='red'> $username </span>" ?></h2>
<br />

<?php 
    $fields = shn_form_get_html_fields($change_password_form);
    include "act_edit_user_tabs.php";
?>

<div class='panel'>
    <div class="form-container"> 
        <form class="form-horizontal"  action='<?php echo get_url('admin', 'edit_password', null, array('uid' => $username)) ?>' method='post'>
            <div class="alert alert-info">
                <?php echo _t('Minimum 8 characters. Combining upper and lower case letters, numbers and symbols increases password complexity. Using a password vault is recommended.'); ?>
            </div>

            <?php  echo $fields['password1']  ?>
            <div class='controls'>
                <div class="progressbar">
                    <div id="progress1" class="progress"></div>
                </div>
            </div>
            
            <?php  echo $fields['password2']  ?>
            <div class='controls'>
                <div class="progressbar">
                    <div id="progress2" class="progress"></div>
                </div>
            </div>

            <div class="control-group">
                <div class="controls">
                    <a class="btn" href="<?php get_url('admin', 'edit_user', null, array('uid' => $username)) ?> " >
                        <i class="icon-remove-circle"></i> <?php echo _t('CANCEL') ?>
                    </a>
                <button type="submit" class="btn btn-primary" name="save" id="submit">
                    <i class="icon-ok icon-white"></i> <?php echo _t('SAVE') ?>
                </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="res/js/jquery.complexify.js"></script>
<script type="text/javascript">
    $(function () {
        var password1 = $("#password1");
        var password2 = $("#password2");
        var submit = $("#submit");
        var options = {minimumChars:8, strengthScaleFactor:0.7};

        submit.attr('disabled', 'disabled');

        complexifyField(password1, $('#progress1'));
        complexifyField(password2, $('#progress2'));

        function complexifyField(passwordField, progressbar) {
            passwordField.complexify(options, function (valid, complexity) {
                
                var passwordsMatch = (password1.val() == password2.val());

                if(valid && passwordsMatch) {
                    submit.removeAttr('disabled');
                } else {
                    submit.attr('disabled', 'disabled');
                }

                if (!valid) {
                    progressbar.css({'width':complexity + '%'}).removeClass('progressbarValid').addClass('progressbarInvalid');
                } else {
                    progressbar.css({'width':complexity + '%'}).removeClass('progressbarInvalid').addClass('progressbarValid');
                }
            });
        }
    });


</script>
<style type="text/css">
    .progressbar {
        width:216px;
        height:8px;
        display:block;
        border:1px solid #ccc;
        border-radius: 8px;
        overflow:hidden;
        background-color: white;
        position: relative;
        top:-15px;
    }

    .progress {
        display:block;
        height:8px;
        width:0%;
    }

    .progressbarValid {
        background-color:green;
        background-image: -o-linear-gradient(-90deg, #8AD702 0%, #389100 100%);
        background-image: -moz-linear-gradient(-90deg, #8AD702 0%, #389100 100%);
        background-image: -webkit-linear-gradient(-90deg, #8AD702 0%, #389100 100%);
        background-image: -ms-linear-gradient(-90deg, #8AD702 0%, #389100 100%);
        background-image: linear-gradient(-90deg, #8AD702 0%, #389100 100%);
    }

    .progressbarInvalid {
        background-color:red;
        background-image: -o-linear-gradient(-90deg, #F94046 0%, #92080B 100%);
        background-image: -moz-linear-gradient(-90deg, #F94046 0%, #92080B 100%);
        background-image: -webkit-linear-gradient(-90deg, #F94046 0%, #92080B 100%);
        background-image: -ms-linear-gradient(-90deg, #F94046 0%, #92080B 100%);
        background-image: linear-gradient(-90deg, #F94046 0%, #92080B 100%);
    }
</style>
