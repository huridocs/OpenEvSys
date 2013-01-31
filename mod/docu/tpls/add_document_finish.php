<?php global $conf; 
    include_once('docu_title.php');    
?>


<div class="panel">
    <br />

    <div class='alert alert-info'> <button type="button" class="close" data-dismiss="alert">×</button> 
        <strong>Finished! You can do the following</strong><br />
        <a class="btn" href="<?php get_url('docu','edit_document', null, array('doc_id'=>$supporting_docs_meta->doc_id))?>" ><?php echo _t('CONTINUE_EDITING_THE_DETAILS_OF_THIS_DOCUMENT')?></a><br />
        <a class="btn btn-primary" href="<?php get_url('docu','new_document',null)?>"><i class="icon-plus icon-white"></i> <?php echo _t('ADD_NEW_DOCUMENT')?></a>
    </div>
    
<?php
    $document_form = document_form('new');
    popuate_formArray($document_form , $supporting_docs_meta);
    shn_form_get_html_labels($document_form , false);
?>
    <br />
</div>
