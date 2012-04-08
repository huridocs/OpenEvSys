<?php global $conf; 
    include_once('tabs.php');
    include_once('docu_title.php');    
?>
<?php
    include_once('view_card_list.php');
    draw_card_list('al');  
?>
<div class="panel">
<br />    
    <?php if(!isset($logs) || sizeof($logs) == 0 ){ ?>
        <div class="notice">
        <?php echo _t('LOG_DETAILS_UNAVAILABLE_FOR_THIS_PERSON') ?>
        </div>
    <?php }else{?>
    <table class='browse'>
        <thead>
            <tr>
                <td class="title"><?php echo _t('TIMESTAMP');?></td>
                <td class="title"><?php echo _t('ACTION')?> </td>
                <td class="title"><?php echo _t('MODULE')?> </td>
                <td class="title"><?php echo _t('ENTITY')?></td>
                <td class="title"><?php echo _t('RECORD_NO')?></td>
                <td class="title"><?php echo _t('USER')?></td>
            </tr>
        </thead>
        <tbody>
        <?php foreach($logs as $log){   ?>
            <tr <?php echo ($i++%2==1)?'class="odd"':''; ?> >
                <td><?php echo $log['timestamp']; ?></td>
                <td><?php echo $log['action'] ; ?></td>
                <td><?php echo $log['module'] ; ?></td>
                <td><?php echo $log['entity']; ?></td>
                <td><?php echo $log['record_number']; ?></td>
                <td><?php echo $log['username']; ?></td>
            </tr>
        <?php }   ?>
           
       </tbody>
    </table>
    <?php } ?>
    <br style="clear:both" />
    <br /><br />
</div>
