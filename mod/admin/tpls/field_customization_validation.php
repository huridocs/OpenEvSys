<div id="browse">
<table class='table table-bordered table-striped table-hover'>
    <thead>
        <tr>
            <th><?php echo(_t('FIELD_NUMBER')); ?></th>
            <th><?php echo(_t('FIELD_NAME')); ?></th>
            <th><?php echo(_t('FIELD_TYPE')); ?></th>
            <th><?php echo(_t('LABEL')); ?></th>
            <th><?php echo(_t('IS_REQUIRED')); ?></th>
            <th><?php echo(_t('VALIDATION_TYPE')); ?></th>
            
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
                <?php $name = 'required_'.$record['field_number'];?>
                <input type="checkbox" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value='y' <?php if(strtolower($record['required'])=='y')echo "checked='true'";?>/>
            </td>
            
            <td style="padding:0px;">
                <?php $name = 'validation_'.$record['field_number'];?>

                <select class="select" name="<?php echo $name?>" id="<?php echo $name?>" <?php echo $select_opts?> >
                    <?php
                    
                    $value = split(',' , $record['validation']);
                    $options = array ( ''=>'', 'phone'=>'Phone No' , 'email' => 'Email' , 'date' => 'Date' , 'number'=>'Numerical'   );
                    foreach ($options as $opt_value => $desc )
                    {
                        if(is_array($value))
                            $sel = ( in_array($opt_value , $value) ) ? 'selected="selected"' : null ;
                        else
                            $sel = ( $opt_value == $value ) ? 'selected="selected"' : null ;
                    ?>
                    <option value="<?php echo $opt_value?>" <?php echo $sel?> ><?php echo $desc?></option>
                    <?php
                    }
                    ?>
                </select>
            </td>
        </tr>

    <?php } ?>
    		
        <tr  <?php echo ($i++%2==1)?'class="odd"':''; ?>>
            <td>  </td>
            <td>  </td>
            <td>  </td>
            <td>  </td>
            
            <td>
            <button type="submit" name="reset_required" class='btn'  ><i class="icon-remove"></i> <?php echo _t('RESET') ?></button></td>
            <td>
            <button type="submit" name="reset_validation" class='btn'  ><i class="icon-remove"></i> <?php echo _t('RESET') ?></button></td>
            
        </tr>
    </tbody>
</table>
</div>
<input type="hidden" name="required" id="required" value="used"/>
<input type="hidden" name="validation" id="validation" value="used"/>

