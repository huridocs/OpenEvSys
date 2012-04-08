<?php global $conf; ?>
<h2><?php echo _t('MICRO_THESAURI_CUSTOMIZATION')?></h2>
<div class="form-container"> 
<form action="<?php  get_url('admin','mt_customization')?>" method='get'>
<fieldset style="margin:10px;">
	<input type="hidden" name="mod" value="admin" />
    <input type="hidden" name="act" value="mt_customization" />
<?php $fields = shn_form_get_html_fields($customization_form,false);  ?>
        <?php echo $fields['mt_select'] ?>
        <?php echo $fields['change_mt'] ?>
</fieldset>
</form>
</div>

<?php if(isset($mt_cust)){ ?>
<!-- <h3><?php echo  _t('MICRO_THESAURI_TERMS'); ?></h3> -->
<?php if($tree){ 
    $parent_level = rtrim($_GET['parent'],'0');
    if(strlen($parent_level)%2 == 1)
        $parent_level = $parent_level . '0';
    $parent_level = substr($parent_level,0,-2);
if($_GET['parent']!=''){
?>
<br />
 &nbsp;&nbsp;&nbsp;&nbsp;<strong><a href="<?php get_url('admin','mt_customization',null, array('mt_select'=> $_GET['mt_select'] ,'request_page'=> $_GET['request_page'],'parent'=>$parent_level ) ) ?>" ><?php echo _t('GO_TO_PARENT_LEVEL') ?></a></strong>
<?php }
 } ?>
<div id="browse">
<form action='<?php get_url('admin','mt_customization',null, 
								array('mt_select'=> $_GET['mt_select'] ,'request_page'=> $_GET['request_page'],'parent'=>$_GET['parent']  ) ) ?>' method='post'>
<?php ?>
<?php if($delete){ ?>
     <div class='dialog confirm'>
     	<h3><?php echo _t('DO_YOU_WANT_TO_DELETE_THE_SELECTED_MICRO_THESAURUS_TERM_S___')?></h3>
        <?php if($has_children){ ?>
     	<span class="red"><?php echo _t("YOU_HAVE_ALSO_SELECTED_SOME_PARENT_TERM_S___IF_YOU_PROCEED_THE_CHILDRENS_OF_THOSE_TERMS_WILL_ALSO_BE_DELETED_") ?></span>
        <?php } ?>
     	<br />
        <center>
        <input type='submit' name='delete_yes' value='<? echo _t('DELETE') ?>' />
        <input type='submit' name='no' value='<? echo _t('CANCEL') ?>' />
        </center>
    </div>       
<?php } ?>

<?php $l = (isset($locale))? 1 : 0;?>
<?php $mt_cust_pager->render_pages(); ?>
<table class='browse' id='mt_cus'>
    <thead>
    	<tr>
    		<td colspan='<?php echo 4+$l ?>'>
                <input type='submit' name='filter' value='<?php echo _t('FILTER')?>' />
                <span>&nbsp;</span>
                <input type='submit' name='reset' value='<?php echo _t('RESET')?>' /> 
            </td>
    	</tr>
        <tr class="filter">
        	<td style="background:#EEEAD4"><?php echo _t('VISIBLE')?></td>
        	<td style="background:#EEEAD4"><?php echo _t('DELETE')?></td>
        	<td><div class='field'> <input type='text' name='huricode_filter' value='<?php echo $_POST['huricode_filter'] ?>' size='15'/> </div> </td>
        	<td><div class='field'> <input type='text' name='mt_term_filter' value='<?php echo $_POST['mt_term_filter'] ?>' size='40' /> </div> </td>        
        </tr>
        <tr>
        	<td width='16px'><input type='checkbox' onchange='$("input.visible").attr("checked",this.checked)' /></td>
			<td width='16px'><input type='checkbox' onchange='$("input.delete").attr("checked",this.checked)' /></td>
            <td width="150px"><?php echo _t('HURICODE'); ?></td>
            <td ><?php echo _t('TERM_IN')." ".$conf['fb_locale']; ?></td>
<?php 
            if(isset($locale)){
?>
            <td width="40%"><?php echo _t('TERM_IN_')." ".$locale; ?></td>
<?php
            }
?>
        </tr>
    </thead>
    <tbody>	
    
    <?php foreach($mt_cust as $record){ ?>
        <tr <?php echo ($i++%2==1)?'class="odd"':''; ?>>
        	<td class='no_edit'>
                <input name="visible_vocab_number_list[]" type='checkbox' value='<?php echo $record['vocab_number'] ?>' class='visible' 
                <?php 
                if(is_array($_POST['visible_vocab_number_list']) &&in_array($record['vocab_number'],$_POST['visible_vocab_number_list'])) $checkedVisible = true; else $checkedVisible = false;
                if( !isset($_POST['visible_vocab_number_list']))
                if($record['visible'] == 'y' || $record['visible'] == '1')  $checkedVisible =true; else $checkedVisible = false;  
                
                if ($checkedVisible == true)
                echo "checked='checked'"; ?> />
                </td>
                        	
            <td class='no_edit'>
                <input name="vocab_number_list[]" type='checkbox' value='<?php echo $record['vocab_number'] ?>' class='delete' 
                <?php if(is_array($_POST['vocab_number_list']) &&in_array($record['vocab_number'],$_POST['vocab_number_list']))echo "checked='checked'"; ?> />
                </td>
			<td class='no_edit'>
            <?php if($tree){
                    ?><a href="<?php get_url('admin','mt_customization',null, array('mt_select'=> $_GET['mt_select'] ,'request_page'=> $_GET['request_page'],'parent'=>$record['huri_code'] ) ) ?>" ><?php 
                    echo $record['huri_code'] ;
                    ?></a><?php
                  }else
                    echo $record['huri_code'];
            ?>
            </td>
            <td id="<?php echo $record['vocab_number']?>" class='<?php echo $conf['fb_locale'] ?>' ><?php echo $record['mt_term']?></td>
<?php
            if(isset($locale)){
?>
            <td id="<?php echo $record['vocab_number']?>" class='<?php echo $conf['locale'] ?>'><?php echo $record['mt_term_l10n'] ?></td>
<?php
            }
?>
		</tr>
    <?php } ?>
		<tr class='actions' style='background:#EEEAD4'>
		
            <td class='no_edit'>
            	<input type='submit' name='visible' value='<?php echo _t('SET') ?>' style="margin:0;"/>            
            </td>
            
            <td colspan='2' class='no_edit'>
                <input type='submit' name='delete' value='<?php echo _t('DELETE') ?>' />
                <input type='button' name='' value='<?php echo _t('ADD_NEW') ?>' onclick="add_new_mt(this);$('#save_new_terms').show()" />
                
            </td>
            <td class='no_edit'>
            	<input type='submit' name='save_new_terms' id='save_new_terms' value='<?php echo _t('SAVE_NEW_FIELDS') ?>' style="display:none" />
            </td>
<?php
            if(isset($locale)){
?>
            <td class='no_edit'> </td>
<?php
            }
?>
        </tr>        
    </tbody>
</table>
<script type="text/javascript" src="res/jquery/jquery.uitableedit.js"></script>
<script language='javascript'>
    var t = $('#mt_cus');
    $.uiTableEdit( t , {'dataVerify':update_mt} );
    
    var mt_row_class ='odd';
    function add_new_mt(button)
    {
        var style = "style='width:100%;border:1px solid #777'"
        $('#mt_cus > tbody').append(
            "<tr class='"+mt_row_class+"'><td></td><td></td>"+
            "<td><input type='text' name='new_huricode[]' "+style+"  /></td>"+
            "<td><input type='text' name='new_term[]' "+style+" /></td>"+
<?php
            if(isset($locale)){
?>
            "<td><input type='text' name='new_term_l10n[]'  "+style+" /></td>"+
<?php
            }
?>
            "</tr>"
        );
        if(mt_row_class == 'odd')mt_row_class='';else mt_row_class='odd';
    }
</script>
<?php } ?>
<br />
</form>
</div>
