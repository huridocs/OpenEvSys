<?php 
	include('tabs.php');
	include_once('event_title.php');
    include_once('card_list.php');
    
	draw_card_list('src',$event_id);
?>
<div class="panel">
<?php if($del_confirm){ ?>
    <div class='dialog confirm'>
    <h3><?php echo _t('DO_YOU_WANT_TO_DELETE_THE_SELECTED_INFORMATION_S__')?></h3>
    <form action="<?php get_url('events','delete_information')?>" method="post">
        <br />
        <center>
        <input type='submit' name='yes' value='<? echo _t('YES') ?>' />
        <input type='submit' name='no' value='<? echo _t('NO') ?>' />
        </center>
        <?php			
			foreach($_POST['informations'] as $val){ ?>
        		<input type='hidden' name='informations[]' value='<?php echo $val ?>' />
        <?php			 
			}
		?>
    </form>
    </div>

<table class='view'>
    <thead>
        <tr>			
            <td class="title"><?php echo _t('DATE_OF_SOURCE_MATERIAL')?></td>
            <td class="title"><?php echo _t('SOURCE_NAME')?></td>
            <td class="title"><?php echo _t('INFORMATION')?></td>
        </tr>
    </thead>
    <tbody>		
<?php 			
       		foreach($sources as $record){ ?>
        <tr class='<?php if($i++%2==1)echo "odd ";if($_GET['row']==$i)echo 'active'; ?>' >			
            <td><?php echo $record['date_of_source_material']; ?></td>
            <td><?php echo $record['person_name']; ?></td>
            <td><?php echo $record['connection']; ?></td>            
        </tr>
		
<?php 	
			}		
?>
	</tbody>
</table>
<?php 
	}		
?>
<br />
<center>
	<a class="but" href="<?php echo get_url('events','src_list',null,array('eid'=>$event_id)) ?>"><?php echo _t('BACK')?></a>
</center>
<br />
</div>
