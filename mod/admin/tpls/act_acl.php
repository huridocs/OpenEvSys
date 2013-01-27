<?php global $conf; ?>
<h2><?php echo _t('ROLES__AMP__MODULE_ACCESS_CONTROL')?></h2>
<br />
<form class="form-horizontal"  action="<?php get_url('admin','acl'); ?>" method="post">
<center>
<label><?php echo _t('NEW_ROLE_NAME') ?></label>
<input type="text" name="role_name" /> 
<button  type="submit" class="btn btn-primary"  name='add_role' ><i class="icon-plus icon-white"></i><?php echo _t('ADD_ROLE') ?></button>
</center>
<table class='table table-bordered table-striped table-hover'>
    <thead>
        <tr>
            <th><?php echo '';?></th>
            <th colspan='<?php echo count($roles); ?>' align='center'><?php echo _t('ROLES') ?></th>
        </tr>
        <tr>
            <th align='center'><?php echo _('MODULES');?></th>
            <?php foreach($roles as $role){ ?>
            <th align='center'><?php echo $role;?></th>
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
<button type="submit" class="btn"  name='submit' ><i class="icon-ok"></i> <?php echo _t('UPDATE_ACCESS_CONTROL_LIST') ?></button>
</center>
</form>
