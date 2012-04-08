<?php global $conf; ?>
<h2><?php echo _t('FORM_CUSTOMIZATION')?></h2>
<div class="form-container">
<form action='<?php echo get_url('admin','field_customization')?>' method='get'>
<input type='hidden' name='mod' value='admin' />
<input type='hidden' name='act' value='field_customization' />
<fieldset style="margin:10px"> 
<?php $fields = shn_form_get_html_fields($customization_form,false);  ?>
        <?php echo $fields['entity_select'] ?>
        <?php echo $fields['change_entity'] ?>
</fieldset>
</form>
</div>

<?php if(isset($entity_select)){ ?>
<h3><?php echo _t('CHANGE')." [ {$entity_select} ] "._t('FORM'); ?></h3>
<?php
function print_tab_attr($act)
{
    global $entity_select;
?>
    href="<?php get_url('admin','field_customization',null,array('sub_act'=>$act,'entity_select'=>$_REQUEST['entity_select']))?>";
<?php
    if($act==$_REQUEST['sub_act'])echo " class='active'";
}

?>
<br />
<div class="card_list">
<a <?php print_tab_attr('label'); ?> ><?php echo _t('LABELS') ?></a>
<a <?php print_tab_attr('visibility'); ?> ><?php echo _t('VISIBILITY') ?></a>
<a <?php print_tab_attr('validation'); ?> ><?php echo _t('VALIDATION') ?></a>
<a <?php print_tab_attr('search'); ?> ><?php echo _t('SEARCH') ?></a>
<a <?php print_tab_attr('order'); ?> ><?php echo _t('ORDER') ?></a>
<a <?php print_tab_attr('cnotes'); ?> ><?php echo _t('CLARIFYING_NOTES') ?></a>
<a <?php print_tab_attr('help'); ?> ><?php echo _t('HELP_TEXT') ?></a>
</div>
<div class="panel">
<form action='<?php echo get_url('admin','field_customization',null,array('sub_act'=>$_REQUEST['sub_act'],'entity_select'=>$entity_select))?>' method='post'>
<?php
    if(isset($entity_form))
        $fields1 = shn_form_get_html_fields($entity_form);
    $file = 'field_customization_'.$sub_act.'.php';
    if(file_exists(APPROOT.'mod/admin/tpls/'.$file))
        include_once($file);
    else 
        throw new shn404Exception();
?>
<center>
    <?php echo $fields1['update'] ?>
    <?php echo $fields1['reset'] ?>
</center>
<?php echo $fields1['entity_select'] ?>    
</form>
</div>
<?php } ?>
