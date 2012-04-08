<div id="browse">
<?php foreach($entity_list as $entity){ ?>
<table class='browse'>
    <thead>
        <tr>
            <td colspan='6'><?php echo _t('SELECT_FIELDS_FROM_').'['.$entity.']'; ?></td>
        </tr>
        <tr>
            <td><?php echo(_t('FIELD_NUMBER')); ?></td>
            <td><?php echo(_t('FIELD_NAME')); ?></td>
            <td><?php echo(_t('FIELD_TYPE')); ?></td>
            <td><?php echo(_t('LABEL')); ?></td>
            <td><?php echo(_t('SEARCHABLE')); ?></td>
            <td><?php echo(_t('IN_SEARCH_RESULTS')); ?></td>
        </tr>
    </thead>
    <tbody>
    <?php foreach($entity_fields[$entity] as $record){  ?>
        <tr <?php echo ($i++%2==1)?'class="odd"':''; ?>>
            <td><?php echo $record['field_number']; ?></td>
            <td><?php echo $record['field_name']; ?></td>
            <td><?php echo $record['field_type']; ?></td>
            <td><?php echo $record['field_label'];?></td>
            <td align="center">
                <?php $name = "search_{$record['field_name']}_$entity"?>
                <input type="checkbox" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value='y' <?php if($record['shuffel_search']=='search')echo "checked='true'";?> />
            </td>
            <td align="center">
                <?php $name = "search_view_{$record['field_name']}_$entity";?>
                <input type="checkbox" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value='y' <?php if($record['shuffel_search_view']=='search_view')echo "checked='true'";?> />
            </td>
        </tr>

    <?php } ?>
    </tbody>
</table>
<br />
<?php } ?>
<center>
<input type="submit" name="save" value="<?php echo _t('SAVE') ?>"/>
</center>
</div>
