<?php
global $conf;

$imagesfolder = WWWWROOT . "images" . DS . "uploads" . DS;
$ext_img = array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff');
$ext_file = array('doc', 'docx', 'pdf', 'xls', 'xlsx', 'txt', 'csv', 'html', 'psd', 'sql', 'php', 'log', 'fla', 'xml', 'ade', 'adp', 'ppt', 'pptx');
$ext_video = array('mov', 'mpeg', 'mp4', 'avi', 'mpg', 'wma');
$ext_music = array('mp3', 'm4a', 'ac3', 'aiff', 'mid');
$ext_misc = array('zip', 'rar',);


$ext = array_merge($ext_img, $ext_file, $ext_misc, $ext_video, $ext_music); //extensions allowed

if (!isset($_GET['img_only'])) {
    $_GET['img_only'] = 0;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="robots" content="noindex,nofollow">
            <link rel="stylesheet" type="text/css" media="screen" href="theme/<?php echo $conf['theme'] ?>/css/bootstrap.min.css" />
            <link rel="stylesheet" type="text/css" media="screen" href="theme/<?php echo $conf['theme'] ?>/css/bootstrap-responsive.min.css" />

            <link rel="stylesheet" type="text/css" media="screen" href="theme/<?php echo $conf['theme'] ?>/screen.css?v=<?php echo $version ?>"/>
            <link rel="stylesheet" type="text/css" media="screen" href="res/jquery/tinymce/4.0b3/plugins/filemanager/css/bootstrap-lightbox.min.css?v=<?php echo $version ?>"/>

            <link href="res/jquery/tinymce/4.0b3/plugins/filemanager/css/style.css" rel="stylesheet" type="text/css" />
            <link href="res/jquery/tinymce/4.0b3/plugins/filemanager/css/dropzone.css" type="text/css" rel="stylesheet" />
            <script src="res/jquery/jquery-1.9.1.min.js"></script>

            <script src="res/bootstrap/bootstrap.min.js"></script>
            <script type="text/javascript" src="res/jquery/tinymce/4.0b3/plugins/filemanager/js/bootstrap-lightbox.min.js"></script>
            <script type="text/javascript" src="res/jquery/tinymce/4.0b3/plugins/filemanager/js/dropzone.min.js"></script>
            <script>
                var ext_img=new Array('<?php echo implode("','", $ext_img) ?>');
            </script>
            <script type="text/javascript" src="res/jquery/tinymce/4.0b3/plugins/filemanager/js/include.js"></script>
    </head>
    <body style="padding-top:0px;">
        <input type="hidden" id="track" value="<?php echo $_GET['editor']; ?>" />
        <input type="hidden" id="insert_folder_name" value="<?php echo _t('Insert folder name:'); ?>" />

        <!----- uploader div start ------->
        <div class="uploader">            
            <form action="index.php?mod=home&act=filemanagerupload" id="my-dropzone" class="dropzone">

            </form>
            <center><button class="btn btn-large btn-primary close-uploader"><i class="icon-backward icon-white"></i> <?php echo _t('Return to files list') ?></button></center>
            <div class="space10"></div><div class="space10"></div>
        </div>
        <!----- uploader div start ------->

        <div class="container-fluid">


            <!----- header div start ------->
            <div class="filters"><button class="btn btn-primary upload-btn" style="margin-left:5px;"><i class="icon-upload icon-white"></i> <?php echo _t('Upload a file') ?></button> 
            </div>
        </div>

        <!----- header div end ------->




        <div class="row-fluid ff-container">
            <div class="span12 pull-right">
                <ul class="thumbnails ff-items">
                    <?php
                    $class_ext = '';
                    $src = '';
                    $dir = opendir($imagesfolder);
                    $i = 0;
                    $k = 0;
                    if ($_GET['img_only'] == 1)
                        $apply = 'apply_img';
                    else
                        $apply = 'apply';

                    $files = scandir($imagesfolder);

                    //foreach ($files as $file) {
                    /* foreach ($files as $file) {
                      if (is_dir($imagesfolder . $file) && $file != '.' && $file != '..') {
                      if (($i + $k) % 6 == 0 && $i + $k > 0) {
                      ?>
                      </div>
                      <div class="space10"></div>
                      <?php
                      }
                      if (($i + $k) % 6 == 0) {
                      ?>
                      <div class="row-fluid">
                      <?php
                      }
                      $class_ext = 3;

                      $k++;
                      }
                      } */
                    foreach ($files as $file) {
                        if ($file != '.' && $file != '..' && !is_dir($imagesfolder . $file)) {
                            if (($i) % 6 == 0 && $i > 0) {
                                ?></div><div class="space10"></div><?php
                }
                if (($i) % 6 == 0) {
                                ?>
                        <div class="row-fluid"><?php
                }
                $is_img = false;
                $file_ext = substr(strrchr($file, '.'), 1);

                if (in_array($file_ext, $ext)) {
                    if (in_array($file_ext, $ext_img)) {
                        $src = "images/uploads/" . $file;
                        $is_img = true;
                    } elseif (file_exists(WWWROOT . 'res/jquery/tinymce/4.0b3/plugins/filemanager/ico/' . strtoupper($file_ext) . ".png")) {
                        $src = 'res/jquery/tinymce/4.0b3/plugins/filemanager/ico/' . strtoupper($file_ext) . ".png";
                    } else {
                        $src = 'res/jquery/tinymce/4.0b3/plugins/filemanager/ico/Default.png';
                    }

                    if (in_array($file_ext, $ext_video)) {
                        $class_ext = 4;
                    } elseif (in_array($file_ext, $ext_img)) {
                        $class_ext = 2;
                    } elseif (in_array($file_ext, $ext_music)) {
                        $class_ext = 5;
                    } elseif (in_array($file_ext, $ext_misc)) {
                        $class_ext = 3;
                    } else {
                        $class_ext = 1;
                    }
                                ?>
                            <li class="span2 ff-item-type-<?php echo $class_ext; ?>">
                                <div class="boxes thumbnail">
                                    <form action="index.php?mod=home&act=forcedownload" method="post" class="download-form">
                                        <input type="hidden" name="path" value="<?php echo $file ?>"/>
                                        <input type="hidden" name="name" value="<?php echo $file ?>"/>

                                        <div class="btn-group">
                                            <button type="submit" title="<?php echo _t('Download') ?>" class="btn"><i class="icon-download"></i></button>

                                            <?php if ($is_img) { ?>
                                                <a class="btn preview" title="<?php echo _t('Preview') ?>" data-url="<?php echo $src; ?>" data-toggle="lightbox" href="#demoLightbox"><i class=" icon-eye-open"></i></a>
                                            <?php } else { ?>
                                                <a class="btn preview disabled"><i class=" icon-eye-open"></i></a>
                                            <?php } ?>
                                            <a href="index.php?mod=home&act=filemanager&del_file=<?php echo $file; ?>&img_only=<? echo $_GET['img_only'] ?>&editor=<?php echo $_GET['editor'] ? $_GET['editor'] : 'mce_0'; ?>" class="btn erase-button btn-error" onclick="return confirm('<?php echo _t('Are you sure you want to delete this file?'); ?>');" title="<?php echo _t('Erase') ?>"><i class="icon-trash"></i></a>
                                        </div>

                                    </form>
                                    <a href="#" title="<?php echo _t('Select') ?>" onclick="<?php echo $apply; ?>('<?php echo $src; ?>')">
                                        <img data-src="holder.js/140x100" alt="140x100" src="<?php echo $src; ?>" height="100">
                                            <h4><?php echo substr($file, 0, '-' . (strlen($file_ext) + 1)); ?></h4></a>


                                </div>
                            </li>
                            <?php
                            $i++;
                        }
                    }
                }
                ?></div><?php
                closedir($dir);
                ?>
            </ul>
        </div>
        </div>
        </div>

        <!----- lightbox div end ------->    
        <div id="demoLightbox" class="lightbox hide fade"  tabindex="-1" role="dialog" aria-hidden="true">
            <div class='lightbox-content'>
                <img id="full-img" src="">
            </div>    
        </div>
        <!----- lightbox div end ------->
        <script>
        
            $(document).ready(function(){	
                $('input[name=radio-sort]').click(function(){
                    var li=$(this).data('item');
                    $('.filters label').removeClass("btn-info");
                    $('#'+li).addClass("btn-info");
                    if(li=='ff-item-type-all'){ 
                        $('.thumbnails li').fadeTo(500,1); 
                    }else{
                        if($(this).is(':checked')){
                            $('.thumbnails li').not('.'+li).fadeTo(500,0.1);
                            $('.thumbnails li.'+li).fadeTo(500,1);
                        }
                    }
                });
                $('.upload-btn').click(function(){
                    $('.uploader').show(500);
                });
                $('.close-uploader').click(function(){
                    $('.uploader').hide(500);
                    window.location.reload();
                });
                $('.preview').click(function(){
                    $('#full-img').attr('src',$(this).data('url'));
                    return true;
                });
                $('.new-folder').click(function(){
                    folder_name=window.prompt($('#insert_folder_name').val(),'Nuova Cartella');
                    if(folder_name){
                        folder_path=$('#root').val()+$('#cur_dir').val()+ folder_name;
                        $.ajax({
                            type: "POST",
                            url: "createfolder.php",
                            data: {path: folder_path}
                        }).done(function( msg ) {
                            window.location.reload();
                        });
                    }
                });
	
                var boxes = $('.boxes');
                boxes.height('auto');
                var maxHeight = Math.max.apply(
                Math, boxes.map(function() {
                    return $(this).height();
                }).get());
                boxes.height(maxHeight);
            });

	

            function apply(file){
                var path = $('#cur_dir').val();
                var track = $('#track').val();
                var target = window.parent.document.getElementById(track+'_ifr');
                var closed = window.parent.document.getElementsByClassName('mce-filemanager');
                var ext=file.split('.').pop();
                var fill='';
                if($.inArray(ext, ext_img) > -1){
    	
                    fill=$("<img />",{"src":file});
                }else{
                    fill=$("<a />").attr("href", file).text(ext_check[0]);
                }
                $(target).contents().find('#tinymce').append(fill);
                $(closed).find('.mce-close').trigger('click');
            }
            function apply_img(file){
                var path = $('#cur_dir').val();
                var track = $('#track').val();
                var target = window.parent.document.getElementsByClassName('mce-img_'+track);
                var closed = window.parent.document.getElementsByClassName('mce-filemanager');
                $(target).val(file);
                $(closed).find('.mce-close').trigger('click');
            }

        </script>
    </body>
</html>
<?php
exit;
?>
