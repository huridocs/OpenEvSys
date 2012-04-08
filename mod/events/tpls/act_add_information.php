<?php include_once('tabs.php') ?>
<?php include_once('event_title.php'); ?>
<?php
    include_once('card_list.php');
    draw_card_list('src',$event_id);
?>
<div class="panel">
    <div class='flow'>
        <span class="over first"><?php echo _t('ADD_SOURCE')?></span>
        <strong><?php echo _t('ADD_INFORMATION')?></strong>
        <span><?php echo _t('FINISH')?></span>
    </div>    
    <br />
    <h3><?php echo _t('INFORMATION_ABOUT'). "[". $event->event_title ."]"; ?></h3>
    <br />
    <div class="form-container"> 
        <form id="information" name="information" action='<?php echo get_url('events','add_information',null)?>' method='post' enctype='multipart/form-data'>
        <?php            
            $fields = shn_form_get_html_fields($information_form);
            place_form_elements($information_form,$fields);
        ?>
        <br />
        <center>
        <a class="but" href="<?php echo get_url('events','add_source',null,array('person_id'=> $_SESSION['src']['source'])) ?>"><?php echo _t('PREVIOUS')?></a><span>&nbsp;&nbsp;</span>
           <a class="but" href="<?php echo get_url('events','add_source',null) ?>"><?php echo _t('CANCEL')?></a><span>&nbsp;&nbsp;</span>
           <?php echo $fields['finish'];?>
        </center>
        </form>
    </div>
</div>
