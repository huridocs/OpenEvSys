
<?php include_once('event_title.php') ?>

<div class="panel">    
    <?php include_once('vp_list_table.php'); ?>
    <?php if (isset($_GET['type'])) { ?>
        <br />
        <br />
        <div class="form-container">
            <?php
            switch ($_GET['type']) {
                case 'victim':
                    echo "<h3>" . _t('VIEW_VICTIM_RECORD') . "</h3>&nbsp;";
                    echo "<br />";
                    ?>
                    <a class="btn" href="<?php echo get_url('events', 'edit_victim', null, array('act_id' => $_GET['act_id'], 'pid' => $victim->person_record_number)) ?>"><i class="icon-edit"></i> <?php echo _t('EDIT_THIS_PERSON') ?></a>
                    <a class="btn" href="<?php echo get_url('person', 'person', null, array('pid' => $victim->person_record_number)) ?>"><i class="icon-zoom-in"></i> <?php echo _t('MORE_ABOUT_THIS_PERSON') ?></a><?php
            echo "<br />";
            echo "<br />";
            $person_form = person_form('view');
            popuate_formArray($person_form, $victim);
            shn_form_get_html_labels($person_form, false);
            break;
        case 'perter':
            echo "<h3>" . _t('VIEW_PERPETRATOR_RECORD') . "</h3>&nbsp;";
            echo "<br />";
                    ?>
                    <a class="btn" href="<?php echo get_url('events', 'edit_perpetrator', null, array('inv_id' => $_GET['inv_id'], 'pid' => $perpetrator->person_record_number)) ?>"><i class="icon-edit"></i> <?php echo _t('EDIT_THIS_PERSON') ?></a>
                    <a class="btn" href="<?php echo get_url('person', 'person', null, array('pid' => $perpetrator->person_record_number)) ?>"><i class="icon-zoom-in"></i> <?php echo _t('MORE_ABOUT_THIS_PERSON') ?></a>
                   
                <a class="btn"  href="<?php get_url('person', 'role_list', null, array('eid'=>null,'pid' => $perpetrator->person_record_number)) ?>"><i class="icon-zoom-in"></i> <?php echo _t("View person's roles")?></a>
                        <?php
            echo "<br />";
            echo "<br />";
            $person_form = person_form('view');
            popuate_formArray($person_form, $perpetrator);
            shn_form_get_html_labels($person_form, false);
            break;
        case 'act':
            echo "<h3>" . _t('VIEW_ACT_RECORD') . "</h3>&nbsp;";
            echo "<br />";
                    ?>
                    <a class="btn" href="<?php echo get_url('events', 'edit_act', null, array('row' => $_GET['row'], 'act_id' => $_GET['act_id'])) ?>"><i class="icon-edit"></i> <?php echo _t('EDIT_THIS_ACT') ?></a>
                    <a class="btn" href="<?php get_url('events', 'edit_ad', null, array('act_id' => $_GET['act_id'])) ?>"><i class="icon-edit"></i> <?php echo _t('EDIT_ADDITIONAL_DETAILS') ?></a>
                    <a class="btn" href="<?php get_url('events', 'add_perpetrator', null, array('act_id' => $_GET['act_id'])) ?>"><i class="icon-plus"></i> <?php echo _t('ADD_PERPETRATOR_S_') ?></a>
                    <?php if (isset($ad)) { ?>
                        <a class="btn btn-grey" href="<?php get_url('events', 'delete_ad', null, array('act_id' => $_GET['act_id'])) ?>"><i class="icon-trash"></i>  <?php echo _t('DELETE_ADDITIONAL_DETAILS') ?></a>
                        <?php if (isset($delete_ad)) { ?>
                            <div class="alert alert-error" >
                                <h3><?php echo _t('DO_YOU_WANT_TO_DELETE_ADDITIONAL_DETAILS__') ?></h3>
                                <form class="form-horizontal"  action="<?php get_url('events', 'delete_ad', null, array('act_id' => $act->act_record_number)) ?>" method="post">
                                    <br />
                                    <center>
                                        <button type='submit' class='btn btn-grey' name='yes' ><i class="icon-trash"></i> <?php echo _t('DELETE') ?></button>
                                        <button type='submit' class='btn' name='no' ><i class="icon-remove-circle"></i> <?php echo _t('CANCEL') ?></button>
                                    </center>
                                </form>
                            </div>
                            <?php
                        }
                    }
                    ?>
                    <?php
                    echo "<br />";
                    echo "<br />";
                    $act_form = act_form('view');
                    popuate_formArray($act_form, $act);
                    shn_form_get_html_labels($act_form, false);
                    if (isset($ad)) {
                        echo "<br />";
                        echo "<br />";
                        echo "<h3>" . _t('ADDITIONAL_DETAILS') . "</h3>&nbsp;";
                        $ad_form = generate_formarray($ad_type, 'view');
                        popuate_formArray($ad_form, $ad);
                        shn_form_get_html_labels($ad_form, false);
                    }
                    break;
                case 'inv':
                    echo "<h3>" . _t('VIEW_INVOLVEMENT_RECORD') . "</h3>&nbsp;";
                    echo "<br />";
                    ?>            <a class="btn" href="<?php echo get_url('events', 'edit_involvement', null, array('eid' => $_GET['eid'], 'row' => $_GET['row'], 'inv_id' => $_GET['inv_id'])) ?>"><i class="icon-edit"></i> <?php echo _t('EDIT_THIS_INVOLVEMENT') ?></a><?php
            echo "<br />";
            echo "<br />";
            $involvement_form = involvement_form('view');
            popuate_formArray($involvement_form, $inv);
            shn_form_get_html_labels($involvement_form, false);
            break;
    }
    ?>
        </div>
<?php } ?>
</div>
