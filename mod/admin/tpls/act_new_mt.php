<?php global $conf; ?>

<h2><?php echo _t('Add new Micro-thesauri') ?></h2>
<div class="form-container">
    <form class="form-horizontal"  action='<?php echo get_url('admin', 'new_mt') ?>' method='post'>
          

      
        <div class='control-group'>	
            <label  class="control-label" for="term"><?php echo _t('MICRO_THESAURI') ?></label>

            <div class="controls">
                <input title="term" type="text" name="term" id="term"  value=""   class='input-large'  />
                <div class="help-inline"><span class="label label-important"><?php echo _t('IS_REQUIRED') ?></span>    </div>
            </div>
        </div>
        
        <br style="clear: both" />
        <div class="control-group">
            <div class="controls">
                <button  type="submit" class="btn btn-primary"  name='add_new' ><i class="icon-plus icon-white"></i> <?php echo _t('Add Micro-thesauri') ?></button>

            </div></div>


    </form>
</div>
