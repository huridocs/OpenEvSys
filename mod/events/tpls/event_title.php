<h2><?php echo '<span>'._t('EVENT_TITLE').'</span> : <span>'.htmlspecialchars($event->event_title).'</span>';?></h2>
<?php 
    $ms = get_mt_term($event->monitoring_status); 
    if(isset($ms)&&$ms!=''){ ?>
<strong class='mstatus'><span><?php echo _t('MONITORING_STATUS');?></span> : <span ><?php echo htmlspecialchars($ms)?></span></strong>
<?php } ?>
<br />
