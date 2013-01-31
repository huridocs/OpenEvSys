<?php 
    include_once('tabs.php');
?><br />
<center class='phide'>
    <?php echo _t('PRINTABLE_VIEW') ?> 
    :: 
    <a href='JavaScript:window.print();' class="btn" ><i class="icon-print"></i> <?php echo _t('PRINT_THIS_PAGE')?></a>
    :: 
    <a href='<?php get_url('person','person',null,array('pid'=>$_GET['pid'])) ?>' class="btn"><?php echo _t('BACK_TO_PERSON_VIEW') ?></a>
</center>
<?php     
    include_once('person_name.php');
//print the event summary 
    $person_form = generate_formarray('person','view');
    popuate_formArray($person_form , $person);
    shn_form_get_html_labels($person_form , false);
?>
<br />
<?php
if(isset($biographics)){
    echo '<h2>'._t('BIOGRAPHIC_DETAILS').'</h2>';
    ?>
    <br />
    <table class='print'>
        <thead>
            <tr>
                <td></td>
                <td class="title"><?php echo _t('TYPE_OF_RELATIONSHIP')?></td>
                <td class="title"><?php echo _t('RELATED_PERSON') ?></td>
                <td class="title"><?php echo _t('INITIAL_DATE')?></td>
                <td class="title"><?php echo _t('FINAL_DATE')?></td>
            </tr>
        </thead>
        <tbody>
    <?php 			
            foreach($biographics as $key=>$record){ 
    ?>
            <tr class='<?php if($i++%2==1)echo "odd ";if($_GET['row']==$i)echo 'active'; ?>' >
                <td><?php echo ++$key ?></td>
                <td><?php echo $record['relationship_type']; ?></td>
                <td><?php echo $record['name']; ?></td>
                <td><?php echo $record['initial_date']; ?></td>            
                <td><?php echo $record['final_date']; ?></td>            
            </tr>		
    <?php 	
            }		
    ?> 
        </tbody>
    </table>
    <?php

    $bio_form = generate_formarray('biographic_details','view');
    $bio = new BiographicDetail();

    foreach($biographics as $key=>$record){ 
        echo '<br /><h3>'.++$key.'. '._t('PERSON_NAME').' : '.$record['name'].'</h3><br />';
        //print victim details
        $person->LoadFromRecordNumber($record['related_person']);
        $person->LoadRelationships();
        $bio->LoadFromRecordNumber($record['biographic_details_record_number']);
        $bio->LoadRelationships();
        popuate_formArray($bio_form , $bio);
        shn_form_get_html_labels($bio_form, false);
        echo '<br />';
    } 
}




