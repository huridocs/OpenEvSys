<?php global $conf ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php  echo $conf['locale']  ?>" lang="<?php  echo $conf['locale']  ?>">
<head>
<?php $version = explode( '=', file_get_contents(APPROOT.'/www/VERSION')); ?>
<title>OpenEvSys <?php echo $version[1]?></title>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <link rel="shortcut icon" href="res/img/oevsys.png" type="image/x-icon" />

    <link rel="stylesheet" type="text/css" media="screen"  href="theme/<?php echo $conf['theme'] ?>/screen.css"/>
    <link rel="stylesheet" type="text/css" media="screen"  href="theme/<?php echo $conf['theme'] ?>/datepicker.css"/>
    <link rel="stylesheet" type="text/css" media="print" href="theme/<?php echo $conf['theme'] ?>/print.css"/>
    <link rel="stylesheet" type="text/css" media="screen" href="res/jquery/jquery.asmselect.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="res/jquery/jquery-treeview/jquery.treeview.css" />
    
    <link rel="stylesheet" type="text/css" media="screen" href="theme/jqgrid/coffee/grid.css" /> 
	<link rel="stylesheet" type="text/css" media="screen" href="theme/jqgrid/jqModal.css" /> 
	<link type="text/css" href="theme/fg-menu/fg.menu.css" media="screen" rel="stylesheet" />
	<link type="text/css" href="theme/fg-menu/theme/ui.all.css" media="screen" rel="stylesheet" />
	<link type="text/css" href="theme/fg-menu/style.css" media="screen" rel="stylesheet" />
	<link type="text/css" href="theme/pnotify/jquery.pnotify.default.css" media="screen" rel="stylesheet" />
	
	<link type="text/css" href="theme/jqhelp/ajaxhelptextviewer.css" media="screen" rel="stylesheet" />
	<link type="text/css" href="res/js/jwysiwyg/jquery.wysiwyg.css" media="screen" rel="stylesheet" />
    <link href="res/locale/<?php  echo $conf['locale']  ?>.json" lang="<?php echo $conf['locale'] ?>" rel="gettext"/>

    <script type="text/javascript" src="res/jquery/jquery.js"></script>	
    <script type="text/javascript" src="res/jquery/jquery.gettext.js"></script>


	
    <script type="text/javascript" src="res/jquery/jquery.pop.js"></script>	
    <script type="text/javascript" src="res/jquery/datepicker.js"></script>	
    <script type="text/javascript" src="res/jquery/jquery.hotkeys.js"></script>
    <script type="text/javascript" src="res/jquery/jquery.pnotify.js"></script>
    <script type="text/javascript" src="res/js/main.js"></script>
    <script type="text/javascript" src="res/js/adv-search.js"></script>
    <script type="text/javascript" src="res/jquery/jquery.asmselect.js"></script>
    <script type="text/javascript" src="res/jquery/jquery.blockUI.js"></script>
    <script type="text/javascript" src="res/jquery/jquery-treeview/lib/jquery.cookie.js"></script>
    <script type="text/javascript" src="res/jquery/jquery-treeview/jquery.treeview.js"></script>
    <script type="text/javascript" src="res/jquery/jquery-treeview/jquery.treeview.async.js"></script>
    <script type="text/javascript" src="res/jquery/jquery.json-2.3.js"></script>

    <script type="text/javascript" src="res/jquery/jquery.jqGrid.js"></script> 
	<script type="text/javascript" src="res/js/jqgrid/js/jqModal.js"></script> 
	<script type="text/javascript" src="res/js/jqgrid/js/jqDnR.js"></script> 
	<script type="text/javascript" src="res/js/jqgrid.js"></script>
	<script type="text/javascript" src="res/jquery/fg.menu.js"></script>
	<script type="text/javascript" src="res/js/fg.menu.js"></script>
	
	<script type="text/javascript" src="res/js/jwysiwyg/jquery.wysiwyg.js"></script>
	<script type="text/javascript" src="res/jquery/jquery.ajaxhelptextviewer.js"></script>
 <script type="text/javascript" src="res/openlayers/OpenLayers.js"></script>
  <script type="text/javascript" src="res/openlayers/map.js"></script>
   <script type="text/javascript" src="https://maps.google.com/maps/api/js?v=3.7&amp;sensor=false"></script>
</head>
<body>
<div id="container">
	<div id="top_bar">
        <?php include_section('session'); ?>
	</div>
	<div id="header">
		<h1><a href="<?php get_url(); ?>" title="OpenEvSys"><span>OpenEvSys</span></a></h1>
	</div>
    <?php include_section('menu'); ?>
    <?php include_section('mod_menu')?>
    <?php include_section('breadcrumb')?>
    <?php include_section('modwrap_open') // these are put in to fotmat admin section ?>
    <div id="content">
        <?php echo $content; ?>
    </div>
    <?php include_section('modwrap_close')?>

    <div id="footer">
        <span>About</span>
        <a href="http://openevsys.org" target='blank'>OpenEvSys</a><span>&nbsp;|&nbsp;</span>
        <span>&copy;</span>
        <a href="http://www.huridocs.org" target='blank'>HURIDOCS</a><span>&nbsp;|&nbsp;</span>
        <a href="http://www.fsf.org/licensing/licenses/agpl-3.0.html" target='blank'>AGPL v3 licensed</a><span>&nbsp;|&nbsp;</span>
        <span class='version'><?php 
         $version = explode( '=', file_get_contents(APPROOT.'/www/VERSION'));
         echo _t('VERSION');echo " : ";echo $version[1]        
        ?></span>
    </div>

<?php if($_SESSION['translator']){ ?>
<div id='translate'>
    <a class='but' href="<?php get_url('admin','translate',null,array('disable_translator'=>'true')) ?>" ><?php echo _t('DISABLE_INTERACTIVE_TRANSLATION') ?></a> 
    <a class='but' href="<?php get_url('admin','translate',null,array('compile'=>'true')) ?>" ><?php echo _t('APPLY_CHANGES_PERMANENTLY') ?></a> 
</div>
<?php } ?>
</div>
</body>
</html>
