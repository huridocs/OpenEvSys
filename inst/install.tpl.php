<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<?php $version = explode( '=', file_get_contents(APPROOT.'/www/VERSION')); ?>
<title>OpenEvSys <?php echo $version[1]?> Installer</title>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <link rel="stylesheet" type="text/css" media="screen"  href="theme/default/screen.css"/>
    <link rel="stylesheet" type="text/css" media="print" href="theme/default/print.css"/>
    <link rel="stylesheet" type="text/css" media="screen" href="res/jquery/jquery.asmselect.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="res/jquery/jquery-treeview/jquery.treeview.css" />
    <script src="theme/default/theme.js" type="text/javascript"></script>
    <script type="text/javascript" src="res/jquery/jquery.js"></script>
    <script type="text/javascript" src="res/jquery/jquery-ui/jquery-ui.js"></script>
    <script type="text/javascript" src="res/jquery/jquery.hotkeys.js"></script>
    <script type="text/javascript" src="res/js/main.js"></script>
    <script type="text/javascript" src="res/jquery/jquery.asmselect.js"></script>
    <script type="text/javascript" src="res/jquery/jquery-treeview/lib/jquery.cookie.js"></script>
    <script type="text/javascript" src="res/jquery/jquery-treeview/jquery.treeview.js"></script>
</head>
<body>
<div id="container">
    <br />
    <br />
    <div class="form-container">
    <form action='index.php' method='post'>
        <center><h2>OpenEvSys Installer</h2></center>
        <div width="600px" style="margin:auto;width:600px;">
            <div class="panel">
                <div id="installer">
                <?php 
                    if($sucess)
                    {
                ?>
                <strong class='green'>Installation was successful</strong>
                <p><em><strong>Warning :</strong></em> Please make the "../conf/sysconf.php" read only.</p>
                <br />
                <center><a href='index.php'>Click to Continue</a></center>
                <?php
                    }
                    else{
                        if(is_array($global['error'])){ 
                            echo "<div class='error'>";
                            echo "<ul>";
                            foreach($global['error'] as $error){
                                echo "<li>$error</li>";
                            }
                            echo "</ul>";
                            echo "</div>";
                        }
                ?>
                    <h3>1. Check file permission</h3>
                    <p>OpenEvSys require the following tables to be writable via webserver.</p>
                    <ul>
                        <li><?php echo APPROOT?>conf/  &nbsp; <?php echo $conf_dir; ?></li>
                        <li><?php echo APPROOT?>media/ &nbsp; <?php echo $media; ?></li>
                    </ul>
                    <br />
                    <h3>2. Database Setup</h3>
                    <div class='field'>                    
                        <label>Host</label>
                        <input type='input' name='db_host'/>
                    </div>
                    <div class='field'>                    
                        <label>User</label>
                        <input type='input' name='db_user' />
                    </div>
                    <div class='field'>                    
                        <label>Password</label>
                        <input type='password' name='db_password' />
                    </div>
                    <div class='field'>                    
                        <label>Database Name</label>
                        <input type='input' name='db_name'/>
                    </div>
                    <br />
                    <h3>3. Admin Password</h3>
                    <br />
                    <div class='field'>                    
                        <label>Type Password</label>
                        <input type='password' name='password' />
                    </div>
                    <div class='field'>                    
                        <label>Confirm Password</label>
                        <input type='password' name='password_c' />
                    </div>
                    <br />
                    <h3>4. System identifier</h3>
                    <p>Following id should be unique to this installation of openevsys. </p>
                    <div class='field'>                  
                        <label>Unique identifier</label>
                        <input type='text' name='base_uuid' value="<?php echo getUniqueCode(5); ?>" />
                    </div>
                    <br />
                    <center>
                        <?php if($file_check){ ?>
                        <input type='submit' value='Check Permissions' class='submit'/>
                        <?php }else{?>
                        <input type='submit' value='Install' class='submit' name='install' />
                        <?php }?>
                    </center>
                <?php
                    }
                ?>
                </div>
            </div>
        </div>
    </form>
    </div>
    <div id="footer">
        <span>About</span>
        <a href="http://openevsys.org" target='blank'>OpenEvSys</a><span>&nbsp;|&nbsp;</span>
        <span>&copy;</span>
        <a href="http://www.huridocs.org" target='blank'>HURIDOCS</a><span>&nbsp;|&nbsp;</span>
        <a href="http://www.fsf.org/licensing/licenses/agpl-3.0.html" target='blank'>AGPL v3 licensed</a><span>&nbsp;|&nbsp;</span>
        <span class='version'><?php echo 'Version';echo " : ";echo file_get_contents(APPROOT.'/VERSION'); ?></span>
    </div>
</div>
</body>
</html>
