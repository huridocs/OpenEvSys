<?php
global $conf;
?>

<script type="text/javascript" src="res/jquery/jquery-ui-map/jquery.ui.map.js"></script>
<script type="text/javascript" src="res/markerclusterer/markerclusterer_packed.js"></script>
<script type="text/javascript" src="res/js/jquery.facetsearch.js"></script>
 <script type="text/javascript" src="http://www.google.com/jsapi"></script>
        <script type="text/javascript">
            google.load('visualization', '1', {packages: ['corechart','charteditor']});
        </script>
<div class="panel">
    <ul class="nav nav-tabs facet-tabs" id="myTab">
        <?php
        $i = 0;

        foreach ($entities as $entity) {
            ?>
            <li <?php if ($i == 0) echo 'class="active"' ?>><a href="#<?php echo $entity ?>"><?php echo $domaindata->$entity->label ?></a></li>
            <?php
            $i++;
        }
        ?>
    </ul>

    <div class="tab-content">
        <?php
        $i = 0;
        $facetlabels = array();
        foreach ($entities as $entity) {
            if ($domaindata->$entity->ac_type) {
                $en = $domaindata->$entity->ac_type;
                $fields = $domaindata->$en->fields;
            } else {
                $fields = $domaindata->$entity->fields;
            }
            ?>
            <div class="tab-pane <?php if ($i == 0) echo "active"; ?>" id="<?php echo $entity ?>">
                <select name="facets" multiple="multiple" class="facetsselect" id="<?php echo $entity ?>facets"> <?php
        $allowedTypes = array("mt_tree", "mt_select", "checkbox", "radio");
        foreach ($fields as $field) {
            if (in_array($field->field_type, $allowedTypes)) {
                $facetlabels[$entity][$field->value] = $field->label;
                    ?>
                            <option value="<?php echo $field->value ?>"><?php echo $field->label ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>

                <button type="button" class="btn btn-primary loadfacets" name="load"  data-entity="<?php echo $entity ?>" ><i class="icon-zoom-in icon-white"></i> <?php echo _t('Load') ?></button>

            </div>
            <?php
            $i++;
        }
        
        
        ?>
    </div>
    <br/> <br/>
    <div class="facetsearchcontainer"></div>

</div>

<script type="text/javascript">
    var facetlabels = <?php echo json_encode($facetlabels)?>;
    
    jQuery(document).ready(function($) {
        $('.loadfacets').click(function (e) {
            $('.facetsearchcontainer').html("")
            var entity = $(this).data("entity");
            
            var selectedfacets = $("#"+entity+"facets").val()
            var facets = new Array();
            for (i in selectedfacets){
                var facet = new Object();
                facet.field = selectedfacets[i];
                facet.display = facetlabels[entity][selectedfacets[i]];
                facet.entity = entity;
                facets.push(facet)
            }
        
        
            $('.facetsearchcontainer').facetsearch({
                search_url: 'index.php?mod=analysis&act=facetsearchresults&',
                facets: facets,
                entities: [
                    entity, 
                ],
                paging: {
                    from: 0,
                    size: 30
                }
            });
        })
    
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
       
 
    });
</script>