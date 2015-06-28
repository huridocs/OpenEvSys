<?php
global $conf;
?>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
    google.load('visualization', '1', {packages: ['corechart']});
</script>
<div class="panel">
    <div class="row-fluid">
        <div class="span12">
            <form class="form-inline" action='<?php echo get_url('dashboard', 'dashboard') ?>' method="post">
                <div class="well">
                    <label><?php echo _t("Date range") ?>:</label>

                    <div class="input-prepend">
                        <span class="add-on"><i class="icon-calendar icon-large"></i></span>
                        <input name="daterange" type="text" value="<?php if ($daterange) {
                            echo $daterange;
                        } ?>" id="daterangepickerinput"/>
                    </div>
                    <label><?php echo _t("Timeline type") ?>:</label>
                    <select name="timelinetype">
                        <option value="day" <?php if ($timelineType == "day") {
                            echo "selected='selected'";
                        } ?> ><?php echo _t("DAY") ?></option>
                        <option value="month" <?php if ($timelineType == "month") {
                            echo "selected='selected'";
                        } ?> ><?php echo _t("MONTH") ?></option>
                        <option value="year" <?php if ($timelineType == "year") {
                            echo "selected='selected'";
                        } ?> ><?php echo _t("YEAR") ?></option>
                    </select>
                    <button type="submit" class="btn" name="load"><?php echo _t("Load") ?></button>
                </div>
            </form>
            <script type="text/javascript">
                $(document).ready(function () {
                    $('#daterangepickerinput').daterangepicker(
                        {
                            ranges: {
                                'Today': ['today', 'today'],
                                'Yesterday': ['yesterday', 'yesterday'],
                                'Last 7 Days': [Date.today().add({days: -6}), 'today'],
                                'Last 30 Days': [Date.today().add({days: -29}), 'today'],
                                'This Month': [Date.today().moveToFirstDayOfMonth(), Date.today().moveToLastDayOfMonth()],
                                'Last Month': [Date.today().moveToFirstDayOfMonth().add({months: -1}), Date.today().moveToFirstDayOfMonth().add({days: -1})]
                            },
                            opens: 'right',
                            format: 'yyyy-MM-dd',
                            separator: ' , ',
                            startDate: Date.today().add({days: -29}),
                            endDate: Date.today(),

                            showWeekNumbers: true,
                            buttonClasses: ['btn-grey'],
                            dateLimit: false
                        }
                    );


                });
            </script>


            <h4><?php echo _t("Total Count"); ?></h4>

            <div class="stats">
                <div class="row-fluid">
                    <?php
                    $class = "span2";
                    if (count($response["counts"]) < 5) {
                        $class = "span3";
                    }
                    $colors = array("primary", "danger", "warning", "success", "info", "purple");
                    $i = 0;
                    foreach ($response["counts"] as $format => $val) {
                        if ($i == count($colors)) {
                            $i = 0;
                        }
                        $color = $colors[$i];
                        $i++;

                        ?>
                        <div class="<?php echo $class?>">
                            <div class="stat <?php echo $color?>">
                                <h2><?php echo (int)$val[0]; ?></h2>
                                <h6><?php echo $val[1]; ?></h6>
                            </div>
                        </div>
                    <?php }
                    ?>

                </div>
            </div>


            <hr/>
            <?php
            $activeFormats = getActiveFormats();
            foreach ($response["timeline"] as $key => $timeline) {
                ?>
                <h4>'<?php echo $timeline['entity_label']." - ".$timeline['field_label']?>' by <?php echo _t(ucfirst($timelineType)) ?></h4>
                <div class="stats">
                    <div class="row-fluid">
                        <div class="span12">
                            <div id="<?php echo $timeline['entity']?>timeline"></div>
                        </div>
                    </div>
                </div>
            <?php
            } ?>


            <hr/>
            <div class="stats">
                <div class="row-fluid">

                    <?php
                    $classes = array("", "bar-danger", "bar-warning", "bar-success");
                    $j = 0;
                    foreach ($response["barchart"] as $bchart) {
                        ?>
                        <div class="span3">
                            <h4><?php echo $bchart["title"] ?></h4>

                            <div class="widget">
                                <table style="width:100%">
                                    <?php
                                    $i = 0;
                                    foreach ($bchart["data"] as $bdata) {
                                        $i++;

                                        if ($i == 1) {
                                            continue;
                                        }
                                        ?>

                                        <tr>
                                            <td class="bar-label"><?php echo $bdata[0] ?></td>
                                            <td class="bar-number"><?php echo $bdata[1] ?></td>
                                            <td>
                                                <div class="progress">
                                                    <div
                                                        style="width:<?php echo (int)(100 * $bdata[1] / $bchart["total"]) ?>%"
                                                        class="bar <?php echo $classes[$j] ?>"></div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </table>
                            </div>
                        </div>

                        <?php
                        $j++;
                    }
                    ?>


                </div>
            </div>
            <hr/>

            <div class="stats">
                <div class="row-fluid">
                    <div class="span3">
                        <h4><?php echo _t('Added in last 30 days') ?> </h4>
                        <ul class="cards widget list">
                            <?php
                            $i = 0;
                            $classes = array("badge-info", "badge-important", "badge-warning", "badge-success");

                            foreach ($response["counts30"] as $key => $value) {
                                if ($i == count($classes)) {
                                    $i = 0;
                                }
                                $color = $classes[$i];
                                $i++;
                                ?>
                                <li><?php echo $value[1] ?>
                                    <span class="pull-right badge <?php echo $color ?>">
                                    <?php echo $value[0] ?>
                                </span>
                                </li>
                            <?php
                            }
                            ?></ul>
                    </div>
                    <div class="span3">
                        <h4><?php echo _t('Most active users (new items)') ?> </h4>
                        <ul class="cards widget list">
                            <?php
                            $i = 0;

                            foreach ($response["activeusers"] as $key => $val) {
                                ?>
                                <li><?php echo $val[0] ?><span
                                        class="pull-right badge badge-important"><?php echo $val[1] ?></span></li>
                                <?php
                                $i++;
                            }
                            ?></ul>
                    </div>
                    <div class="span3">
                        <h4><?php echo _t('User activity (edited items)') ?> </h4>
                        <ul class="cards widget list">
                            <?php
                            $i = 0;

                            foreach ($response["editusers"] as $key => $val) {
                                ?>
                                <li><?php echo $val[0] ?><span
                                        class="pull-right badge badge-warning"><?php echo $val[1] ?></span></li>
                                <?php
                                $i++;
                            }
                            ?></ul>
                    </div>
                    <div class="span3">
                        <h4><?php echo _t('Recently deleted') ?> </h4>
                        <ul class="cards widget list">
                            <?php
                            $i = 0;

                            foreach ($response["deleteusers"] as $key => $val) {
                                ?>
                                <li><span class=""><?php echo $val[2] ?></span><span
                                        class="badge badge-success pull-right"><?php echo $val[0] . " - " . $val[1] ?></span>
                                </li>
                                <?php
                                $i++;
                            }
                            ?></ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        var facetlabels = <?php echo json_encode($facetlabels) ?>;
        function drawCharts() {
            <?php
           $activeFormats = getActiveFormats();
           foreach($response["timeline"] as $key=>$timeline){
                if($timeline['data']){?>
            var data1 = google.visualization.arrayToDataTable(<?php echo json_encode($timeline['data']) ?>);

            new google.visualization.ColumnChart(document.getElementById("<?php echo $timeline['entity']?>timeline")).draw(data1, {
                titleTextStyle: {
                    fontSize: 14
                },
                height: 200,
                colors: ['#ea494a']
            });
            <?php }else{?>
            $("#<?php echo $timeline['entity']?>timeline").html("<?php echo _t("No data available")?>");
            <?php }
                }?>


            <?php /* foreach ($response["barchart"] as $bchart) { ?>
              var data<?php echo $bchart["id"] ?> = google.visualization.arrayToDataTable(<?php echo json_encode($bchart["data"]) ?>);
              new google.visualization.BarChart(document.getElementById("<?php echo $bchart["id"] ?>")).draw(data<?php echo $bchart["id"] ?>, {
              title:"<?php echo $bchart["title"] ?>",
              titleTextStyle: {
              fontSize: 14
              },
              height:300
              });

              <?php
              } */
            ?>
        }
        jQuery(document).ready(function ($) {
            google.setOnLoadCallback(drawCharts);
        });

    </script>