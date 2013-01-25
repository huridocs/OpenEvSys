<ul class="nav nav-list"> 
    <?php
    $action = $_GET['act'];
    switch ($action) {
        case 'new_person':
            $breadcrumbs->pushCrumb(array('name' => _t('NEW_PERSON'), 'mod' => 'person', 'act' => 'new_person'), 1);
            break;
        case 'person':
            $breadcrumbs->pushCrumb(array('name' => _t('VIEW_PERSON'), 'mod' => 'person', 'act' => 'person'), 1);
            break;
        case 'biography_list':
            $breadcrumbs->pushCrumb(array('name' => _t('VIEW_PERSON'), 'mod' => 'person', 'act' => 'person'), 1);
            $breadcrumbs->pushCrumb(array('name' => _t('BIOGRAPHIC_DETAIL_S_'), 'mod' => 'person', 'act' => 'biography_list'), 2);
            break;
        case 'role_list':
            $breadcrumbs->pushCrumb(array('name' => _t('VIEW_PERSON'), 'mod' => 'person', 'act' => 'person'), 1);
            $breadcrumbs->pushCrumb(array('name' => _t('ROLE_LIST'), 'mod' => 'person', 'act' => 'role_list'), 2);
            break;
        case 'audit_log':
            $breadcrumbs->pushCrumb(array('name' => _t('VIEW_PERSON'), 'mod' => 'person', 'act' => 'person'), 1);
            $breadcrumbs->pushCrumb(array('name' => _t('AUDIT_LOG'), 'mod' => 'person', 'act' => 'audit_log'), 2);
            break;
        case 'address_list':
            $breadcrumbs->pushCrumb(array('name' => _t('VIEW_PERSON'), 'mod' => 'person', 'act' => 'person'), 1);
            $breadcrumbs->pushCrumb(array('name' => _t('PERSON_ADDRESS_ES_'), 'mod' => 'person', 'act' => 'address_list'), 2);
            break;
        case 'permissions':
            $breadcrumbs->pushCrumb(array('name' => _t('VIEW_PERSON'), 'mod' => 'person', 'act' => 'person'), 1);
            $breadcrumbs->pushCrumb(array('name' => _t('PERMISSIONS'), 'mod' => 'person', 'act' => 'permissions'), 2);
            break;
        case 'browse':
        default:
            //$breadcrumbs->pushCrumb(array('name' => _t('BROWSE_PERSON'), 'mod' => 'person', 'act' => 'browse'), 1);
            break;
    }
    ?>
    <li <?php if (in_array($active, array("browse"))) echo "class='active'" ?> ><a href="<?php get_url('person', 'browse') ?>"><?php echo _t('BROWSE_PERSONS') ?></a>
    </li>
    <?php
    $id = (isset($_GET['pid'])) ? $_GET['pid'] : $_SESSION['pid'];
    if (isset($id)) {
        ?>
        <li class="<?php if ($action == "person") echo "active" ?>"><a  href="<?php get_url('person', 'person') ?>"><?php echo _t('VIEW_PERSON') ?></a>

            <?php if (!in_array($active, array("browse", "new"))) {
                ?>

             <li class="subnav <?php if ($action == "address_list") echo "active" ?>"><a href="<?php get_url('person', 'address_list', null) ?> " >
                    <?php echo _t('PERSON_ADDRESS_ES_') ?>
                </a></li>
            <li class="subnav <?php if ($action == "biography_list") echo "active" ?>"><a href="<?php get_url('person', 'biography_list', null) ?>" >
                    <?php echo _t('BIOGRAPHIC_DETAIL_S_') ?>
                </a></li>
            <li class="subnav <?php if ($action == "role_list") echo "active" ?>">	<a href="<?php get_url('person', 'role_list', null) ?>"  >
                    <?php echo _t('ROLE_LIST') ?>
                </a></li>
            <li class="subnav <?php if ($action == "audit_log") echo "active" ?>">	<a href="<?php get_url('person', 'audit_log', null) ?>" >
                    <?php echo _t('AUDIT_LOG') ?>
                </a></li>
            <?php global $person;
            if ($person->confidentiality == 'y') { ?>
                <li class="subnav <?php if ($action == "permissions") echo "active" ?> permission">	<a href="<?php get_url('person', 'permissions', null) ?>" >
                <?php echo _t('PERMISSONS') ?>
                    </a> </li>       
            <?php } ?>

        <?php }
        ?>
    </li>
    <?php
}
?>
<li <?php if (in_array($active, array("new"))) echo "class='active'" ?> ><a href="<?php get_url('person', 'new_person') ?>"><?php echo _t('NEW_PERSON') ?></a>
</li>

</ul>





