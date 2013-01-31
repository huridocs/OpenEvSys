<?php
$module = get_module();
$action = get_action();
if(in_array($module,array("events","person","docu"))){
?>
<div class="navbar navtop"  >
    <div class="navbar-inner">
        <ul class="nav"> 
           <?php

if ($module == "events") {
           ?>
            <li class="<?php if ($action == "browse") echo "active" ?>" ><a href="<?php get_url('events', 'browse') ?>"><?php echo _t('BROWSE_EVENTS') ?></a>
            </li>

            <li class="<?php if ($action != "browse") echo "active" ?>" ><a href="<?php get_url('events', 'get_event') ?>"><?php echo _t('VIEW_EVENT') ?></a>
            </li>
<?php
} elseif ($module == "person") {
   ?> 
             <li class="<?php if ($action == "browse") echo "active" ?>" ><a href="<?php get_url('person', 'browse') ?>"><?php echo _t('BROWSE_PERSONS') ?></a>
            </li>

            <li class="<?php if ($action != "browse") echo "active" ?>" ><a href="<?php get_url('person', 'person') ?>"><?php echo _t('VIEW_PERSON') ?></a>
            </li>

  <?php  
} elseif ($module == "docu") {
   ?> 
             <li <?php if ($action == "browse") echo "class='active'" ?> ><a href="<?php get_url('docu', 'browse', null, null) ?>"><?php echo _t('BROWSE_DOCUMENTS') ?></a>
            </li>

            <li class="<?php if ($action != "browse") echo "active" ?>"><a  href="<?php get_url('docu', 'view_document') ?>"><?php echo _t('VIEW_DOCUMENT') ?></a>

            </li>

  <?php  
} elseif ($module == "analysis") {
   ?> 
  <?php  
} elseif ($module == "admin") {
   ?> 
  <?php  
}elseif ($module == "home") {
   ?> 
  <?php  
}
?>

        </ul>
    </div>
</div>
<?php
}
?>