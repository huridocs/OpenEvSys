<?php 
	include_once('event_title.php');
    
?>
<div class="panel">
<?php
	if(is_array($vp_list) && count($vp_list) != 0){ 
	if($del_confirm){ ?>
    <div class="alert alert-error" >
    <h3><?php echo _t('DO_YOU_WANT_TO_DELETE_THE_SELECTED_RECORD_S___')?></h3>
    <form class="form-horizontal"  action="<?php get_url('events','delete_act')?>" method="post">
        <br />
        <center>
                  <button type='submit' class='btn btn-danger' name='yes' ><i class="icon-trash icon-white"></i> <?php echo _t('DELETE') ?></button>
        <button type='submit' class='btn' name='no' ><i class="icon-stop"></i> <?php echo _t('CANCEL') ?></button>
     
       </center>
        <?php
			if(isset($_POST['acts'])){
				foreach($_POST['acts'] as $val){ ?>
        			<input type='hidden' name='acts[]' value='<?php echo $val ?>' />
        		<?php
				}
			}
			else if (isset($_POST['invs'])){
				foreach($_POST['invs'] as $val){ ?>
        			<input type='hidden' name='invs[]' value='<?php echo $val ?>' />
        		<?php
				}
			}			
		?>
    </form>
    </div>
<?php
	//if(is_array($vp_list) && count($vp_list) != 0){
?>
<table class="table table-bordered table-striped table-hover">
    <thead>
        <tr>			
            <th><?php echo _t('INITIAL_DATE') ?></th>
            <th><?php echo _t('VICTIM_NAME') ?></th>
            <th><?php echo _t('TYPE_OF_ACT') ?></th>
            <th><?php echo _t('PERPETRATOR_NAME_S_') ?></th>
            <th><?php echo _t('INVOLVEMENT') ?></th>
        </tr>
    </thead>
    <tbody>	
    <?php foreach($vp_list as $record){ ?>
        <tr class='<?php if($i++%2==1)echo "odd ";if($_GET['row']==$i)echo 'active'; ?>' >
			<td><?php echo $record['initial_date'] ?></td>
            <td><?php echo $record['vname']?></td>
            <td><?php echo get_mt_term($record['type_of_act'])?></td>
            <td><?php echo $record['pname']?></td>
            <td><?php echo get_mt_term($record['degree_of_involvement'])?></td>
        </tr>
    <?php } ?>		
    </tbody>
	</table>
<?php
		} 
	}
	else{
		shnMessageQueue::addInformation(_t('NO_RECORDS_WERE_SELECTED_'));
        echo shnMessageQueue::renderMessages();
	}		
?>
<center>
	<a class="btn" href="<?php echo get_url('events','vp_list',null,array('eid'=>$event_id)) ?>"><?php echo _t('BACK')?></a>
</center>
<br />
</div>
