<html>
<head>
<?php $version = explode( '=', file_get_contents(WWWWROOT.'VERSION'));
$version = $version[1]; ?>
<title>OpenEvSys <?php echo $version?> Installer</title>

    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <link rel="shortcut icon" href="res/img/oevsys.png" type="image/x-icon" />
    <link rel="stylesheet" type="text/css"  href="theme/default/screen.css"/>
    <link rel="stylesheet" type="text/css" media="screen" href="theme/default/css/bootstrap.min.css" >
  
    <script src="theme/default/theme.js" type="text/javascript"></script>
   <script src="res/jquery/jquery-1.9.1.min.js"></script>
       
         <script src="res/bootstrap/bootstrap.min.js"></script>
       	<script type="text/javascript">
      	$(document).ready(function(){
   			$('#username').focus();
 		});
    </script>
    <style>
        input{
            height:30px !important;
        }
        
        </style>
</head>
<body>
<div id="container">
    <div id="login-container">
        <div id="content">
               <center><img src="theme/default/images/img_logo.png?v=1" class="logo"/></center>
           <br/><br/>
    <form action='index.php' method='post' class="form-horizontal hero-unit" style="margin: 0 auto;width: 600px;padding:20px;">
        <center><h3>OpenEvSys Installer</h3></center>
                <?php 
                    if(isset($sucess) && $sucess)
                    {
                ?>
        <div class="alert alert-success">
  <button type="button" class="close" data-dismiss="alert">&times;</button>

                <strong>Installation was successful</strong>
                <p><em><strong>Warning :</strong></em> Please make the "../conf/sysconf.php" read only.</p>
                <br />
                <center><a href='index.php'>Click to Continue</a></center>
        </div>
                <?php
                    }
                    else{
                        if(is_array($global['error'])){ 
                            ?>
         <div class="alert alert-error">
  <button type="button" class="close" data-dismiss="alert">&times;</button>

  <ul>
                           <?php foreach($global['error'] as $error){
                                echo "<li>$error</li>";
                            }?>
                            </ul>
                            </div>
        <?php
                        }
                ?>
                    <h4>1. Check file permission</h4>
                    <p>OpenEvSys require the following folders to be writable via webserver.</p>
                    <ul>
                        <li><?php echo APPROOT?>conf/  &nbsp; <?php echo $conf_dir; ?></li>
                        <li><?php echo APPROOT?>media/ &nbsp; <?php echo $media; ?></li>
                        <li><?php echo WWWWROOT?>images/uploads/ &nbsp; <?php echo $imagesuploads; ?></li>
                       
                    </ul>
                    <br />
                    <h4>2. Database Setup</h4>
                    
                   <div class="control-group">
      
    <label class="control-label" for="db_host">Host</label>
    <div class="controls">
      <input type="text" id="db_host" name="db_host"  autocomplete="off">
    </div>
  </div>
             <div class="control-group">
      
    <label class="control-label" for="db_user">User</label>
    <div class="controls">
      <input type="text" id="db_user" name="db_user"  autocomplete="off">
    </div>
  </div>
                     <div class="control-group">
      
    <label class="control-label" for="db_password">Password</label>
    <div class="controls">
      <input type="password" id="db_password" name="db_password"  autocomplete="off">
    </div>
  </div>
                     <div class="control-group">
      
    <label class="control-label" for="db_name">Database Name</label>
    <div class="controls">
      <input type="text" id="db_name" name="db_name"  autocomplete="off">
    </div>
  </div>
                   <br />
                    <h4>3. Admin Password</h4>
                    <br />
                    <div class="control-group">
      
    <label class="control-label" for="password">Type Password</label>
    <div class="controls">
      <input type="password" id="password" name="password"  autocomplete="off">
    </div>
  </div>
                    <div class="control-group">
      
    <label class="control-label" for="password_c">Confirm Password</label>
    <div class="controls">
      <input type="password" id="password_c" name="password_c"  autocomplete="off">
    </div>
  </div>
                    <br />
                    <h4>4. System identifier</h4>
                    <p>Following id should be unique to this installation of OpenEvSys. </p>
                    <div class="control-group">
      
    <label class="control-label" for="base_uuid">Unique identifier</label>
    <div class="controls">
      <input type="text" id="base_uuid" name="base_uuid" value="<?php echo getUniqueCode(5); ?>" autocomplete="off">
    </div>
  </div>
                    <br />
                    <center>
                        <?php if(isset($file_check) && $file_check){ ?>
                        <input type='submit' value='Check Permissions' class='btn'/>
                        <?php }else{?>
                        <input type='submit' value='Install' class='btn' name='install' />
                        <?php }?>
                    </center>
                <?php
                    }
                ?>
           
    </form>
    </div>

        <div id="footer">
            <span>About</span>
            <a href="http://openevsys.org" target='blank'>OpenEvSys</a><span>&nbsp;|&nbsp;</span>
            <span>&copy;</span>
            <a href="http://www.huridocs.org" target='blank'>HURIDOCS</a><span>&nbsp;|&nbsp;</span>
            <a href="http://www.fsf.org/licensing/licenses/agpl-3.0.html" target='blank'>AGPL v3 licensed</a><span>&nbsp;|&nbsp;</span>
            <span class='version'>VERSION : <?php echo $version; ?></span>
        </div>
    </div>
</div>
</body>
</html>
