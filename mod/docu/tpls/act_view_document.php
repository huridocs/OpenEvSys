<?php global $conf; 
    include_once('docu_title.php');    
?>
<?php
    include_once('view_card_list.php');
    draw_card_list('dc');  
?>

<div class="panel">
    <div class="form-container"> 
    <a class="but" href="<?php echo get_url('docu','edit_document',null,array('doc_id'=>$_GET['doc_id'])) ?>"><img src="<?php echo data_uri(APPROOT.'www/res/img/gtk-edit.png','image/png') ?>"> <?php echo _t('EDIT_DETAILS')?></a>
    <a class="but" href="<?php echo get_url('docu', 'delete_document', null, array('doc_id'=>$_GET['doc_id']))?>"><img src="<?php echo data_uri(APPROOT.'www/res/img/edit-delete.png','image/png') ?>"> <?php echo _t('DELETE_DOCUMENT');?></a>
<?php
	if($supporting_docs->uri != null){
?>
		 <a class="but" href="<?php echo get_url('docu','download',null,array('doc_id'=>$_GET['doc_id'])) ?>"><?php echo _t('DOWNLOAD_DOCUMENT'); ?></a>
<?php		
	}
	else{
		shnMessageQueue::addInformation('No attachment found to this Document.');
	} 
?>    
   
    <br /><br />
<?php
    $document_form = document_form('view');
    $document_form['file_size']=  array('type'=>'text', 'label'=>'File Size', 'map'=>array('entity'=>'supporting_docs_meta', 'field'=>'file_size'));

    popuate_formArray($document_form,$supporting_docs_meta);
    shn_form_get_html_labels($document_form,false);
?>
        
    </div>
</div>
<?php
    //set_redirect_header('docu','view_document');
?>
