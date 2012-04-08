<?php global $conf; 
    include_once('tabs.php');
    include_once('docu_title.php');    
?>
<div class="card_list">
    <a href="<?php get_url('docu','view_document')?>" class="active first"><?php echo _t('DOCUMENT_DETAILS') ?></a>
    <a class='inactive'><?php echo _t('LINKS') ?></a>
    <a class='last inactive'><?php echo _t('AUDIT') ?></a>
</div>

<div class="panel">
	<form action='<?php echo get_url('docu','edit_document',null, array('doc_id'=>$_REQUEST['doc_id']))?>' method='post' enctype="multipart/form-data">
	<?php		
		if($fileExist){ 
	?>
	<div class='dialog confirm'>		
	    	<h3><?php echo _t('DO_YOU_WANT_TO_REMOVE_THE_EXISTING_FILE_ATTACHMENT_FOR_THIS_DOCUMENT_')?></h3>	    
	        <br />
	        <center>
	        <input type='submit' name='yes' value='<? echo _t('YES') ?>' />
	        <input type='submit' name='no' value='<? echo _t('NO') ?>' />
	        </center>		
	</div>
	<?php
		}
	?>
	
    <div class="form-container">    
<?php
    //$document_form['doc_id']= array('type'=>'hidden',null,'extra_opts'=>array('value'=>$this->supporting_docs_meta->doc_id)); 
    popuate_formArray($document_form,$supporting_docs);
    popuate_formArray($document_form,$supporting_docs_meta);
    $fields = shn_form_get_html_fields($document_form);
    place_form_elements($document_form,$fields);
?>
    <br />
    <a class="but" href="<?php echo get_url('docu','view_document',null,array('doc_id'=>$_GET['doc_id'])) ?>"><?php echo _t('CANCEL')?></a>    
    <?php echo $fields['update']; ?>
    </div>
    </form>
</div>
