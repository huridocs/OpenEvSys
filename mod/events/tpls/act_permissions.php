<?php 
include_once('tabs.php');
include_once('event_title.php');
include_once('card_list.php');
draw_card_list('pe',$event_id);
?>
<div class="panel permission_panel">
    <h3><?php echo _t('ROLES_WITH_PERMISSION_TO_ACCESS_THIS_EVENT') ?></h3>
    <br />
    <div class="form-container">
    <form action='<?php echo get_url('events','permissions')?>' method='post' enctype='multipart/form-data'>
    <div class="field">
    <label for="<?php echo $name?>"></label>
    <ul class='role_list controler_list'>
<?php 
    foreach($roles as $role=>$item_name){
        $disable = ($role=='admin')?' disabled="disabled" ':'';
        $checked = (is_array($value)&&array_key_exists($role,$value))?"checked='checked'":'';
?>
     <li><input type="checkbox" name="roles[]" value="<?php echo $role?>" <?php echo $checked.$disable ?> /><span><?php echo $item_name ?></span></li>
	<?php
    }
?>
    </ul>
    <input type="submit" name='update' value="<?php echo _t('UPDATE') ?>" />
    </div>
    <div style='clear:both' />
    <br />
    <br />
    <h3><?php echo _t('USERS_WITH_PERMISSION_TO_ACCESS_THIS_EVENT') ?></h3>
    <br />
    <input type="text" value='' name='add_user' />
    <input type="submit" value="<?php echo _t('SHARE') ?>" />
    <span>&nbsp;<?php echo _t('TYPE_THE_USERID_IN_THE_TEXT_BOX_AND_CLICK_SHARE_TO_MAKE_THIS_EVENT_ACCESSIBLE_TO_A_DESIRED_USER_') ?></span>
    <br />
    <br />
    <table class="browse">
    <thead>
        <tr><td class="title" colspan="4" ><?php echo _t('USERS_WITH_PERMISSIONS') ?></td></tr>
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
    <!-- <a class="but" href="<?php echo get_url('events','get_event') ?>"><?php echo _t('CANCEL')?></a> -->
    </form>
    </div>
</div>
<script language="javascript">
    function remove_user_permission(userid){
        $('form').append("<input type='hidden' name='remove_user' value='"+userid+"' />").submit();
    }
</script>
