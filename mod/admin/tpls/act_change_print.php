<?php global $conf; ?>
<script type="text/javascript" src="res/jquery/tinymce/4.0b3/jquery.tinymce.min.js"></script>
<script type="text/javascript" src="res/jquery/tinymce/4.0b3/tinymce.min.js"></script>

<h2><?php echo _t('Print configuration') ?></h2>
<div class="form-container">
    <form class="form-horizontal"  action='<?php echo get_url('admin', 'change_print') ?>' method='post'>
        <div class="control-group">
            <div class="controls">
                <button type="submit" class="btn btn-primary" name="save" ><i class="icon-ok icon-white"></i> <?php echo _t('SAVE') ?></button>

            </div></div>
        
        <div class="row-fluid">
            <div class="span12" >
                <h3><?php echo _t('Header for event print view') ?></h3>

                <textarea class="tinymce" name="print_event_header" rows="15"><?php echo htmlentities($conf['print_event_header'])?></textarea>     

            </div>

           <!-- <div class="span6" >
                <h3><?php echo _t('Sidebar for event print view') ?></h3>

                <textarea class="tinymce" name="print_event_sidebar"><?php echo htmlentities($conf['print_event_sidebar'])?></textarea>     

            </div>-->
        </div>
        <div class="row-fluid">
            <div class="span12" >
                <h3><?php echo _t('Header for person print view') ?></h3>

                <textarea class="tinymce" name="print_person_header" rows="15"><?php echo htmlentities($conf['print_person_header'])?></textarea>     

            </div>

            <!--<div class="span6" >
                <h3><?php echo _t('Sidebar for person print view') ?></h3>

                <textarea class="tinymce" name="print_person_sidebar"><?php echo htmlentities($conf['print_person_sidebar'])?></textarea>     

            </div>-->
        </div>

        <br style="clear: both" />
        <div class="control-group">
            <div class="controls">
                <button type="submit" class="btn btn-primary" name="save" ><i class="icon-ok icon-white"></i> <?php echo _t('SAVE') ?></button>

            </div></div>


    </form>
</div>

<script>
    tinymce.init({
        selector: ".tinymce",
        plugins: [
            "advlist autolink link image lists charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code insertdatetime media nonbreaking",
            "table contextmenu directionality emoticons paste textcolor filemanager"
        ],
        toolbar: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect forecolor backcolor | link unlink anchor | filemanager image media | print preview code"
    });
</script>
