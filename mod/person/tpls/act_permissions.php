<?php  global $conf ?> 
<?php include_once('person_name.php')?>
<?php	
	include_once 'view_card_list.php';
	draw_card_list('pe',$pid);	
?>
<div class="panel permission_panel">
    <h3><?php echo _t('ROLES_WITH_PERMISSION_TO_ACCESS_THIS_PERSON') ?></h3>
    <br />
    <div class="form-container">
    <form class="form-horizontal"  action='<?php echo get_url('person','permissions')?>' method='post' enctype='multipart/form-data'>
    <div class="field">
         <fieldset>
    <label for="<?php echo $name?>"></label>
    
<?php 
    foreach($roles as $role=>$item_name){
        $disable = ($role=='admin')?' disabled="disabled" ':'';
        $checked = (is_array($value)&&array_key_exists($role,$value))?"checked='checked'":'';
?>
        <label class="checkbox">
            <input type="checkbox" name="roles[]" value="<?php echo $role?>" <?php echo $checked.$disable ?> /><span><?php echo $item_name ?></span>
        </label>
	<?php
    }
?>
  </fieldset>
    <button class="btn btn-primary" name='update' type="submit"  ><i class="icon-ok icon-white"></i> <?php echo _t('SAVE') ?></button>
      </div>
    <div style='clear:both' />
    <br />
    <br />
    <h3><?php echo _t('USERS_WITH_PERMISSION_TO_ACCESS_THIS_PERSON') ?></h3>
    <br />
    <input type="text" value='' name='add_user' />
    <button type="submit" class="btn"  ><i class="icon-share"></i> <?php echo _t('SHARE') ?></button>
    <span>&nbsp;<?php echo _t('TYPE_THE_USERID_IN_THE_TEXT_BOX_AND_CLICK_SHARE_TO_MAKE_THIS_PERSON_ACCESSIBLE_TO_A_DESIRED_USER_') ?></span>
    <br />
    <br />
    <table class="table table-bordered table-striped table-hover">
    <thead>
        <tr><th class="title" colspan="4" ><?php echo _t('USERS_WITH_PERMISSIONS') ?></th></tr>
    </thead>
    <tbody>
    <?php for($j = 0 ; $j< count($users);$j=$j+4){?>
            <tr  class='<?php if($i++%2==1) echo "odd ";?>' >
                <?php for($k=0;$k<4;$k++){ ?>
                    <td width="25%" class="user_cell">
                        <?php echo $users[$j+$k] ?> 
                        <?php if(isset($users[$j+$k])){?><a class="remove_permission" href="#" onclick="remove_user_permission('<?php echo $users[$j+$k] ?>')">x</a><?php } ?>
                    </td>
                <?php }?>
            </tr>
    <?php }?>
    </tbody>
    </table>
    </div>
    <br />
    <!-- <a class="btn" href="<?php echo get_url('person','get_event') ?>"><i class="icon-remove-circle"></i> <?php echo _t('CANCEL')?></a> -->
    </form>
    </div>
</div>
<script language="javascript">
    function remove_user_permission(userid){
        $('form').append("<input type='hidden' name='remove_user' value='"+userid+"' />").submit();
    }
</script>
