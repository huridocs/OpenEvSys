<?php include_once('tabs.php'); ?>
<br />
<center class='phide'>
    <?php echo _t('PRINTABLE_VIEW') ?>
    ::
    <a href='JavaScript:window.print();' ><?php echo _t('PRINT_THIS_PAGE')?></a>
    ::
    <a href='<?php get_url('events','get_event') ?>'><?php echo _t('BACK_TO_EVENT_VIEW') ?></a>
</center>
<?php include_once('event_title.php');?>

<?php 
//print the event summary 
    $event_form = generate_formarray('event','view');
    popuate_formArray($event_form , $event);
    shn_form_get_html_labels($event_form , false);

echo "<br class='page_break' />";

if(isset($vp_list)){
    //print the victim and perpotraters
    echo '<h2>'._t('VICTIMS_AND_PERPETRATORS').'</h2>';
    ?>
    <br />
     <form action="<?php get_url('events','delete_act')?>" method="post">
    <table class='print'>
        <thead>
            <tr>
                <td width="10pt"></td>
                <td><?php echo _t('VICTIM_NAME') ?></td>
                <td><?php echo _t('TYPE_OF_ACT') ?></td>
                <td><?php echo _t('PERPETRATOR_NAME_S_') ?></td>
                <td><?php echo _t('INVOLVEMENT') ?></td>
            </tr>
        </thead>
        <tbody>       
        <?php foreach($vp_list as $key=>$record){ ?>
            <tr class='<?php if($i++%2==1)echo "odd ";if($_GET['row']==$i)echo 'active'; ?>' >
                <td><?php echo ++$key ?></td>
                <td><?php echo $record['vname']?></td>
                <td><?php echo get_mt_term($record['type_of_act'])?></td>
                <td><?php echo $record['pname']?></td>
                <td><?php echo get_mt_term($record['degree_of_involvement'])?></td>
            </tr>
        <?php } ?>
            
        </tbody>
    </table>
    </form>
    <br />
    <?php
    $person_form = generate_formarray('person','view');
    $act_form = generate_formarray('act','view');
    $inv_form = generate_formarray('involvement','view');
    $person = new Person();
    $act = new Act();
    $inv = new Involvement();
    //travers through 
    foreach($vp_list as $key=>$record){ 
        echo '<br /><h3>'._t('ACT_').++$key.' : '.get_mt_term($record['type_of_act']).'</h3><br />';
        //print victim details
        echo '<h4>'._t('VICTIM').' : '.$record['vname'].'</h4><br />';
        $person->LoadFromRecordNumber($record['victim_record_number']);
        $person->LoadRelationships();
        popuate_formArray($person_form , $person);
        shn_form_get_html_labels($person_form, false);
        echo "<br class='page_break' />";
        //print act details
        echo '<h4>'._t('ACT_DETAILS').' : '.get_mt_term($record['type_of_act']).'</h4><br />';
        $act->LoadFromRecordNumber($record['act_record_number']);
        $act->LoadRelationships();
        popuate_formArray($act_form , $act);
        shn_form_get_html_labels($act_form, false);
        echo "<br class='page_break' />";
        //print perpetrator details
        if(isset($record['perpetrator_record_number'])){
            echo '<h4>'._t('PERPETRATOR').' : '.$record['pname'].'</h4><br />';
            $person->LoadFromRecordNumber($record['perpetrator_record_number']);
            $person->LoadRelationships();
            popuate_formArray($person_form , $person);
            shn_form_get_html_labels($person_form, false);
            echo "<br class='page_break' />";
        }
        //print involvement details
        if(isset($record['involvement_record_number'])){
            echo '<h4>'._t('INVOLVEMENT_DETAILS').' : '.get_mt_term($record['degree_of_involvement']).'</h4><br />';
            $inv->LoadFromRecordNumber($record['involvement_record_number']);
            $inv->LoadRelationships();
            popuate_formArray($inv_form , $inv);
            shn_form_get_html_labels($inv_form, false);
            echo "<br class='page_break' />";
        }
    }
}




//print out source
if(isset($sources)){
    echo '<h2>'._t('SOURCES').'</h2>';
    ?>
    <table class='print'>
        <thead>
            <tr>
                <td></td>
                <td class="title"><?php echo _t('DATE_OF_SOURCE_MATERIAL')?></td>
                <td class="title"><?php echo _t('SOURCE_NAME') ?></td>
                <td class="title"><?php echo _t('INFORMATION')?></td>
            </tr>
        </thead>
        <tbody>
    <?php 			
            foreach($sources as $key=>$record){ 
    ?>
            <tr class='<?php if($i++%2==1)echo "odd ";if($_GET['row']==$i)echo 'active'; ?>' >
                <td><?php echo ++$key ?></td>
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

    $src_form = generate_formarray('information','view');
    $src = new Information();

    foreach($sources as $key=>$record){ 
        echo '<br /><h3>'._t('SOURCE').++$key.' : '.$record['person_name'].'</h3><br />';
        //print victim details
        $person->LoadFromRecordNumber($record['related_person']);
        $person->LoadRelationships();
        popuate_formArray($person_form , $person);
        shn_form_get_html_labels($person_form, false);
        echo "<br class='page_break' />";
        //print act details
        echo '<h4>'._t('INFORMATION').' : '.$record['connection'].'</h4><br />';
        $src->LoadFromRecordNumber($record['information_record_number']);
        $src->LoadRelationships();
        popuate_formArray($src_form , $src);
        shn_form_get_html_labels($src_form, false);
        echo "<br class='page_break' />";
    } 
}





//print out interventions
if(isset($intv_list)){
    echo '<h2>'._t('INTERVENTIONS').'</h2>';
    ?>
    <table class="print">
        <thead>
            <tr>
                <td></td>
                <td><?php echo _t('DATE_OF_INTERVENTION') ?></td>
                <td><?php echo _t('INTERVENING_PARTY') ?></td>
                <td><?php echo _t('TYPE_OF_INTERVENTION') ?></td>

            </tr>
        </thead>
        <tbody>
        <?php foreach($intv_list as $key=>$record){ ?>
            <tr class='<?php if($i++%2==1)echo "odd ";if($_GET['row']==$i)echo 'active'; ?>' >
                <td><?php echo ++$key ?></td>
                <td><?php echo $record['date_of_intervention'] ?></td>
                <td><?php echo $record['person_name']?></td>
                <td><?php echo get_mt_term($record['type_of_intervention'])?></td>
                
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <?php
    $intv_form = generate_formarray('intervention','view');
    $intv = new Intervention();

    foreach($intv_list as $key=>$record){ 
        echo '<br /><h3>'._t('INTERVENING_PARTY').++$key.' : '.$record['person_name'].'</h3><br />';
        //print victim details
        $person->LoadFromRecordNumber($record['intervening_party']);
        $person->LoadRelationships();
        popuate_formArray($person_form , $person);
        shn_form_get_html_labels($person_form, false);
        echo "<br class='page_break' />";
        //print act details
        echo '<h4>'._t('INTERVENTION').' : '.get_mt_term($record['type_of_intervention']).'</h4><br />';
        $intv->LoadFromRecordNumber($record['intervention_record_number']);
        $intv->LoadRelationships();
        popuate_formArray($intv_form , $intv);
        shn_form_get_html_labels($intv_form, false);
        echo "<br class='page_break' />";
    }
}



//print out chane of events
if(isset($related_events)){
    echo '<h2>'._t('CHANE_OF_EVENTS').'</h2>';
?>
    <table class="print">
        <thead>
            <tr>
				<td></td>
                <td><?php echo _t('INITIAL_DATE') ?></td>
                <td><?php echo _t('RELATED_EVENT_TITLE') ?></td>
                <td><?php echo _t('TYPE_OF_CHAIN_OF_EVENTS') ?></td>
            </tr>
        </thead>
        <tbody>
            <?php foreach($related_events as $key=>$record){ ?>
            <tr <?php if($i++%2==1)echo "class='odd'" ?>>
                <td><?php echo ++$key?></td>
                <td><?php echo $record['initial_date']; ?></td>
                <td><?php echo $record['event_title'];?></td>
                <td><?php echo get_mt_term($record['type_of_chain_of_events']); ?></td>            
            </tr>	

            <?php }?>
        </tbody>
    </table>
<?php
    $coe_form = generate_formarray('chain_of_events','view');
    $coe = new ChainOfEvents();

    foreach($related_events as $key=>$record){ 
        echo '<br /><h3>'._t('CHANE_OF_EVENTS_').++$key.' : '.get_mt_term($record['type_of_chain_of_events']).'</h3>';
        echo '<h3>'._t('RELATED_EVENT').$key.' : '.$record['event_title'].'</h3><br />';
        //print victim details
        $coe->LoadFromRecordNumber($record['coe_id']);
        $coe->LoadRelationships();
        popuate_formArray($coe_form , $coe);
        shn_form_get_html_labels($coe_form, false);
        echo "<br class='page_break' />";
    } 
}
