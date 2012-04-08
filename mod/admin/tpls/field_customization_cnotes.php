<div id="browse">
<table class='browse'>
    <thead>
        <tr>
            <td><?php echo(_t('FIELD_NUMBER')); ?></td>
            <td><?php echo(_t('FIELD_NAME')); ?></td>
            <td><?php echo(_t('FIELD_TYPE')); ?></td>
            <td><?php echo(_t('LABEL')); ?></td>
            <td><?php echo(_t('CLARIFY')); ?></td>            
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
    		<tr  <?php echo ($i++%2==1)?'class="odd"':''; ?>> <td colspan='13'> &nbsp;</td> </tr>
    		
            <tr  <?php echo ($i++%2==1)?'class="odd"':''; ?>>
            <td> <?php /*echo $fields1['reset']*/ ?>      </td>
            <td>  </td>
            <td>  </td>
            <td>  </td>

            <td><?php echo $fields1['reset_clari'] ?></td>
            
        </tr>
    </tbody>
</table>
</div>
<input type="hidden" name="clari" id="clari" value="used"/>
