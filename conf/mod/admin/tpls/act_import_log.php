<h2><?php echo '<span>'._t('IMPORT_LOG').'</span>  '?></h2>
<br/>

<?php if(!isset($values) || sizeof($values) == 0 ){ ?>
        <div class='alert alert-info'> <button type="button" class="close" data-dismiss="alert">×</button> 
        <?php echo _t('IMPORT_LOG_DETAILS_ARE_NOT_AVAILABLE') ?>
        </div>
    <?php }else{?>
    <?php $errorlist->render_pages(); ?>
    <table class='table table-bordered table-striped table-hover'>
        <thead>
            <tr>
                <th class="title"><?php echo _t('IMPORT_DATE')?> </th>
                <th class="title"><?php echo _t('IMPORT_TIME')?> </th>
                <th class="title"><?php echo _t('STATUS')?></th>
                <th class="title"><?php echo _t('EXPORT_INSTANCE')?></th>
                <th class="title"><?php echo _t('EXPORT_DATE')?></th>
                <th class="title"><?php echo _t('EXPORT_TIME')?></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($values as $error){  
               $odd = ($i++%2==1) ? "odd " : '' ;
		?>
			    <tr id="<?php echo $count; ?>" class="<?php echo  $odd; ?>">
                <td><?php echo $error['date'] ; ?></td>
                <td><?php echo $error['time'] ; ?></td>
               
                <td><?php if($error['status'] == 'Error') {  ?>
                    <a href="<?php get_url('admin','import_log_show',null,array('report_name'=>$error['file_name']));?>">
                <?php }echo $error['status']; ?></a></td>
                <td><?php echo $error['export_instance']; ?></td>
                <td><?php echo $error['export_date']; ?></td>
                <td><?php echo $error['export_time']; ?></td>
            </tr>
        <?php } ?>
           
       </tbody>
    </table>
    <?php $errorlist->render_pages(); ?>
    <?php } ?>
