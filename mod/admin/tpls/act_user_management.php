<?php global $conf; ?>
<h2><?php echo _t('USERS')?></h2>
<br />

<?php $user_pager->render_pages(); ?>

<form class="form-horizontal"  action='<?php echo get_url('admin','delete_user')?>' method='post'>
<table class='table table-bordered table-striped table-hover'>
    <thead>
        <tr>
            <th width='16px'><input type='checkbox' onchange='$("input.delete").attr("checked",this.checked)' /></th>        
            <th><?php echo(_t('USER_NAME')); ?></th>
<!--            <th><?php echo(_t('FIRST_NAME')); ?></th> -->
<!--            <th><?php echo(_t('LAST_NAME')); ?></th> -->
<!--            <th><?php echo(_t('ORGANIZATION')); ?></th> -->
<!--            <th><?php echo(_t('DESIGNATION')); ?></th> -->
            <th><?php echo(_t('EMAIL')); ?></th>
<!--            <th><?php echo(_t('ADDRESS')); ?></th> --> 
            <th><?php echo(_t('ROLE')); ?></th>
            <th><?php echo(_t('STATUS')); ?></th>
            <th><?php echo(_t('ACTION')); ?></th>
            
            
        </tr>
    </thead>
    <tbody>
    <?php foreach($users as $user){ ?>
        <tr <?php echo ($i++%2==1)?'class="odd"':''; ?>>
            <?php $disable = ($user['username']=='admin'||$user['username']==$_SESSION['username']||( key(acl_get_user_roles($user['username'])) == 'admin' && $_SESSION['username']!='admin' ) )?' disabled="disabled" ': "class='delete'" ?>
            <td><input name="users[]" type='checkbox' value='<?php echo $user['username'] ?>' <?php echo $disable ?>/></td>            
            <td><strong><a href="<?php get_url('admin','edit_user',null, array('uid'=>$user['username'] ) );?> " ><?php echo $user['username']; ?></a></strong></td>
<!--            <td> <?php  echo $user['first_name'];  ?>  </td>-->
<!--            <td> <?php  echo $user['last_name'];  ?>  </td>-->
<!--            <td> <?php  echo $user['organization'];  ?>  </td>-->
<!--            <td> <?php  echo $user['designation'];  ?>  </td>-->
            <td> <?php  echo $user['email'];  ?>  </td>
<!--            <td> <?php  echo $user['address'];  ?>  </td>-->
            <td> <?php  array_to_list(acl_get_user_roles( $user['username'] )) ?>  </td>
            <td> <?php  echo $user['status'];  ?>  </td>
            <td>      <a href="<?php get_url('admin','edit_user',null, array('uid'=>$user['username'] ) );?> " ><?php echo _t('EDIT') ?></a>
            </td>
            
            
        </tr>

    <?php } ?>
            <tr class='actions'>
            <td colspan='11'><button type='submit' class='btn btn-danger' name='delete' >
<i class="icon-trash icon-white"></i> <?php echo _t('DELETE') ?></button>
            </td>
        </tr>
    </tbody>
</table>
<?php $user_pager->render_pages(); ?>
<br />
</form>



