<?php
global $conf;
 if ($conf['custom_form'] == false){ ?>

<div class="fs" style="float:left">
    <?php echo $fields['related_event_title'] ?><br />
    <?php echo $fields['type_of_chain_of_events'] ?><br />            
    <?php echo $fields['remarks'] ?><br />
    <?php echo $fields['comments'] ?><br />    
</div>
<div class="fs" style="float:left">
</div>
<br style="clear:both" />


<?php 
    }else{
        $fields = place_form_elements($chain_of_events_form,$fields);
    }
?>