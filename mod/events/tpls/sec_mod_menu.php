<ul class="nav nav-list"> 
    <?php
    $action = $_GET['act'];
    switch ($action) {
        case 'new_event':
            $breadcrumbs->pushCrumb(array('name' => _t('NEW_EVENT'), 'mod' => 'events', 'act' => 'new_event'), 1);
            break;
        case 'get_event':
            $breadcrumbs->pushCrumb(array('name' => _t('VIEW_EVENT'), 'mod' => 'events', 'act' => 'get_event'), 1);
            break;
        case 'vp_list':
            $breadcrumbs->pushCrumb(array('name' => _t('VIEW_EVENT'), 'mod' => 'events', 'act' => 'get_event'), 1);
            $breadcrumbs->pushCrumb(array('name' => _t('VICTIMS_AND_PERPETRATORS'), 'mod' => 'events', 'act' => 'vp_list'), 2);
            break;
        case 'src_list':
            $breadcrumbs->pushCrumb(array('name' => _t('VIEW_EVENT'), 'mod' => 'events', 'act' => 'get_event'), 1);
            $breadcrumbs->pushCrumb(array('name' => _t('SOURCES'), 'mod' => 'events', 'act' => 'src_list'), 2);
            break;
        case 'intv_list':
            $breadcrumbs->pushCrumb(array('name' => _t('VIEW_EVENT'), 'mod' => 'events', 'act' => 'get_event'), 1);
            $breadcrumbs->pushCrumb(array('name' => _t('INTERVENTIONS'), 'mod' => 'events', 'act' => 'intv_list'), 2);
            break;
        case 'coe_list':
            $breadcrumbs->pushCrumb(array('name' => _t('VIEW_EVENT'), 'mod' => 'events', 'act' => 'get_event'), 1);
            $breadcrumbs->pushCrumb(array('name' => _t('CHAIN_OF_EVENTS'), 'mod' => 'events', 'act' => 'coe_list'), 2);
            break;
        case 'doc_list':
            $breadcrumbs->pushCrumb(array('name' => _t('VIEW_EVENT'), 'mod' => 'events', 'act' => 'get_event'), 1);
            $breadcrumbs->pushCrumb(array('name' => _t('DOCUMENTS'), 'mod' => 'events', 'act' => 'doc_list'), 2);
            break;
        case 'audit':
            $breadcrumbs->pushCrumb(array('name' => _t('VIEW_EVENT'), 'mod' => 'events', 'act' => 'get_event'), 1);
            $breadcrumbs->pushCrumb(array('name' => _t('AUDIT_LOG'), 'mod' => 'events', 'act' => 'audit'), 2);
            break;
        case 'permissions':
            $breadcrumbs->pushCrumb(array('name' => _t('VIEW_EVENT'), 'mod' => 'events', 'act' => 'get_event'), 1);
            $breadcrumbs->pushCrumb(array('name' => _t('PERMISSIONS'), 'mod' => 'events', 'act' => 'permissions'), 2);
            break;
        case 'browse':
        default:
            //$breadcrumbs->pushCrumb(array('name' => _t('BROWSE_EVENT'), 'mod' => 'events', 'act' => 'browse'), 1);
            break;
    }
    ?>
    <li <?php if (in_array($active, array("browse"))) echo "class='active'" ?> ><a href="<?php get_url('events', 'browse') ?>"><?php echo _t('BROWSE_EVENTS') ?></a>
    </li>
    <?php
    $id = (isset($_GET['eid'])) ? $_GET['eid'] : $_SESSION['eid'];
    if (isset($id)) {
        ?>
        <li class="<?php if ($action == "get_event") echo "active" ?>"><a  href="<?php get_url('events', 'get_event') ?>"><?php echo _t('VIEW_EVENT') ?></a>

            <?php if (!in_array($active, array("browse", "new"))) {
                ?>

            <li class="subnav <?php if ($action == "vp_list") echo "active" ?>"><a href="<?php get_url('events', 'vp_list', null, array('eid' => $id)) ?>" >
                    <?php echo _t('VICTIMS_AND_PERPETRATORS') ?>
                </a></li>
            <li class="subnav <?php if ($action == "src_list") echo "active" ?>"><a href="<?php get_url('events', 'src_list', null, array('eid' => $id)) ?>"  >
                    <?php echo _t('SOURCES') ?>
                </a></li>
            <li class="subnav <?php if ($action == "intv_list") echo "active" ?>"><a href="<?php get_url('events', 'intv_list', null, array('eid' => $id)) ?>">
                    <?php echo _t('INTERVENTIONS') ?>
                </a></li>
            <li class="subnav <?php if ($action == "coe_list") echo "active" ?>"><a href="<?php get_url('events', 'coe_list', null, array('eid' => $id)) ?>" >
                    <?php echo _t('CHAIN_OF_EVENTS') ?>
                </a></li>
            <li class="subnav <?php if ($action == "doc_list") echo "active" ?>"><a href="<?php get_url('events', 'doc_list', null, array('eid' => $id)) ?>" >
                    <?php echo _t('DOCUMENTS') ?>
                </a></li>
            <li class="subnav <?php if ($action == "audit") echo "active" ?>"><a href="<?php get_url('events', 'audit', null, array('eid' => $id)) ?>"  >
                    <?php echo _t('AUDIT_LOG') ?>
                </a></li>
            <?php global $event;
            if ($event->confidentiality == 'y') {
                ?>
                <li class="subnav permission <?php if ($action == "permissions") echo "active" ?>"><a href="<?php get_url('events', 'permissions', null, array('eid' => $id)) ?>"  >
                <?php echo _t('PERMISSIONS') ?>
                    </a></li>
            <?php } ?>  

        <?php }
        ?>
    </li>
    <?php
}
?>
<li <?php if (in_array($active, array("new"))) echo "class='active'" ?> ><a href="<?php get_url('events', 'new_event') ?>"><?php echo _t('NEW_EVENT') ?></a>
</li>

</ul>
