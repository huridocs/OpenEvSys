<div class="panel">

    <?php
    if ($delete) {
        ?>
        <form class="form-horizontal"  action="<?php get_url('analysis', 'delete_query') ?>" method='post'>
            <div class="alert alert-error">
                <h3><?php echo _t('DO_YOU_WANT_TO_DELETE_THE_SELECTED_QUERIES__') ?></h3>
                <br />
                <center>
                    <button type='submit' class='btn btn-danger' name='delete_yes' ><i class="icon-trash icon-white"></i> <?php echo _t('DELETE') ?></button>
                    <button type='submit' class='btn' name='no' ><i class="icon-stop"></i> <?php echo _t('CANCEL') ?></button>

                </center>
            </div>   
            <?php
            foreach ($_GET['sq'] as $query) {
                ?><input type='hidden' value='<?php echo $query ?>' name='sq[]' /><?php
    }
            ?>
        </form>
        <?php
    }
    ?>
    <form action="<?php get_url('analysis', 'search_query') ?>" method='get' >
        <input type='hidden' name='mod' value='analysis' />
        <input type='hidden' name='act' value='search_query' />
        <?php
        if ($query_list != null && $query_list->RecordCount()) {
            $query_result_pager->render_pages();
            $types = array('' => '', 'advanced' => _t('ADVANCED'), 'basic' => _t('BASIC'));
            ?>
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr class='top_but'>
                        <td colspan='7'>
                            <button type='submit' class='btn' name='filter' ><i class="icon-filter"></i> <?php echo _t('FILTER') ?></button>
                            <a href="<?php get_url('analysis', 'search_query') ?>" class='btn'><i class="icon-remove"></i> <?php echo _t('RESET') ?></a>
                        </td>
                    <tr>
                    <tr class='filter'>
                        <td></td>
                        <td><?php shn_form_text(null, 'save_query_record_number', array('value' => $_GET['save_query_record_number'],"class"=>"input-block-level")) ?></td>
                        <td><?php shn_form_text(null, 'name', array('value' => $_GET['name'],"class"=>"input-block-level")) ?></td>
                        <td><?php shn_form_text(null, 'description', array('value' => $_GET['description'],"class"=>"input-block-level")) ?></td>
                        <td><?php shn_form_date(null, 'created_date', array('value' => $_GET['created_date'],"class"=>"input-block-level")) ?></td>
                        <td><?php shn_form_text(null, 'created_by', array('value' => $_GET['created_by'],"class"=>"input-block-level")) ?></td>
                        <td><?php shn_form_select(null, 'query_type', array('options' => $types, 'value' => $_GET['query_type'],"class"=>"input-block-level")) ?></td>
                    </tr>
                    <tr>		
                        <td><input type='checkbox' onchange='$("input.delete").attr("checked",this.checked)' /></td>
                        <td><?php echo _t('QUERY_ID'); ?></td>
                        <td><?php echo _t('NAME'); ?></td>
                        <td><?php echo _t('DESCRIPTION'); ?></td>
                        <td><?php echo _t('CREATED_DATE'); ?></td>
                        <td><?php echo _t('CREATED_BY'); ?></td>		
                        <td><?php echo _t('TYPE'); ?></td>		
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($query_list as $record) {
                        ?>
                        <tr <?php echo ($i++ % 2 == 1) ? 'class="odd"' : ''; ?>>
                            <td>
                                <input name="sq[]" type='checkbox' value='<?php echo $record['save_query_record_number'] ?>' class='delete'
                                       <?php if (is_array($_GET['sq']) && in_array($record['save_query_record_number'], $_GET['sq'])) echo "checked='checked'"; ?>                 />
                            </td>
                            <td>
                                <?php
                                if ($record['query_type'] == 'advanced')
                                    $query_url = get_url('analysis', 'adv_search', null, array('query' => urlencode($record['query'])), null, true);
                                else
                                    $query_url = get_url('analysis', 'search_result', null, unserialize($record['query']), null, true);
                                ?>
                                <a href="<?php echo $query_url . "&qid=" . $record['save_query_record_number']; ?>"><?php echo $record['save_query_record_number']; ?></a>
                            </td>
                            <td><?php echo $record['name']; ?></td>
                            <td><?php echo $record['description']; ?></td>
                            <td><?php echo $record['created_date']; ?></td>
                            <td><?php echo $record['created_by']; ?></td>		
                            <td><?php echo $types[$record['query_type']]; ?></td>		
                        </tr>
                        <?php
                    }
                    ?>
                    <tr class='actions' >
                        <td></td>
                        <td colspan='6'>
                            <button type='submit' class='btn btn-danger' name='delete' ><i class="icon-trash icon-white"></i> <?php echo _t('DELETE') ?></button>

                    </tr>
                </tbody>
            </table>
            <?php
            $query_result_pager->render_pages();
        }
        else {
            shnMessageQueue::addInformation(_t('NO_QUERIES_SAVED_YET_'));
        }
        ?>
    </form>
</div>
