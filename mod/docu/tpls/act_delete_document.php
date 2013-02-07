<?php 
    global $conf; 
    include_once('tabs.php');
    include_once('docu_title.php');    
?>
<form class="form-horizontal"  action="<?php get_url('docu','delete_document', null, array('doc_id' => $_GET['doc_id']))?>" method="post">
<div class="alert alert-error">
     
    <h3><?php echo _t('DO_YOU_WANT_TO_DELETE_THIS_DOCUMENT_')?></h3>
    
        <br />
        <center>
             <button type='submit' class='btn btn-danger' name='delete' ><i class="icon-trash icon-white"></i> <?php echo _t('DELETE') ?></button>
        <button type='submit' class='btn' name='cancel' ><i class="icon-remove-circle"></i> <?php echo _t('CANCEL') ?></button>
       
        </center>        
</div>
</form>
