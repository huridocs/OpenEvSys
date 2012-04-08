<?php
function draw_card_list($active,$event_id,$c = 'y'){
    $breadcrumbs = shnBreadcrumbs::getBreadcrumbs();
    switch($active){
        case 'sum':
            $ca = "active";
            $breadcrumbs->pushCrumb(array('name'=>_t('EVENT_DESCRIPTION'),'mod'=>'events','act'=>'get_event'),2);        
            break;
        case 'vp':
            $cb = "active";
            $ca = "before";
            $breadcrumbs->pushCrumb(array('name'=>_t('VICTIMS_AND_PERPETRATORS'),'mod'=>'events','act'=>'vp_list'),2);        
            break;
        case 'src':
            $cc = "active";
            $cb = "before";
            $breadcrumbs->pushCrumb(array('name'=>_t('SOURCES'),'mod'=>'events','act'=>'src_list'),2);        
            break;
        case 'int':
            $cd = "active";
            $cc = "before";
            $breadcrumbs->pushCrumb(array('name'=>_t('INTERVENTIONS'),'mod'=>'events','act'=>'intv_list'),2);        
            break;
        case 'coe':
            $ce = "active";
            $cd = "before";
            $breadcrumbs->pushCrumb(array('name'=>_t('CHAIN_OF_EVENTS'),'mod'=>'events','act'=>'coe_list'),2);        
            break;
       	case 'dl':
            $cf = "active";
            $ce = "before";
            $breadcrumbs->pushCrumb(array('name'=>_t('DOCUMENTS'),'mod'=>'events','act'=>'doc_list'),2);        
            break;
        case 'al':
            $cg = "active";
            $cf = "before";
            $breadcrumbs->pushCrumb(array('name'=>_t('AUDIT_LOG'),'mod'=>'events','act'=>'audit'),2);        
            break;
        case 'pe':
            $ch = "active";
            $cg = "before";
            $breadcrumbs->pushCrumb(array('name'=>_t('PERMISSIONS'),'mod'=>'events','act'=>'permissions'),2);        
            break;
    }
?>
    <div class="card_list">
        <a href="<?php get_url('events','get_event', null,array('eid'=>$event_id))?> " class="first <?php echo $ca ?>" >
            <?php echo _t('EVENT_DESCRIPTION') ?>
        </a>
        <a href="<?php get_url('events','vp_list',null,array('eid'=>$event_id))?>" class="<?php echo $cb ?>">
        <?php echo _t('VICTIMS_AND_PERPETRATORS') ?>
        </a>
        <a href="<?php get_url('events','src_list',null,array('eid'=>$event_id))?>" class="<?php echo $cc ?>" >
        <?php echo _t('SOURCES') ?>
        </a>
        <a href="<?php get_url('events','intv_list',null,array('eid'=>$event_id))?>"  class="<?php echo $cd ?>">
        <?php echo _t('INTERVENTIONS') ?>
        </a>
        <a href="<?php get_url('events','coe_list',null,array('eid'=>$event_id))?>"  class="<?php echo $ce ?>">
        <?php echo _t('CHAIN_OF_EVENTS') ?>
        </a>
        <a href="<?php get_url('events','doc_list',null,array('eid'=>$event_id))?>"  class="<?php echo $cf ?>">
        <?php echo _t('DOCUMENTS') ?>
        </a>
        <a href="<?php get_url('events','audit', null ,array('eid'=>$event_id))?>" class="<?php echo $cg ?>" >
        <?php echo _t('AUDIT_LOG') ?>
        </a>
        <?php global $event; if($event->confidentiality == 'y'){ ?>
        <a href="<?php get_url('events','permissions', null,array('eid'=>$event_id))?>" class="permission last <?php echo $ch ?>" >
        <?php echo _t('PERMISSIONS') ?>
        </a>
        <?php } ?>
    </div>
<?php
}

