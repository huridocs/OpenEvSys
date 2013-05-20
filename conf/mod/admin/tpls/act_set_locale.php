<h2><?php echo _t('SET_SYSTEM_LANGUAGE') ?></h2>
<div class="form-container">
    <form class="form-horizontal"  action='<?php echo get_url('admin', 'set_locale') ?>' method='post'>

        <div class='control-group'>
            <?php
            shn_form_select('Select language', 'locale', array('options' => $locales, 'value' => $current_locale));
            ?>
        </div>
        <div class="control-group">
            <div class="controls"><button type="submit"  name="update_locale"  class="btn" ><i class="icon-ok"></i> <?php echo _t('SELECT') ?></button>
            </div></div>

    </form>
</div>
