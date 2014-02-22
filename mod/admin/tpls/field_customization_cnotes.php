<div id="browse">
    <table class='table table-bordered table-striped table-hover'>
        <thead>
            <tr>
                <th><?php echo(_t('FIELD_NAME')); ?></th>
                <th><?php echo(_t('LABEL')); ?></th>                
                <th><?php echo(_t('Multivalue')); ?></th> 
                <th><?php echo(_t('Enabled')); ?></th> 
                <th><?php echo(_t('IS_REQUIRED')); ?></th> 
                <th><?php echo(_t('CLARIFY')); ?></th> 

            </tr>
        </thead>
        <tbody>
            <?php foreach ($res as $record) {
                ?>
                <tr>
                    <td><?php echo $record['field_name']; ?></td>
                    <td><?php echo $record['field_label']; ?></td>
                    <td align="center">
                       <?php
                       if (($record['field_type'] == "mt_tree" || $record['field_type'] == "mt_select" || $record['field_type'] == "user_select") && strtolower($record['is_repeat']) == 'y') {
                        echo _t('YES');
                    }?>
                        
                    </td>
                    <td align="center">
                        <?php $name = 'enabled_' . $record['field_number']; ?>
                        <input type="checkbox" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value='y' <?php if (strtolower($record['enabled']) == 'y') echo "checked='true'"; ?>  <?php echo ($record['essential'] == 'y' ? ' disabled="disables"' : "") ?>/>
                    </td>                   
                    <td align="center">
                        <?php $name = 'required_' . $record['field_number']; ?>
                        <input type="checkbox" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value='y' <?php if (strtolower($record['required']) == 'y') echo "checked='true'"; ?> <?php if($record['essential'] == 'y' || $record['field_type'] == 'line'){ echo ' disabled="disables"';} ?> />
                    </td>
                    
                    <td align="center">
                        <?php $name = 'clari_' . $record['field_number']; ?>
                        <input type="checkbox" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value='y' <?php if (strtolower($record['clar_note']) == 'y') echo "checked='true'"; ?> <?php if($record['field_type'] == 'line'){ echo ' disabled="disables"';} ?>/>
                    </td>


                </tr>

            <?php } ?>


        </tbody>
    </table>
</div>
<input type="hidden" name="clari" id="clari" value="used"/>
<input type="hidden" name="enabled" id="enabled" value="used"/>
<input type="hidden" name="is_repeat" id="is_repeat" value="used"/>
<input type="hidden" name="required" id="required" value="used"/>