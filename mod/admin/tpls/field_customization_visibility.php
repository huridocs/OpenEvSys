<?php global $conf; ?>
<div id="browse">
    <div style="display:none">
        <?php
        $fields = shn_form_get_html_fields($fields_form);
        foreach($fields_form as $fieldName => $field){
            if($field["type"] == "mt_tree" || $field["type"] == "mt_select"){
                echo $fields[$fieldName];
            }
        }
        ?>
    </div>
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
                <th><?php echo(_t('Hide in case of')); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($res as $record) { ?>
                <tr >
                    <td><?php echo $record['field_name']; ?></td>
                    <td><?php echo $record['field_label']; ?></td>

                    <td align="center">
                        <?php $name = 'visible_new_' . $record['field_number']; ?>
                        <input type="checkbox" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value='y' <?php if ($record['visible_new'] == 'y') echo "checked='checked'"; ?> <?php echo ($record['essential'] == 'y' ? ' disabled="disabled"' : null) ?> />
                    </td>
                    <td align="center">
                        <?php $name = 'visible_view_' . $record['field_number']; ?>
                        <input type="checkbox" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value='y' <?php if ($record['visible_view'] == 'y') echo "checked='checked'"; ?> />
                    </td>

                    <?php if ($browse_needed) { ?>           
                        <td align="center">
                            <?php $name = 'visible_browse_' . $record['field_number']; ?>
                            <input type="checkbox" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value='y' <?php if ($record['visible_browse'] == 'y' || $record['visible_browse'] == 'Y') echo "checked='checked'"; ?> <?php if($record['field_type'] == 'user_select' || $record['field_type'] == 'line' || $record['field_type'] == 'location')echo ' disabled="disabled"'; ?> />
                        </td>
                    <?php } ?>
                    <td align="center">
                        <?php $name = 'visible_adv_search_' . $record['field_number']; ?>
                        <input type="checkbox" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value='y' <?php if ($record['visible_adv_search'] == 'y') echo "checked='checked'"; ?> <?php if($record['field_type'] == 'user_select' || $record['field_type'] == 'line'|| $record['field_type'] == 'location')echo ' disabled="disabled"'; ?> />
                    </td>
                    <td align="center">
                        <?php $name = 'visible_adv_search_display_' . $record['field_number']; ?>
                        <input type="checkbox" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value='y' <?php if ($record['visible_adv_search_display'] == 'y') echo "checked='checked'"; ?> <?php if($record['field_type'] == 'user_select'|| $record['field_type'] == 'line' || $record['field_type'] == 'location')echo ' disabled="disabled"'; ?> />
                    </td>
                    <td align="center">
                        <?php $name = 'visibility_field_' . $record['field_number']; ?>
                        <select name="<?php echo $name?>" id="<?php echo $name?>" data-fieldnumber="<?php echo $record['field_number']?>" class="select fieldforhide">
                            <option value=""></option>
                            <?php
                            $i = 0;

                            foreach ($fields_for_hide as $field) {
                                ?>
                                <option data-fieldname="<?php echo $field['field_name'] ?>" value="<?php echo $field['field_number'] ?>"><?php echo $field['field_label'] ?></option>
                                <?php
                                $i++;
                            }
                            ?>
                        </select>
                        <div id="<?php echo $name?>_box"></div>
                    </td>
                </tr>

            <?php } ?>

        </tbody>
    </table>
</div>

<input type="hidden" name="visibility_field" id="visibility_field" value="used"/>
<input type="hidden" name="visible_new" id="visible_new" value="used"/>
<input type="hidden" name="visible_edit" id="visible_edit" value="used"/>
<input type="hidden" name="visible_view" id="visible_view" value="used"/>
<?php if ($browse_needed) { ?>     
    <input type="hidden" name="visible_browse" id="visible_browse" value="used"/>
<?php } ?>     
<input type="hidden" name="visible_adv_search" id="visible_adv_search" value="used"/>
<input type="hidden" name="visible_adv_search_display" id="visible_adv_search_display" value="used"/>


<script type="text/javascript">
    <?php
    $php_array = array();
    foreach($visibility_fields as $vfield){
            $php_array[$vfield['field_number']][$vfield['field_number2']][] = $vfield['value'];
        
    }
    ?>
    var visibility_fields = <?php echo json_encode($php_array) ?>;
    //console.log(visibility_fields);
    jQuery(document).ready(function($) {
        
        $(".fieldforhide").on("change", function(event) { 
            var field_number = $('#'+event.target.id).data('fieldnumber')
            var field_number2 = $('#'+event.target.id+' option:selected').val();
            var fieldname = event.target.name;
             
            var sel = $('select[name="'+$('#'+event.target.id+' option:selected').data('fieldname')+'"]');
            $('#'+fieldname+'_box').html('')
            if(sel.length){
                var clone = $('select[name="'+$('#'+event.target.id+' option:selected').data('fieldname')+'"]').clone();
            }else{
               var clone = $('select[name="'+$('#'+event.target.id+' option:selected').data('fieldname')+'[]"]').clone();
             
            }
            clone.attr('multiple', 'multiple');
            clone.attr('name', fieldname+"_vals[]");
            clone.attr('id', fieldname+"_vals");
            clone.show();
            clone.val([]);
            if(visibility_fields.hasOwnProperty(field_number) && visibility_fields[field_number].hasOwnProperty(field_number2)){
                var vals = visibility_fields[field_number][field_number2];
                clone.val(vals);
            } 
            clone.appendTo('#'+fieldname+'_box');
           
            clone.select2({
                width: 'resolve',
                allowClear: true,
                closeOnSelect:false,
                placeholder: "Select when to hide"
            });
        });
        <?php
        foreach($php_array as $field_number=>$v){
            foreach($v as $field_number2=>$val){
            $n = "visibility_field_".$field_number;
            //echo "$('#".$n."').val('$field_number2')";
            echo "$('#".$n."').val( '$field_number2' ).attr('selected',true);";
            echo "$('#".$n."').val( '$field_number2' ).change();";
            
            //echo "$('#".$n."').select2('val','$field_number2');";
            }
        
    }
    ?>
       
 
    });
</script>