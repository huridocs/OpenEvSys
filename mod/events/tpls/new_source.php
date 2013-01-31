
<?php include_once('event_title.php') ?>

<div class="panel">
<div class="fuelux">
        <div id="myWizard" class="wizard">
            <ul class="steps">
                <li class="active">
                        <span class="badge badge-info">1</span><?php echo _t('ADD_SOURCE') ?><span class="chevron"></span>
                   
                
                <li><span class="badge ">2</span><?php echo _t('ADD_INFORMATION') ?><span class="chevron"></span></li>
                 <li><span class="badge">3</span><?php echo _t('FINISH') ?><span class="chevron"></span></li>
            </ul>
           
        </div>
    </div>
<br />

<h2><?php echo _t('WHO_IS_THE_SOURCE_') ?></h2>
<br />
<div class="form-container"> 
<form class="form-horizontal"  id="person_form" name="person_form" action='<?php echo get_url('events','add_source','new_source',array('eid'=>$event_id))?>' method='post' enctype='multipart/form-data'>
	<?php $person_form = person_form('new');?>
    <?php $fields = shn_form_get_html_fields($person_form);  ?>
    <?php $fields = place_form_elements($person_form,$fields); ?>
   <div class="control-group">
            <div class="controls"> 
	<a class="btn" href="<?php echo get_url('events','add_source',null,array('eid'=>$event_id)) ?>"><i class="icon-stop"></i> <?php echo _t('CANCEL')?></a>
	<button type="submit" class="btn"  name='save'><?php echo _t('CONTINUE') ?></button>
	
	</div></div>
</form>
</div>

<br />
</div>
