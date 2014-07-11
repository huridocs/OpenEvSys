<?php
    global $conf;
    $username = $user->getUserName();
?>

<h2><?php echo _t('EDIT_USER') . " : <span class='red'> $username </span>" ?></h2>
<br />

<?php include "act_edit_user_tabs.php" ?>

<div class='panel'>
    <div class="form-container"> 
        <form class="form-horizontal"  action='<?php echo get_url('admin', 'edit_security', null, array('uid' => $username)) ?>' method='post'>
        
            <?php
                include "act_edit_security_tabs.php";
                include "act_edit_security_none.php";
                include "act_edit_security_ga.php";
                include "act_edit_security_yubikey.php";
            ?>

            <div class="control-group">
                <div class="controls">
                    <button type="submit" class="btn btn-primary" name="save" ><i class="icon-ok icon-white"></i> <?php echo _t('SAVE') ?></button>
                </div>
            </div>

        </form>
    </div>
</div>

