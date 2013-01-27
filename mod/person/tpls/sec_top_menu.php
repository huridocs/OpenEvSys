<div class="navbar navtop"  >
    <div class="navbar-inner">
        <ul class="nav"> 
    <?php
    $action = $_GET['act'];
    $breadcrumbs = shnBreadcrumbs::getBreadcrumbs();

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
             <li class="<?php if ($action == "browse") echo "active" ?>" ><a href="<?php get_url('person', 'browse') ?>"><?php echo _t('BROWSE_PERSONS') ?></a>
            </li>

            <li class="<?php if ($action != "browse") echo "active" ?>" ><a href="<?php get_url('person', 'person') ?>"><?php echo _t('VIEW_PERSON') ?></a>
            </li>


</ul>

</div>
</div>



