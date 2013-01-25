
<?php include_once('event_title.php')?>

<div class="panel">
<?php include_once('intv_list_table.php'); ?>
<?php if(isset($_GET['type'])){ ?>
<br />
<br />
<?php
    switch($_GET['type']){
        case 'intv_party':
            echo "<h3>"._t('VIEW_INTERVENTION_PARTY_RECORD')."</h3>";			
            echo '<br />';
?>
			<a class="btn" href="<?php echo get_url('events','edit_intv_party',null,array('pid'=>$intervening_party->person_record_number,'intervention_id'=>$intervention->intervention_record_number)) ?>"><i class="icon-edit"></i> <?php echo _t('EDIT_THIS_PERSON')?></a>
			<a class="btn" href="<?php echo get_url('person','person',null,array('pid'=>$intervening_party->person_record_number)) ?>"><?php echo _t('MORE_ABOUT_THIS_PERSON')?></a>
<?php
            echo '<br />';
            echo '<br />';
            $person_form = person_form('view');           
            popuate_formArray($person_form, $intervening_party);
            shn_form_get_html_labels($person_form, false);
            break;
        case 'intv':
            echo "<h3>"._t('VIEW_INTERVENTION_RECORD')."</h3>";	
            echo '<br />';
?><a class="btn" href="<?php echo get_url('events','edit_intv',null,array('intervention_id'=>$intervention->intervention_record_number)) ?>"><i class="icon-edit"></i> <?php echo _t('EDIT_THIS_INTERVENTION')?></a><?php            
            echo '<br />';
            echo '<br />';
            $intervention_form = intervention_form('view');
            popuate_formArray($intervention_form,$intervention);
            shn_form_get_html_labels($intervention_form, false);
            break;

    }
?>
<?php } ?>
</div>
