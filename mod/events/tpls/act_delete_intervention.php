<?php 
	
	include_once('event_title.php');
   
?>
<div class="panel">
<?php if($del_confirm){ ?>
    <div class="alert alert-error" >
    <h3><?php echo _t('DO_YOU_WANT_TO_DELETE_THE_SELECTED_INTERVENTION_S__')?></h3>
    <form class="form-horizontal"  action="<?php get_url('events','delete_intervention')?>" method="post">
        <br />
        <center>
              <button type='submit' class='btn btn-danger' name='yes' ><i class="icon-trash icon-white"></i> <?php echo _t('DELETE') ?></button>
        <button type='submit' class='btn' name='no' ><i class="icon-stop"></i> <?php echo _t('CANCEL') ?></button>
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
<table class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th><?php echo _t('DATE_OF_INTERVENTION') ?></th>
            <th><?php echo _t('INTERVENING_PARTY') ?></th>
            <th><?php echo _t('TYPE_OF_INTERVENTION') ?></th>

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
	<a class="btn" href="<?php echo get_url('events','intv_list',null,array('eid'=>$event_id)) ?>"><?php echo _t('BACK')?></a>
</center>
<br />
</div>
