<?php 
	include('tabs.php');
	include_once('event_title.php');
    include_once('card_list.php');
    
	draw_card_list('vp',$event_id);
?>
<div class="panel">
<?php
	if(is_array($vp_list) && count($vp_list) != 0){ 
	if($del_confirm){ ?>
    <div class='dialog confirm'>
    <h3><?php echo _t('DO_YOU_WANT_TO_DELETE_THE_SELECTED_RECORD_S___')?></h3>
    <form action="<?php get_url('events','delete_act')?>" method="post">
        <br />
        <center>
        <input type='submit' name='yes' value='<? echo _t('YES') ?>' />
        <input type='submit' name='no' value='<? echo _t('NO') ?>' />
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
<table class="view">
    <thead>
        <tr>			
            <td><?php echo _t('INITIAL_DATE') ?></td>
            <td><?php echo _t('VICTIM_NAME') ?></td>
            <td><?php echo _t('TYPE_OF_ACT') ?></td>
            <td><?php echo _t('PERPETRATOR_NAME_S_') ?></td>
            <td><?php echo _t('INVOLVEMENT') ?></td>
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
	<a class="but" href="<?php echo get_url('events','vp_list',null,array('eid'=>$event_id)) ?>"><?php echo _t('BACK')?></a>
</center>
<br />
</div>
