
<br />
<?php if($del_confirm){ ?>
    <div class='dialog confirm'>
    <h3><?php echo _t('DO_YOU_WANT_TO_DELETE_THE_SELECTED_USER_S___')?></h3>
    <form action="<?php get_url('admin','delete_user')?>" method="post">
        <br />
        <center>
        <input type='submit' name='yes' value='<? echo _t('YES') ?>' />
        <input type='submit' name='no' value='<? echo _t('NO') ?>' />
        </center>
        <?php foreach($_POST['users'] as $val){ ?>
        <input type='hidden' name='users[]' value='<?php echo $val ?>' />
        <?php } ?>
    </form>
    </div>
<?php } ?>
    <div id="browse">
    <table class='browse'>
        <thead>
            <tr>
            <td><?php echo(_t('USERNAME')); ?></td>
            <td><?php echo(_t('FIRST_NAME')); ?></td>
            <td><?php echo(_t('LAST_NAME')); ?></td>
            <td><?php echo(_t('ORGANIZATION')); ?></td>
            <td><?php echo(_t('DESIGNATION')); ?></td>
            <td><?php echo(_t('EMAIL')); ?></td>
            <td><?php echo(_t('ADDRESS')); ?></td>
            <td><?php echo(_t('ROLE')); ?></td>
            <td><?php echo(_t('STATUS')); ?></td>
            <td><?php echo(_t('ACTION')); ?></td>
            </tr>
        </thead>
        <tbody>
    <?php $i=0;
           foreach($users as $user){ ?>
        <tr <?php echo ($i++%2==1)?'class="odd"':''; ?>>
            <td> <a href="<?php get_url('admin','edit_user',null, array('uid'=>$user['username'] ) );?> " ><?php echo $user['username']; ?></a>  </td>
            <td> <?php  echo $user['first_name'];  ?>  </td>
            <td> <?php  echo $user['last_name'];  ?>  </td>
            <td> <?php  echo $user['organization'];  ?>  </td>
            <td> <?php  echo $user['designation'];  ?>  </td>
            <td> <?php  echo $user['email'];  ?>  </td>
            <td> <?php  echo $user['address'];  ?>  </td>
            <td> <?php  echo $user['role'];  ?>  </td>
            <td> <?php  echo $user['status'];  ?>  </td>
            <td>      <a href="<?php get_url('admin','edit_user',null, array('uid'=>$user['username'] ) );?> " ><?php echo _t('EDIT') ?></a>
            </td>
            
            
        </tr>
    <?php } ?>
        </tbody>
    </table>
    </div>
<br />