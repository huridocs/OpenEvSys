<?php global $conf; ?>
<div id="browse">
    <div class="pull-left" >

        <select name="bulkaction" class="select">

            <option value=""></option>
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
                <th ><input type="checkbox" class="checkall"/></th>

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
                    <td> <input  type="checkbox" class="chk" name="vocab_number_list[<?php echo $record['vocab_number']; ?>]"  value="1"
                        <?php
                        if ($_POST['bulkaction'] == "deleteselected" && is_array($_POST['vocab_number_list']) && isset($_POST['vocab_number_list'][$record['vocab_number']])) {
                            echo " checked='checked' ";
                        }
                        ?>
                                 ></input>
                    </td>
                    <td><?php
                    if ($record['visible'] == 'n') {
                        echo "<a href='#' style='color:grey;font-style: italic;' data-toggle='tooltip' title='" . _t('disabled') . "'>";
                    }
                    echo $record['vocab_number'];
                    if ($record['visible'] == 'n') {
                        echo "</a>";
                    }
                        ?></td>
                    <td>
                        <?php
                        foreach ($locales as $code => $loc) {
                            ?>
                            <div class="labelinputdiv labelinputdiv_<?php echo $code ?>" <?php
                    if ($locale != $code) {
                        echo 'style="display:none"';
                    }
                            ?>>
                                <input  type="text" name="label[<?php echo $record['vocab_number']; ?>][<?php echo $code ?>]"  value="<?php if ($record['label_' . $code]) echo htmlentities ($record['label_' . $code]); ?>"></input>

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
   
    $(document).ready(function(){
        $('#langTabs a').click(function (e) {
            e.preventDefault();
            var locale = $(this).data('locale');
            $('.labelinputdiv').hide();
            $('.labelinputdiv_'+locale).show();
        });
    
        $('.checkall').click(function(){
        $(".chk").prop("checked",$(".checkall").prop("checked"))
        }) ;
    });
    function add_new_mt()
    {
        var template = "";
        template += "<tr><td></td><td></td><td>";
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
                    $('#mtlist > tbody').prepend(template);
                    /*if($('#mtlist > tbody > tr:first').lenght){
                        $('#mtlist > tbody > tr:first').before(template);
                    }else{
                        $('#mtlist > tbody:last').append(template);
                    }*/
                }
</script>
