<div id="browse">
<?php foreach($entity_list as $entity){ ?>
<table class='table table-bordered table-striped table-hover'>
    <thead>
        <tr>
            <th colspan='6'><?php echo _t('SELECT_FIELDS_FROM_').'['.$entity.']'; ?></th>
        </tr>
        <tr>
            <th><?php echo(_t('FIELD_NUMBER')); ?></th>
            <th><?php echo(_t('FIELD_NAME')); ?></th>
            <th><?php echo(_t('FIELD_TYPE')); ?></th>
            <th><?php echo(_t('LABEL')); ?></th>
            <th><?php echo(_t('SEARCHABLE')); ?></th>
            <th><?php echo(_t('IN_SEARCH_RESULTS')); ?></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($entity_fields[$entity] as $record){  ?>
        <tr <?php echo ($i++%2==1)?'class="odd"':''; ?>>
            <th><?php echo $record['field_number']; ?></th>
            <th><?php echo $record['field_name']; ?></th>
            <th><?php echo $record['field_type']; ?></th>
            <th><?php echo $record['field_label'];?></th>
            <th align="center">
                <?php $name = "search_{$record['field_name']}_$entity"?>
                <input type="checkbox" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value='y' <?php if($record['shuffel_search']=='search')echo "checked='true'";?> />
            </th>
            <th align="center">
                <?php $name = "search_view_{$record['field_name']}_$entity";?>
                <input type="checkbox" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value='y' <?php if($record['shuffel_search_view']=='search_view')echo "checked='true'";?> />
            </th>
        </tr>

    <?php } ?>
    </tbody>
</table>
<br />
<?php } ?>
<center>
<button type="submit" class="btn" name="save" ><i class="icon-ok"></i> <?php echo _t('SAVE') ?></button>
</center>
</div>
