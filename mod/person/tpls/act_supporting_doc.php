<?php include_once('tabs.php')?>
<?php include_once('person_name.php')?>
<?php
    include_once('view_card_list.php');
    draw_card_list('sd',$pid);	
?>
<div class="panel">
<br/>

<div class="form-container">
<form class="form-horizontal"  action='<?php echo get_url('person','supporting_doc',null,array('pid'=>$pid))?>' method='post' enctype='multipart/form-data'>
<?php
	echo "<h3>" ._t('ADD_SUPPORTING_DOCUMENT_S_') . "</h3>";
	echo "<br />";
	//$fields = shn_form_get_html_fields($supporting_doc_form);
	//include_once APPROOT.'mod/person/tpls/supporting_doc_form.php';
?>
</form>
</div>
</div>

