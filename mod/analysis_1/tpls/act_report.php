<h2><?php echo _t('SEARCH_REPORT');?></h2>
<br />
<a class="btn" href='JavaScript:window.print();' ><i class="icon-print"></i>  <?php echo _t('PRINT_THIS_PAGE')?></a>
<br /><br />
<div id="browse">
<?php
	if($rpt_columnValues != null){		
		shn_form_get_html_table($columnNames, $rpt_columnValues);		
	}
?>
<br />
</div>
<a class="btn" href="<?php echo get_url('analysis','search_result',null,array('main_entity'=>$main_entity)); ?>"><i class="icon-chevron-left"></i> <?php echo _('BACK'); ?></a>
