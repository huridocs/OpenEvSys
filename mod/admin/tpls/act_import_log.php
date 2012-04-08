<h2><?php echo '<span>'._t('IMPORT_LOG').'</span>  '?></h2>
<br/>

<?php if(!isset($values) || sizeof($values) == 0 ){ ?>
        <div class="notice">
        <?php echo _t('IMPORT_LOG_DETAILS_ARE_NOT_AVAILABLE') ?>
        </div>
    <?php }else{?>
    <?php $errorlist->render_pages(); ?>
    <table class='browse'>
        <thead>
            <tr>
                <td class="title"><?php echo _t('IMPORT_DATE')?> </td>
                <td class="title"><?php echo _t('IMPORT_TIME')?> </td>
                <td class="title"><?php echo _t('STATUS')?></td>
                <td class="title"><?php echo _t('EXPORT_INSTANCE')?></td>
                <td class="title"><?php echo _t('EXPORT_DATE')?></td>
                <td class="title"><?php echo _t('EXPORT_TIME')?></td>
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
