
<div>
   
    <h2><?php echo _t('Select Event') ?></h2>
    <div class="form-container"> 
        <form class="form-horizontal"  action='<?php echo get_url('events', 'add_act_full', null, array('eid' => $_GET['eid'])) ?>' method='post' enctype='multipart/form-data'>            
           
                       <div class="box-content">
                            <?php
                            include_once APPROOT . 'inc/EventSearch.class.php';
                            //$data['eid'] = null;
                            $eventSearch = New EventSearch();
                            $eventSearch->render($data);
                            ?></div>
          


        </form>       
    </div>
</div>
