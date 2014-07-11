<?php 
    $tabActive = "class='active'";
    $userTab = get_action() ==  "edit_user" ? $tabActive : null;
    $passwordTab = (get_action() ==  "edit_password") ? $tabActive : null;
    $securityTab = (get_action() ==  "edit_security") ? $tabActive : null;
?>

<div>
    <ul class="nav nav-tabs tabnav">
        <li <?php echo $userTab ?>>
            <a href="<?php get_url('admin', 'edit_user', null, array('uid' => $username)); ?> " >
                <?php echo _t('EDIT_PROFILE') ?>
            </a>
        </li>
        <li <?php echo $passwordTab ?>>
            <a  href="<?php get_url('admin', 'edit_password', null, array('uid' => $username)); ?> " >
                <?php echo _t('CHANGE_PASSWORD') ?>
            </a>
        </li>
        <li <?php echo $securityTab ?>>
            <a  href="<?php get_url('admin', 'edit_security', null, array('uid' => $username)); ?> " >
                <?php echo _t('Security') ?>
            </a>
        </li>
    </ul>
</div> 