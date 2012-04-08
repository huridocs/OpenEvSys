<?php include('tabs.php')?>
<?php include_once('event_title.php')?>
<?php
    include_once('card_list.php');
    draw_card_list('src',$event_id);
?>
<div class="panel">
<?php include_once('src_list_table.php'); ?>
<?php if(isset($_GET['type'])){ ?>
<br/>
<br/>
<?php
	switch($_GET['type']){
		case 'person':			
			echo "<h3>" ._t('VIEW_SOURCE_RECORD') . "</h3>";
            echo '<br />';
?>
			<a class="but" href="<?php echo get_url('events','edit_source',null,array('information_id'=>$_GET['information_id'],'pid'=>$source->person_record_number)) ?>"><img src="<?php echo data_uri(APPROOT.'www/res/img/gtk-edit.png','image/png') ?>"> <?php echo _t('EDIT_THIS_PERSON')?></a>
			<a class="but" href="<?php echo get_url('person','person',null,array('pid'=>$source->person_record_number)) ?>"><?php echo _t('MORE_ABOUT_THIS_PERSON')?></a><?php			
            echo '<br />';
            echo '<br />';
            $source_form = person_form('view');
            popuate_formArray($source_form,$source);
            shn_form_get_html_labels($source_form,false);
			break;
		case 'information':			
			echo "<h3>" ._t('VIEW_INFORMATION_RECORD') . "</h3>";
?>	
			<br />
			<a class="but" href="<?php echo get_url('events','edit_information',null,array('eid'=>$event_id,'information_id'=>$_GET['information_id'])) ?>"><?php echo _t('EDIT_THIS_INFORMATION')?></a>
			<br /><br />
<?php	
            $information_form = generate_formarray('information','view');
            popuate_formArray($information_form,$information);
            shn_form_get_html_labels($information_form,false);				
			break;
	}
?>
<?php } ?>
</div>
