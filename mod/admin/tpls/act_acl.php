<?php global $conf; ?>
<h2><?php echo _t('ROLES__AMP__MODULE_ACCESS_CONTROL')?></h2>
<br />
<form action="<?php get_url('admin','acl'); ?>" method="post">
<center>
<label><?php echo _t('NEW_ROLE_NAME') ?></label>
<input type="text" name="role_name" /> 
<input type="submit" value="<?php echo _t('ADD_ROLE') ?>" name='add_role' />
</center>
<table class='view'>
    <thead>
        <tr>
            <td><?php echo '';?></td>
            <td colspan='<?php echo count($roles); ?>' align='center'><?php echo _t('ROLES') ?></td>
        </tr>
        <tr>
            <td align='center'><?php echo _('MODULES');?></td>
            <?php foreach($roles as $role){ ?>
            <td align='center'><?php echo $role;?></td>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
<?php   foreach($modules as $module => $nice_name){ ?>
        <tr>
            <td><?php echo $nice_name ?></td>
            <?php foreach($roles as $id=>$role){ ?>
            <?php $disable = ($role=='Admin')?' disabled="disabled" ':'' ?>
            <td align='center'><input type='checkbox' name='<?php echo $module.'_'.$id; ?>'  <?php echo $disable; echo $selected[$module.'_'.$id] ?>  /></td>
            <?php } ?>
        </tr>
<?php   } ?>
    </tbody>
</table>
<center>
<input type="submit" value="<?php echo _t('UPDATE_ACCESS_CONTROL_LIST') ?>" name='submit' />
</center>
</form>
