<?php global $conf; ?>
<div id="browse">
    <table class='table table-bordered table-striped table-hover'>
        <thead>
            <tr>
                <th ><?php echo(_t('FIELD_NUMBER')); ?></th>
                <th ><?php echo(_t('FIELD_NAME')); ?></th>
                <th ><?php echo(_t('FIELD_TYPE')); ?></th>
                <!--<th><?php echo(_t('LABEL')); ?></th>-->
                <th>
        <ul class="nav nav-tabs" id="langTabs">   
            <?php
            foreach ($locales as $code => $loc) {
                ?>
                <li <?php if ($locale == $code) {
                echo 'class="active"';
            } ?>><a href="#<?php echo $code ?>" data-toggle="tab"><?php echo $loc ?></a></li>
                <?php
            }

            /* if(isset($locale)){
              ?>
              <th><?php echo(_t('LABEL_IN_')).$locale; ?></th>
              <?php
              } */
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
                    <td><?php echo $record['field_number']; ?></td>
                    <td><?php echo $record['field_name']; ?></td>
                    <td><?php echo $record['field_type']; ?></td>
                    <td>
                        <?php
                        foreach ($locales as $code => $loc) {
                            ?>
                            <div <?php if ($locale != $code) {
                        echo 'style="display:none"';
                    } ?>>
        <?php $name = 'label_' . $record['field_number']; //echo $record['field_label'];  ?>
                                <input  type="text" name="<?php echo $name; ?>_<?php echo $locale ?>" id="<?php echo $name; ?>_<?php echo $locale ?>"  value="<?php echo $record['field_label']; ?>"></input>

                            </div>
                            <?php
                        }
                        ?>
    <?php $name = 'label_' . $record['field_number']; //echo $record['field_label'];  ?>
                                  <!-- <input  type="text" name="<?php echo $name; ?>" id="<?php echo $name; ?>"  value="<?php echo $record['field_label']; ?>"></input>
                        -->
                    </td>
                    <?php
                    /* if(isset($locale)){
                      ?>
                      <td>
                      <?php $name = 'label_l10n_'. $record['field_number']; //echo $record['field_label']; ?>
                      <input type="text" name="<?php echo $name;?>" id="<?php echo $name;?>"  value="<?php echo $record['field_label_l10n'];?>"></input>
                      </td>
                      <?php
                      } */
                    ?>
                </tr>
<?php } ?>
           <!-- <tr  <?php echo ($i++ % 2 == 1) ? 'class="odd"' : ''; ?>>
                <td>  </td>
                <td>  </td>
                <td>  </td>
                <td align="center"> 
            <?php echo $fields1['reset_label'] ?> 
                </td>
            <?php
            if (isset($locale)) {
                ?>
                    <td>  </td>
                <?php
            }
            ?>
            </tr>-->
        </tbody>
    </table>
</div>
<input type="hidden" name="label" id="label" value="used"/>

<script>
    $('#langTabs a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    })
</script>