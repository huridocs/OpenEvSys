

<div class="panel">
    <div class="form-container"> 
        <form class="form-horizontal"  action='<?php echo get_url('docu', 'new_document') ?>' method='post' enctype="multipart/form-data">
            <?php
            $fields = shn_form_get_html_fields($document_form);
            place_form_elements($document_form, $fields);
            ?>
            <br />    
            <div class="control-group">
                <div class="controls">

                    <button type="submit" class="btn" name="save" ><i class="icon-ok"></i> <?php echo _t('SAVE') ?></button>

                    <a class="btn" href="<?php echo get_url('docu', 'browse'); ?>"><i class="icon-stop"></i> <?php echo _t('CANCEL'); ?></a>
                </div>
            </div>
        </form>
    </div>
</div>
