<div class="form-container">
    <form class="form-horizontal"  action='<?php echo get_url('admin', 'permissions') ?>' method='post'  style="margin:0px">

        <div class='control-group'> <?php
shn_form_select(_t('SELECT_ROLE'), 'role', array('options' => $roles));
?></div>
        <div class="control-group">
            <div class="controls"><button type="submit"  name="change_role"  class="btn" ><?php echo _t('SELECT') ?></button>
            </div></div>

    </form>
</div>
<h2><?php echo _t('PERMISSIONS_OF_') . " : <span class='red'>$role</span>" ?></h2>
<form class="form-horizontal"  action='<?php echo get_url('admin', 'permissions') ?>' method='post'  style="margin:0px">
    <input type="hidden" value="<?php echo $role ?>" name='role' />
    <h3><?php echo _t('ENTITY_PERMISSIONS') ?></h3>
   
    <table class='table table-bordered table-striped table-hover'>
        <thead>
            <tr>
                <th>Entity Groups</th>
                <?php foreach ($crud as $opt) { ?>
                    <th><?php echo $opt ?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $disable = ($role == 'admin') ? ' disabled="disabled" ' : '';
            foreach ($entity_groups as $key => $group) {
                echo "<tr>";
                echo "<td>$group</td>";
                foreach ($crud as $opt) {
                    $check = ($select[$key . "_" . $opt]) ? 'checked="true"' : '';
                    echo "<td><input type='checkbox' name='{$key}_{$opt}' value='$module' $check  $disable /></td>";
                }
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    <br />
    <?php if ($role != 'admin') { ?>

        <button type="submit" class="btn" name="change_permissions" ><i class="icon-ok"></i> <?php echo _t('UPDATE_PERMISSIONS') ?></button>

    <?php } ?>
</form>
