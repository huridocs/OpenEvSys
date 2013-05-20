<h2>
	<?php echo _t('EXTENSIONS')?>
</h2>
<br>
<table class='table table-bordered table-striped table-hover'>
	<thead>
		<tr>
			<th><?php echo _t('EXTENSIONS'); ?></th>
		</tr>
		<?php 
			global $conf;
			$lines = file($conf['extension'].'/extensions.list?host='.$_SERVER['HTTP_HOST'],FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
			foreach ($lines as $line_num => $line) {
				echo "<tr><td>". $line ."</td></tr><br />\n";
			}
		?>
	</thead>
</table>
