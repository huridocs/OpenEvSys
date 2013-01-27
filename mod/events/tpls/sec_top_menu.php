<div class="navbar navtop"  >
    <div class="navbar-inner">
        <ul class="nav"> 
            <?php
            $action = $_GET['act'];
            $breadcrumbs = shnBreadcrumbs::getBreadcrumbs();

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
            <li class="<?php if ($action == "browse") echo "active" ?>" ><a href="<?php get_url('events', 'browse') ?>"><?php echo _t('BROWSE_EVENTS') ?></a>
            </li>

            <li class="<?php if ($action != "browse") echo "active" ?>" ><a href="<?php get_url('events', 'get_event') ?>"><?php echo _t('VIEW_EVENT') ?></a>
            </li>

        </ul>
    </div>
</div>