<h3 class="breadcrumb" style="padding-top:4px;padding-bottom: 4px"><?php echo '<span>'._t('EVENT_TITLE').'</span> : <span>'.htmlspecialchars($event->event_title).'</span>';?></h3>
<?php 
    $ms = get_mt_term($event->monitoring_status); 
    if(isset($ms)&&$ms!=''){ ?>
<strong class='mstatus'><span><?php echo _t('MONITORING_STATUS');?></span> : <span ><?php echo htmlspecialchars($ms)?></span></strong>
<?php } ?>

