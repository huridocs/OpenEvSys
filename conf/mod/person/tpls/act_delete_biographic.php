<div class="panel">
<?php if($del_confirm){ ?>
    <div class="alert alert-error">
     
    <h3><?php echo _t('DO_YOU_WANT_TO_DELETE_THE_SELECTED_BIOGRAPHIC_DETAILS_')?></h3>
    <form class="form-horizontal"  action="<?php get_url('person','delete_biographic')?>" method="post">
        <br />
        <center>
             <button type='submit' class='btn btn-grey' name='yes' ><i class="icon-trash"></i> <?php echo _t('DELETE') ?></button>
        <button type='submit' class='btn' name='no' ><i class="icon-remove-circle"></i> <?php echo _t('CANCEL') ?></button>
       
        </center>
        <?php foreach($_POST['biographics'] as $val){ ?>
        <input type='hidden' name='biographics[]' value='<?php echo $val ?>' />
        <?php } ?>
    </form>
    </div>
<?php } ?>

    <form class="form-horizontal"  action="<?php get_url('person','browse')?>" method="get">
    <input type="hidden" name="mod" value="person" />
    <input type="hidden" name="act" value="browse" />
    <table class='table table-bordered table-striped table-hover'>
    <thead>
        <tr>
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
			<tr class='<?php if($i++%2==1)echo "odd ";if($_GET['row']==$i)echo 'active'; ?>' >
            <td><?php echo $bio['relationship_type']; ?></td>
			<td><?php echo $bio['person_name']; ?></td>
			<td><?php echo $bio['initial_date']; ?></td>
			<td><?php echo $bio['final_date']; ?></td>            
        </tr>
<?php
			}		
?>   
	</tbody>
</table>
    </form>
<br />
	<a class="btn" href="<?php echo get_url('person','biography_list',null,array('eid'=>$event_id)) ?>"><i class="icon-chevron-left"></i> <?php echo _t('BACK')?></a>
<br />
</div>