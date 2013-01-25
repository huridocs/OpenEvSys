<div id="browse">
<table class='table table-bordered table-striped table-hover'>
    <thead>
        <tr>
            <th><?php echo(_t('FIELD_NUMBER')); ?></th>
            <th><?php echo(_t('FIELD_NAME')); ?></th>
            <th><?php echo(_t('FIELD_TYPE')); ?></th>
            <th><?php echo(_t('LABEL')); ?></th>
            <th><?php echo(_t('ORDER')); ?></th>
        </tr>
    </thead>
    <tbody>
<?php foreach($res as $record){  ?>
        <tr <?php echo ($i++%2==1)?'class="odd"':''; ?>>
            <td><?php echo $record['field_number']; ?></td>
            <td><?php echo $record['field_name']; ?></td>
            <td><?php echo $record['field_type']; ?></td>
            <td><?php echo $record['field_label'];?></td>
            <td style="padding:0px;">
                <?php $name = 'order_'. $record['field_number']; //echo $record['field_label']; ?> 
                <input style="border:1px solid #777" type="text" name="<?php echo $name;?>" id="<?php echo $name;?>"  value="<?php echo $record['label_number'];?>" size="5" />
            </td>
        </tr>

    <?php } ?>
          <tr  <?php echo ($i++%2==1)?'class="odd"':''; ?>>
            <td>  </td>
            <td>  </td>
            <td>  </td>
            <td>  </td>
            <td><?php echo $fields1['reset_order'] ?></td>
          </tr>
    </tbody>
</table>
</div>
<input type="hidden" name="order" id="order" value="used"/>

