<?php global $conf; ?>
<div id="browse">
    <div style="display:none">
        <?php
        $fields = shn_form_get_html_fields($fields_form);
        foreach ($fields_form as $fieldName => $field) {
            if ($field["type"] == "mt_select" || $field["type"] == "mt_tree") {

                $data_array = MtFieldWrapper::getMTList($field['map']['mt']);
                $size = count($data_array);
                $options = array();
                for ($i = 0; $i < $size; $i++) {
                    $options[$data_array[$i]['vocab_number']] = $data_array[$i]['label'];
                }

                ?>
                <select class="mt_select input-large" name=""
                        id="fieldOptionsTemplate_<?php echo $field['field_number'] ?>"
                        multiple="multiple">
                    <?php
                    foreach ($options as $opt_value => $desc) { ?>
                        <option value="<?php echo $opt_value ?>"><?php echo $desc ?></option>
                    <?php
                    }
                    ?>
                </select>
            <?php
            } elseif ($field["type"] == "radio") {
                $options = (isset($field['extra_opts']['options'])) ? $field['extra_opts']['options'] : array(
                    'y' => 'Yes',
                    'n' => 'No'
                );
                ?>
                <select class="mt_select input-large" name=""
                        id="fieldOptionsTemplate_<?php echo $field['field_number'] ?>">
                    <?php
                    foreach ($options as $opt_value => $desc) {
                        ?>
                        <option value="<?php echo $opt_value ?>"><?php echo $desc ?></option>
                    <?php
                    }
                    ?>
                </select>
            <?php
            }
        }
        ?>
        <select name="" id="fieldSelectorTemplate"
                data-fieldnumber=""
                class="select fieldforhide">
            <option value=""></option>
            <?php
            $i = 0;

            foreach ($fields_for_hide as $field) {
                ?>
                <option value="<?php echo $field['field_number'] ?>"><?php echo $field['field_label'] ?></option>
                <?php
                $i++;
            }
            ?>
        </select>
        <button id="removeButtonTemplate" type='button' class='btn btn-grey removeCondition '><i
                class="icon-trash"></i> <?php echo _t('REMOVE') ?></button>

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
            <tr>
                <td><?php echo $record['field_name']; ?></td>
                <td><?php echo $record['field_label']; ?></td>

                <td align="center">
                    <?php $name = 'visible_new_' . $record['field_number']; ?>
                    <input type="checkbox" name="<?php echo $name; ?>" id="<?php echo $name; ?>"
                           value='y' <?php if ($record['visible_new'] == 'y') {
                        echo "checked='checked'";
                    } ?> <?php echo($record['essential'] == 'y' ? ' disabled="disabled"' : null) ?> />
                </td>
                <td align="center">
                    <?php $name = 'visible_view_' . $record['field_number']; ?>
                    <input type="checkbox" name="<?php echo $name; ?>" id="<?php echo $name; ?>"
                           value='y' <?php if ($record['visible_view'] == 'y') {
                        echo "checked='checked'";
                    } ?> />
                </td>

                <?php if ($browse_needed) { ?>
                    <td align="center">
                        <?php $name = 'visible_browse_' . $record['field_number']; ?>
                        <input type="checkbox" name="<?php echo $name; ?>" id="<?php echo $name; ?>"
                               value='y' <?php if ($record['visible_browse'] == 'y' || $record['visible_browse'] == 'Y') {
                            echo "checked='checked'";
                        } ?> <?php if ($record['field_type'] == 'user_select' || $record['field_type'] == 'line' || $record['field_type'] == 'location') {
                            echo ' disabled="disabled"';
                        } ?> />
                    </td>
                <?php } ?>
                <td align="center">
                    <?php $name = 'visible_adv_search_' . $record['field_number']; ?>
                    <input type="checkbox" name="<?php echo $name; ?>" id="<?php echo $name; ?>"
                           value='y' <?php if ($record['visible_adv_search'] == 'y') {
                        echo "checked='checked'";
                    } ?> <?php if ($record['field_type'] == 'user_select' || $record['field_type'] == 'line' || $record['field_type'] == 'location') {
                        echo ' disabled="disabled"';
                    } ?> />
                </td>
                <td align="center">
                    <?php $name = 'visible_adv_search_display_' . $record['field_number']; ?>
                    <input type="checkbox" name="<?php echo $name; ?>" id="<?php echo $name; ?>"
                           value='y' <?php if ($record['visible_adv_search_display'] == 'y') {
                        echo "checked='checked'";
                    } ?> <?php if ($record['field_type'] == 'user_select' || $record['field_type'] == 'line' || $record['field_type'] == 'location') {
                        echo ' disabled="disabled"';
                    } ?> />
                </td>
                <td align="center">
                    <?php if ($record['essential'] != 'y') { ?>

                        <?php $name = 'visibility_field_' . $record['field_number']; ?>
                        <div>
                            <button class="btn addnewcondition" type="button" id="addnewcondition_btn_<?php echo $record['field_number'] ?>"
                                    data-fieldnumber="<?php echo $record['field_number'] ?>">
                                <i class="icon-plus"></i> <?php echo _t('Add new Condition') ?></button>
                            <br/>

                            <div id="<?php echo $name ?>_fields_container">

                            </div>
                        </div>

                    <?php } ?>
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
    var conditionIndex = 0;

    var visibility_fields = <?php echo json_encode($php_array) ?>;
    //console.log(visibility_fields);
    jQuery(document).ready(function ($) {
        $(".removeCondition").on("click", removeCondition);
        function removeCondition(event) {
            var field_container = $(event.target).closest("div.field_container");
            field_container.remove();
        }

        $(".addnewcondition").on("click", function (event) {
            var fieldSelector = $('select#fieldSelectorTemplate').clone();
            var fieldNumber = $(event.target).data('fieldnumber');
            fieldSelector.attr('data-fieldnumber', fieldNumber);
            conditionIndex++;
            fieldSelector.attr('data-condition-index', conditionIndex);
            fieldSelector.attr('name', "visibility_field_" + fieldNumber + "["+conditionIndex+"]");
            fieldSelector.attr('id',"visibility_field_" + fieldNumber + "_"+conditionIndex);
            fieldSelector.show();
            var fields_container = $('#visibility_field_' + fieldNumber + '_fields_container');
            var field_container = $(document.createElement('div'));
            field_container.addClass('field_container');
            field_container.appendTo(fields_container);
            fieldSelector.appendTo(field_container);

            var field_box = $(document.createElement('div'));
            field_box.addClass('field_box');
            field_box.appendTo(field_container);

            var remove_btn = $('#removeButtonTemplate').clone();
            remove_btn.attr('id','');
            remove_btn.appendTo(field_container);

            fieldSelector.select2({
                width: 'resolve',
                allowClear: true,
                closeOnSelect: false,
                placeholder: _("SELECT")
            });

            $(".fieldforhide").on("change", fieldForHideChange);
            $(".removeCondition").on("click", removeCondition);
        });

        function fieldForHideChange(event) {
            var fieldNumber = $(event.target).data('fieldnumber');
            var conditionIndex = $(event.target).data('condition-index');
            var optionsFieldNumber = $(event.target).val();
            var fieldName = event.target.name;
            var box = $(event.target).siblings('div.field_box');
            box.html('');
            var optionsSelect = $('select#fieldOptionsTemplate_' + optionsFieldNumber).clone();
            optionsSelect.attr('multiple', 'multiple');
            optionsSelect.attr('name',"visibility_field_"+fieldNumber+"_vals["+conditionIndex+"][]");
            optionsSelect.attr('id',"visibility_field_"+fieldNumber+"_vals_"+conditionIndex);
            optionsSelect.show();
            optionsSelect.val([]);

            if (visibility_fields.hasOwnProperty(fieldNumber) && visibility_fields[fieldNumber].hasOwnProperty(optionsFieldNumber)) {
                var vals = visibility_fields[fieldNumber][optionsFieldNumber];
                optionsSelect.val(vals);
            }
            optionsSelect.appendTo(box);
            optionsSelect.select2({
                width: 'resolve',
                allowClear: true,
                closeOnSelect: false,
                placeholder: "Select when to hide"
            });
        }

        $(".fieldforhide").on("change", fieldForHideChange);
        <?php
        foreach($php_array as $field_number=>$v){
            foreach($v as $optionsFieldNumber=>$vals){
            echo "$('#addnewcondition_btn_".$field_number."').click();\n";
            $fieldSelector = "visibility_field_".$field_number."_";

            echo "$('#".$fieldSelector."'+conditionIndex).val( '$optionsFieldNumber' ).attr('selected',true);\n";
            echo "$('#".$fieldSelector."'+conditionIndex).val( '$optionsFieldNumber' ).change();\n";

            $fieldSelector = "visibility_field_".$field_number."_vals_";
            echo "$('#".$fieldSelector."'+conditionIndex).val( ['".implode("','",$vals)."'] ).trigger('change');\n";

            //echo "$('#".$fieldSelector."'+conditionIndex).change();";



            //echo "$('#".$n."').select2('val','$optionsFieldNumber');";
            }
        
    }
    ?>


    });
</script>