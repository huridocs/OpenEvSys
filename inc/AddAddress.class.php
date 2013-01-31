<?php

class AddAddress {

    public function render() {
        $this->address_form();
    }

    public function address_form() {
        include_once APPROOT . 'inc/lib_form_util.inc';

        echo "<h4>" . _t('ADDING_ADDRESS___') . "</h4>";

        $address_form = address_form('new');
        $fields = shn_form_get_html_fields($address_form);
        $fields = place_form_elements($address_form, $fields);

        foreach ($address_form as $field_id => $field_name) {
            $validate_field_name = "validate_" . $field_id;
            $validate_field_value = $field_name['extra_opts']['validation'][1];
            echo "<input type='hidden' name='$validate_field_name' value='$validate_field_value'/>";
        }
        ?>		
        <div class="control-group">
            <div class="controls"> 
                <button type="submit" class="btn" name="save_address"  onclick="listAddress(); return false;" ><i class="icon-ok"></i> <?php echo _t('SAVE_ADDRESS') ?></button>
                <a class="btn" id="close_address_frm" href="#address_field"><i class="icon-remove"></i> <?php echo _t('CLOSE'); ?></a>
            </div></div>

        <?php
        
    }

}
?>
