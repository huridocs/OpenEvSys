<?php

$customization_form = array(
    'mt_select' => array(
        'type' => 'select',
        'label' => _t('SELECT_A_MICRO_THESAURUS'),
        'extra_opts' => array(
            'options' => $options,
            'value' => $_REQUEST['mt_select']
        )
    ),
    'change_mt' => array(
        'type' => 'submit',
        'label' => _t('SELECT')
    )
);

function mt_customization_update() {
    set_time_limit(1000);
    global $conf;
    global $global;
    //sanitize strings
    include_once(APPROOT . 'inc/i18n/lib_l10n.inc');
    $locales = l10n_get_locals();
    if (isset($_POST['mt_label']) && isset($_POST['label']) && is_array($_POST['label'])) {
        foreach ($_POST['label'] as $vocab_number => $labels) {
            if ($_POST["bulkaction"] == "updateselected" && !isset($_POST['vocab_number_list'][$vocab_number])) {
                continue;
            }

            if (is_array($labels)) {
                $vocab_number = $global['db']->qstr($vocab_number);
                foreach ($locales as $code => $loc) {
                    if (!trim($labels[$code])) {
                        continue;
                    }
                    $msgstr = $global['db']->qstr($labels[$code]);

                    if ($code == "en" && isset($labels["en"])) {
                        $sql = "UPDATE mt_vocab SET english=$msgstr WHERE vocab_number = $vocab_number ";
                        $res = $global['db']->Execute($sql);
                    } elseif (isset($labels[$code])) {
                        $l10nValues = array();
                        $l10nValues['msgid'] = $vocab_number;
                        $l10nValues['msgstr'] = $msgstr;
                        $l10nValues['locale'] = $global['db']->qstr($code);
                        $res = $global['db']->Replace('mt_vocab_l10n', $l10nValues, array('msgid', 'locale'));
                    }
                }
            }
        }
    }
}

function mt_customization_update_terms_order($itemorders, $parent_vocab_number = 0, $term_level = 0, $term_order = 0) {
    global $global;
    global $conf;

    if (!is_array($itemorders)) {
        return;
    }
    /* $itemorders = @json_decode(stripslashes($_POST['itemsorder']),true);
     */

    foreach ($itemorders as $itemorder) {
        if (!$itemorder['id']) {
            continue;
        }
        $term_order++;
        $fieldValues = array();
        $fieldValues['term_order'] = (int) $term_order;
        $fieldValues['term_level'] = (int) $term_level;
        $fieldValues['parent_vocab_number'] = $parent_vocab_number;

        $global['db']->AutoExecute('mt_vocab', $fieldValues, 'UPDATE', "vocab_number = '" . $itemorder['id'] . "'");
        if (is_array($itemorder["children"])) {
            $term_order = mt_customization_update_terms_order($itemorder["children"], $itemorder['id'], $term_level + 1, $term_order);
        }
    }
    return $term_order;
}

function mt_customization_delete($mt_select, $vocab_number_list) {
    $add = true;
    $vocab_numbers = array();
    if (is_array($vocab_number_list)) {
        foreach ($vocab_number_list as $vocab_number => $v) {
            try {
                $mtTerms = new MtTerms();
                //if the mt is a tree delete children as well 
                $vocab_numbers2 = mt_customization_get_branch_select($mt_select, $vocab_number);
                $vocab_numbers = array_merge($vocab_numbers, $vocab_numbers2);

                $vocab_numbers = array_merge($vocab_numbers, array($vocab_number));
            } catch (Exception $e) {
                if ($add) {
                    shnMessageQueue::addInformation(_t('UNABLE_TO_REMOVE_SOME_MICRO_THESAURUS_TERMS_BECAUSE_THEY_ARE_ALREADY_USED_IN_RECORDS_'));
                    $add = false;
                }
            }
        }
        if ($vocab_numbers) {

            $mtTerms->DeleteFromRecordNumber($mt_select, $vocab_numbers);
        }
    }
}

function mt_customization_add_terms($mt_term) {
    global $global;

    include_once(APPROOT . 'inc/i18n/lib_l10n.inc');
    $locales = l10n_get_locals();


    $fieldValues = array();

    $mtIndex = new MtIndex();
    $mt_table = 'mt_' . $mt_term . '_' . $mtIndex->getTermforCode($mt_term);
    $index = 0;
    $mt_term = (strlen($mt_term) == 1) ? '0' . $mt_term : $mt_term;
    if (is_array($_POST['new_vocab_number']) && is_array($_POST['new_term_label'])) {
        foreach ($_POST['new_vocab_number'] as $vocab_number) {
            //$fieldValues['vocab_number'] = $_POST['vocab_number'][$index];
            $recordNumber = $vocab_number . $mt_term;
            $vocab_number = trim($vocab_number);
            $huri_term = trim($_POST['new_term_label']['en'][$index]);

            if ($vocab_number == '' || $huri_term == '') {
                continue;
            }

            $fieldValues['vocab_number'] = $vocab_number;
            $fieldValues['list_code'] = $mt_term;
            $fieldValues['english'] = $huri_term;
            if (!mt_customization_validate_mtterm($mt_term, $vocab_number, $huri_term)) {
                continue;
            }

            try {
                //svar_dump($fieldValues);
                $global['db']->AutoExecute('mt_vocab', $fieldValues, 'INSERT');
                $global['db']->AutoExecute($mt_table, $fieldValues, 'INSERT');


                foreach ($locales as $code => $loc) {
                    if ($code != "en" && trim($_POST['new_term_label'][$code][$index])) {
                        $msgstr = $global['db']->qstr(trim($_POST['new_term_label'][$code][$index]));

                        $l10nValues = array();
                        $l10nValues['msgid'] = $vocab_number;
                        $l10nValues['msgstr'] = $msgstr;
                        $l10nValues['locale'] = $global['db']->qstr($code);
                        $res = $global['db']->Replace('mt_vocab_l10n', $l10nValues, array('msgid', 'locale'));
                    }
                }
            } catch (Exception $e) {
                shnMessageQueue::addError(_t('ERROR_OCCURRED_WHILE_CREATING_HURITERM_') . "  [ $vocab_number : $huri_term ]");
            }
            $index++;
        }
    }
}

function mt_customization_add_new_taxonomy() {
    global $global;
    $term = str_replace(" ", "", strtolower($_POST['term']));
    $term = preg_replace('/[^A-Za-z0-9\-_]/', '', $term); // Removes special chars.
    $term = trim($term);
    if (!$term) {
        shnMessageQueue::addError(_t('Micro-thesauri cannot be empty'));
        return false;
    }
    
    $mtIndex = new MtIndex();
    $code = $mtIndex->getCodeforTerm($term);
    
    if ($code) {
        shnMessageQueue::addError(_t('Micro-thesauri name already exists'));
        return false;
    }
    $max = $global['db']->GetOne("select max(no) from mt_index");
    $code = (int) $max + 1;
    
    $dbQuery = new Browse();
    try {
        $dbQuery->ExecuteNonQuery("insert into mt_index(`no`,`term`) values(" . $code . ",'" . $term . "')");

        $sql = "CREATE TABLE IF NOT EXISTS mt_" . $code . "_" . $term . "(
            vocab_number VARCHAR(14) NOT NULL ,
            PRIMARY KEY ( vocab_number ) ,
            FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
            ON DELETE CASCADE
            ON UPDATE CASCADE

        ) ENGINE = InnoDB;";
        $dbQuery->ExecuteNonQuery($sql);
    } catch (Exception $e) {
        return false;
    }
    return true;
}

function mt_customization_is_tree($mt_code) {
    global $global;
    $mt_code = (strlen($mt_code) == 1) ? "0$mt_code" : $mt_code;
    $sql = "SELECT field_type FROM data_dict WHERE list_code = '$mt_code'";
    $res = $global['db']->GetOne($sql);
    if ($res == 'mt_tree')
        return true;
    else
        return false;
}

function mt_customization_has_children($vocab_number) {
    global $global;
    $sql = "SELECT count(*) from mt_vocab WHERE parent_vocab_number = '$vocab_number'";
    $res = $global['db']->GetOne($sql);

    if ($res > 0)
        return true;
    else
        return false;
}

function mt_customization_get_branch_select($mt_select, $vocab_number) {
    global $global;
    $sql = "SELECT max(term_level) from mt_vocab WHERE list_code = '$mt_select'";
    $depth = $global['db']->GetOne($sql);
    $sql = "";
    $sqllevel = "'$vocab_number'";
    for ($i = 1; $i <= $depth; $i++) {
        $sqllevel = "select vocab_number from  mt_vocab mt$i where  mt$i.parent_vocab_number in (" . $sqllevel . ")";
        $sql[] = $sqllevel;
    }
    $result = array();
    if ($sql) {
        $sql = implode(" union ", $sql);
        $browse = new Browse();
        $res = $browse->ExecuteQuery($sql);
        foreach ($res as $row) {
            $result[] = $row['vocab_number'];
        }
    }
    return $result;
}

function mt_customization_validate_mtterm($list_code, $vocab_number, $huri_term, $level = null) {
    global $global;
    //check if right length and if it is a number
    //check if the huri term is empty
    if (isset($huri_term) && $huri_term == '') {
        shnMessageQueue::addError(_t('HURITERM_CAN_NOT_BE_EMPTY_') . "  [ $vocab_number : $huri_term ]");
        return false;
    }
    //check if already exist
    $sql = "SELECT vocab_number FROM mt_vocab WHERE vocab_number='$vocab_number' ";
    $vocab_number = $global['db']->GetOne($sql);

    if (isset($vocab_number) && $vocab_number != '') {
        shnMessageQueue::addError(_t('FOLLOWING_HURITERM_ALREADY_EXIST_') . "  [ $vocab_number : $huri_term ]");
        return false;
    }


    return true;
}

//Visibility
function mt_visibility_update($vocab_numbers, $val) {
    global $conf;
    global $global;
    //sanitize strings
    if (!is_array($vocab_numbers)) {
        $vocab_numbers = array($vocab_numbers);
    } else {
        $vocab_numbers = array_keys($vocab_numbers);
    }
    if (!empty($vocab_numbers)) {
        $vocab_numbers = implode("','", $vocab_numbers);
        $sql = "UPDATE mt_vocab SET visible='$val' WHERE vocab_number in ('" . $vocab_numbers . "')";
        $res = $global['db']->Execute($sql);
    }
}
