
<?php include_once('event_title.php') ?>

<div class="panel">
<?php include_once('src_list_table.php'); ?>
    <br />
	<br />
    <h3><?php echo _t('EDIT_THIS_PERSON'); ?></h3>    
    <div class="form-container"> 
        <form class="form-horizontal"  id="person_form" name="person_form" action='<?php echo get_url('events','edit_source',null,array('information_id'=>$_GET['information_id'],'eid'=>$event_id))?>' method='post' enctype='multipart/form-data'>
        <?php			
			place_form_elements($person_form,$fields);			
		?>              
		<center>
		<?php echo $fields['update']; ?>
        <a class="btn" href="<?php echo get_url('events','src_list',null,array('eid'=>$event_id,'information_id'=>$_GET['information_id'],'type'=>'person')) ?>"><?php echo _t('CANCEL')?></a>
        </center>        
        </form>
    </div>
</div>
