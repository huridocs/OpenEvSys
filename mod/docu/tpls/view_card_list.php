<?php
	function draw_card_list($active)
	{		
		switch($active){
	        case 'dc':			
				$dc = "active";	 
				break;
	        case 'lk':
				$lk = "active";
	            break;
			case 'al':			
				$al = "active";
	            break;
	    }
?>
		<div class="card_list">
	    	<a href="<?php get_url('docu','view_document')?>" class="first <?php echo $dc ?>"><?php echo _t('DOCUMENT_DETAILS') ?></a>
	     	<a href="<?php get_url('docu','link')?>" class="<?php echo $lk ?>"><?php echo _t('LINKS') ?></a>
	    	<a href="<?php get_url('docu', 'audit')?>" class="<?php echo $al ?>"><?php echo _t('AUDIT_LOG') ?></a>    
		</div>
<?php 		
	}
?>