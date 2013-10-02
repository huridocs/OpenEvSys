<?php global $conf; ?>
<h2><?php echo _t('MICRO_THESAURI_CUSTOMIZATION') ?></h2>
<h3><?php echo _t('LABELS') ?></h3>

    <form class="form-horizontal"  action='<?php echo get_url('admin', 'mt_translate') ?>' method='post'>
      
            <button type="submit" name="update" class='btn  btn-primary'  ><i class="icon-ok icon-white"></i> <?php echo _t('SAVE') ?></button>

            <table class='table table-bordered table-striped table-hover'>
                <thead>
                    <tr>
                        <th ><?php echo(_t('Number')); ?></th>
                        <th ><?php echo(_t('Term')); ?></th>
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
                    foreach ($mts as $no => $term) {
                        ?>
                        <tr <?php echo ($i++ % 2 == 1) ? 'class="odd"' : ''; ?>>
                            <td><?php echo $no; ?></td>
                            <td><?php echo $term; ?></td>
                            <td>
                                <?php
                                foreach ($locales as $code => $loc) {
                                    ?>
                                    <div class="labelinputdiv labelinputdiv_<?php echo $code ?>" <?php
                            if ($locale != $code) {
                                echo 'style="display:none"';
                            }
                                    ?>>
                                         <?php $name = 'label_' . $no; ?>
                                        <input  type="text" name="<?php echo $name; ?>[<?php echo $code ?>]" id="<?php echo $name; ?>_<?php echo $code ?>"  value="<?php if ($translations[$no][$code]) echo htmlentities($translations[$no][$code]['value'], ENT_QUOTES, "UTF-8"); ?>"></input>

                                    </div>
                                    <?php
                                }
                                ?>

                            </td>

                        </tr>
                    <?php } ?>

                </tbody>
            </table>
       
        <script>
            $('#langTabs a').click(function (e) {
                e.preventDefault();
                var locale = $(this).data('locale');
                $('.labelinputdiv').hide();
                $('.labelinputdiv_'+locale).show();
            })
        </script>

        <input type="hidden" name="save" value="1" />
            <button type="submit" name="update" class='btn  btn-primary'  ><i class="icon-ok icon-white"></i> <?php echo _t('SAVE') ?></button>

    </form>
