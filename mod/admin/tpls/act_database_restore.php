<h2><?php echo _t('DATABASE_RESTORE') ?></h2>

<div class="form-container">
    <form class="form" action='<?php echo get_url('admin', 'database_restore') ?>' method='post' enctype="multipart/form-data">
        <div class="control-group">
            <label class="control-label" for="file"><?php echo _t('Backup file') ?></label>
            <div class="controls">
                <input title="file" type="file" name="file" id="file" class="input-large" />
                <div class="help-inline"><span class="label label-important"><?php echo _t('IS_REQUIRED') ?></span> </div>
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <button type="submit" class="btn btn-primary" name="upload">
                    <i class="icon-upload icon-white"></i>
                    <?php echo _t('Database restore') ?>
                </button>
            </div>
        </div>
    </form>
</div>