<div class="panel">
    <div class="form-container"> 
        <a class="btn" href="<?php echo get_url('docu', 'edit_document', null, array('doc_id' => $_GET['doc_id'])) ?>"><i class="icon-edit"></i> <?php echo _t('EDIT_DETAILS') ?></a>
        <?php
        if ($supporting_docs->uri != null) {
            ?>
            <a class="btn" href="<?php echo get_url('docu', 'download', null, array('doc_id' => $_GET['doc_id'])) ?>"><i class="icon-download-alt"></i> <?php echo _t('DOWNLOAD_DOCUMENT'); ?></a>
            <?php }
        ?>
        <a class="btn btn-danger" href="<?php echo get_url('docu', 'delete_document', null, array('doc_id' => $_GET['doc_id'])) ?>"><i class="icon-trash icon-white"></i>  <?php echo _t('DELETE_DOCUMENT'); ?></a>
        <?php
        if ($supporting_docs->uri == null) {
            shnMessageQueue::addInformation('No attachment found to this Document.');
        }
        ?>    

        <br /><br />
        <?php
        $document_form = document_form('view');
        $document_form['file_size'] = array('type' => 'text', 'label' => 'File Size', 'map' => array('entity' => 'supporting_docs_meta', 'field' => 'file_size'));

        popuate_formArray($document_form, $supporting_docs_meta);
        shn_form_get_html_labels($document_form, false);
        ?>

    </div>
</div>
<?php
//set_redirect_header('docu','view_document');
?>
