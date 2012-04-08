<?php include_once('tabs.php')?>
<?php include_once('event_title.php'); ?>
<?php
    include_once('card_list.php');
    draw_card_list('al',$event_id);
?>
<div class="panel">
    <?php if(!isset($logs) || sizeof($logs) == 0 ){ ?>
        <div class="notice">
        <?php echo _t('AUDIT_LOG_DETAILS_ARE_NOT_AVAILABLE_FOR_THIS_EVENT') ?>
        </div>
    <?php }else{?>
    <?php $pager->render_pages(); ?>
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
            <tr class="<?php echo ($i++%2==1)?'odd':'';  ?>" >
                <td><?php echo $log['timestamp']; ?></td>
                <td><?php echo $log['action'] ; ?></td>
                <td><?php echo $log['module'] ; ?></td>
                <td><?php echo $log['entity']; ?></td>
                <td><?php echo $log['record_number']; ?></td>
                <td><?php echo $log['username']; ?></td>
            </tr>
        <?php } ?>
           
       </tbody>
    </table>
    <?php $pager->render_pages(); ?>
    <?php } ?>
</div>
