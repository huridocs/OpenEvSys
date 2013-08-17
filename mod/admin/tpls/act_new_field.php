<?php global $conf; ?>

<h2><?php echo _t('ADD_NEW_FIELD') ?></h2>
<div class="form-container">
    <form class="form-horizontal"  action='<?php echo get_url('admin', 'new_field') ?>' method='post'>
        <div class="control-group">
            <div class="controls">
                <button  type="submit" class="btn btn-primary"  name='add_new' ><i class="icon-plus icon-white"></i> <?php echo _t('ADD_FIELD') ?></button>

            </div></div>
        <div class='control-group'>
            <label  class="control-label" for="field_number"><?php echo _t('SELECT_A_FORMAT') ?></label>

            <div class="controls">
                <select  name="entity_select" id="entity_select"    class='select input-large'  >
                    <?php foreach ($entity_select_options as $k => $v) {
                        ?>
                        <option value="<?php echo $k ?>" <?php if(isset($_POST['entity_select']) && $_POST['entity_select'] == $k ){echo " selected='selected' ";} ?>><?php echo $v ?></option>
                        <?php
                    }
                    ?>
                </select>
                <div class="help-inline"><span class="label label-important"><?php echo _t('IS_REQUIRED') ?></span>    </div>
            </div>

        </div>

        <div class='control-group'>	
            <label  class="control-label" for="field_number"><?php echo _t('FIELD_NUMBER') ?></label>

            <div class="controls">
                <input title="field_number" type="text" name="field_number" id="field_number"  value="<?php if(isset($_POST['field_number'])){echo $_POST['field_number'];} ?>"   class='input-large'  />
                <div class="help-inline"><span class="label label-important"><?php echo _t('IS_REQUIRED') ?></span>    </div>
            </div>
        </div>
        <div class='control-group'>	
            <label  class="control-label" for="field_name"><?php echo _t('FIELD_NAME') ?></label>

            <div class="controls">
                <input title="field_name" type="text" name="field_name" id="field_name"  value="<?php if(isset($_POST['field_name'])){echo $_POST['field_name'];} ?>"   class='input-large'  />
                <div class="help-inline"><span class="label label-important"><?php echo _t('IS_REQUIRED') ?></span>    </div>
            </div>
        </div>
        <div class='control-group'>	
            <label  class="control-label" for="field_label"><?php echo _t('FIELD_LABEL') ?></label>

            <div class="controls">
                <input title="field_label" type="text" name="field_label" id="field_label"  value="<?php if(isset($_POST['field_label'])){echo $_POST['field_label'];} ?>"  class='input-large'  />
                <div class="help-inline"><span class="label label-important"><?php echo _t('IS_REQUIRED') ?></span>    </div>
            </div>
        </div>
        <div class='control-group'>        <label  class="control-label" for="field_type"><?php echo _t('FIELD_TYPE') ?></label>
            <div class="controls">
                <select class="select input-large" title="field_type" name="field_type" id="field_type"  >
                    <?php foreach ($field_type_options as $k => $v) {
                        ?>
                        <option value="<?php echo $k ?>" <?php if(isset($_POST['field_type']) && $_POST['field_type'] == $k ){echo " selected='selected' ";} ?>><?php echo $v ?></option>
                        <?php
                    }
                    ?>
                </select>

                <div class="help-inline">    </div>
            </div>
        </div> 
        <div class='control-group' id="termsdiv" <?php if( isset($_POST['field_type']) && in_array($_POST['field_type'],array('mt_tree','mt_tree_multi','mt_select','mt_select_multi'))){echo 'style="display: block"';}else{echo 'style="display: none"';}?> >       
            <label  class="control-label" for="list_code"><?php echo _t('SELECT_A_MICRO_THESAURUS') ?></label>
            <div class="controls">
                <select class="select input-large" title="list_code" name="list_code" id="list_code"  >
                    <?php foreach ($taxonomies as $k => $v) {
                        ?>
                        <option value="<?php echo $k ?>" <?php if(isset($_POST['list_code']) && $_POST['list_code'] == $k ){echo " selected='selected' ";} ?>><?php echo $v ?></option>
                        <?php
                    }
                    ?>
                </select>

                <div class="help-inline"><span class="label label-important"><?php echo _t('IS_REQUIRED') ?></span> </div>
            </div>
        </div> 
        <div class='control-group'>	
            <label  class="control-label" for="required"><?php echo _t('IS_REQUIRED') ?></label>

            <div class="controls">
                <input title="required" type="checkbox" name="required" id="required"  value="y" <?php if(isset($_POST['IS_REQUIRED']) ){echo " checked='checked' ";} ?>    />
            </div>
        </div>
        <div class='control-group'>	
            <label  class="control-label" for="clarify"><?php echo _t('CLARIFY') ?></label>

            <div class="controls">
                <input title="clarify" type="checkbox"  name="clarify" id="clarify"  value="y" <?php if(isset($_POST['clarify'])){echo " checked='checked' ";} ?>   />
            </div>
        </div>

        <div class='control-group'>	
            <label  class="control-label" for="visible_new"><?php echo _t('VISIBLE_IN_FORM') ?></label>

            <div class="controls">
                <input title="visible_new" type="checkbox" <?php if(!$_POST || isset($_POST['visible_new'])){echo " checked='checked' ";} ?>  name="visible_new" id="visible_new"  value="y"    />
            </div>
        </div>
        <div class='control-group'>	
            <label  class="control-label" for="visible_view"><?php echo _t('VISIBLE_IN_VIEW') ?></label>

            <div class="controls">
                <input title="visible_view" type="checkbox" <?php if(!$_POST || isset($_POST['visible_view'])){echo " checked='checked' ";} ?>  name="visible_view" id="visible_view"  value="y"    />
            </div>
        </div>
        <div class='control-group'>	
            <label  class="control-label" for="visible_browse"><?php echo _t('VISIBLE_IN_BROWSE') ?></label>

            <div class="controls">
                <input <?php if( isset($_POST['field_type']) && $_POST['field_type'] == "location"){
                echo ' disabled="disabled" ';
                    
                }?>
                    title="visible_browse" type="checkbox"  name="visible_browse" id="visible_browse"  value="y" <?php if(isset($_POST['visible_browse']) ){echo " checked='checked' ";} ?>   />
                
            </div>
        </div>
        <div class='control-group'>	
            <label  class="control-label" for="visible_adv_search"><?php echo _t('Searchable in Analysis') ?></label>

            <div class="controls">
                <input <?php if( isset($_POST['field_type']) && $_POST['field_type'] == "location"){
                echo ' disabled="disabled" ';
                    
                }?> title="visible_adv_search" type="checkbox" <?php if(!$_POST || isset($_POST['visible_adv_search'])){echo " checked='checked' ";} ?>  name="visible_adv_search" id="visible_adv_search"  value="y"    />
            </div>
        </div>
        <div class='control-group'>	
            <label  class="control-label" for="visible_adv_search_display"><?php echo _t('Visible in Analysis') ?></label>

            <div class="controls">
                <input <?php if( isset($_POST['field_type']) && $_POST['field_type'] == "location"){
                echo ' disabled="disabled" ';
                    
                }?> title="visible_adv_search_display" type="checkbox"  name="visible_adv_search_display" id="visible_adv_search_display"  value="y"  <?php if(isset($_POST['visible_adv_search_display']) ){echo " checked='checked' ";} ?>   />
            </div>
        </div>
        <br style="clear: both" />
        <div class="control-group">
            <div class="controls">
                <button  type="submit" class="btn btn-primary"  name='add_new' ><i class="icon-plus icon-white"></i> <?php echo _t('ADD_FIELD') ?></button>

            </div></div>


    </form>
</div>

<script>
   
    $(document).ready(function(){
        $("#field_type").on("change", function() {
            var type = $("#field_type").select2("val");
            console.log(type)
            if(type == 'mt_tree' || type == 'mt_tree_multi' || type == 'mt_select' || type == 'mt_select_multi'){
                $("#termsdiv").show();
                
                $("#visible_adv_search").attr("disabled", false);
               
                $("#visible_adv_search_display").attr("disabled", false);                 
                
            }else if(type == 'location'){
                
                $("#termsdiv").hide();
                $("#visible_browse").prop('checked', false);
                $("#visible_browse").attr("disabled", true);
                
                $("#visible_adv_search").prop('checked', false);
                $("#visible_adv_search").attr("disabled", true);
               
                $("#visible_adv_search_display").prop('checked', false);
                $("#visible_adv_search_display").attr("disabled", true);
               
            }else{
                
                $("#termsdiv").hide();
                $("#visible_browse").attr("disabled", false);
                
                $("#visible_adv_search").attr("disabled", false);
               
                $("#visible_adv_search_display").attr("disabled", false);
            }
        });
        
    });
</script>