<h2><?php echo _t('IMPORT_EVENTS')?></h2>
<div class="form-container"> 
<form action='<?php echo get_url('admin','import')?>' method='post' enctype="multipart/form-data" >
<fieldset>
<?php 
    shn_form_upload(_t('EVENT_STANDARD_FORMAT_XML'), 'xml', $extra_opts = null);
    shn_form_submit(_t('UPLOAD_FILE'), 'upload' , $extra_opts = null);
    if(isset($errors)){
?>
	<a href=<?php get_url('admin','import',null,array('message'=>'error'));?>  id="<?php echo $index_term->file_name ?>" style="float:right;background:#EEEAD4"><?php echo "Download the error report"; ?></a>   
<?php   
    	//shn_form_submit(_t('DOWNLOAD_ERROR_REPORT'), 'error' , $extra_opts = null);
    }
?>
</fieldset>
</form>
 
</div>
