<?php
	global $conf;
?>
<!--<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
-->
<script type="text/javascript" src="res/jquery/jquery-ui-map/jquery.ui.map.js"></script>
<script type="text/javascript" src="res/markerclusterer/markerclusterer_packed.js"></script>
<script type="text/javascript" src="res/js/jquery.facetsearch.js"></script>
	
<div class="panel">
    <ul class="nav nav-tabs" id="myTab">
        <?php
        $i = 0;
        
        foreach($entities as $entity){
            
            ?>
  <li <?php if($i == 0)echo 'class="active"'?>><a href="#<?php echo $entity?>"><?php echo $domaindata->$entity->label?></a></li>
            <?php
            $i++;
        }
        ?>
</ul>
 
<div class="tab-content">
     <?php
        $i = 0;
        
        foreach($entities as $entity){
            if($domaindata->$entity->ac_type){
                $en = $domaindata->$entity->ac_type;
                $fields = $domaindata->$en->fields;
            }else{
                $fields = $domaindata->$entity->fields;
            }
            ?>
  <div class="tab-pane <?php if($i == 0)echo "active";?>" id="<?php echo $entity?>">
     <select name="facets" multiple="multiple" class="facetsselect"> <?php
     $allowedTypes = array("mt_tree","mt_select","checkbox","radio");
      foreach($fields as $field){
          if(in_array($field->field_type,$allowedTypes)){
              ?>
         <option value="<?php echo $field->value?>"><?php echo $field->label?></option>
         <?php
          }
      }
      ?>
     </select>
      
     <button type="button" class="btn btn-primary" name="load"  ><i class="icon-zoom-in icon-white"></i> <?php echo _t('Load') ?></button>
              
  </div>
    <?php
            $i++;
        }
        ?>
</div>
    <div class="facetsearchcontainer"></div>

</div>

  <script type="text/javascript">
jQuery(document).ready(function($) {
    $('#myTab a').click(function (e) {
  e.preventDefault();
  $(this).tab('show');
})
$(".facetsselect").select2({
        width: 'resolve',
        allowClear: true,
        closeOnSelect:false,
        placeholder: "Select facets"
    });
  $('.facetsearchcontainer').facetsearch({
    search_url: 'index.php?mod=analysis&act=facetsearchresults&',
    facets: [
        {'field':'confidentiality', 'display': 'confidentiality','entity':'event'}, 
        {'field':'geographical_term', 'display': 'geographical_term','entity':'event'},        
        {'field':'monitoring_status', 'display': 'monitoring_status','entity':'event'}
        
    ],
    entities: [
        'event', 
    ],
    paging: {
      from: 0,
      size: 10
    }
  });
 
});
  </script>