<?php

function _shn_get_incident_records()
{
    $res = Browse::getIncidentRecords($_GET['eid']);
    $person = new Person();

?>
    <table class="table table-bordered table-striped table-hover">
    <thead>
    <tr><th><?php echo _t('INCIDENT_TABLE')?></th></tr>
    <tr>
    <th><?php echo _t('VICTIM')?></th>
    <th><?php echo _t('ACT')?></th>
    <th><?php echo _t('PERPETRATOR')?></th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach($res as $record)
    {
    ?>
        <tr>
        <td><?php $person->LoadFromRecordNumber($record['victim']); 
        echo $person->name.' '.$person->other_names?></td>
        <td><?php echo get_mt_term($record['type_of_act']);?></td>
        <td><?php $person->LoadFromRecordNumber($record['perpetrator']);
        echo $person->name.' '.$person->other_names?></td>
        </tr>
    <?php } ?>
    </tbody></table>
    <?php
}

function _shn_get_victim_records()
{
//print($_GET['eid']);
    $res = Browse::getSourceRecords($_GET['eid']);
    $person = new Person();
    ?>
    <table class="table table-bordered table-striped table-hover">
    <thead>
    <tr><th><?php echo _t('VICTIM')?></th></tr>
    <tr>
    <th><?php echo _t('SOURCE')?></th>
    <th><?php echo _t('INTERVENTION')?></th>
    <th><?php echo _t('DATE')?></th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach($res as $record)
    {
        ?>
        <tr>
        <td><?php 
            $person->LoadFromRecordNumber($record['source']); 
            echo $person->name.' '.$person->other_names ?></td>
        <td><?php $person->LoadFromRecordNumber($record['intervening_party']); 
            echo $person->name.' '.$person->other_names?></td>
        <td><?php echo $records['date_of_intervention']; ?></td>
        </tr>
        <?php
    }
    ?>
    </tbody>
    </table>
<?php
}

function _shn_get_coe_records($eid)
{   
    $res = Browse::getEventCOE($eid);
    if(count($res)!= 0){
    
    ?>    
    <table class="table table-bordered table-striped table-hover">
    <thead>    
    <tr>
    <th><?php echo _t('THE_EVENT_IS_LINKED')?></th>
   
    </tr>
    </thead>
    <tbody>
    <?php
    foreach($res as $record)
    {
        ?>
        <tr>
        <td><?php echo " By ";?>
        <a href="<?php echo get_url('events','get_event',null,array('eid'=>$record['event'])); ?>"><?php echo $record['event_title']; ?></a>
        <?php echo " as "; ?>
        <?php echo "<strong>". get_mt_term($record['type_of_chain_of_events']) . "</strong>"; ?>
        </td>                
        </tr>
        <?php
    }
    ?>
    </tbody>
    </table>
<?php
    }
}
?>