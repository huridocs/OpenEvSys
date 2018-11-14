<?php global $conf, $global; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php $version = explode( '=', file_get_contents(WWWWROOT.'VERSION'));
$version = $version[1]; ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $conf['locale'] ?>" lang="<?php echo $conf['locale'] ?>">
    <head>
        <title>OpenEvSys <?php echo $version?></title>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
        <link rel="shortcut icon" href="res/img/oevsys.png" type="image/x-icon" />
        <link rel="stylesheet" type="text/css" media="all" href="theme/<?php echo $conf['theme'] ?>/css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" media="all" href="theme/<?php echo $conf['theme'] ?>/css/bootstrap-responsive.min.css" />

        <link rel="stylesheet" type="text/css" media="screen" href="theme/<?php echo $conf['theme'] ?>/screen.css?v=<?php echo $version?>"/>
        <link rel="stylesheet" type="text/css" media="print"  href="theme/<?php echo $conf['theme'] ?>/print.css?v=<?php echo $version?>"/>

        <link rel="stylesheet" type="text/css" media="screen" href="theme/fg-menu/fg.menu.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="theme/fg-menu/theme/ui.all.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="theme/fg-menu/style.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="theme/pnotify/jquery.pnotify.default.css" />


        <link rel="stylesheet" type="text/css" media="screen" href="res/select2/select2.css" />

        <link rel="stylesheet" type="text/css" media="screen" href="theme/<?php echo $conf['theme'] ?>/css/datepicker.css?v=<?php echo $version?>" />
        <link rel="stylesheet" type="text/css" media="screen" href="res/bootstrap/bootstrap-daterangepicker/daterangepicker.css" />
 <link rel="stylesheet" type="text/css" media="screen" href="res/font-awesome/css/font-awesome.min.css" />


        <link href="res/locale/<?php echo $conf['locale'] ?>.json?v=<?php echo $version?>" lang="<?php echo $conf['locale'] ?>" rel="gettext"/>

        <script src="res/jquery/jquery-1.9.1.min.js"></script>

        <script src="res/bootstrap/bootstrap.min.js"></script>
        <script src="res/bootstrap/bootstrap-datepicker.js?v=<?php echo $version?>"></script>
        <script src="res/bootstrap/bootstrap-daterangepicker/date.js"></script>
        <script src="res/bootstrap/bootstrap-daterangepicker/daterangepicker.js"></script>

        <script type="text/javascript" src="res/select2/select2.min.js"></script>
       


        <script type="text/javascript" src="res/jquery/jquery.gettext.js"></script>

        <script type="text/javascript" src="res/jquery/jquery.pnotify.js"></script>
        <script type="text/javascript" src="res/js/main.js?v=<?php echo $version?>"></script>
        <script type="text/javascript" src="res/jquery/jquery.json-2.3.js"></script>

        <script type="text/javascript" src="res/jquery/fg.menu.js?v=<?php echo $version?>"></script>
        <script type="text/javascript" src="res/js/fg.menu.js?v=<?php echo $version?>"></script>
        <?php if(in_array("map", $global["js"])) { ?>
            <script type="text/javascript" src="res/openlayers/OpenLayers.js"></script>
            <script type="text/javascript" src="res/openlayers/map.js"></script>
        <?php } ?>
    </head>
    <?php
        $module = get_module();
        $action = get_action();
        
        ?>
    <body>
        
        <?php
        
            include_section('menu');
        ?>
        <?php /*include_section('top_menu'); */?>

        <div id="container" class="container-fluid">
            <?php 
            if($action != "print"){
                include_section('breadcrumb');
            }?>

            <div class="row-fluid">
                <?php
                $messages = shnMessageQueue::renderMessages();
                echo $messages;
                ?></div>
            <div class="row-fluid">
                <?php include_section('mod_sidebar') ?>

                <?php 
                if($action != "print"){
                    include_section('mod_menu');
                }?>


                <?php if ($module == "admin") { ?>
                    <div class="span10" >
                    <?php } else { ?>
                        <div class="span12" style="margin-left:0px" >
                        <?php } ?>
                        <div class="row-fluid">

                            <div class="span12">

                                <?php echo $content; ?>
                            </div>
                        </div>
                    </div>
                    

                </div>
                <footer>
                    <div id="footer">
                        <span>About</span>
                        <a href="http://openevsys.org" target='blank'>OpenEvSys</a><span>&nbsp;|&nbsp;</span>
                        <span>&copy;</span>
                        <a href="http://www.huridocs.org" target='blank'>HURIDOCS</a><span>&nbsp;|&nbsp;</span>
                        <a href="http://www.fsf.org/licensing/licenses/agpl-3.0.html" target='blank'>AGPL v3 licensed</a><span>&nbsp;|&nbsp;</span>
                        <span class='version'><?php
                                echo _t('VERSION');
                                echo " : ";
                                echo $version;
                                ?></span>
                    </div>
                </footer>
                <?php if ($_SESSION['translator']) { ?>
                    <div id='translate'>
                        <a class='but' href="<?php get_url('admin', 'translate', null, array('disable_translator' => 'true')) ?>" ><?php echo _t('DISABLE_INTERACTIVE_TRANSLATION') ?></a> 
                        <a class='but' href="<?php get_url('admin', 'translate', null, array('compile' => 'true')) ?>" ><?php echo _t('APPLY_CHANGES_PERMANENTLY') ?></a> 
                    </div>
                <?php } ?>
                </body>
                </html>

