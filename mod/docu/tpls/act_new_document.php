<div class="card_list">
    <a href="<?php get_url('docu','view_document')?>" class="active first"><?php echo _t('DOCUMENT_DETAILS') ?></a>
    <a class='inactive'><?php echo _t('LINKS') ?></a>
    <a class='inactive'><?php echo _t('AUDIT') ?></a>
</div>

<div class="panel">
    <div class="form-container"> 
    <form action='<?php echo get_url('docu','new_document')?>' method='post' enctype="multipart/form-data">
    <?php
        $fields = shn_form_get_html_fields($document_form);
        place_form_elements($document_form,$fields);
    ?>
    <br />    
    <a class="but" href="<?php echo get_url('docu','browse'); ?>"><?php echo _t('CANCEL'); ?></a>
	<?php echo $fields['save']; ?>
    </form>
    </div>
</div>
