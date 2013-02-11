<?php  global $global ; global $conf;?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>OpenEvSys 1.1</title>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <link rel="shortcut icon" href="res/img/oevsys.png" type="image/x-icon" />
    <link rel="stylesheet" type="text/css"  href="theme/<?php echo $conf['theme'] ?>/screen.css"/>
    <script src="theme/default/theme.js" type="text/javascript"></script>
    <script type="text/javascript" src="res/jquery/jquery.js"></script>
	<script type="text/javascript">
      	$(document).ready(function(){
   			$('#username').focus();
 		});
    </script>
</head>
<body>
<div id="container">
    <div id="login-container">
        <div id="content">
            <center><img src="theme/<?php echo $conf['theme'] ?>/images/img_logo.png" class="logo"/></center>
            <?php   include_once APPROOT.'/tpls/html_login_form.php'; ?>
        </div>

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
    </div>
</div>
</body>
</html>
