<h2><?php echo _t('DATABASE_BACKUP') ?></h2>
<div class="form-container">
    <a href="<?php echo get_url('admin', 'database_backup') ?>&download=true" 
        class="btn btn-primary">
        <i class="icon-download icon-white"></i> <?php echo _t('Export database') ?>
    </a>
</div>