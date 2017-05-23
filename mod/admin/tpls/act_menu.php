<?php ?>
<script type="text/javascript" src="res/jquery/jquery.nestable.js"></script>

<h2><?php echo _t('Menu') ?></h2>
<div class="row-fluid">
    <form class="form-horizontal"  action='<?php echo get_url('admin', 'menu',null,array('activemenu' => $activemenu)); ?>' method='post' id="update-nav-menu">
       <div class="span3">
            <div class="sidebar-nav">
                <div class="well" style="padding: 8px 5px;">
                    <label><?php echo _t('Click to add an item to a menu')?></label>
                    <?php
                    $menuItems = $defaulMenuItemsOrdered;
                    $count = count($menuItems);
                    $levelsarray = array(-1 => 0);

                    foreach ($menuItems as $key => $menu):
                        $element1 = $menu;
                        $element2 = $menuItems[$key + 1];

                        $levelsarray[$level] = $menu['slug'];

                        $level = $element1['level']; ?>
                        <label data-id="<?php echo $menu['slug']; ?>" class="menu-item-title" <?php if($level)echo "style='margin-left:".($level*15)."px'";?>>
                            <a href="#" class="addmenulink has-spinner" data-slug="<?php echo $menu['slug'] ?>" data-title="<?php echo $menu['title'] ?>">
                                <span class="spinner"><i class="icon-spinner icon-spin"></i></span> <?php echo $menu['title']; ?></a>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="span9">
            <button type="submit" name="save" class='btn btn-primary'><i class="icon-ok icon-white"></i> <?php echo _t('SAVE') ?></button>
            <br/><br/>

            <div>
                <ul class="nav nav-tabs tabnav">
                    <?php $maxDepth = 1;
                    foreach ($menuNames as $menu => $label): ?>
                        <li <?php if ($menu == $activemenu){ echo " class='active'"; $maxDepth = $label['depth'];}; ?>>
                            <a href="<?php get_url('admin', 'menu', null, array('activemenu' => $menu)) ?>">
                                <?php echo $label['title'] ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="dd" id="nestable" style="width:100%">
                <ol class="dd-list" id="nestable-ddlist">
                    <?php $menuItems = $activeMenuItems;
                    $count = count($menuItems);
                    $levelsarray = array(-1 => 0);
                    $maxid = 0;

                    foreach ($menuItems as $key => $menu):
                        $id = $menu['id'];

                        $maxid = max($maxid, $id);
                        $element1 = $menu;
                        $element2 = $menuItems[$key + 1];

                        $levelsarray[$level] = $menu['slug'];

                        $level = $element1['level']; ?>

                        <li class="dd-item" data-id="<?php echo $id; ?>" data-slug="<?php echo $menu['slug'] ?>" id="dd-item-<?php echo $id; ?>">
                            <div class="dd-handle nestableorderbg menu-item-edit-inactive" id="titlecontainer-<?php echo $id ?>"><?php echo $menu['title']; ?></div>
                            <div>
                                <a class="item-edit" id="edit-<?php echo $id ?>" data-id="<?php echo $id ?>"  href="#menu-item-settings-<?php echo $id ?>"><span class="caret"></span></a>
                            </div>
                            <div class="menu-item-settings" id="menu-item-settings-<?php echo $id ?>" style="display: none;">
                                <p class="description description-thin">
                                    <label for="edit-menu-item-title-<?php echo $id ?>">
                                        <input type="text" id="edit-menu-item-title-<?php echo $id ?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php echo $id ?>]" value="<?php echo $menu['title']; ?>">
                                    </label>
                                </p>
                                <p class="description description-thin menu-item-actions">
                                    Original: <?php echo $defaultMenuItems[$menu['slug']]['title']; ?>
                                    <a class="item-delete submitdelete deletion" id="delete-<?php echo $id ?>" data-id="<?php echo $id ?>" href="#delete-menu-item-<?php echo $id ?>">Remove</a>
                                </p>
                            </div>
                            <?php if ($element2['parent'] == $id):
                                $level++; ?>
                                <ol class="dd-list">
                            <?php elseif ($element2['parent'] == $element1['parent']): ?>
                                </li>
                            <?php else:
                                    $level2 = $element2['level'];
                                    //$key = array_search($element2['parent_vocab_number'], $levelsarray);
                                    echo str_repeat("</li></ol>", $level - $level2);

                                    /* if($key !== false){
                                      echo str_repeat("</li></ol>", $level-$key-1);
                                      $level = $key+1;
                                      } */
                            endif ?>
                        <?php endforeach;
                    echo str_repeat("</li></ol>", $level);
                    echo "</li>"; ?>
                </ol>
            </div>

            <div style="clear:both;"></div>

            <input type="hidden" name="order" id="order" value="used"/>
            <input type="hidden" name="itemsorder" id="itemsorder" value=""/>

            <br/><br/>
            <button type="submit" name="save" class='btn  btn-primary'><i class="icon-ok icon-white"></i> <?php echo _t('SAVE') ?></button>
        </div>
    </form>
</div>

<script>
    var id = <?php echo $maxid ?>;


    var oeNavMenu;

    (function($) {

        var api = oeNavMenu = {



            nestable : undefined,

            // Functions that run on init.
            init : function() {
                this.refresh();


            },
            refresh:function(){


                this.nestable = $('#nestable').nestable({
                    maxDepth :<?php echo $maxDepth?>,
                    group:1
                });
                this.nestable.on('change', this.updateHidden);

                this.updateHidden($('#nestable').data('output', $('#itemsorder')));
                this.attachMenuEditListeners();

            },updateHidden : function(e)
            {
                if (window.JSON) {
                    $('#itemsorder').val(window.JSON.stringify($('#nestable').nestable('serialize')));//, null, 2));
                }
            },
            attachMenuEditListeners : function() {
                $('.item-edit').unbind('click');
                $('.item-edit').bind('click', function(e) {
                    if ( e.target) {
                        return oeNavMenu.eventOnClickEditLink(this);
                    }
                });
                $('.addmenulink').unbind('click');
                $('.addmenulink').bind('click',function(e){
                    $(this).toggleClass('active');

                    var slug = $(this).data('slug');
                    var title = $(this).data('title');
                    id = id+1;

                    var item =  '<li class="dd-item" data-id="'+id+'" id="dd-item-'+id+'"  data-slug="'+slug+'">';
                    item += '<div class="dd-handle nestableorderbg menu-item-edit-inactive" id="titlecontainer-'+id+'">'+title+'</div>';
                    item += '<div><a class="item-edit" id="edit-'+id+'" data-id="'+id+'"  href="#menu-item-settings-'+id+'"><span class="caret"></span></a></div>';

                    item += '<div class="menu-item-settings" id="menu-item-settings-'+id+'" style="display: none;">';
                    item += '<p class="description description-thin">'
                    item += '<label for="edit-menu-item-title-'+id+'">'
                    item += ' <input type="text" id="edit-menu-item-title-'+id+'" class="widefat edit-menu-item-title" name="menu-item-title['+id+']" value="'+title+'">'
                    item += '</label>'
                    item += '</p>'
                    item += '<p class="description description-thin menu-item-actions">'
                    item += '    Original: '+title
                    item += '<a class="item-delete submitdelete deletion" id="delete-'+id+'" data-id="'+id+'" href="#delete-menu-item-'+id+'" > Remove</a>'
                    item += '</p>'
                    item += '</div></li>'
                    $("#nestable-ddlist").append(item);

                    oeNavMenu.refresh();

                    that = this
                    var i=setInterval(function () {
                        hideSpinner(that);
                    },300);
                    function hideSpinner(that)
                    {
                        for (var j = 0; j <= i; j++){
                            window.clearInterval(j);
                        }
                        $(that).toggleClass('active');

                    }

                    return false;

                });

                $('.item-delete').unbind('click');
                $(".item-delete").bind('click',function(e){

                    var id = $(this).data('id');
                    $('#dd-item-'+id).remove();
                    oeNavMenu.refresh();
                });
            },
            eventOnClickEditLink : function(clickedEl) {
                var settings, item,
                matchedSection = /#(.*)$/.exec(clickedEl.href);
                id = $(clickedEl).data('id');
                if ( matchedSection && matchedSection[1] ) {
                    settings = $('#menu-item-settings-'+id);
                    var id = matchedSection[1].substring(19);//
                    item = $('#titlecontainer-'+id);;//settings.parent();
                    if( 0 != item.length ) {
                        if( item.hasClass('menu-item-edit-inactive') ) {
                            /*if( ! settings.data('menu-item-data') ) {
                                                   settings.data( 'menu-item-data', settings.getItemData() );
                                           }*/
                            settings.slideDown('fast');
                            item.removeClass('menu-item-edit-inactive')
                            .addClass('menu-item-edit-active');
                        } else {
                            settings.slideUp('fast');
                            item.removeClass('menu-item-edit-active')
                            .addClass('menu-item-edit-inactive');
                        }
                        return false;
                    }
                }
            }


        };

        $(document).ready(function(){ oeNavMenu.init(); });

    })(jQuery);

</script>