<?php include_once('tabs.php')?>
<?php include_once('event_title.php'); ?>
<?php
    include_once('card_list.php');
    draw_card_list('vp',$event_id);
?>
<div class="panel">
    <br />
    <?php include_once('vp_list_table.php');?>
    <br />
    <?php 
    echo "<h3>"._t('EDIT_THIS_PERPETRATOR')."</h3>&nbsp;";
    ?>
    <div class="form-container">
    <form id="person_form" name="person_form" action='<?php echo get_url('events','edit_perpetrator',null,array('eid'=>$event_id,'inv_id'=>$_GET['inv_id']))?>' method='post' enctype='multipart/form-data'>
    <?php       
        place_form_elements($person_form,$fields); 
	?>
	<center>
	<?php echo $fields['update']; ?>
    <a class="but" href="<?php echo get_url('events','vp_list',null,array('eid'=>$event_id,'inv_id'=>$_GET['inv_id'],'row'=>$_GET['row'],'type'=>'perter')) ?>"><?php echo _t('CANCEL')?></a> <span>&nbsp;</span>
    </center>        
    </form>
    </div>
    <br />
</div>
