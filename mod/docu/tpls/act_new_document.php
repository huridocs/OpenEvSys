

<div class="panel">
    <div class="form-container"> 
        <form class="form-horizontal"  action='<?php echo get_url('docu', 'new_document') ?>' method='post' enctype="multipart/form-data">
            <?php
            $fields = shn_form_get_html_fields($document_form);
            place_form_elements($document_form, $fields);
            ?>
            <br />    
            <a class="btn" href="<?php echo get_url('docu', 'browse'); ?>"><?php echo _t('CANCEL'); ?></a>
            <?php echo $fields['save']; ?>
        </form>
    </div>
</div>
