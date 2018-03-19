<h2><?php echo _t('DATABASE_BACKUP') ?></h2>
<div class="form-container">
    <form class="form" action='<?php echo get_url('admin', 'database_backup') ?>' method='post'>
        <div class="controls">
            <button type="submit" class="btn btn-primary" name="export">
                <i class="icon-download icon-white"></i> <?php echo _t('Export database') ?>
            </button>
        </div>
    </form>
</div>