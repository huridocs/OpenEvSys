

<div class="panel">
    <div class="form-container"> 
        <form class="form-horizontal"  action='<?php echo get_url('docu', 'new_document') ?>' method='post' enctype="multipart/form-data">
            <div class="control-group">
                <div class="controls">

                 
                    <a class="btn" href="<?php echo get_url('docu', 'browse'); ?>"><i class="icon-remove-circle"></i> <?php echo _t('CANCEL'); ?></a>
                   <button type="submit" class="btn btn-primary" name="save" ><i class="icon-ok icon-white"></i> <?php echo _t('SAVE') ?></button>
</div>
            </div><?php
            $fields = shn_form_get_html_fields($document_form);
            place_form_elements($document_form, $fields);
            ?>
            <br />    
            <div class="control-group">
                <div class="controls">

                 
                    <a class="btn" href="<?php echo get_url('docu', 'browse'); ?>"><i class="icon-remove-circle"></i> <?php echo _t('CANCEL'); ?></a>
                   <button type="submit" class="btn btn-primary" name="save" ><i class="icon-ok icon-white"></i> <?php echo _t('SAVE') ?></button>
</div>
            </div>
        </form>
    </div>
</div>
