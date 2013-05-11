<?php global $conf; ?>
<div id="browse">
    <table class='table table-bordered table-striped table-hover'>
        <thead>
            <tr>
                <th><?php echo(_t('FIELD_NAME')); ?></th>
                <th><?php echo(_t('LABEL')); ?></th>
                <th><?php echo(_t('VISIBLE_IN_FORM')); ?></th>
                <th><?php echo(_t('VISIBLE_IN_VIEW')); ?></th>
                <?php if ($browse_needed) { ?>     
                    <th><?php echo(_t('VISIBLE_IN_BROWSE')); ?></th>
                <?php } ?>     
                
                <th><?php echo(_t('Searchable in Analysis')); ?></th>
                <th><?php echo(_t('Visible in Analysis')); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($res as $record) { ?>
                <tr >
                    <td><?php echo $record['field_name']; ?></td>
                    <td><?php echo $record['field_label']; ?></td>

                    <td align="center">
                        <?php $name = 'visible_new_' . $record['field_number']; ?>
                        <input type="checkbox" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value='y' <?php if ($record['visible_new'] == 'y') echo "checked='checked'"; ?> <?php echo ($record['essential'] == 'y' ? ' disabled="disables"' : null) ?> />
                    </td>
                    <td align="center">
                        <?php $name = 'visible_view_' . $record['field_number']; ?>
                        <input type="checkbox" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value='y' <?php if ($record['visible_view'] == 'y') echo "checked='checked'"; ?> />
                    </td>

                    <?php if ($browse_needed) { ?>           
                        <td align="center">
                            <?php $name = 'visible_browse_' . $record['field_number']; ?>
                            <input type="checkbox" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value='y' <?php if ($record['visible_browse'] == 'y' || $record['visible_browse'] == 'Y') echo "checked='checked'"; ?>  />
                        </td>
                    <?php } ?>
                     <td align="center">
                        <?php $name = 'visible_adv_search_' . $record['field_number']; ?>
                        <input type="checkbox" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value='y' <?php if ($record['visible_adv_search'] == 'y') echo "checked='checked'"; ?> />
                    </td>
                     <td align="center">
                        <?php $name = 'visible_adv_search_display_' . $record['field_number']; ?>
                        <input type="checkbox" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value='y' <?php if ($record['visible_adv_search_display'] == 'y') echo "checked='checked'"; ?> />
                    </td>
                </tr>

            <?php } ?>

        </tbody>
    </table>
</div>
<input type="hidden" name="visible_new" id="visible_new" value="used"/>
<input type="hidden" name="visible_edit" id="visible_edit" value="used"/>
<input type="hidden" name="visible_view" id="visible_view" value="used"/>
<?php if ($browse_needed) { ?>     
    <input type="hidden" name="visible_browse" id="visible_browse" value="used"/>
<?php } ?>     
<input type="hidden" name="visible_adv_search" id="visible_adv_search" value="used"/>
<input type="hidden" name="visible_adv_search_display" id="visible_adv_search_display" value="used"/>