<?php include_once('tabs.php')?>
<?php include_once('person_name.php')?>
<?php
    include_once('view_card_list.php');
    draw_card_list('al',$pid);  
?>
<div class="panel">
<br />    
    <?php if(!isset($logs) || sizeof($logs) == 0 ){ ?>
        <div class='alert alert-info spanauto'> <button type="button" class="close" data-dismiss="alert">×</button> 
        <?php echo _t('LOG_DETAILS_UNAVAILABLE_FOR_THIS_PERSON') ?>
        </div><br/>
    <?php }else{?>
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
