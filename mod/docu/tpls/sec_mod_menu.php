<ul class="nav nav-tabs"> 
    <?php
    $action = $_GET['act'];
    $breadcrumbs = shnBreadcrumbs::getBreadcrumbs();
    switch ($action) {
        case 'new_document':
            $breadcrumbs->pushCrumb(array('name' => _t('NEW_DOCUMENT'), 'mod' => 'docu', 'act' => 'new_document'), 1);
            break;
        case 'view_document':
            $breadcrumbs->pushCrumb(array('name' => _t('VIEW_DOCUMENT'), 'mod' => 'docu', 'act' => 'view_document'), 1);
            break;
        case 'link':
            $breadcrumbs->pushCrumb(array('name' => _t('VIEW_DOCUMENT'), 'mod' => 'docu', 'act' => 'view_document'), 1);
            $breadcrumbs->pushCrumb(array('name' => _t('LINKS'), 'mod' => 'docu', 'act' => 'link'), 2);
            break;
        case 'audit':
            $breadcrumbs->pushCrumb(array('name' => _t('VIEW_DOCUMENT'), 'mod' => 'docu', 'act' => 'view_document'), 1);
            $breadcrumbs->pushCrumb(array('name' => _t('AUDIT_LOG'), 'mod' => 'docu', 'act' => 'audit'), 2);
            break;
       
       case 'browse':
        default:
            //$breadcrumbs->pushCrumb(array('name' => _t('BROWSE_DOCUMENT'), 'mod' => 'docu', 'act' => 'browse'), 1);
            break;
    }
    ?>
    <li <?php if (in_array($action, array("browse"))) echo "class='active'" ?> ><a href="<?php get_url('docu','browse',null,null)?>"><?php echo _t('BROWSE_DOCUMENTS') ?></a>
    </li>
    <?php
    $id = (isset($_GET['doc_id'])) ? $_GET['doc_id'] : $_SESSION['doc_id'];
    if (isset($id)) {
        ?>
        <li class="<?php if ($action == "view_document") echo "active" ?>"><a  href="<?php get_url('docu', 'view_document') ?>"><?php echo _t('VIEW_DOCUMENT') ?></a>

            <?php if (!in_array($action, array("browse", "new_document"))) {
                ?>

          
            <li class="<?php if ($action == "link") echo "active" ?>"><a href="<?php get_url('docu', 'link') ?>" ><?php echo _t('LINKS') ?></a>
            </li>
            <li  class="<?php if ($action == "audit") echo "active" ?>"><a href="<?php get_url('docu', 'audit') ?>"><?php echo _t('AUDIT_LOG') ?></a>    
            </li> 

        <?php }
        ?>
    </li>
    <?php
}
?>
<li <?php if (in_array($action, array("new_document"))) echo "class='active'" ?> ><a href="<?php get_url('docu', 'new_document') ?>"><?php echo _t('NEW_DOCUMENT') ?></a>
</li>

</ul>

