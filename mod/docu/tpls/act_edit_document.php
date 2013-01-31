<?php
global $conf;
include_once('tabs.php');
include_once('docu_title.php');
?>


<div class="panel">
    <form class="form-horizontal"  action='<?php echo get_url('docu', 'edit_document', null, array('doc_id' => $_REQUEST['doc_id'])) ?>' method='post' enctype="multipart/form-data">
        <?php
        if ($fileExist) {
            ?>
            <div class="alert alert-error">

                <h3><?php echo _t('DO_YOU_WANT_TO_REMOVE_THE_EXISTING_FILE_ATTACHMENT_FOR_THIS_DOCUMENT_') ?></h3>	    
                <br />
                <center>
                    <button type='submit' class='btn btn-danger' name='yes' ><i class="icon-trash icon-white"></i> <?php echo _t('DELETE') ?></button>
                    <button type='submit' class='btn' name='no' ><i class="icon-stop"></i> <?php echo _t('CANCEL') ?></button>

                </center>		
            </div>
            <?php
        }
        ?>

        <div class="form-container">    
            <?php
            //$document_form['doc_id']= array('type'=>'hidden',null,'extra_opts'=>array('value'=>$this->supporting_docs_meta->doc_id)); 
            popuate_formArray($document_form, $supporting_docs);
            popuate_formArray($document_form, $supporting_docs_meta);
            $fields = shn_form_get_html_fields($document_form);
            place_form_elements($document_form, $fields);
            ?>
            <br />
            <div class="control-group">
                <div class="controls">

                    <button type="submit" class="btn" name="update" ><i class="icon-ok"></i> <?php echo _t('SAVE') ?></button>

                    <a class="btn" href="<?php echo get_url('docu', 'view_document', null, array('doc_id' => $_GET['doc_id'])) ?>"><i class="icon-stop"></i> <?php echo _t('CANCEL') ?></a>    
                </div>
            </div>  
        </div>
    </form>
</div>
