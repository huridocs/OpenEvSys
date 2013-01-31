<div id="browse">
<table class='table table-bordered table-striped table-hover'>
    <thead>
        <tr>
            <th><?php echo(_t('FIELD_NUMBER')); ?></th>
            <th><?php echo(_t('FIELD_NAME')); ?></th>
            <th><?php echo(_t('FIELD_TYPE')); ?></th>
            <th><?php echo(_t('LABEL')); ?></th>
            <th><?php echo(_t('CLARIFY')); ?></th>            
        </tr>
    </thead>
    <tbody>
    <?php foreach($res as $record){  ?>
        <tr <?php echo ($i++%2==1)?'class="odd"':''; ?>>
            <td><?php echo $record['field_number']; ?></td>
            <td><?php echo $record['field_name']; ?></td>
            <td><?php echo $record['field_type']; ?></td>
            <td><?php echo $record['field_label'];?></td>
           
            <td align="center">
                <?php $name = 'clari_'.$record['field_number'];?>
                <input type="checkbox" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value='y' <?php if(strtolower($record['clar_note'])=='y')echo "checked='true'";?>/>
            </td>
            

        </tr>

    <?php } ?>
    		
            <tr  <?php echo ($i++%2==1)?'class="odd"':''; ?>>
            <td> <?php /*echo $fields1['reset']*/ ?>      </td>
            <td>  </td>
            <td>  </td>
            <td>  </td>

            <td>
            <button type="submit" name="reset_clari" class='btn'  ><i class="icon-remove"></i> <?php echo _t('RESET') ?></button></td>
            </td>
            
        </tr>
    </tbody>
</table>
</div>
<input type="hidden" name="clari" id="clari" value="used"/>
