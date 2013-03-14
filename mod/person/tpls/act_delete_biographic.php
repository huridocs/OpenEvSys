<?php include_once('person_name.php')?>
<?php if($del_confirm){ ?>
    <div class='dialog confirm'>
    <h3><?php echo _t('DO_YOU_WANT_TO_DELETE_THE_SELECTED_BIOGRAPHIC_DETAILS_')?></h3>
    <form action="<?php get_url('person','delete_biographic')?>" method="post">
        <br />
        <center>
        <input type='submit' name='yes' value='<? echo _t('YES') ?>' />
        <input type='submit' name='no' value='<? echo _t('NO') ?>' />
        </center>
        <?php foreach($_POST['biographics'] as $val){ ?>
        <input type='hidden' name='biographics[]' value='<?php echo $val ?>' />
        <?php } ?>
    </form>
    </div>
<?php } ?>
<div id="browse">
    <form action="<?php get_url('person','browse')?>" method="get">
    <input type="hidden" name="mod" value="person" />
    <input type="hidden" name="act" value="browse" />
    <table class='view'>
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
	<a class="but" href="<?php echo get_url('person','biography_list',null,array('eid'=>$event_id)) ?>"><?php echo _t('BACK')?></a>
<br />
</div>
