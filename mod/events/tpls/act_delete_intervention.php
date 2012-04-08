<?php 
	include_once('tabs.php');
	include_once('event_title.php');
    include_once('card_list.php');

    draw_card_list('int',$event_id);
?>
<div class="panel">
<?php if($del_confirm){ ?>
    <div class='dialog confirm'>
    <h3><?php echo _t('DO_YOU_WANT_TO_DELETE_THE_SELECTED_INTERVENTION_S__')?></h3>
    <form action="<?php get_url('events','delete_intervention')?>" method="post">
        <br />
        <center>
        <input type='submit' name='yes' value='<? echo _t('YES') ?>' />
        <input type='submit' name='no' value='<? echo _t('NO') ?>' />
        </center>
        <?php			
			foreach($_POST['interventions'] as $val){ ?>
        		<input type='hidden' name='interventions[]' value='<?php echo $val ?>' />
        <?php			 
			}
		?>
    </form>
    </div>
<br />
<table class="view">
    <thead>
        <tr>
            <td><?php echo _t('DATE_OF_INTERVENTION') ?></td>
            <td><?php echo _t('INTERVENING_PARTY') ?></td>
            <td><?php echo _t('TYPE_OF_INTERVENTION') ?></td>

        </tr>
    </thead>
    <tbody>
    <?php foreach($intv_list as $record){ ?>
        <tr class='<?php if($i++%2==1)echo "odd ";if($_GET['row']==$i)echo 'active'; ?>' >
            <td><?php echo $record['date_of_intervention'] ?></td>
            <td><?php echo $record['name']?></td>
            <td><?php echo get_mt_term($record['type_of_intervention'])?></td>            
        </tr>
    <?php } ?>
    </tbody>
</table>
<?php 
	}		
?>
<center>
	<a class="but" href="<?php echo get_url('events','intv_list',null,array('eid'=>$event_id)) ?>"><?php echo _t('BACK')?></a>
</center>
<br />
</div>
