<?php include_once('tabs.php')?>
<?php include_once('person_name.php')?>
<?php
    include_once('view_card_list.php');
    draw_card_list('bd',$pid);	
?>
<div class="panel">
<a class="btn btn-primary" href="<?php echo get_url('person','new_biography',null,array('pid'=>$pid, 'search_type'=>'person')) ?>"><i class="icon-plus icon-white"></i> <?php echo _t('ADD_BIOGRAPHIC_DETAILS')?></a>
<br />
<br />
<?php
	if(is_array($biographics) && count($biographics) !=0 ){
?>
<form class="form-horizontal"  action="<?php get_url('person','delete_biographic')?>" method="post">
<table class='table table-bordered table-striped table-hover'>
    <thead>
        <tr>
			<th width='16px'><input type='checkbox' onchange='$("input.delete").attr("checked",this.checked)' /></th>
			<th class="title"><?php echo _t('VIEW_BIOGRAPHIC_DETAILS')?></th>  
			<th class="title"><?php echo _t('TYPE_OF_RELATIONSHIP')?></th>           
            <th class="title"><?php echo _t('RELATED_PERSON')?></th>
            <th class="title"><?php echo _t('INITIAL_DATE')?></th>
            <th class="title"><?php echo _t('FINAL_DATE')?></th>
        </tr>
    </thead>
    <tbody>		
<?php 			
       		foreach($biographics as $bio){ 
?>
			<tr class='<?php if($i++%2==1)echo "odd ";if($_GET['biography_id']==$bio['biographic_details_record_number'])echo 'active'; ?>' >
			<td><input name="biographics[]" type='checkbox' value='<?php echo $bio['biographic_details_record_number'] ?>' class='delete'/></td>
			<td><a href="<?php echo get_url('person','biography_list',null,array('biography_id'=>$bio['biographic_details_record_number'], 'type'=>'bd')); ?>"><?php echo $bio['biographic_details_record_number']; ?></a></td>
            <td><a href="<?php echo get_url('person','biography_list',null,array('biography_id'=>$bio['biographic_details_record_number'], 'type'=>'bd')); ?>"><?php echo $bio['relationship_type']; ?></a></td>
			<td><a href="<?php echo get_url('person','biography_list',null,array('biography_id'=>$bio['biographic_details_record_number'], 'type'=>'rp')); ?>"><?php echo $bio['person_name']; ?></a></td>
			<td><?php echo $bio['initial_date']; ?></td>
			<td><?php echo $bio['final_date']; ?></td>            
        </tr>
<?php
			}		
?>   
		<tr class='actions'>
            <td colspan='8'><button type='submit' class='btn btn-danger' name='delete' >
<i class="icon-trash icon-white"></i> <?php echo _t('DELETE') ?></button>
          </td>
        </tr>
	</tbody>
</table>
</form>
<?php
	}	
	else{
		echo '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">×</button>';
    	echo _t('THERE_IS_NO_BIOGRAPHIC_DETAILS_ABOUT_THIS_PERSON_YET__YOU_SHOULD_ADD_SOME_');
    	echo "</div>";
	}
?>
<div class="form-container">
<form class="form-horizontal"  action='<?php echo get_url('person','biography_list')?>' method='post' enctype='multipart/form-data'>
<?php
	if($_GET['type']=='bd'){
?>
<br />
<br />
<?php
	echo "<h3>" ._t('VIEW_BIOGRAPHIC_DETAILS') . "</h3>";
	echo "<br />";
?>
	<a class="btn" href="<?php echo get_url('person','edit_biography',null,array('biography_id'=>$_GET['biography_id'])) ?>"><i class="icon-edit"></i> <?php echo _t('EDIT_THIS_BIOGRAPHIC_DETAILS')?></a>
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
	<a class="btn" href="<?php echo get_url('person','person',null,array('pid'=>$biographic_details->related_person)) ?>"><i class="icon-zoom-in"></i><?php echo _t('MORE_ABOUT_THIS_PERSON')?></a>
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

