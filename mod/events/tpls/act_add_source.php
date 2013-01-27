
<?php include_once('event_title.php'); ?>

<div class="panel">
<div class='flow'>
    <strong class='first'><?php echo _t('ADD_SOURCE')?></strong>
    <span><?php echo _t('ADD_INFORMATION')?></span>
    <span><?php echo _t('FINISH')?></span>
</div>
<br />
<h2><?php echo _t('WHO_IS_THE_SOURCE_') ?></h2>
<br />
<?php
if(isset($source)){
    $person_form = person_form('view');
    popuate_formArray($person_form , $source);
    shn_form_get_html_labels($person_form , false);
}
?>

<br />
<?php if( acl_is_entity_allowed_boolean('person','create' ) ){// if create is ever changed for this please update acl_person_entity_is_allowed() ?>
<a class="btn btn-primary" href="<?php echo get_url('events','add_source','new_source',array('eid'=>$event_id)) ?>"><i class="icon-plus icon-white"></i><?php echo _t('ADD_NEW')?></a><span>&nbsp;&nbsp;</span>
<?php } ?>
<?php if( acl_is_entity_allowed_boolean('person','read' ) ){// if read is ever changed for this please update acl_person_entity_is_allowed() ?>
<a class="btn" href="<?php echo get_url('events','add_source','search_source',array('eid'=>$event_id)) ?>"><i class="icon-search"></i> <?php echo _t('SEARCH_IN_DATABASE')?></a><span>&nbsp;&nbsp;</span>
<?php } ?>
<a class="btn" href="<?php echo get_url('events','src_list',null,array('eid'=>$event_id)) ?>"><?php echo _t('CANCEL')?></a><span>&nbsp;&nbsp;</span>
<?php if(isset($source)){ ?>
<a class="btn" href="<?php echo get_url('events','add_information',null,array('eid'=>$event_id)) ?>"><?php echo _t('CONTINUE')?></a><span>&nbsp;&nbsp;</span>
<?php } ?>

<br />
<br />
</div>
