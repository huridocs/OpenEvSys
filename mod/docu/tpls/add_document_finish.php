<?php global $conf; 
    include_once('docu_title.php');    
?>
<div class="card_list">
    <a href=" <?php get_url('docu','view_document')?>" class="active first"><?php echo _t('DOCUMENT_DETAILS') ?></a>
    <a class='inactive'><?php echo _t('LINKS') ?></a>
    <a class='inactive'><?php echo _t('AUDIT') ?></a>
</div>

<div class="panel">
    <br />

    <div class="notice">
        <strong>Finished! You can do the following</strong><br />
        <a href="<?php get_url('docu','edit_document', null, array('doc_id'=>$supporting_docs_meta->doc_id))?>" ><?php echo _t('CONTINUE_EDITING_THE_DETAILS_OF_THIS_DOCUMENT')?></a><br />
        <a href="<?php get_url('docu','new_document',null)?>"><?php echo _t('ADD_NEW_DOCUMENT')?></a>
    </div>
    <br />
<?php
    $document_form = document_form('new');
    popuate_formArray($document_form , $supporting_docs_meta);
    shn_form_get_html_labels($document_form , false);
?>
    <br />
</div>
