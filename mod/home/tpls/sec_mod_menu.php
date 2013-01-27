<ul class="nav nav-tabs"> 
    <?php
    $action = $_GET['act'];
    $breadcrumbs = shnBreadcrumbs::getBreadcrumbs();
    switch ($action) {
        case 'edit_user':
            $breadcrumbs->pushCrumb(array('name' => _t('EDIT_PROFILE'), 'mod' => 'home', 'act' => 'edit_user'), 1);
            break;
        case 'view_homement':
            $breadcrumbs->pushCrumb(array('name' => _t('CHANGE_PASSWORD'), 'mod' => 'home', 'act' => 'edit_password'), 1);
            break;
        default:
            //$breadcrumbs->pushCrumb(array('name' => _t('BROWSE_DOCUMENT'), 'mod' => 'home', 'act' => 'browse'), 1);
            break;
    }
    ?>
    <li <?php if (in_array($action, array("edit_user"))) echo "class='active'" ?> ><a href="<?php get_url('home', 'edit_user', null, null) ?>"><?php echo _t('EDIT_PROFILE') ?></a>
    </li>

    <li <?php if (in_array($action, array("edit_password"))) echo "class='active'" ?> ><a href="<?php get_url('home', 'edit_password') ?>"><?php echo _t('CHANGE_PASSWORD') ?></a>
    </li>

</ul>

