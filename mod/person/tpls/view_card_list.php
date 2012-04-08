<?php

function draw_card_list($active,$pid){
    $breadcrumbs = shnBreadcrumbs::getBreadcrumbs();
    switch($active){
        case 'pd':			
			$pd = "active";			
            $breadcrumbs->pushCrumb(array('name'=>_t('PERSON_RECORD_S_'),'mod'=>'person','act'=>'person'),2);
			break;
        case 'bd':
			$bd = "active";			            
            $breadcrumbs->pushCrumb(array('name'=>_t('BIOGRAPHIC_DETAIL_S_'),'mod'=>'person','act'=>'biography_list'),2);
            break;
		case 'rl':			
			$rl = "active";			            
            $breadcrumbs->pushCrumb(array('name'=>_t('ROLE_LIST'),'mod'=>'person','act'=>'role_list'),2);
            break;
		case 'al':			
			$al = "active";			            
            $breadcrumbs->pushCrumb(array('name'=>_t('AUDIT_LOG'),'mod'=>'person','act'=>'audit_log'),2);
            break;
       	case 'pa':			
			$pa = "active";			            
            $breadcrumbs->pushCrumb(array('name'=>_t('PERSON_ADDRESS_ES_'),'mod'=>'person','act'=>'address_list'),2);
            break;
       	case 'pe':			
			$pe = "active";			            
            $breadcrumbs->pushCrumb(array('name'=>_t('PERMISSIONS'),'mod'=>'person','act'=>'permissions'),2);
            break;
    }
?>
    <div class="card_list">
        <a href="<?php get_url('person','person', null)?> " class="first <?php echo $pd ?>" >
            <?php echo _t('PERSON_RECORDS_S_') ?>
        </a>
        <a href="<?php get_url('person','address_list', null)?> " class="first <?php echo $pa ?>" >
            <?php echo _t('PERSON_ADDRESS_ES_') ?>
        </a>
        <a href="<?php get_url('person','biography_list',null)?>" class="<?php echo $bd ?>">
        <?php echo _t('BIOGRAPHIC_DETAIL_S_') ?>
        </a>
		<a href="<?php get_url('person','role_list',null)?>" class="<?php echo $rl ?>" >
        <?php echo _t('ROLE_LIST') ?>
        </a>
		<a href="<?php get_url('person','audit_log',null)?>" class="<?php echo $al ?>" >
        <?php echo _t('AUDIT_LOG') ?>
        </a>
        <?php global $person; if($person->confidentiality == 'y'){ ?>
 		<a href="<?php get_url('person','permissions',null)?>" class="<?php echo $pe ?> permission" >
        <?php echo _t('PERMISSONS') ?>
        </a>        
        <?php } ?>
    </div>
<?php
}

