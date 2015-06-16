<?php
/**
 *
 * Sahana HTML form library
 *
 * PHP version 4 and 5
 *
 * LICENSE: This source file is subject to LGPL license
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * 
 *
 * @package    moduleAPI
 * @subpackage form 
 * @author     Chamindra de Silva (chamindra@opensource.lk>
 * @author     Janaka Wickramasinghe <janaka@opensource.lk>
 * @author     Ravindra De Silva <ravindra@opensource.lk><http://r4vi.org>
 * @author     Richard Smith <s0459387@sms.ed.ac.uk>
 * @copyright  Lanka Software Foundation - http://www.opensource.lk
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 *
 */
global $shn_tabindex;
global $shn_help_id;
global $shn_clarify_id;

function shn_form_extra_opts($extra_opts) {
    ?>
    <div class="help-inline"><?php
    global $shn_help_id;
    // output a required flag
    $required = ($extra_opts['required']) ? '<span class="label label-important">required</span>' : '';
    echo $required;

    if (isset($extra_opts['help'])) {
        $id = $extra_opts['help'];
        echo "<a href='" . get_url('help', 'help_popup', null, array('id' => $id), null, true) . "' class='help' target='_blank' title='" . htmlentities($extra_opts['label'],ENT_QUOTES, "UTF-8") . "'><i class='icon-question-sign'></i></a>";
    }

    if (isset($extra_opts['error']) && count($extra_opts['error'])) {
        echo "<span class='help-inline'>";
        echo implode(", ", $extra_opts['error']);
        echo "</span>";
    }
    ?>
    </div>
    <?php
}
function shn_form_visibility_field($name,$extra_opts,$type = 'select') {
    if ($extra_opts['visibility_field']) {
        $visibility_field = $extra_opts['visibility_field'];
        $values = (isset($_POST[$name]) ? $_POST[$name] : $extra_opts['value']);
        if (!is_array($values)) {
            $values = array($values);
        }
        if($type == 'radio'){
            $selector = "input[type=radio][name=".$name."]";
        }else{
            $selector = "#".$name;
        }
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                <?php
                foreach ($visibility_field as $v => $f) {

                    if (in_array($v, $values)) {
                        foreach ($f as $field) {
                            echo "$('#" . $field . "_group').hide();";
                        }
                    }
                }
                ?>
                $("<?php echo $selector ?>").on("change", function(event) {
                    var visibility_fields = <?php echo json_encode($visibility_field) ?>;
                    val = $(this).val()
                    values = val;
                    if(!$.isArray(val)){
                        values = new Array(val);
                    }
                    for(val2 in visibility_fields){

                        if($.inArray(val2,values)<0){
                            for(i in visibility_fields[val2]){
                                $("#"+visibility_fields[val2][i]+"_group").show();
                            }
                        }
                    }

                    for(j in values){
                        val = values[j];

                        if(visibility_fields.hasOwnProperty(val)){
                            for(i in visibility_fields[val]){
                                var field_name = visibility_fields[val][i];
                                $("#"+field_name+"_group").hide();
                            }
                        }
                    }
                });
            });
        </script>
    <?php }

}
function shn_form_link($mod, $act, $tpl, $label, $extra_opts) {
    ?>
    <a class="btn" href="<?php echo get_url($mod, $act, $tpl, $extra_opts) ?>" ><?php echo _($label); ?></a>
    <?php
}

function shn_form_clari($name, $value = '') {
    $value = ($_POST[$name . '_clarify']) ? $_POST[$name . '_clarify'] : $value;
    $hide = (isset($value) && $value != '') ? '' : 'style="display:none"';
    ?>
    <textarea id="<?php echo $name ?>_clarify" name="<?php echo $name ?>_clarify" class="clarify" <?php echo $hide ?>><?php echo htmlspecialchars($value) ?></textarea>
    <?php
}

function shn_form_text($label, $name, $extra_opts) {
    global $shn_tabindex;
    if (isset($extra_opts['onfocus'])) {
        $text_options .= " onfocus='{$extra_opts['onfocus']}' ";
    }

    if (isset($extra_opts['onclick'])) {
        $text_options .= " onclick='{$extra_opts['onclick']}' ";
    }

    /* if(isset($extra_opts['size']))
      $text_options .= " size='{$extra_opts['size']}' ";
      else
      $text_options .= " size='40'"; */

    if (isset($extra_opts['class'])) {
        $class .= $extra_opts['class'];
    } else {
        $class .= "input-large";
    }
    if (isset($extra_opts['error']) && count($extra_opts['error']) > 0)
        $class .= ' error ';

    if (isset($class))
        $text_options .= " class='$class'";

    $value = ($_POST[$name]) ? $_POST[$name] : $extra_opts['value'];
    $readonly = ($extra_opts['readonly'] == true) ? 'readonly = "' . $extra_opts['readonly'] . '"' : '';

    if (get_magic_quotes_gpc())
        $value = stripslashes($value);
    if ($extra_opts['no_label'] !== true && isset($label)) {
        ?>	
        <label  class="control-label" for="<?php echo $name ?>"><?php echo $label ?></label>
        <?php
    }
    ?>	
    <div class="controls">
        <input title="<?php echo $name ?>" type="text" name="<?php echo $name ?>" id="<?php echo $name ?>" <?php echo $readonly ?> value="<?php echo htmlentities($value,ENT_QUOTES, "UTF-8") ?>" tabindex="<?php echo++$shn_tabindex ?>" <?php echo $text_options ?>  />
        <?php
        if ($extra_opts['clari']) {
            if (is_array($extra_opts['clari_value']) && count($extra_opts['clari_value']) > 0)
                $clari_value = array_pop($extra_opts['clari_value']);
            shn_form_clari($name, $clari_value);
        }
        shn_form_extra_opts($extra_opts);
        ?>
    </div>
    <?php
}

function shn_form_submit($label, $name, $extra_opts = null) {
    global $shn_tabindex;
    $class = "btn";
    if (isset($extra_opts['class'])) {
        $class .= " " . $extra_opts['class'];
    }
    $class = "class='$class'";
    $optjs = (isset($extra_opts['optjs'])) ? "{$extra_opts['optjs']}" : '';
    ?>
    <input type="submit" value="<?php echo $label ?>" name="<?php echo $name ?>" tabindex="<?php echo++$shn_tabindex ?>" <?php echo $class ?> <?php echo $optjs ?> />
    <?php
}

function shn_form_button($name, $button_opts = null, $extra_opts = null) {
    global $shn_tabindex;
    ?>
    <input
        type="button" value="<?php echo $name ?>" <?php echo $button_opts ?>
        tabindex="<?php echo++$shn_tabindex ?>" />
        <?php
        //shn_form_extra_opts($extra_opts);
    }

    function shn_form_upload($label, $name, $extra_opts = null) {
        global $shn_tabindex;
        ?>
    <label  class="control-label" ><?php echo $label ?></label>
    <div class="controls">
        <input type="file" name="<?php echo $name ?>" tabindex="<?php echo++$shn_tabindex ?>" />
        <input type="hidden" name="<?php echo $name . "_id" ?>" value="<?php echo $extra_opts['value']; ?>"/>

        <?php
        shn_form_extra_opts($extra_opts);
        ?>
    </div>
    <?php
}

function shn_form_date($label, $name, $extra_opts) {
    global $shn_tabindex;

    $value = ($_POST[$name]) ? $_POST[$name] : $extra_opts['value'];
    $value = ($value == '0000-00-00') ? '' : $value;

    $class = 'datepicker ';
    if (isset($extra_opts['class'])) {
        $class .= $extra_opts['class'];
    } else {
        $class .= "input-large";
    }

    if (isset($extra_opts['error']) && count($extra_opts['error']) > 0)
        $class .= ' error ';

    $text_opts .= " class='$class'";

    if (isset($label)) {
        ?>
        <label  class="control-label" ><?php echo $label ?></label>
    <?php } ?>
    <div class="controls">
        <input title="<?php echo $name ?>" type="text" name="<?php echo $name ?>" value="<?php echo $value ?>" id="<?php echo $name ?>" size="10" maxlength="10" tabindex="<?php echo++$shn_tabindex ?>" <?php echo $text_opts ?>/>
        <!--<script language='javascript'>
            $(document).ready(function(){
                $('#<?php echo $name ?>').DatePicker({
                    format:'Y-m-d',
                    date: $('#<?php echo $name ?>').val(),
                    current: '<?php echo date('Y-m-d') ?>',
                    starts: 1,
                    position: 'right',
                    onBeforeShow: function(){
                        $('#<?php echo $name ?>').DatePickerSetDate($('#<?php echo $name ?>').val(), true);
                    },
                    onChange: function(formated, dates){
                        $('#<?php echo $name ?>').val(formated);
                    }
                });
            });
        </script>-->

        <?php
        if ($extra_opts['clari']) {
            if (is_array($extra_opts['clari_value']) && count($extra_opts['clari_value']) > 0)
                $clari_value = array_pop($extra_opts['clari_value']);
            shn_form_clari($name, $clari_value);
        }

        shn_form_extra_opts($extra_opts);
        ?>
    </div>
    <?php
}

function shn_form_textarea($label, $name, $extra_opts) {
    global $shn_tabindex;

    $cols = (isset($extra_opts['cols'])) ? $extra_opts['cols'] : '70';
    $rows = (isset($extra_opts['rows'])) ? $extra_opts['rows'] : '4';
    $value = ($_POST[$name]) ? $_POST[$name] : $extra_opts['value'];

    if (isset($extra_opts['error']) && count($extra_opts['error']) > 0)
        $class .= ' error ';

    if (isset($class)) {
        $class .= $class;
    } else {
        $class .= "input-xlarge";
    }

    if (isset($class))
        $text_opts .= " class='$class'";

    if ($extra_opts['no_label'] !== true) {
        ?>
        <label  class="control-label" for="<?php echo $name ?>"><?php echo $label ?></label>
        <?php
    }
    ?>
    <div class="controls">
        <textarea name="<?php echo $name ?>" id="<?php echo $name ?>"
                  cols="<?php echo $cols ?>" rows="<?php echo $rows ?>"
                  <?php echo $text_opts ?> tabindex="<?php echo++$shn_tabindex ?>"><?php echo htmlspecialchars($value) ?></textarea>

        <?php
        if ($extra_opts['clari']) {
            if (is_array($extra_opts['clari_value']) && count($extra_opts['clari_value']) > 0)
                $clari_value = array_pop($extra_opts['clari_value']);
            shn_form_clari($name, $clari_value);
        }
        shn_form_extra_opts($extra_opts);
        ?>
    </div>
    <?php
}

function shn_form_select($label, $name, $extra_opts) {
    global $global;
    global $shn_tabindex;
    //var_dump($extra_opts);
    $options = $extra_opts['options'];
    //array('person'=>'Person','event'=>'Event', 'act'=>'Act', 'chain_of_event'=>'Chain of Event', 'involvement' => 'Involvement', 'information' => 'Information', 'intervention' => 'Intervention');
    //print_r($options);

    $value = (isset($_POST[$name]) ? $_POST[$name] : $extra_opts['value']);
    //to prevent warnings set $options to array() if it is null
    $options = ($options == null) ? array() : $options;

    if (isset($label)) {
        ?>
        <label  class="control-label" for="<?php echo $name ?>"><?php echo $label ?></label>
    <?php } ?>
    <div class="controls">
        <select class="select input-large" title="<?php echo $name ?>" name="<?php echo $name ?>" id="<?php echo $name ?>" <?php echo $select_opts ?> tabindex="<?php echo++$shn_tabindex ?>">
            <?php
            foreach ($options as $opt_value => $desc) {
                if (is_array($value))
                    $sel = ( in_array($opt_value, $value) ) ? 'selected="selected"' : null;
                else
                    $sel = ( $opt_value == $value ) ? 'selected="selected"' : null;
                ?>
                <option value="<?php echo $opt_value ?>" <?php echo $sel ?> ><?php echo $desc ?></option>
                <?php
            }
            ?>
        </select>

        <?php
        if ($extra_opts['clari']) {
            if (is_array($extra_opts['clari_value']) && count($extra_opts['clari_value']) > 0)
                $clari_value = array_pop($extra_opts['clari_value']);
            shn_form_clari($name, $clari_value);
        }
        shn_form_extra_opts($extra_opts);
        ?>
    </div>
    <?php
}

function shn_form_mt_select($label, $name, $extra_opts) {
    global $global;
    global $shn_tabindex;

    $options = array();
    $data_array = MtFieldWrapper::getMTList($extra_opts['mt']);
    $size = count($data_array);
    $options[''] = ' ';
    for ($i = 0; $i < $size; $i++) {
        $options[$data_array[$i]['vocab_number']] = $data_array[$i]['label'];
    }
    //print_r($options);

    $value = (isset($_POST[$name]) ? $_POST[$name] : $extra_opts['value']);
    //to prevent warnings set $options to array() if it is null
    $options = ($options == null) ? array() : $options;

    if (isset($label)) {
        ?>
        <label  class="control-label" for="<?php echo $name ?>"><?php echo $label ?></label>
    <?php } ?>
    <div class="controls">
        <select class="mt_select input-large" title="<?php echo $name ?>" onchange="<?php echo $extra_opts['onchange'] ?>" name="<?php echo $name ?>" id="<?php echo $name ?>" <?php echo $select_opts ?> tabindex="<?php echo++$shn_tabindex ?>">
            <?php
            foreach ($options as $opt_value => $desc) {
                if (is_array($value))
                    $sel = ( in_array($opt_value, $value) ) ? 'selected="selected"' : null;
                else
                    $sel = ( $opt_value == $value ) ? 'selected="selected"' : null;
                ?>
                <option value="<?php echo $opt_value ?>" <?php echo $sel ?> ><?php echo $desc ?></option>
                <?php
            }
            ?>
        </select>
        <?php
        if ($extra_opts['clari']) {
            if (is_array($extra_opts['clari_value']) && count($extra_opts['clari_value']) > 0)
                $clari_value = array_pop($extra_opts['clari_value']);
            shn_form_clari($name, $clari_value);
        }

        shn_form_extra_opts($extra_opts);
        shn_form_visibility_field($name,$extra_opts);
        ?>
    </div>
    <?php
}

function shn_form_user_select($label, $name, $extra_opts) {
    global $global;
    global $shn_tabindex;

    $options = array();
    $data_array = Browse::getUserListAll();
    $size = count($data_array);
    $options[''] = ' ';
    for ($i = 0; $i < $size; $i++) {
        $fullname = $data_array[$i]['first_name'] . " " . $data_array[$i]['last_name'];
        $fullname = trim($fullname);
        if (!$fullname) {
            $fullname = $data_array[$i]['username'];
        }
        $options[$data_array[$i]['username']] = $fullname;
    }
    //print_r($options);

    $value = (isset($_POST[$name]) ? $_POST[$name] : $extra_opts['value']);
    //to prevent warnings set $options to array() if it is null
    $options = ($options == null) ? array() : $options;

    if (isset($label)) {
        ?>
        <label  class="control-label" for="<?php echo $name ?>"><?php echo $label ?></label>
    <?php } ?>
    <div class="controls">
        <select class="mt_select input-large" title="<?php echo $name ?>" name="<?php echo $name ?>" id="<?php echo $name ?>" <?php echo $select_opts ?> tabindex="<?php echo++$shn_tabindex ?>">
            <?php
            foreach ($options as $opt_value => $desc) {
                if (is_array($value))
                    $sel = ( in_array($opt_value, $value) ) ? 'selected="selected"' : null;
                else
                    $sel = ( $opt_value == $value ) ? 'selected="selected"' : null;
                ?>
                <option value="<?php echo $opt_value ?>" <?php echo $sel ?> ><?php echo $desc ?></option>
                <?php
            }
            ?>
        </select>
        <?php
        if ($extra_opts['clari']) {
            if (is_array($extra_opts['clari_value']) && count($extra_opts['clari_value']) > 0)
                $clari_value = array_pop($extra_opts['clari_value']);
            shn_form_clari($name, $clari_value);
        }

        shn_form_extra_opts($extra_opts);
        ?>
    </div>
    <?php
}

function shn_form_user_select_mlt($label, $name, $extra_opts) {
    global $global;
    global $shn_tabindex;


    $data_array = Browse::getUserListAll();
    $size = count($data_array);
    
    for ($i = 0; $i < $size; $i++) {
        $fullname = $data_array[$i]['first_name'] . " " . $data_array[$i]['last_name'];
        $fullname = trim($fullname);
        if (!$fullname) {
            $fullname = $data_array[$i]['username'];
        }
        $options[$data_array[$i]['username']] = $fullname;
    }

    $value = (isset($_POST[$name]) ? $_POST[$name] : $extra_opts['value']);
    //to prevent warnings set $options to array() if it is null
    $options = ($options == null) ? array() : $options;

    if (isset($label)) {
        ?>
        <label  class="control-label" for="<?php echo $name ?>"><?php echo $label ?></label>
    <?php } ?>
    <div class="controls">
        <select class="mt_select_mlt input-large" name="<?php echo $name ?>[]" id="<?php echo $name ?>" multiple="multiple" <?php echo $select_opts ?> tabindex="<?php echo++$shn_tabindex ?>" title="<?php echo _t('SELECT') . ' ' . $label ?>">
            <?php
            foreach ($options as $opt_value => $desc) {
                if (is_array($value))
                    $sel = ( in_array($opt_value, $value) ) ? 'selected="selected"' : null;
                else
                    $sel = ( $opt_value == $value ) ? 'selected="selected"' : null;
                ?>
                <option value="<?php echo $opt_value ?>" <?php echo $sel ?> ><?php echo $desc ?></option>
                <?php
            }
            ?>
        </select>

        <?php
        if ($extra_opts['clari']) {
            if (is_array($extra_opts['clari_value'])) {
                foreach ($extra_opts['clari_value'] as $key => $cval) {
                    $ctid = $name . '_' . $key . '_clarify';
                    ?>
                    <textarea id="<?php echo $ctid ?>" name="<?php echo $ctid ?>" class="clarify" ><?php echo $cval ?></textarea>
                    <?php
                }
            }
        }
        ?>
    </div>
    <?php
}

function shn_form_hidden($label, $name, $extra_opts) {
    ?>
    <input type="hidden" name="<?php echo $name ?>" value="<?php echo $extra_opts['value'] ?>" />
    <?php
}

function shn_form_label($label, $value, $extra_opts = array()) {
    ?>
    <div class="label" ><?php echo $label ?> : </div>
    <?php
    if (is_array($value)) {
        foreach ($value as $val) {
            ?>
            <div class="input"><strong>&nbsp;<?php echo $val ?></strong></div>
            <?php
        }
    } else {
        ?>
        <div class="input"><strong>&nbsp;<?php echo $value ?></strong></div>
        <?php
    }
    shn_form_extra_opts($extra_opts);
}

function shn_form_mt_select_mlt($label, $name, $extra_opts) {
    global $global;
    global $shn_tabindex;


    $options = array();
    $data_array = MtFieldWrapper::getMTList($extra_opts['mt']);
    $size = count($data_array);
    for ($i = 0; $i < $size; $i++) {
        $options[$data_array[$i]['vocab_number']] = $data_array[$i]['label'];
    }

    $value = (isset($_POST[$name]) ? $_POST[$name] : $extra_opts['value']);
    //to prevent warnings set $options to array() if it is null
    $options = ($options == null) ? array() : $options;

    if (isset($label)) {
        ?>
        <label  class="control-label" for="<?php echo $name ?>"><?php echo $label ?></label>
    <?php } ?>
    <div class="controls">
        <select class="mt_select_mlt input-large" name="<?php echo $name ?>[]" id="<?php echo $name ?>" multiple="multiple" <?php echo $select_opts ?> tabindex="<?php echo++$shn_tabindex ?>" title="<?php echo _t('SELECT') . ' ' . $label ?>">
            <?php
            foreach ($options as $opt_value => $desc) {
                if (is_array($value))
                    $sel = ( in_array($opt_value, $value) ) ? 'selected="selected"' : null;
                else
                    $sel = ( $opt_value == $value ) ? 'selected="selected"' : null;
                ?>
                <option value="<?php echo $opt_value ?>" <?php echo $sel ?> ><?php echo $desc ?></option>
                <?php
            }
            ?>
        </select>

        <?php
        if ($extra_opts['clari']) {
            if (is_array($extra_opts['clari_value'])) {
                foreach ($extra_opts['clari_value'] as $key => $cval) {
                    $ctid = $name . '_' . $key . '_clarify';
                    ?>
                    <textarea id="<?php echo $ctid ?>" name="<?php echo $ctid ?>" class="clarify" ><?php echo $cval ?></textarea>
                    <?php
                }
            }
        }
        ?>

        <?php
        shn_form_extra_opts($extra_opts);
        shn_form_visibility_field($name,$extra_opts);
        ?>
    </div>
    <?php
}

/**
 * shn_form_radio print out radio options 
 * 
 * @param mixed $label 
 * @param mixed $name 
 * @param mixed $extra_opts 
 * @access public
 * @return void
 */
function shn_form_radio($label, $name, $extra_opts) {
    global $shn_tabindex;
    //check for existing value
    $value = (isset($_POST[$name])) ? $_POST[$name] : $extra_opts['value'];
    //if the label is set print the lable
    if (isset($label)) {
        ?>
        <label  class="control-label" for="<?php echo $name ?>"><?php echo $label ?></label>
        <?php
    }
    ?>
    <div class="controls">
        <?php
        //
        //if options is not set default to yes/no
        $options = (isset($extra_opts['options'])) ? $extra_opts['options'] : array('y' => 'Yes', 'n' => 'No');
        //print the option buttons
        foreach ($options as $val => $option) {
            $selected = ($val == $value) ? 'checked="true"' : '';
            ?>
            <input type="radio" value="<?php echo $val ?>" name="<?php echo $name ?>" tabindex="<?php echo $shn_tabindex++; ?>" <?php echo $selected ?> /><span>&nbsp;<?php echo $option ?></span>
            <?php
        }
        if ($extra_opts['clari']) {
            if (is_array($extra_opts['clari_value']) && count($extra_opts['clari_value']) > 0)
                $clari_value = array_pop($extra_opts['clari_value']);
            shn_form_clari($name, $clari_value);
        }
        shn_form_extra_opts($extra_opts);
        shn_form_visibility_field($name,$extra_opts,'radio');
        ?>
    </div>
    <?php
}

function shn_form_mt_tree($label, $name, $extra_opts) {
    _shn_form_mt_tree($label, $name, $extra_opts);
}

function _shn_form_mt_tree($label, $name, $extra_opts, $multiple = false) {

    global $global;
    global $shn_tabindex;

    $options = array();

    $value = (isset($_POST[$name]) ? $_POST[$name] : $extra_opts['value']);
    //to prevent warnings set $options to array() if it is null
    if (isset($label)) {
        ?>
        <label  class="control-label" for="<?php echo $name ?>"><?php echo $label ?></label>
        <?php
    }
    //print out the tree
    ?><div class="controls">
    <?php
    $mt = $extra_opts['mt'];
    $data_array = MtFieldWrapper::getMTList($mt);
    $count = count($data_array);
    if (!function_exists(strlen2)) {

        function strlen2($val) {
            return strlen($val["label"]);
        }

    }
    $lengths = array_map('strlen2', $data_array);
    $letterlength = 8;

    $width = max($lengths) * $letterlength;
    if ($width < 220) {
        $width = "";
    } elseif ($width > 400) {
        $width = 400;
    }
    ?>
        <select class="mt-tree input-large" title="<?php echo $name ?>" <?php if ($width) echo 'data-width="' . $width . '"'; ?> 
                onchange="<?php echo $extra_opts['onchange'] ?>" 
                name="<?php echo $name ?><?php if ($multiple) echo'[]'; ?>" id="<?php echo $name ?>"
                <?php echo $select_opts ?> tabindex="<?php echo++$shn_tabindex ?>"
            <?php if ($multiple) echo ' multiple="multiple"'; ?> >
            <option value=""></option>
            <?php
            for ($i = 0; $i < $count;) {
                $element1 = $data_array[$i];
                if (is_array($value)) {
                    $sel = ( in_array($element1['vocab_number'], $value) ) ? 'selected="selected"' : null;
                } else {
                    $sel = ( $element1['vocab_number'] == $value ) ? 'selected="selected"' : null;
                }
                ?>
                <option value="<?php echo $element1['vocab_number'] ?>" <?php echo $sel ?> data-level="<?php echo $element1['term_level'] ?>"><?php echo $element1['label'] ?></option>

                <?php
                $i++;
            }
            ?>
        </select>


        <?php
        if ($extra_opts['clari']) {
            if (is_array($extra_opts['clari_value']) && count($extra_opts['clari_value']) > 0)
                $clari_value = array_pop($extra_opts['clari_value']);
            shn_form_clari($name, $clari_value);
        }
        shn_form_extra_opts($extra_opts);
        if ($extra_opts['visibility_field']) {
            $visibility_field = $extra_opts['visibility_field'];
            $values = $value;
            if (!is_array($value)) {
                $values = array($value);
            }
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function($) {
        <?php
        foreach ($visibility_field as $v => $f) {

            if (in_array($v, $values)) {
                foreach ($f as $field) {
                    echo "$('#" . $field . "_group').hide();";
                }
            }
        }
        ?>
                $("#<?php echo $name ?>").on("change", function(event) { 
                    var visibility_fields = <?php echo json_encode($visibility_field) ?>;
                    val = $(this).val()
                    values = val;
                    if(!$.isArray(val)){
                        values = new Array(val);
                    }
                    for(val2 in visibility_fields){
                                            
                        if($.inArray(val2,values)<0){
                            for(i in visibility_fields[val2]){
                                $("#"+visibility_fields[val2][i]+"_group").show();
                            }
                        }
                    }
                    for(j in values){
                        val = values[j]
                                            
                        if(visibility_fields.hasOwnProperty(val)){
                            for(i in visibility_fields[val]){
                                $("#"+visibility_fields[val][i]+"_group").hide();
                            }
                        }
                    }
                });
            });
            </script>
            <?php
        }
        ?>
    </div>
    <?php
}

function _shn_form_print_mt_tree($mt, $name) {
    $data_array = MtFieldWrapper::getMTList($mt);
    $count = count($data_array);
    ?>
    <select class="mt-tree input-large" title="<?php echo $name ?>" onchange="<?php echo $extra_opts['onchange'] ?>" name="<?php echo $name ?>[]" id="<?php echo $name ?>" <?php echo $select_opts ?> tabindex="<?php echo++$shn_tabindex ?>">
        <?php
        $level = 0;
        for ($i = 0; $i < $count;) {
            $element1 = $data_array[$i];
            ?>
            <option value="<?php echo $element1['vocab_number'] ?>" data-level="<?php echo $element1["term_level"] ?>"><?php echo $element1['label'] ?></option>

            <?php
            $i++;
        }
        ?>
    </select>
    <?php
}

function shn_form_mt_tree_mlt($label, $name, $extra_opts) {
    _shn_form_mt_tree($label, $name, $extra_opts, true);
}

/**
 * HTML checkbox element
 *
 * @param mixed $label
 * @param mixed $name
 * @param mixed $text_opts
 * @param mixed $extra_opts
 * @access public
 * @return void
 */
function shn_form_checkbox($label, $name, $extra_opts) {
    global $shn_tabindex;
    $value = (isset($_POST[$name])) ? $_POST[$name] : $extra_opts['value'];
    if (isset($label)) {
        ?>
        <label  class="control-label" for="<?php echo $name ?>"><?php echo $label ?></label>
        <?php
    }
    $selected = ($value == 'y' || $value == '1' || $value == 'on') ? 'checked="true"' : '';
    ?>
    <div class="controls">
        <input type="checkbox" name="<?php echo $name ?>" tabindex="<?php echo $shn_tabindex++; ?>" <?php echo $selected ?> />
        <?php
        if ($extra_opts['clari']) {
            if (is_array($extra_opts['clari_value']) && count($extra_opts['clari_value']) > 0)
                $clari_value = array_pop($extra_opts['clari_value']);
            shn_form_clari($name, $clari_value);
        }
        shn_form_extra_opts($extra_opts);
        ?>
    </div>
    <?php
}

function shn_form_checkarray($label, $name, $extra_opts) {
    global $shn_tabindex;
    $value = ($_POST[$name]) ? $_POST[$name] : $extra_opts['value'];
    ?>
    <label  class="control-label" for="<?php echo $name ?>"><?php echo $label ?></label>
    <div class="controls">
        <ul class='controler_list'>
            <?php
            foreach ($extra_opts['options'] as $key => $item_name) {
                $checked = (is_array($value) && array_key_exists($key, $value)) ? "checked='checked'" : '';
                ?>
                <li><input type="checkbox" name="<?php echo $name ?>[]" value="<?php echo $key ?>" <?php echo $checked ?>	tabindex="<?php echo++$shn_tabindex ?>" /><span><?php echo $item_name ?></span></li>
                <?php
            }
            ?>
        </ul>
        <?php
        shn_form_extra_opts($extra_opts);
        ?>
    </div>
    <?php
}

function shn_form_related_person($label, $name, $extra_opts) {
    global $shn_tabindex;

    if (isset($_GET['person_id'])) {
        $_POST[$name] = $_GET['person_id'];
    }

    $value = ($_POST[$name]) ? $_POST[$name] : $extra_opts['value'];

    $p = new Person();
    $p->LoadFromRecordNumber($_GET['pid']);
    $person_name = $p->person_name;

    $rp = new Person();
    $rp->LoadFromRecordNumber($value);
    $related_person_name = $rp->person_name;
    ?>

    <label  class="control-label" for="<?php echo $name ?>"><?php echo $label ?></label>
    <div class="controls">
        <input type="text" readonly="readonly" value="<?php echo $related_person_name; ?>"/>
        <input type="hidden" name="<?php echo $name ?>" id="<?php echo $name ?>" readonly="true" value="<?php echo $value ?>"/>
        <?php
        if (isset($_POST['search']) || $_GET['request_page'] != null) {
            if ($_GET['person_id'] != null) {
                ?>
                <a class="show_search_form" href="#" id="show_related_person" style="display:none;" ><?php echo _t('SHOW_SEARCH_FORM'); ?></a>
                <a class="hide_search_form" href="#" id="hide_related_person" style="display;"><?php echo _t('HIDE_SEARCH_FORM'); ?></a>
                <?php
            } else {
                ?>
                <a class="show_search_form" href="#" id="show_related_person" style="display;"><?php echo _t('SHOW_SEARCH_FORM'); ?></a>
                <a class="hide_search_form" href="#" id="hide_related_person" style="display:none;"><?php echo _t('HIDE_SEARCH_FORM'); ?></a>
                <?php
            }
            ?>


            <fieldset id='person_search_form'>
                <div class="box" style="margin-top:10px">
                    <h4 class="box-header round-top"><?php echo _t('PERSONS') ?> </h4>
                    <div class="box-container-toggle">
                        <div class="box-content"><?php
            include_once APPROOT . 'inc/PersonSearch.class.php';
            $personSearch = New PersonSearch();
            $personSearch->render();
            ?>
                        </div></div></div></fieldset>
            <?php
        } else {
            ?>
            <a class="show_search_form" href="#" id="show_related_person" style="display;"><?php echo _t('SHOW_SEARCH_FORM'); ?></a>
            <a class="hide_search_form" href="#" id="hide_related_person" style="display:none;"><?php echo _t('HIDE_SEARCH_FORM'); ?></a>
            <fieldset id='person_search_form' style='display:none;'>
                <div class="box" style="margin-top:10px">
                    <h4 class="box-header round-top"><?php echo _t('PERSONS') ?> </h4>
                    <div class="box-container-toggle">
                        <div class="box-content">
                            <?php
                            include_once APPROOT . 'inc/PersonSearch.class.php';
                            $personSearch = New PersonSearch();
                            $personSearch->render();
                            ?>
                        </div></div></div></fieldset>
            <?php
        }
        $extra_opts['help'] = null;
        shn_form_extra_opts($extra_opts);
        ?>
    </div>
    <?php
}

function shn_form_related_victim($label, $name, $extra_opts) {
    global $shn_tabindex;

    if (isset($_GET['person_id'])) {
        $_POST[$name] = $_GET['person_id'];
    }

    $value = ($_POST[$name]) ? $_POST[$name] : $extra_opts['value'];

    $person = new Person();
    $person->LoadFromRecordNumber($value);
    $person_name = $person->person_name;


    if (get_magic_quotes_gpc())
        $value = stripslashes($value);
    if ($extra_opts['no_label'] !== true && isset($label)) {
        ?>	
        <label  class="control-label" for="<?php echo $name ?>"><?php echo $label ?></label>
        <?php
    }
    ?>    
    <div class="controls">
        <input type="text" readonly="readonly" value="<?php echo $person_name ?>"/>
        <input type="hidden" name="<?php echo $name ?>" id="<?php echo $name ?>" readonly="true" value="<?php echo $value ?>"/>
        <?php
        if (isset($_POST['search']) || $_GET['request_page'] != null) {
            if ($_GET['person_id'] != null) {
                ?>
                <a class="show_search_form" href="#" id="show_related_victim" style="display:none;"><?php echo _t('SHOW_SEARCH_FORM'); ?></a>
                <a class="hide_search_form" href="#" id="hide_related_victim" style="display;"><?php echo _t('HIDE_SEARCH_FORM'); ?></a>
                <?php
            } else {
                ?>
                <a class="show_search_form" href="#" id="show_related_victim" style="display;"><?php echo _t('SHOW_SEARCH_FORM'); ?></a>
                <a class="hide_search_form" href="#" id="hide_related_victim" style="display:none;"><?php echo _t('HIDE_SEARCH_FORM'); ?></a>
                <?php
            }
            ?>
            <fieldset id='related_victim_search'>
                <div class="box" style="margin-top:10px">
                    <h4 class="box-header round-top"><?php echo _t('PERSONS') ?> </h4>
                    <div class="box-container-toggle">
                        <div class="box-content"><?php
            include_once APPROOT . 'inc/PersonVictimSearch.class.php';
            $personSearch = New PersonVictimSearch();
            $personSearch->render();
            ?></div></div></div></fieldset><?php
    } else {
        ?>
            <a class="show_search_form" href="#" id="show_related_victim" style="display;"><?php echo _t('SHOW_SEARCH_FORM'); ?></a>
            <a class="hide_search_form" href="#" id="hide_related_victim" style="display:none;"><?php echo _t('HIDE_SEARCH_FORM'); ?></a>
            <fieldset id='related_victim_search' style='display:none;'>
                <div class="box" style="margin-top:10px">
                    <h4 class="box-header round-top"><?php echo _t('PERSONS') ?> </h4>
                    <div class="box-container-toggle">
                        <div class="box-content">
                            <?php
                            include_once APPROOT . 'inc/PersonVictimSearch.class.php';
                            $personSearch = New PersonVictimSearch();
                            $personSearch->render();
                            ?></div></div></div></fieldset><?php
    }
    $extra_opts['help'] = null;
    shn_form_extra_opts($extra_opts);
    ?>
    </div>
    <?php
}

function shn_form_related_event($label, $name, $extra_opts) {
    global $shn_tabindex;

    if (isset($_GET['event_id'])) {
        $_POST[$name] = $_GET['event_id'];
    }

    $value = ($_POST[$name]) ? $_POST[$name] : $extra_opts['value'];

    $event = new Event();
    $event->LoadFromRecordNumber($value);
    $event_name = $event->event_title;

    if (get_magic_quotes_gpc()) {
        $value = stripslashes($value);
    }
    ?>	
    <label  class="control-label" for="<?php echo $name ?>"><?php echo $label ?></label>  
    <div class="controls">    
        <input type="text" readonly="readonly" value="<?php echo $event_name ?>"/>
        <input type="hidden" name="<?php echo $name ?>" id="<?php echo $name ?>" readonly="true" value="<?php echo $value ?>"/>

        <?php
        if (isset($_POST['search']) || $_GET['request_page'] != null) {
            if ($_GET['event_id'] != null) {
                ?>
                <a class="show_search_form" href="#" id="show_related_event" style="display:none;"><?php echo _t('SHOW_SEARCH_FORM'); ?></a>
                <a class="hide_search_form" href="#" id="hide_related_event" style="display;"><?php echo _t('HIDE_SEARCH_FORM'); ?></a>
                <?php
            } else {
                ?>
                <a class="show_search_form" href="#" id="show_related_event" style="display;"><?php echo _t('SHOW_SEARCH_FORM'); ?></a>
                <a class="hide_search_form" href="#" id="hide_related_event" style="display:none;"><?php echo _t('HIDE_SEARCH_FORM'); ?></a>
                <?php
            }
            ?><fieldset id='related_event_search'>
                <div class="box" style="margin-top:10px">
                    <h4 class="box-header round-top"><?php echo _t('EVENTS') ?> </h4>
                    <div class="box-container-toggle">
                        <div class="box-content">
                            <?php
                            include_once APPROOT . 'inc/EventSearch.class.php';
                            $data['event_id'] = $_GET['eid'];
                            $eventSearch = New EventSearch();
                            $eventSearch->render($data);
                            ?></div></div></div></fieldset>
            <?php
        } else {
            ?>
            <a class="show_search_form" href="#" id="show_related_event" style="display;"><?php echo _t('SHOW_SEARCH_FORM'); ?></a>
            <a class="hide_search_form" href="#" id="hide_related_event" style="display:none;"><?php echo _t('HIDE_SEARCH_FORM'); ?></a>
            <fieldset id='related_event_search' style='display:none;'>
                <div class="box" style="margin-top:10px">
                    <h4 class="box-header round-top"><?php echo _t('EVENTS') ?> </h4>
                    <div class="box-container-toggle">
                        <div class="box-content"><?php
            include_once APPROOT . 'inc/EventSearch.class.php';
            $data['event_id'] = $_GET['eid'];
            $eventSearch = New EventSearch();
            $eventSearch->render($data);
            ?></div></div></div></fieldset><?php
    }


    $extra_opts['help'] = null;
    shn_form_extra_opts($extra_opts);
    ?>
    </div>
    <?php
}

function shn_form_person_search($mod, $act, $view, $extra_opts) {
    include_once APPROOT . 'mod/person/relatedPersonSearch.class.php';
    $relatedPersonSearch = New RelatedPersonSearch();
    $data = array('mod' => $mod, 'act' => $act, 'view' => $view, 'cancel' => $extra_opts['cancel']);
    $relatedPersonSearch->render($data);
}

function shn_form_password($label, $name, $extra_opts) {
    global $shn_tabindex;
    if (isset($extra_opts['size']))
        $text_options .= " size='{$extra_opts['size']}' ";

    if (isset($extra_opts['class']))
        $class .= $extra_opts['class'];

    if (isset($extra_opts['error']))
        $class .= ' error ';

    if (isset($class))
        $text_options .= " class='$class'";

    if ($extra_opts['no_label'] !== true && isset($label)) {
        ?>	
        <label  class="control-label" for="<?php echo $name ?>"><?php echo $label ?></label>
        <?php
    }
    ?>	
    <div class="controls">
        <input type="password" name="<?php echo $name ?>" id="<?php echo $name ?>" tabindex="<?php echo++$shn_tabindex ?>" <?php echo $text_options ?>  />
        <?php
        shn_form_extra_opts($extra_opts);
        ?>
    </div>
    <?php
}

function shn_form_document($label, $name, $extra_opts) {
    $doclistarray = array();

    if (isset($_POST['supporting_documents'])) {
        foreach ($_POST['supporting_documents'] as $val) {
            if (!in_array($val, $doclistarray)) {
                $doclistarray[] = $val;
            }
        }
        unset($_POST['supporting_documents']);
    }

    if (isset($extra_opts['value'])) {
        foreach ($extra_opts['value'] as $val) {
            if (!in_array($val, $doclistarray)) {
                $doclistarray[] = $val;
            }
        }
        unset($extra_opts['value']);
    }

    if (isset($_POST['save_doc'])) {
        $document_form = document_form('new');
        unset($document_form['doc_id']);
        $status = shn_form_validate($document_form);
        if ($status) {
            $supporting_doc = new SupportingDocs();
            $supporting_doc->doc_id = shn_create_uuid('doc');
            $type = null;
            $uri = shn_files_store('choose_file_upload', null, $type);

            if ($uri == null) {
                $uri = '';
            }
            $supporting_doc->uri = $uri;
            $supporting_doc->Save();

            $supporting_docmeta = new SupportingDocsMeta();
            $supporting_docmeta->doc_id = $supporting_doc->doc_id;
            form_objects($document_form, $supporting_docmeta);
            $supporting_docmeta->format = $type;
            $supporting_docmeta->Save();

            if (!in_array($supporting_doc->doc_id, $doclistarray)) {
                $doclistarray[] = $supporting_doc->doc_id;
            }

            foreach ($document_form as $key => $field) {
                unset($_POST[$key]);
            }
        }
    }
    ?>	
    <a name='document_field'></a>
    <label  class="control-label" for="<?php echo $name ?>"><?php echo $label ?></label>	
    <div class="controls">
        <?php
        if (isset($_POST['search']) || $_GET['request_page'] != null || isset($_POST['new_doc'])) {
            ?>
            <a class="show_search_form" href="#document_field" id="show_document" style="display:none;"><?php echo _t('SHOW_SUPPORTING_DOCUMENT_S_'); ?></a>
            <a class="hide_search_form" href="#document_field" id="hide_document" style="display;"><?php echo _t('HIDE_SUPPORTING_DOCUMENT_S_'); ?></a>
            <?php
        } else {
            ?>
            <a class="show_search_form" href="#document_field" id="show_document" style="display;"><?php echo _t('SHOW_SUPPORTING_DOCUMENT_S_'); ?></a>
            <a class="hide_search_form" href="#document_field" id="hide_document" style="display:none;"><?php echo _t('HIDE_SUPPORTING_DOCUMENT_S_'); ?></a>
            <?php
        }
        ?>

        <div class='document_list' id="document_list" >
            <ol class='asmList' id="doclist">
                <?php
                if (count($doclistarray) != 0) {
                    $doc_obj = new SupportingDocsMeta();
                    for ($i = 0; $i < count($doclistarray); $i++) {
                        $doc_obj->LoadFromRecordNumber($doclistarray[$i]);
                        echo "<li id='$i'>";
                        echo $doc_obj->title;
                        echo "<input type='hidden' name='supporting_documents[]' value='" . $doclistarray[$i] . "' />";
                        echo "&nbsp;&nbsp;&nbsp;<a href='#document_field' onclick='removeElement($i);'>" . _t('REMOVE') . "</a>";
                        echo "</li>";
                    }
                }
                ?>
            </ol>
        </div>

        <?php
        if (isset($_POST['search']) || $_GET['request_page'] != null || isset($_POST['new_doc'])) {
            ?><fieldset id = document_search> <div class="box" style="margin-top:10px">
                    <h4 class="box-header round-top"><?php echo _t('DOCUMENTS') ?> </h4>
                    <div class="box-container-toggle">
                        <div class="box-content"><?php
            include_once APPROOT . 'inc/DocumentSearch.class.php';
            $documentSearch = New DocumentSearch();
            $documentSearch->render();
            ?></div></div></div></fieldset><?php
    } else {
        ?><fieldset id='document_search' style='display:none;'>
                <div class="box" style="margin-top:10px">
                    <h4 class="box-header round-top"><?php echo _t('DOCUMENTS') ?> </h4>
                    <div class="box-container-toggle">
                        <div class="box-content"><?php
        include_once APPROOT . 'inc/DocumentSearch.class.php';
        $documentSearch = New DocumentSearch();
        $documentSearch->render();
            ?></div></div></div></fieldset><?php
    }
    unset($doclistarray);
    $extra_opts['help'] = null;
    shn_form_extra_opts($extra_opts);
    ?>
    </div>
    <?php
}

function shn_form_address($label, $name, $extra_opts) {
    ?>
    <a name='address_field'></a>	
    <label  class="control-label" for="<?php echo $name ?>"><?php echo $label ?></label>	
    <div class="controls">
        <a id="show_address_frm" href="#address_field"><?php echo _t('SHOW_ADDRESS_FORM'); ?></a>
        <a id="hide_address_frm" href="#address_field"><?php echo _t('HIDE_ADDRESS_FORM'); ?></a>
        <br />
        <br />	
        <fieldset id='address_frm' >
            <div class="box" style="margin-top:10px">
                <h4 class="box-header round-top"><?php echo _t('ADDRESS') ?> </h4>
                <div class="box-container-toggle">
                    <div class="box-content">
                        <?php
                        include_once APPROOT . 'inc/AddAddress.class.php';
                        $addAddress = New AddAddress();
                        $addAddress->render();
                        ?>
                    </div></div></div></fieldset>
        <?php
        if ((is_array($extra_opts['value']) && count($extra_opts['value']) != 0) || (isset($_POST['person_address']))) {
            ?>
            <fieldset> <div class="box" style="margin-top:10px">
                    <h4 class="box-header round-top"><?php echo _t('Address') ?> </h4>
                    <div class="box-container-toggle">
                        <div class="box-content">
                            <table id="addressTable" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th><?php echo _t('ADDRESS_TYPE') ?></th>
                                        <th><?php echo _t('ADDRESS') ?></th>
                                        <th><?php echo _t('COUNTRY') ?></th>
                                        <th><?php echo _t('PHONE') ?></th>
                                        <th><?php echo _t('CELLULAR') ?></th>
                                        <th><?php echo _t('FAX') ?></th>
                                        <th><?php echo _t('EMAIL') ?></th>
                                        <th><?php echo _t('WEBSITE') ?></th>
                                        <th><?php echo _t('START_DATE') ?></th>
                                        <th><?php echo _t('END_DATE') ?></th>
                                        <th><?php echo _t('ACTION') ?></th>				
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $address = new Address();
                                    $count = 0;


                                    if (is_array($extra_opts['value']) && count($extra_opts['value']) != 0) {
                                        foreach ($extra_opts['value'] as $add) {
                                            $address->LoadfromRecordNumber($add);
                                            ?>
                                            <tr id="<?php echo $count; ?>">
                                                <td><?php echo get_mt_term($address->address_type); ?><input name="person_address[<?php echo $add; ?>][address_count]" value="<?php echo $count; ?>" type="hidden"><input name="person_address[<?php echo $add; ?>][address_type]" value="<?php echo $address->address_type; ?>" type="hidden"><input name="person_address[<?php echo $add; ?>][address_record_number]" value="<?php echo $address->address_record_number; ?>" type="hidden"></td>
                                                <td><?php echo $address->address; ?><input name="person_address[<?php echo $add; ?>][address]" value="<?php echo $address->address; ?>" type="hidden"></td>
                                                <td><?php echo get_mt_term($address->country); ?><input name="person_address[<?php echo $add; ?>][country]" value="<?php echo $address->country; ?>" type="hidden"></td>
                                                <td><?php echo $address->phone; ?><input name="person_address[<?php echo $add; ?>][phone]" value="<?php echo $address->phone; ?>" type="hidden"></td>
                                                <td><?php echo $address->cellular; ?><input name="person_address[<?php echo $add; ?>][cellular]" value="<?php echo $address->cellular; ?>" type="hidden"></td>
                                                <td><?php echo $address->fax; ?><input name="person_address[<?php echo $add; ?>][fax]" value="<?php echo $address->fax; ?>" type="hidden"></td>
                                                <td><?php echo $address->email; ?><input name="person_address[<?php echo $add; ?>][email]" value="<?php echo $address->email; ?>" type="hidden"></td>
                                                <td><?php echo $address->web; ?><input name="person_address[<?php echo $add; ?>][web]" value="<?php echo $address->web; ?>" type="hidden"></td>
                                                <td><?php echo $address->start_date; ?><input name="person_address[<?php echo $add; ?>][start_date]" value="<?php echo $address->start_date; ?>" type="hidden"></td>
                                                <td><?php echo $address->end_date; ?><input name="person_address[<?php echo $add; ?>][end_date]" value="<?php echo $address->end_date; ?>" type="hidden"></td>
                                                <td><a id="edit_address_frm" href="#address_field" onclick="edit_address('<?php echo $add; ?>');"><?php echo _t('EDIT'); ?></a>&nbsp;|&nbsp;<a href="#address_field" onclick="removeElement('<?php echo $count; ?>');" ><?php echo _t('REMOVE'); ?></a></td>
                                            </tr>
                                            <?php
                                            $count++;
                                        }
                                    }


                                    if (is_array($_POST['person_address']) && count($_POST['person_address']) != 0) {
                                        foreach ($_POST['person_address'] as $key => $add) {
                                            if (!in_array($key, $extra_opts['value'])) {
                                                ?>
                                                <tr id="<?php echo $count; ?>">
                                                    <td><?php echo get_mt_term($_POST['person_address'][$key]['address_type']); ?><input name="person_address[<?php echo $key; ?>][address_type]" value="<?php echo $_POST['person_address'][$key]['address_type']; ?>" type="hidden"></td>
                                                    <td><?php echo $_POST['person_address'][$key]['address']; ?><input name="person_address[<?php echo $key; ?>][address]" value="<?php echo $_POST['person_address'][$key]['address']; ?>" type="hidden"></td>
                                                    <td><?php echo get_mt_term($_POST['person_address'][$key]['country']); ?><input name="person_address[<?php echo $key; ?>][country]" value="<?php echo $_POST['person_address'][$key]['country']; ?>" type="hidden"></td>
                                                    <td><?php echo $_POST['person_address'][$key]['phone']; ?><input name="person_address[<?php echo $key; ?>][phone]" value="<?php echo $_POST['person_address'][$key]['phone']; ?>" type="hidden"></td>
                                                    <td><?php echo $_POST['person_address'][$key]['cellular']; ?><input name="person_address[<?php echo $key; ?>][cellular]" value="<?php echo $_POST['person_address'][$key]['cellular']; ?>" type="hidden"></td>
                                                    <td><?php echo $_POST['person_address'][$key]['fax']; ?><input name="person_address[<?php echo $key; ?>][fax]" value="<?php echo $_POST['person_address'][$key]['fax']; ?>" type="hidden"></td>
                                                    <td><?php echo $_POST['person_address'][$key]['email']; ?><input name="person_address[<?php echo $key; ?>][email]" value="<?php echo $_POST['person_address'][$key]['email']; ?>" type="hidden"></td>
                                                    <td><?php echo $_POST['person_address'][$key]['web']; ?><input name="person_address[<?php echo $key; ?>][web]" value="<?php echo $_POST['person_address'][$key]['web']; ?>" type="hidden"></td>
                                                    <td><?php echo $_POST['person_address'][$key]['start_date']; ?><input name="person_address[<?php echo $key; ?>][start_date]" value="<?php echo $_POST['person_address'][$key]['start_date']; ?>" type="hidden"></td>
                                                    <td><?php echo $_POST['person_address'][$key]['end_date']; ?><input name="person_address[<?php echo $key; ?>][end_date]" value="<?php echo $_POST['person_address'][$key]['end_date']; ?>" type="hidden"></td>
                                                    <td><a href="#address_field" onclick="removeElement('<?php echo $count; ?>');" ><?php echo _t('REMOVE'); ?></a></td>
                                                </tr>
                                                <?php
                                                $count++;
                                            }
                                        }
                                        unset($_POST['person_address']);
                                    }
                                    ?>		
                                </tbody>
                            </table>
                        </div></div></div></fieldset>
            <?php
        } else {
            ?>
            <fieldset id="addressArrayList">
                <div class="box" style="margin-top:10px">
                    <h4 class="box-header round-top"><?php echo _t('Address') ?> </h4>
                    <div class="box-container-toggle">
                        <div class="box-content">
                            <table id="addressTable" name="addressTable" style="display:none;" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th><?php echo _t('ADDRESS_TYPE') ?></th>
                                        <th><?php echo _t('ADDRESS') ?></th>
                                        <th><?php echo _t('COUNTRY') ?></th>
                                        <th><?php echo _t('PHONE') ?></th>
                                        <th><?php echo _t('CELLULAR') ?></th>
                                        <th><?php echo _t('FAX') ?></th>
                                        <th><?php echo _t('E_MAIL') ?></th>
                                        <th><?php echo _t('WEB') ?></th>
                                        <th><?php echo _t('START_DATE') ?></th>
                                        <th><?php echo _t('END_DATE') ?></th>
                                        <th><?php echo _t('ACTION') ?></th>				
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div></div></div></fieldset>
            <?php
        }
        ?>
    </div>
    <?php
}

function shn_form_location($label, $name, $extra_opts) {
    //var_dump($extra_opts);exit;
    global $global;
    $global["js"][] = "map";

    $value = (isset($_POST[$name]) ? $_POST[$name] : $extra_opts['value']);
    //to prevent warnings set $options to array() if it is null
    $options = ($options == null) ? array() : $options;

    if (!isset($value['latitude']) && !isset($value['longitude'])) {

        /* $value['latitude'] = 46.1983922;
          $value['longitude'] = 6.1422961; */
    }
    if (isset($label)) {
        ?>
        <label  class="control-label" for="<?php echo $name ?>"><?php echo $label ?></label>
    <?php } ?>
    <div class="controls">
        <div class="locationFieldsConatiner">
            <input type="hidden" name="<?php echo $name ?>_latitude" id="<?php echo $name ?>_latitude" value="<?php if ($value['latitude']) echo $value['latitude'] ?>">
            <input type="hidden" name="<?php echo $name ?>_longitude" id="<?php echo $name ?>_longitude" value="<?php if ($value['longitude']) echo $value['longitude'] ?>">
            <input type="hidden" name="<?php echo $name ?>_country_name" id="<?php echo $name ?>_country_name" value="Armenia" />
            <input type="hidden" name="<?php echo $name ?>_incident_zoom" id="<?php echo $name ?>_incident_zoom" value="" />

            <div id="<?php echo $name ?>_mapContainer" class="mapContainer"></div>
            <div class="findLocation">
                <div id="<?php echo $name ?>_editContainer" class="olControlEditingToolbar editContainer"></div>
                <div class="locationButtons" >
                    <ul >
                        <li><a href="#" class="<?php echo $name ?>_locationButtonsLast" onclick="return false;">DELETE LAST</a></li>
                        <li><a href="#" class="<?php echo $name ?>_locationButtonsDelete" onclick="return false;">DELETE SELECTED</a></li>
                        <li><a href="#" class="<?php echo $name ?>_locationButtonsClear" onclick="return false;">CLEAR MAP</a></li>
                    </ul>
                </div>
                <div style="clear:both;"></div>
                <input type="text" id="<?php echo $name ?>_locationFind" name="<?php echo $name ?>_location_find" value=""  title="City, State and/or Country" class="findtext" />						
                <div style="float:left;margin:9px 0 0 5px;">
                    <input type="button" name="button" id="button" value="Find Location" class="<?php echo $name ?>_buttonFind" />
                </div>
                <div id="<?php echo $name ?>_findLoading" class="<?php echo $name ?>_findLoading"></div>
                <div style="clear:both;" id="<?php echo $name ?>_find_text">* Search for your location using a location name OR latitude,longitude coordinates (format: 38.19, 85.61), OR click on the map to pinpoint the correct location..</div>
            </div>
        </div>
        <script type="text/javascript">
            jQuery(window).load(function() {
                initEditMap(<?php
    $value["mapContainer"] = $name . "_mapContainer";
    $value["editContainer"] = $name . "_editContainer";
    $value["fieldName"] = $name;
    echo json_encode($value);
    ?>);
        });
        </script>
        <?php
        if ($extra_opts['clari']) {
            if (is_array($extra_opts['clari_value']) && count($extra_opts['clari_value']) > 0)
                $clari_value = array_pop($extra_opts['clari_value']);
            shn_form_clari($name, $clari_value);
        }

        shn_form_extra_opts($extra_opts);
        ?>
    </div>
    <?php
}

function shn_form_line($label, $name, $extra_opts) {
    global $shn_tabindex;
    if ($extra_opts['no_label'] !== true) {
        ?>
        <label  class="control-label" for="<?php echo $name ?>" style="font-weight: bold;"><?php echo $label ?></label><br/>
        <?php
    }
    ?>
    <hr/>
    <?php
}
