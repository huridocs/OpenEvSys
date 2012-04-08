<?php 
	include_once('tabs.php');
	include_once('event_title.php');
    include_once('card_list.php');

    draw_card_list('coe',$event_id);
?>
<div class="panel">
    <a class="but" href="<?php echo get_url('events','add_coe') ?>"><?php echo _t('ADD_CHAIN_OF_EVENTS')?></a><span>&nbsp;&nbsp;</span>
    <br />
    <br />
    <?php
	if ($related_events==NULL){
    ?>
    <div class="notice">
        <?php echo _t('THERE_IS_NO_INFORMATION_ABOUT_CHAIN_OF_EVENTS_YET__YOU_SHOULD_ADD_SOME_') ?>
    </div>
    <?php
    }
    else
    {    
    ?>
    <form action="<?php get_url('events','delete_coe')?>" method="post">
    <table class="view">
        <thead>
            <tr>
				<td width='16px'><input type='checkbox' onchange='$("input.delete").attr("checked",this.checked)' /></td>
                <td><?php echo _t('INITIAL_DATE') ?></td>
                <td><?php echo _t('RELATED_EVENT_TITLE') ?></td>
                <td><?php echo _t('TYPE_OF_CHAIN_OF_EVENTS') ?></td>
            </tr>
        </thead>
        <tbody>		
        <?php foreach($related_events as $record){ ?>
        <tr class='<?php if($i++%2==1) echo "odd "; if($coe->chain_of_events_record_number==$record['coe_id'])echo " active" ?>'>
			<td><input name="coes[]" type='checkbox' value='<?php echo $record['coe_id'] ?>' class='delete'/></td>
            <td><?php echo $record['initial_date']; ?></td>
            <td><a href="<?php get_url('events','coe_list',null,array('type'=>'coe','coe_id'=>$record['coe_id'],'related_event'=>$record['related_event']))  ?>"><?php echo $record['event_title'];?></a></td>
			<td><a href="<?php get_url('events', 'coe_list', null, array('type'=>'coe_view', 'coe_id'=> $record['coe_id']))?>"><?php echo get_mt_term($record['type_of_chain_of_events']); ?></a></td>            
        </tr>	

        <?php }?>
		<tr class='actions'>
            <td colspan='8'><input type='submit' name='delete' value='<?php echo _t('DELETE') ?>' /></td>
        </tr>				
    </tbody>
    </table>
    </form>
    <?php }?>
    <?php if(isset($_GET['type'])){ ?>
    <br />
    <br />
    <?php
        switch($_GET['type']){
            case 'coe':
                echo "<h3>"._t('VIEW_EVENT_RECORD')."</h3>&nbsp;";
                echo "<br />";
                ?><a class="but" href="<?php echo get_url('events','get_event',null,array('eid'=>$_GET['related_event'])) ?>"><?php echo _t('MORE_ABOUT_THIS_EVENT')?></a><?php
                echo "<br />";
                echo "<br />";
                $event_form = event_form('view');
                popuate_formArray($event_form,$related_event);
                shn_form_get_html_labels($event_form , false );               
                break;
            case 'coe_view':
                echo "<h3>"._t('VIEW_CHAIN_OF_EVENTS_RECORD')."</h3>&nbsp;";
                echo "<br />";
                ?><a class="but" href="<?php echo get_url('events','edit_coe',null,array('coeid'=>$_GET['coe_id'])) ?>"><?php echo _t('EDIT_THIS_CHAIN_OF_EVENT')?></a><?php
                echo "<br />";
                echo "<br />";	
                $chain_of_events_form = chain_of_events_form('view');
                popuate_formArray($chain_of_events_form,$coe);
                shn_form_get_html_labels($chain_of_events_form , false );	
                break;
        }
    }
    ?>
</div>
