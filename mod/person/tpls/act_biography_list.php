<?php include_once('tabs.php')?>
<?php include_once('person_name.php')?>
<?php
    include_once('view_card_list.php');
    draw_card_list('bd',$pid);	
?>
<div class="panel">
<a class="but" href="<?php echo get_url('person','new_biography',null,array('pid'=>$pid, 'search_type'=>'person')) ?>"><?php echo _t('ADD_BIOGRAPHIC_DETAILS')?></a>
<br />
<br />
<?php
	if(is_array($biographics) && count($biographics) !=0 ){
?>
<form action="<?php get_url('person','delete_biographic')?>" method="post">
<table class='view'>
    <thead>
        <tr>
			<td width='16px'><input type='checkbox' onchange='$("input.delete").attr("checked",this.checked)' /></td>
			<td class="title"><?php echo _t('TYPE_OF_RELATIONSHIP')?></td>           
            <td class="title"><?php echo _t('RELATED_PERSON')?></td>
            <td class="title"><?php echo _t('INITIAL_DATE')?></td>
            <td class="title"><?php echo _t('FINAL_DATE')?></td>
        </tr>
    </thead>
    <tbody>		
<?php 			
       		foreach($biographics as $bio){ 
?>
			<tr class='<?php if($i++%2==1)echo "odd ";if($_GET['biography_id']==$bio['biographic_details_record_number'])echo 'active'; ?>' >
			<td><input name="biographics[]" type='checkbox' value='<?php echo $bio['biographic_details_record_number'] ?>' class='delete'/></td>
            <td><a href="<?php echo get_url('person','biography_list',null,array('biography_id'=>$bio['biographic_details_record_number'], 'type'=>'bd')); ?>"><?php echo $bio['relationship_type']; ?></a></td>
			<td><a href="<?php echo get_url('person','biography_list',null,array('biography_id'=>$bio['biographic_details_record_number'], 'type'=>'rp')); ?>"><?php echo $bio['person_name']; ?></a></td>
			<td><?php echo $bio['initial_date']; ?></td>
			<td><?php echo $bio['final_date']; ?></td>            
        </tr>
<?php
			}		
?>   
		<tr class='actions'>
            <td colspan='8'><input type='submit' name='delete' value='<?php echo _t('DELETE') ?>' /></td>
        </tr>
	</tbody>
</table>
</form>
<?php
	}	
	else{
		echo "<div class='notice'>";
    	echo _t('THERE_IS_NO_BIOGRAPHIC_DETAILS_ABOUT_THIS_PERSON_YET__YOU_SHOULD_ADD_SOME_');
    	echo "</div>";
	}
?>
<div class="form-container">
<form action='<?php echo get_url('person','biography_list')?>' method='post' enctype='multipart/form-data'>
<?php
	if($_GET['type']=='bd'){
?>
<br />
<br />
<?php
	echo "<h3>" ._t('VIEW_BIOGRAPHIC_DETAILS') . "</h3>";
	echo "<br />";
?>
	<a class="but" href="<?php echo get_url('person','edit_biography',null,array('biography_id'=>$_GET['biography_id'])) ?>"><img src="<?php echo data_uri(APPROOT.'www/res/img/gtk-edit.png','image/png') ?>"> <?php echo _t('EDIT_THIS_BIOGRAPHIC_DETAILS')?></a>
	<br />
    <br />
<?php
		
    shn_form_get_html_labels($biography_form , false );	
	$fields['save'] = null;

	}
	if($_GET['type']=='rp'){
?>
<br />
<br />
<?php
	echo "<h3>" ._t('VIEW_RELATED_PERSON') . "</h3>";
?>
	<br />
	<a class="but" href="<?php echo get_url('person','person',null,array('pid'=>$biographic_details->related_person)) ?>"><?php echo _t('MORE_ABOUT_THIS_PERSON')?></a>
	<br />
<?php
	echo "<br />";		
	$fields['save'] = null;
    shn_form_get_html_labels($related_person_form , false );	
//	$person_form = person_form('view');
//	popuate_formArray($person_form , $person );
//    shn_form_get_html_labels($person_form , false );
	}
?>
</form>
</div>
</div>

