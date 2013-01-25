
<?php include_once('event_title.php'); ?>

<div class="panel">
    <?php if(!isset($logs) || sizeof($logs) == 0 ){ ?>
        <div class='alert alert-info spanauto'> <button type="button" class="close" data-dismiss="alert">×</button> 
        <?php echo _t('AUDIT_LOG_DETAILS_ARE_NOT_AVAILABLE_FOR_THIS_EVENT') ?>
        </div><br/>
    <?php }else{?>
    <?php $pager->render_pages(); ?>
    <table class='table table-bordered table-striped table-hover'>
        <thead>
            <tr>
                <th class="title"><?php echo _t('TIMESTAMP');?></th>
                <th class="title"><?php echo _t('ACTION')?> </th>
                <th class="title"><?php echo _t('MODULE')?> </th>
                <th class="title"><?php echo _t('ENTITY')?></th>
                <th class="title"><?php echo _t('RECORD_NO')?></th>
                <th class="title"><?php echo _t('USER')?></th>
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
