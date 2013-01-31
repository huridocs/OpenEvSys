<?php global $conf; ?>

<h2><?php echo _t('ADD_NEW_FIELD') ?></h2>
<div class="form-container">
    <form class="form-horizontal"  action='<?php echo get_url('admin', 'new_field') ?>' method='post'>

        <?php $fields = shn_form_get_html_fields($customization_form, false); ?>
        <div class='control-group'><?php echo $fields['entity_select'] ?></div>
        <div class="control-group">
            <div class="controls"><button type="submit"  name="change_entity"  class="btn" ><?php echo _t('SELECT') ?></button>
            </div></div>

    </form>
</div>

<?php if (isset($entity_select)) { ?>
    <h3><?php echo _t('ADD_FIELD_TO') . " [ {$entity_select} ] " . _t('FORM'); ?></h3>
    <div class="form-container">
        <form class="form-horizontal"  action='<?php echo get_url('admin', 'new_field', null) ?>' method='post'>

            <?php $fields = shn_form_get_html_fields($new_field_form); ?>
            <?php place_form_elements($new_field_form, $fields); ?>
            <div class="control-group">
                <div class="controls">
                    <button  type="submit" class="btn btn-primary"  name='add_new' ><i class="icon-plus icon-white"></i> <?php echo _t('ADD_FIELD') ?></button>

                    <?php echo $fields['entity_select'] ?>
                </div></div>

        </form>
    </div>
<?php } ?>
