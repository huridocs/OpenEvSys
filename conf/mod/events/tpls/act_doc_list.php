<?php 
	
	include_once('event_title.php');
   
?>
<div class="panel">    
	<?php
		if(isset($_GET['filter']) || count($columnValues) ){
	?>
	<div id="browse">
	<?php 
		if($columnValues != null && count($columnValues) ){
			$result_pager->render_pages();
		}
		
		shn_form_get_html_filter_table($columnNames, $columnValues, $htmlFields);
			
		if($columnValues != null && count($columnValues) ){
			$result_pager->render_pages();
		}		
	?>
	</div>
	<br />
	<?php
		}
		else{
	        ?>
    		<div class='alert alert-info'> <button type="button" class="close" data-dismiss="alert">×</button> 
        		<?php echo _t('THERE_ARE_NO_SUPPORTING_DOCUMENTS_ABOUT_EVENTS_YET__YOU_SHOULD_ADD_SOME_') ?>
    		</div>
    		<?php
		}
	?>
    
</div>

<script type="text/javascript">
  $(document).ready(function() {
	  var  td = $("#entity_type").parent().parent();
	  $("#entity_type").remove(); 
	  //td.attr('style', 'background: #EEEAD4;');
  });
</script>
