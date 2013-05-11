<?php global $conf; ?>
<div id="browse">
    <div class="pull-left" >
        
  <select name="action" class="select">
      <option value="deleteselected"><?php echo _t('Delete') ?></option>
      <option value="updateselected"><?php echo _t('Update') ?></option>
      <option value="visible"><?php echo _t('Set visible ') ?></option>
      <option value="disable"><?php echo _t('Disable') ?></option>
  </select>
        <button type="submit" name="apply" class='btn'  ><i class="icon-ok"></i> 
             <?php echo _t('Apply') ?></button>
</div>
    <br/>
    <table class='table table-bordered table-striped table-hover' id="mtlist">
        <thead>
            <tr>
                <th >&nbsp;</th>

                <th ><?php echo(_t('Vocab number')); ?></th>
                <th>
        <ul class="nav nav-tabs" id="langTabs">   
            <?php
            foreach ($locales as $code => $loc) {
                ?>
                <li <?php
            if ($locale == $code) {
                echo 'class="active"';
            }
                ?>><a href="#<?php echo $code ?>" data-toggle="tab" data-locale="<?php echo $code ?>"><?php echo $loc ?></a></li>
                    <?php
                }
                ?>
        </ul>
        </th>
        </tr>
        </thead>
        <tbody>
            <?php
            foreach ($res as $record) {
                ?>
                <tr <?php echo ($i++ % 2 == 1) ? 'class="odd"' : ''; ?>>
                    <td> <input  type="checkbox" name="vocab_number_list[<?php echo $record['vocab_number']; ?>]"  value="1"
                        <?php
                        if (is_array($_POST['vocab_number_list']) && isset($_POST['vocab_number_list'][$record['vocab_number']])) {
                            echo " checked='checked' ";
                        }
                        ?>
                                 ></input>
                    </td>
                    <td><?php echo $record['vocab_number']; ?></td>
                    <td>
                        <?php
                        foreach ($locales as $code => $loc) {
                            ?>
                            <div class="labelinputdiv labelinputdiv_<?php echo $code ?>" <?php
                    if ($locale != $code) {
                        echo 'style="display:none"';
                    }
                            ?>>
                                <input  type="text" name="label[<?php echo $record['vocab_number']; ?>][<?php echo $code ?>]"  value="<?php if ($record['label_' . $code]) echo $record['label_' . $code]; ?>"></input>

                            </div>
                            <?php
                        }
                        ?>
                    </td>

                </tr>
            <?php } ?>

        </tbody>
    </table>
</div>
<input type="hidden" name="mt_label" id="mt_label" value="used"/>

<script>
    $('#langTabs a').click(function (e) {
        e.preventDefault();
        var locale = $(this).data('locale');
        $('.labelinputdiv').hide();
        $('.labelinputdiv_'+locale).show();
    })
</script>
<script language='javascript'>
    function add_new_mt()
    {
        var template = "";
        template += "<tr><td></td><td><input type='text' name='new_vocab_number[]'   /></td><td>";
        template +="<?php
            foreach ($locales as $code => $loc) {
                echo "<div class='labelinputdiv labelinputdiv_" . $code . "' ";
                if ($locale != $code) {
                    echo "style='display:none'";
                }
                echo ">";
                echo "<input  type='text' name='new_term_label[" . $code . "][]'  /></div>";
            }
            ?>";
                    template += "</td></tr>";

                    $('#mtlist > tbody > tr:first').before(template);
                }
</script>
