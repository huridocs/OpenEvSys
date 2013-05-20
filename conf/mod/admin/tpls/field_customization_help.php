<?php global $conf; ?>

    <div id="browse">
<input type="hidden" name="save_help" value="1" />
        <table class='table table-bordered table-striped table-hover'>
            <thead>
                <tr>
                    <th><?php echo(_t('FIELD_LABEL')); ?></th>
                    <th><?php echo _t('SECTION') ?></th>
                    
                    <th>
            <ul class="nav nav-tabs" id="helpTabs">   
                <?php
                foreach ($locales as $code => $loc) {
                    ?>
                    <li <?php
            if ($locale == $code) {
                echo 'class="active"';
            }
                    ?>><a href="#<?php echo $code ?>" data-toggle="tab" data-locale="<?php echo $code ?>"><?php echo $loc ?></a></li>
                        <?php }
                    ?>
            </ul>
            </th>
            </tr>
            </thead>
            <tbody>
                <?php
                foreach ($help_texts as $record) {
                    ?>
                    <tr <?php echo ($i++ % 2 == 1) ? 'class="odd"' : ''; ?> >
                        <td rowspan='4'><?php echo $record['field_label']; ?></td>
                        <td><?php echo(_t('DEFINITION')); ?></td>
                        <td>
                            <?php
                            foreach ($locales as $code => $loc) {
                                ?>
                                <div class="helpdefinitiondiv helpdefinitiondiv_<?php echo $code ?>" <?php
                    if ($locale != $code) {
                        echo 'style="display:none"';
                    }
                                ?>>
                                <?php $name = 'definition_' . $record['field_number']; //echo $record['field_label'];   ?>
                                    <textarea class="input-block-level" name='<?php echo $name; ?>[<?php echo $code ?>]'><?php if ($record['definition_' . $code]) echo $record['definition_' . $code]; ?></textarea>
                                </div>
                                <?php
                            }
                            ?>
                        </td>
                    </tr>
                    <tr <?php echo ($i++ % 2 == 1) ? 'class="odd"' : ''; ?>>
                        <td><?php echo(_t('GUIDELINES')); ?></td>
                        <td>
                            <?php
                            foreach ($locales as $code => $loc) {
                                ?>
                                <div class="helpguidelinesdiv helpguidelinesdiv_<?php echo $code ?>" <?php
                    if ($locale != $code) {
                        echo 'style="display:none"';
                    }
                                ?>>
                                <?php $name = 'guidelines_' . $record['field_number']; //echo $record['field_label'];   ?>
                                    <textarea  class="input-block-level" name='<?php echo $name; ?>[<?php echo $code ?>]'><?php if ($record['guidelines_' . $code]) echo $record['guidelines_' . $code]; ?></textarea>
                                </div>
                                <?php
                            }
                            ?>
                        </td>
                      
                    </tr>
                    <tr <?php echo ($i++ % 2 == 1) ? 'class="odd"' : ''; ?>>
                        <td><?php echo(_t('ENTRY')); ?></td>
                        <td>
                            <?php
                            foreach ($locales as $code => $loc) {
                                ?>
                                <div class="helpentrydiv helpentrydiv_<?php echo $code ?>" <?php
                    if ($locale != $code) {
                        echo 'style="display:none"';
                    }
                                ?>>
                                <?php $name = 'entry_' . $record['field_number']; //echo $record['field_label'];   ?>
                                    <textarea  class="input-block-level" name='<?php echo $name; ?>[<?php echo $code ?>]'><?php if ($record['entry_' . $code]) echo $record['entry_' . $code]; ?></textarea>
                                </div>
                                <?php
                            }
                            ?>
                        </td>
                    </tr>
                    <tr <?php echo ($i++ % 2 == 1) ? 'class="odd"' : ''; ?>>
                        <td><?php echo(_t('EXAMPLE')); ?></td>
                        <td>
                            <?php
                            foreach ($locales as $code => $loc) {
                                ?>
                                <div class="helpexamplesdiv helpexamplesdiv_<?php echo $code ?>" <?php
                    if ($locale != $code) {
                        echo 'style="display:none"';
                    }
                                ?>>
                                <?php $name = 'examples_' . $record['field_number']; //echo $record['field_label'];   ?>
                                    <textarea  class="input-block-level" name='<?php echo $name; ?>[<?php echo $code ?>]'><?php if ($record['examples_' . $code]) echo $record['examples_' . $code]; ?></textarea>
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
   
<script>
    $('#helpTabs a').click(function (e) {
        e.preventDefault();
       var locale = $(this).data('locale');
       $('.helpdefinitiondiv').hide();
       $('.helpguidelinesdiv').hide();
       $('.helpentrydiv').hide();
       $('.helpexamplesdiv').hide();
       
       $('.helpdefinitiondiv_'+locale).show();
       $('.helpguidelinesdiv_'+locale).show();
       $('.helpentrydiv_'+locale).show();
       $('.helpexamplesdiv_'+locale).show();
    })
</script>