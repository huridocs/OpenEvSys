<?php 
    global $conf; 
    include_once('tabs.php');
    include_once('docu_title.php');    
?>
<form class="form-horizontal"  action="<?php get_url('docu','delete_document', null, array('doc_id' => $_GET['doc_id']))?>" method="post">
<div class='dialog confirm'>
    <h3><?php echo _t('DO_YOU_WANT_TO_DELETE_THIS_DOCUMENT_')?></h3>
    
        <br />
        <center>
        <input type='submit' class='btn' name='delete' value='<? echo _t('DELETE') ?>' />
        <input type='submit' class='btn' name='cancel' value='<? echo _t('CANCEL') ?>' />
        </center>        
</div>
</form>
