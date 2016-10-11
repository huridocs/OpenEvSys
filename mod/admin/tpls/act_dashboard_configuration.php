<?php
global $conf;
?><h2><?php echo _t('Dashboard Configuration') ?></h2>
<br>
<form class="form-horizontal" action='<?php
echo get_url('admin', 'dashboard_configuration')
?>' method='post'>

    <center>
        <br/>
        <button type="submit" class="btn btn-primary" name='submit'><i
                class="icon-ok icon-white"></i> <?php echo _t('SAVE') ?></button>
    </center>
    <h2>Dasboard Format Counts</h2>
    <table class='table table-bordered table-striped table-hover'>
        <thead>
        <tr>
            <th><?php echo _t("Format") ?></th>
            <th><?php echo _t("Show") ?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        $i = 0;

        $activeFormats = getActiveFormats();
        foreach ($activeFormats as $key => $value) {
            ?>
            <tr <?php echo ($i++ % 2 == 1) ? 'class="odd"' : ''; ?>>
                <td>
                    <?php echo $value; ?>
                </td>

                <td>
                    <input value='true'
                           type='checkbox' name='dashboard_format_counts_<?php echo $key; ?>'
                        <?php
                        $checked = ($conf['dashboard_format_counts_' . $key] == 'true') ? 'checked="true"' : '';
                        echo $checked;
                        ?>

                        />

                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <h2>Dasboard Counts by Date</h2><br/>
    <select class="mt_select input-large" name="dashboard_date_counts[]"
            multiple="multiple">
        <?php
        $res = Browse::getAllEntityFields();
        $dashboard_date_counts = array();
        if ($conf['dashboard_date_counts']) {
            $dashboard_date_counts = @json_decode($conf['dashboard_date_counts']);
            $dashboard_date_counts = (array)$dashboard_date_counts;
        }
        foreach ($res as $record) {
            $entity = $record['entity'];
            if (!$activeFormats[$entity] || !in_array($record['field_type'], array("date"))) {
                continue;
            }
            $selVal = $entity . "|||" . $record['field_name'];
            ?>
            <option value="<?php echo $selVal ?>"
                <?php if (in_array($selVal, $dashboard_date_counts)) {
                    echo ' selected="selected" ';
                } ?>
                ><?php echo $activeFormats[$entity] . " - " . $record['field_label'] ?></option>
        <?php
        }
        ?>
    </select>

    <h2>Dasboard Counts By Micro-thesauri</h2><br/>
    <select class="mt_select input-large" name="dashboard_select_counts[]"
            multiple="multiple">
        <?php
        $res = Browse::getAllEntityFields();
        $dashboard_select_counts = array();
        if ($conf['dashboard_select_counts']) {
            $dashboard_select_counts = @json_decode($conf['dashboard_select_counts']);
            $dashboard_select_counts = (array)$dashboard_select_counts;
        }
        foreach ($res as $record) {
            $entity = $record['entity'];
            if (!$activeFormats[$entity] || !in_array($record['field_type'], array("mt_tree", "mt_select"))) {
                continue;
            }
            $selVal = $entity . "|||" . $record['field_name'];
            ?>
            <option value="<?php echo $selVal ?>"
                <?php if (in_array($selVal, $dashboard_select_counts)) {
                    echo ' selected="selected" ';
                } ?>
                ><?php echo $activeFormats[$entity] . " - " . $record['field_label'] ?></option>
        <?php
        }
        ?>
    </select>


    <br/> <br/> <br/>

    <button type="submit" class="btn btn-primary" name='submit'><i
            class="icon-ok icon-white"></i> <?php echo _t('SAVE') ?></button>
    </center>
</form>