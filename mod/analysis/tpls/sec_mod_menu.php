<ul class="nav nav-tabs"> 
    <?php
    $action = $_GET['act'];
    $breadcrumbs = shnBreadcrumbs::getBreadcrumbs();
    switch ($action) {
        case 'adv_search':
            $breadcrumbs->pushCrumb(array('name' => _t('ADVANCED_SEARCH'), 'mod' => 'analysis', 'act' => 'adv_search'), 1);
            break;
      
       case 'search_query':
            $breadcrumbs->pushCrumb(array('name' => _t('SAVED_QUERIES'), 'mod' => 'analysis', 'act' => 'search_query'), 1);
            break;
      
      default:
            //$breadcrumbs->pushCrumb(array('name' => _t('BROWSE_DOCUMENT'), 'mod' => 'analysis', 'act' => 'browse'), 1);
            break;
    }
    ?>
    <li <?php if (in_array($action, array("adv_search"))) echo "class='active'" ?> >
<a href="<?php get_url('analysis','adv_search')?>" ><?php echo _t('ADVANCED_SEARCH') ?></a>
    </li>
    <li <?php if (in_array($action, array("search_query"))) echo "class='active'" ?> >
        <a href="<?php get_url('analysis','search_query')?>" class='active'><?php echo _t('SAVED_QUERIES') ?></a>
    </li>
</ul>


