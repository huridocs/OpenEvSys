<h2><?php echo _t('SYSTEM_CONFIGURATION')?></h2>
<br>
<form action='<?php 
echo get_url('admin','System_configuration')
 ?>' method='post'>
<table class='browse'>
<thead>
<tr>
<td><?php echo _t("CONFIG_VARIABLE")?></td>
<td><?php echo _t("CURRENT_VALUE")?></td>
<td><?php echo _t("MODIFIED_VALUE")?></td>
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
				type="text" style="border: medium none ;" name="<?php echo $value?>" id="<?php echo $value?>" <?php echo $readonly?> value="<?php echo $conf[$value]?>"
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
<input type="submit" name='submit' value="<?php echo _t('SAVE') ?>" />
</center>
</form>
