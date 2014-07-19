<?php
    global $conf;
    $username = $user->getUserName();
?>

<div class='panel'>
    <h2><?php echo _t('MY_PREFERENCES') ." : $username " ?></h2>

    <div class="form-container"> 
        <form class="form-horizontal"  action='<?php get_url('home', 'edit_security') ?>' method='post'>
        
            <?php
                include APPROOT . "mod/admin/tpls/act_edit_security_tabs.php";
                include APPROOT . "mod/admin/tpls/act_edit_security_none.php";
                include APPROOT . "mod/admin/tpls/act_edit_security_ga.php";
                include APPROOT . "mod/admin/tpls/act_edit_security_yubikey.php";
            ?>

            <div class="control-group">
                <div class="controls">
                    <button type="submit" class="btn btn-primary" name="save" ><i class="icon-ok icon-white"></i> <?php echo _t('SAVE') ?></button>
                </div>
            </div>

        </form>
    </div>
</div>

