<?php

include_once APPROOT . 'inc/lib_entity_forms.inc';
require_once(APPROOT . 'mod/analysis/lib_analysis.inc');


/**
 * @author ks
 *
 */
class dashboardModule extends shnModule
{

    function act_default()
    {
        set_redirect_header('dashboard', 'dashboard');
    }

    function __construct()
    {
        $this->main_entity = (isset($_POST['main_entity'])) ? $_POST['main_entity'] : $_GET['main_entity'];

        $this->search_entity = (isset($_GET['shuffle_results']) && $_GET['shuffle_results'] != 'all') ? $_GET['shuffle_results'] : $this->main_entity;
    }

    private function getEntityFields()
    {
        $res = Browse::getAllEntityFields();
        $domain = new Domain();
        foreach ($res as $record) {
            $entity = $record['entity'];
            if (isset($entity) && !isset($domain->$entity)) {
                $domain->$entity = new domain();
                $domain->$entity->fields = new domain();
//                $domain->$entity->fields = array();
            }
            $fo = new Domain();
            $name = $record['field_name'];
            $fo->value = $record['field_name'];
            $fo->label = $record['field_label'];
            $fo->field_type = ($record['validation'] == 'number') ? 'number' : $record['field_type'];
            $fo->list_code = $record['list_code'];
            $fo->select = $record['in_results'];
            $domain->$entity->fields->$name = $fo;
        }
        //add the entity list
        $entities = analysis_get_advance_search_entities();

        foreach ($entities as $key => $entity) {
            $domain->$key->value = $entity['type'];
            $domain->$key->label = $entity['title'];
            $domain->$key->desc = $entity['desc'];
            $domain->$key->ac_type = $entity['ac_type'];
        }

        return $domain;
    }

    public function act_dashboard()
    {
        global $global, $conf;

        $activeFormats = getActiveFormats();
        $entityFields = Browse::getAllEntityFields();
        $response = array();
        foreach ($activeFormats as $format => $formatTitle) {
            if ($conf['dashboard_format_counts_' . $format] != 'true') {
                continue;
            }
            $count_query = "SELECT COUNT(*) as count FROM {$format} ";

            try {
                $res_count = $global['db']->Execute($count_query);
                foreach ($res_count as $row) {
                    $response["counts"][$format] = array((int)$row["count"], $formatTitle);
                }
            } catch (Exception $e) {

            }
        }
        $timelineType = "day";
        if ($_REQUEST['timelinetype'] == "month") {
            $timelineType = "month";
        } elseif ($_REQUEST['timelinetype'] == "year") {
            $timelineType = "year";
        }
        $this->timelineType = $timelineType;
        if ($timelineType == "month") {
            $dateFormat = '%b %Y';
        } elseif ($timelineType == "year") {
            $dateFormat = '%Y';
        } else {
            $dateFormat = '%d %b %Y';
        }
        $datestart = "";
        $dateend = "";
        if (isset($_REQUEST['daterange'])) {
            $daterange = $_REQUEST['daterange'];
            $daterange = explode(",", $daterange);
            $datestart2 = trim($daterange[0]);
            $dateend2 = trim($daterange[1]);
            $date_format = 'Y-m-d';
            $timestart = strtotime($datestart2);
            $timeend = strtotime($dateend2);
            if ($timestart && $timeend) {
                $datestart = date($date_format, $timestart);
                $dateend = date($date_format, $timeend);
            }
        }

        if ($datestart && $dateend) {
            $this->daterange = $datestart . " , " . $dateend;
        } else {
            $this->daterange = "";
        }

        /*$sql = "SELECT  DATE_FORMAT(m.date_of_entry,'$dateFormat') as val , COUNT(e.event_record_number) AS count
                FROM  event e join management m on m.entity_id=e.event_record_number and m.entity_type='event' ";
        if ($datestart && $dateend) {
            $sql .= " where m.date_of_entry BETWEEN '$datestart' AND '$dateend' ";
        }
        $sql .= "GROUP BY val order by m.date_of_entry ";*/

        $dashboard_date_counts = array();
        if ($conf['dashboard_date_counts']) {
            $dashboard_date_counts = @json_decode($conf['dashboard_date_counts']);
            $dashboard_date_counts = (array)$dashboard_date_counts;
        }
        $response["timeline"] = array();
        foreach ($entityFields as $record) {
            $entity = $record['entity'];
            $field = $record['field_name'];
            $selVal = $entity . "|||" . $field;
            if (in_array($selVal, $dashboard_date_counts)) {
                $primary_key = get_primary_key($entity);
                if (in_array($field,array('date_received','date_of_entry','date_updated'))) {
                    $sql = "SELECT  DATE_FORMAT(m.$field,'$dateFormat') as val , COUNT(e.$primary_key) AS count
                      FROM  $entity e join management m on m.entity_id=e.$primary_key and m.entity_type='$entity' ";
                    if ($datestart && $dateend) {
                        $sql .= " where m.$field BETWEEN '$datestart' AND '$dateend' ";
                    }
                    $sql .= "GROUP BY val order by m.$field ";

                } else {
                    $sql = "SELECT  DATE_FORMAT(e.$field ,'$dateFormat') as val , COUNT(e.$primary_key) AS count
                      FROM  $entity e  ";
                    if ($datestart && $dateend) {
                        $sql .= " where e.$field  BETWEEN '$datestart' AND '$dateend' ";
                    }
                    $sql .= "GROUP BY val order by e.$field  ";
                }

                $response["timeline"][$selVal]['entity'] = $entity;
                $response["timeline"][$selVal]['entity_label'] = $activeFormats[$entity];
                $response["timeline"][$selVal]['field_name'] = $field;
                $response["timeline"][$selVal]['field_label'] = $record['field_label'];
                try {
                    $res_count = $global['db']->Execute($sql);

                    foreach ($res_count as $row) {
                        $response["timeline"][$selVal]['data'][0] = array(ucfirst($timelineType), "Count");
                        if (!$row["val"] && !(int)$row["count"]) {
                            continue;
                        }
                        if (!$row["val"]) {
                            $row["val"] = "Undefined";
                        }
                        $response["timeline"][$selVal]['data'][] = array($row["val"], (int)$row["count"]);
                    }
                } catch (Exception $e) {

                }
            };
        }

        $barcharts = array();

        $dashboard_select_counts = array();
        if ($conf['dashboard_select_counts']) {
            $dashboard_select_counts = @json_decode($conf['dashboard_select_counts']);
            $dashboard_select_counts = (array)$dashboard_select_counts;
        }
        foreach ($entityFields as $record) {
            $entity = $record['entity'];
            $selVal = $entity . "|||" . $record['field_name'];
            if (in_array($selVal, $dashboard_select_counts)) {
                $barcharts[] = array("entity" => $entity, "field" => $record['field_name']);
            }
        }

        require_once(APPROOT . 'mod/analysis/searchSql.php');


        $searchSql = new SearchResultGenerator();


        foreach ($barcharts as $key => $barchart) {
            $selEntity = $selEntityOriginal = $barchart["entity"];
            $field = $barchart["field"];
            $entityForm = $searchSql->getEntityArray($selEntityOriginal);
            if (!isset($entityForm[$field])) {
                continue;
            }
            $fieldArray = $entityForm[$field];
            $fieldType = $fieldArray["type"];
            $recField = get_primary_key($selEntityOriginal);
            $sqlArray['result'] = "select * from $selEntityOriginal ";

            $sqlchart = "";
            if ($fieldArray['map']['mlt']) {
                $mltTable = 'mlt_' . $searchSql->tableOfEntity($fieldArray['map']['entity']) . '_' . $fieldArray['map']['field'];

                $sqlchart = "SELECT IFNULL(l.msgstr , english) as val, COUNT(t.record_number) AS count
                            FROM ({$sqlArray['result']}) d LEFT JOIN $mltTable t  on  t.record_number=d.$recField left join
                            mt_vocab m on m.vocab_number=t.vocab_number
                            LEFT JOIN mt_vocab_l10n l ON ( l.msgid = m.vocab_number AND l.locale = '{$conf['locale']}' )  GROUP BY t.vocab_number
                            ";
            } elseif (is_management_field($fieldArray)) {
                $f = $fieldArray['map']['field'];

                $sqlchart = "SELECT IFNULL(l.msgstr , english) as val, COUNT(d.$recField) AS count
                            FROM ({$sqlArray['result']}) d LEFT JOIN management t  on t.entity_id=d.$recField and t.entity_type='$selEntity' 
                            left join  mt_vocab m on m.vocab_number=t.{$fieldArray['map']['field']}
                            LEFT JOIN mt_vocab_l10n l ON ( l.msgid = m.vocab_number AND l.locale = '{$conf['locale']}' )  GROUP BY $f
                            ";
            } else {
                $f = $fieldArray['map']['field'];

                if ($fieldType == "mt_select" || $fieldType == "mt_tree") {
                    $sqlchart = "SELECT IFNULL(l.msgstr , english)  as val, COUNT($recField) AS count
                            FROM ({$sqlArray['result']}) d left join  mt_vocab m on m.vocab_number=d.{$f}
                            LEFT JOIN mt_vocab_l10n l ON ( l.msgid = m.vocab_number AND l.locale = '{$conf['locale']}' )  GROUP BY {$f}";
                } else {
                    $sqlchart = "SELECT d.{$f} as val, COUNT($recField) AS count
                            FROM ({$sqlArray['result']}) d   GROUP BY {$f}";
                }
            }

            if ($sqlchart) {
                try {
                    $res = $global['db']->Execute($sqlchart);
                    $chart = array();
                    $chart["type"] = "barchart";
                    $chart["id"] = "barchart" . $key;
                    $chart["title"] = $fieldArray["label"];
                    $chart2 = $chart;
                    $total = 0;
                    foreach ($res as $val) {
                        $chart["data"][0][0] = $chart["title"];
                        $chart["data"][1][0] = "";


                        $vall = _t("Undefined");
                        if ($val[0]) {

                            if ($val[0] == "y") {
                                $vall = _t('YES');
                            } elseif ($val[0] == "n") {
                                $vall = _t('NO');
                            } else {
                                $vall = $val[0];
                            }
                        } elseif (!(int)$val[1]) {
                            continue;
                        }
                        $chart["data"][0][] = $vall;
                        $chart["data"][1][] = (int)$val[1];

                        $chart2["data"][0] = array($chart["title"], _t("COUNT"));
                        $chart2["data"][] = array($vall, (int)$val[1]);
                        $total += (int)$val[1];
                    }
                    $chart2["total"] = $total;

                    $response["barchart"][] = $chart2;
                } catch (Exception $e) {
                    $response->error = "error"; //$e->getMessage();
                }
            }
        }

        $entities = array_keys($activeFormats);

        foreach ($entities as $entity) {
            $recField = get_primary_key($entity);

            $count_query = "SELECT COUNT(m.entity_id) AS count
                FROM  $entity e join management m on m.entity_id=e.$recField and m.entity_type='$entity'
                    where m.date_of_entry BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE()
                    GROUP BY m.entity_type  ";
            try {
                $res_count = $global['db']->Execute($count_query);
                foreach ($res_count as $row) {
                    $response["counts30"][$entity] = array((int)$row["count"], $activeFormats[$entity]);
                }
            } catch (Exception $e) {

            }
        }

        $sql = "SELECT al.username as val,COUNT(al.`action`) AS count
                FROM  audit_log al where al.action='create'
                    GROUP BY al.username order by `count` desc ";
        try {
            $res_count = $global['db']->Execute($sql);
            foreach ($res_count as $row) {
                $response["activeusers"][] = array($row["val"], (int)$row["count"]);
            }
        } catch (Exception $e) {

        }
        $sql = "SELECT al.username as val,COUNT(al.`action`) AS count
                FROM  audit_log al where al.action='update'
                    GROUP BY al.username order by `count` desc ";
        try {
            $res_count = $global['db']->Execute($sql);
            foreach ($res_count as $row) {
                $response["editusers"][] = array($row["val"], (int)$row["count"]);
            }
        } catch (Exception $e) {

        }

        $sql = "SELECT al.username as username,entity,record_number
                FROM  audit_log al where al.action='delete'
                order by `timestamp` desc limit 10  ";
        try {
            $res_count = $global['db']->Execute($sql);
            foreach ($res_count as $row) {
                $response["deleteusers"][] = array($row["entity"], $row["record_number"], $row["username"]);
            }
        } catch (Exception $e) {

        }
        // var_dump($response);exit;
        $this->response = $response;
    }


}
