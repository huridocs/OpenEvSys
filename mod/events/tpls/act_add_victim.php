
<?php include_once('event_title.php')?>

<div class="panel">
    <div class="fuelux">
        <div id="myWizard" class="wizard">
            <ul class="steps">
                <li class="active"><span class="badge badge-info">1</span><?php echo _t('ADD_VICTIM') ?><span class="chevron"></span></li>
                <li class=""><span class="badge ">2</span><?php echo _t('ADD_ACT') ?><span class="chevron"></span></li>
                <li><span class="badge ">3</span><?php echo _t('ADD_PERPETRATOR') ?><span class="chevron"></span></li>
                <li><span class="badge">4</span><?php echo _t('ADD_INVOLVEMENT') ?><span class="chevron"></span></li>
                <li><span class="badge">5</span><?php echo _t('FINISH') ?><span class="chevron"></span></li>
            </ul>
           
        </div>
    </div>

<br />
<h2><?php echo _t('WHO_IS_THE_VICTIM__') ?></h2>
<br />
<?php
if(isset($victim)){?>
<div class="control-group">
            <div class="controls">
                <a class="btn" href="<?php echo get_url('events','vp_list',null,array('eid'=>$event_id)) ?>"><i class="icon-remove-circle"></i> <?php echo _t('CANCEL')?></a>



<?php if( acl_is_entity_allowed_boolean('person','create' ) ){// if create is ever changed for this please update acl_person_entity_is_allowed() ?> 
<a class="btn" href="<?php echo get_url('events','add_victim','new_victim',array('eid'=>$event_id)) ?>"><i class="icon-plus "></i> <?php echo _t('ADD_NEW')?></a>
<?php  } ?>
<?php if( acl_is_entity_allowed_boolean('person','read' ) ){  // if read is ever changed for this please update acl_person_entity_is_allowed()?>
<a class="btn  <?php if(!isset($victim)){ echo "btn-primary";  }?>" href="<?php echo get_url('events','add_victim','search_victim',array('seq'=>'search','eid'=>$event_id)) ?>"><i class="icon-search <?php if(!isset($victim)){ echo "icon-white";  }?>"></i> <?php echo _t('SEARCH_IN_DATABASE')?></a>
<?php } ?>
<?php if(isset($victim)){ ?>
<a class="btn  btn-primary" href="<?php echo get_url('events','add_act',null,array('eid'=>$event_id, 'victim'=>$victim->person_record_number)) ?>"><i class="icon-chevron-right icon-white"></i> <?php echo _t('NEXT')?></a>
<?php } ?> 
            </div></div>
<?php
    $person_form = person_form('view');
    popuate_formArray($person_form , $victim);
    shn_form_get_html_labels($person_form , false);
}
?>

    <div class="control-group">
            <div class="controls">
                <a class="btn" href="<?php echo get_url('events','vp_list',null,array('eid'=>$event_id)) ?>"><i class="icon-remove-circle"></i> <?php echo _t('CANCEL')?></a>



<?php if( acl_is_entity_allowed_boolean('person','create' ) ){// if create is ever changed for this please update acl_person_entity_is_allowed() ?> 
<a class="btn" href="<?php echo get_url('events','add_victim','new_victim',array('eid'=>$event_id)) ?>"><i class="icon-plus "></i> <?php echo _t('ADD_NEW')?></a>
<?php  } ?>
<?php if( acl_is_entity_allowed_boolean('person','read' ) ){  // if read is ever changed for this please update acl_person_entity_is_allowed()?>
<a class="btn  <?php if(!isset($victim)){ echo "btn-primary";  }?>" href="<?php echo get_url('events','add_victim','search_victim',array('seq'=>'search','eid'=>$event_id)) ?>"><i class="icon-search <?php if(!isset($victim)){ echo "icon-white";  }?>"></i> <?php echo _t('SEARCH_IN_DATABASE')?></a>
<?php } ?>
<?php if(isset($victim)){ ?>
<a class="btn  btn-primary" href="<?php echo get_url('events','add_act',null,array('eid'=>$event_id, 'victim'=>$victim->person_record_number)) ?>"><i class="icon-chevron-right icon-white"></i> <?php echo _t('NEXT')?></a>
<?php } ?> 
            </div></div>
</div>
