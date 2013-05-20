<?php
	function draw_person_role_list($records,$person_role,$further_info_msg,$person_type)
	{
		if($records != null && $records->RecordCount()){
?>                   
        <?php $i=0;
           foreach($records as $record){ ?>
            <tr <?php echo ($i++%2==1)?'class="odd"':''; ?>>                
                <td><?php echo $person_role; ?></td>
                <td><a href="<?php echo get_url('events','get_event',null,array('eid'=>$record['event']))?>"><?php echo $record['event_title'] ?></a></td>
<?php
	switch($person_type){
		case 'victim':
			echo "<td><a href='index.php?mod=events&act=vp_list&eid=".$record['event']."&type=act&act_id=".$record['record_number']."'>" . $further_info_msg . $record['further_infor']."</a></td>";
			break;
		case 'perpetrator':
			echo "<td><a href='index.php?mod=events&act=vp_list&eid=".$record['event']."&type=inv&inv_id=".$record['record_number']."'>" . $further_info_msg . $record['further_infor']."</a></td>";
			break;

		case 'source':
			echo "<td><a href='index.php?mod=events&act=src_list&eid=".$record['event']."&type=information&information_id=".$record['record_number']."'>" . $further_info_msg . $record['further_infor']."</a></td>";
			break;
		case 'intervening_party':
			echo "<td><a  href='index.php?mod=events&act=intv_list&eid=".$record['event']."&type=intv&intv_id=".$record['record_number']."'>" . $further_info_msg . $record['further_infor']."</a></td>";
			break;
	}
?>     
                <td><?php echo $record['initial_date']; ?> </td>            
                <td><?php echo $record['final_date'];?> </td>                
            </tr>
    <?php 
			} 
		}
	}
?>