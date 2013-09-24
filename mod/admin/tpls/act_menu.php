<?php 
$defaultMenu = array(
    "eventsbrowse"=>array("level"=>0,"title"=>_t('EVENTS')),
    "get_event"=>array("level"=>1,"title"=>_t('EVENT_DESCRIPTION'),"parent"=>"eventsbrowse"),
    "vp_list"=>array("level"=>1,"title"=>_t('VICTIMS_AND_PERPETRATORS'),"parent"=>"eventsbrowse"),
    "src_list"=>array("level"=>1,"title"=>_t('SOURCES'),"parent"=>"eventsbrowse"),
    "intv_list"=>array("level"=>1,"title"=>_t('INTERVENTIONS'),"parent"=>"eventsbrowse"),
    "coe_list"=>array("level"=>1,"title"=>_t('CHAIN_OF_EVENTS'),"parent"=>"eventsbrowse"),
    "event_doc_list"=>array("level"=>1,"title"=>_t('DOCUMENTS'),"parent"=>"eventsbrowse"),
    "event_audit"=>array("level"=>1,"title"=>_t('AUDIT_LOG'),"parent"=>"eventsbrowse"),
    "event_permissions"=>array("level"=>1,"title"=>_t('PERMISSIONS'),"parent"=>"eventsbrowse"),
    
    "personsbrowse"=>array("level"=>0,"title"=>_t('PERSONS')),
    "person"=>array("level"=>1,"title"=>_t('PERSON_RECORDS_S_'),"parent"=>"personsbrowse"),
    "person_address_list"=>array("level"=>1,"title"=>_t('PERSON_ADDRESS_ES_'),"parent"=>"personsbrowse"),
    "person_biography_list"=>array("level"=>1,"title"=>_t('BIOGRAPHIC_DETAIL_S_'),"parent"=>"personsbrowse"),
    "person_role_list"=>array("level"=>1,"title"=>_t('ROLE_LIST'),"parent"=>"personsbrowse"),
    "person_audit_log"=>array("level"=>1,"title"=>_t('AUDIT_LOG'),"parent"=>"personsbrowse"),
    "person_permissions"=>array("level"=>1,"title"=>_t('PERMISSIONS'),"parent"=>"personsbrowse"),
    
    "documentsbrowse"=>array("level"=>0,"title"=>_t('DOCUMENTS')),
    "view_document"=>array("level"=>1,"title"=>_t('DOCUMENT_DETAILS'),"parent"=>"documentsbrowse"),
    "document_link"=>array("level"=>1,"title"=>_t('LINKS'),"parent"=>"documentsbrowse"),
    "document_audit"=>array("level"=>1,"title"=>_t('AUDIT_LOG'),"parent"=>"documentsbrowse"),
    
    "biographybrowse"=>array("level"=>0,"title"=>_t('BIOGRAPHIC_DETAILS')),
    
    "addnew"=>array("level"=>0,"url"=>"#","title"=>_t('ADD_NEW')),
    "new_event"=>array("level"=>1,"title"=>_t('ADD_NEW_EVENT'),"parent"=>"addnew"),
    "new_person"=>array("level"=>1,"title"=>_t('ADD_NEW_PERSON'),"parent"=>"addnew"),
    "new_document"=>array("level"=>1,"title"=>_t('ADD_NEW_DOCUMENT'),"parent"=>"addnew"),
    "add_user"=>array("level"=>1,"title"=>_t('ADD_NEW_USER'),"parent"=>"addnew"),
    
);

$defaultMenuRight = array(
    "dashboard"=>array("level"=>0,"title"=>_t('Dashboard')),
    "analysis"=>array("level"=>0,"title"=>_t('ANALYSIS')),
    "adv_search"=>array("level"=>1,"title"=>_t('ADVANCED_SEARCH'),"parent"=>"analysis"),
    "search_query"=>array("level"=>1,"title"=>_t('SAVED_QUERIES'),"parent"=>"analysis"),
    "facetsearch"=>array("level"=>1,"title"=>_t('Charts and map'),"parent"=>"analysis"),
    
    
);

$defaultMenuOrdered = array();
$order = 0;
foreach($defaultMenu as $key=>$value){
    $value['order'] = $order;
    $value['slug'] = $key;
    $defaultMenuOrdered[] = $value;
    $order++;
}
?>
<script type="text/javascript" src="res/jquery/jquery.nestable.js"></script>
<h2><?php echo _t('Menu') ?></h2>
<div class="row-fluid">
    <div class="span3">
        <div class="sidebar-nav">
            <div class="well" style="padding: 8px 0;">
            </div>
        </div>
    </div>
    <div class="span9">

        <?php
        $activemenu = $_REQUEST['menu'];
        if (!$activemenu) {
            $activemenu = "top";
        }
        $menuNames = array("top" => _t("Top menu"),
            "top_right" => _t("Top right menu"),
        );
        ?>
        <div>
            <ul class="nav nav-tabs tabnav">
                <?php foreach ($menuNames as $menu => $label) {
                    ?>
                    <li <?php if ($menu == $activemenu) echo " class='active'"; ?> >
                        <a  href="<?php get_url('admin', 'menu', null, array('menu' => $menu)) ?>" >
                            <?php echo $label ?>
                        </a>
                    </li>
                    <?php
                }
                ?>

            </ul>
        </div>

        <div class="dd" id="nestable" style="width:100%">
            <ol class="dd-list">
                <?php
                $menuItems = $defaultMenuOrdered;
                $count = count($menuItems);
                $levelsarray = array(-1 => 0);

                foreach ($menuItems as $key=>$menu ) {

                    $element1 = $menu;
                    $element2 = $menuItems[$key+1];
                     
                    $levelsarray[$level] = $menu['slug'];
                    
                    $level = $element1['level'];
                    ?>
                    <li class="dd-item" data-id="<?php echo $menu['slug']; ?>">

                        <div class="dd-handle nestableorderbg"><?php echo $menu['title']; ?></div>
                        <?php
                        if ($element2['parent'] == $menu['slug']) {
                            $level++;
                            ?>
                            <ol class="dd-list">
                                <?php
                            } elseif ($element2['parent'] == $element1['parent']) {
                                ?>
                        </li>
                        <?php
                    } else {
                        $level2 = $element2['level'];
                        //$key = array_search($element2['parent_vocab_number'], $levelsarray);
                        echo str_repeat("</li></ol>", $level - $level2);

                        /* if($key !== false){
                          echo str_repeat("</li></ol>", $level-$key-1);
                          $level = $key+1;
                          } */
                    }
                    ?>

                    <?php
                }
                echo str_repeat("</li></ol>", $level);
                echo "</li>";
                ?>
            </ol>
        </div>
        <div style="clear:both;"></div> 
        <input type="hidden" name="order" id="order" value="used"/>

        <input type="hidden" name="itemsorder" id="itemsorder" value=""/>
        <br/>
        <script>

            $(document).ready(function()
            {
                var updateHidden = function(e)
                {
        
                    if (window.JSON) {
                        $('#itemsorder').val(window.JSON.stringify($('#nestable').nestable('serialize')));//, null, 2));
                    }
                };
                $('#nestable').nestable({
                    maxDepth :2,
                    group:1
                }).on('change', updateHidden);
        
                updateHidden($('#nestable').data('output', $('#itemsorder')));
    
            });
        </script>
    </div>
</div>