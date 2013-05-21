<h2><?php echo _t('SYSTEM_CONFIGURATION')?></h2>
<br>
<form class="form-horizontal"  action='<?php 
echo get_url('admin','System_configuration')
 ?>' method='post'>
 <?php global $conf; ?>
<script type="text/javascript" src="res/jquery/tinymce/4.0b3/jquery.tinymce.min.js"></script>
<script type="text/javascript" src="res/jquery/tinymce/4.0b3/tinymce.min.js"></script>

<div class="row-fluid">
            <div class="span12" >
                <h3><?php echo _t('Header for login page') ?></h3>

                <textarea class="tinymce" name="login_header" rows="15"><?php echo htmlentities($conf['login_header'], ENT_QUOTES, "UTF-8")?></textarea>     

            </div>
        </div>
<table class='table table-bordered table-striped table-hover'>
<thead>
<tr>
<th><?php echo _t("CONFIG_VARIABLE")?></th>
<th><?php echo _t("CURRENT_VALUE")?></th>
<th><?php echo _t("MODIFIED_VALUE")?></th>
</tr>
</thead>
<tbody>
<?php $i=0;
//FirePHP::getInstance(true)->log('Iterators');
	global $alt_conf;
	$this->alt_conf = $alt_conf;
    global $conf;
    $this->conf = $conf;
    global $alt_conf_check;
    $this->alt_conf_check = $alt_conf_check;
	//$conf value changes according to the database change
	foreach($alt_conf as $key=>$value){?>
		<tr <?php echo ($i++%2==1)?'class="odd"':''; ?>>
			<td>
				<?php echo $value;?>
			</td>
			<td><?php
				if(isset($alt_conf_check[$value])){?>
					<input type='checkbox'
					<?php
					$checked = ($conf[$value]==_t('TRUE')) ? 'checked="true"' : '';
					echo $checked;
					echo "disabled"
					?>
					/>
				<?php 
				}else{
					echo $conf[$value];	
				}
			?></td>
			<td >
			<input
			<?php if(isset($alt_conf_check[$value])){ ?>
				type='checkbox' name='<?php echo $value; ?>'  
					<?php
					echo "value='true'";
					$checked = ($conf[$value]==_t('TRUE')) ? 'checked="true"' : '';
					echo $checked;
					?> 
			<?php }else{ ?> 
				type="text"   name="<?php echo $value?>" id="<?php echo $value?>" <?php echo $readonly?> value="<?php echo $conf[$value]?>"
			<?php } ?>
			/>
			<?php  
				//$extra_opts['required']="y";
	//			$extra_opts["help"]=(4000+$key);
	//			shn_form_extra_opts($extra_opts);
			?>
			</td>
		</tr>
<?php }?>
</tbody>
</table>
<center>
<br />
<button type="submit" class="btn btn-primary" name='submit' ><i class="icon-ok icon-white"></i> <?php echo _t('SAVE') ?></button>
</center>
</form>
<script>
    tinymce.init({
        selector: ".tinymce",
        plugins: [
            "advlist autolink link image lists charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code insertdatetime media nonbreaking",
            "table contextmenu directionality emoticons paste textcolor filemanager"
        ],
        toolbar: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect forecolor backcolor | link unlink anchor | filemanager image media | print preview code"
    });
</script>