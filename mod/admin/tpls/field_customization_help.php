<?php global $conf; ?>
<?php
if($field_help_text){
    echo "<h3>{$field_help_text['field_number']} : {$field_help_text['field_label']}</h3>";
?>
<div id="browse">
<input type='hidden' name='field_number' value='<?php echo $field_help_text['field_number'] ?>' />
<table class='table table-bordered table-striped table-hover'>
    <thead>
        <tr>
            <th><?php echo(_t('SECTION')); ?></th>
            <th><?php echo $conf['fb_locale'] ?></th>
            <?php if(isset($locale)){?><th><?php echo $locale?></th><?php } ?>
        </tr>
    </thead>
    <tbody>
        <tr <?php echo ($i++%2==1)?'class="odd"':''; ?> >
            <td><?php echo(_t('DEFINITION')); ?></td>
            <td>
                <textarea  name='definition'><?php echo $field_help_text['definition']; ?></textarea>
            </td>
            <?php if(isset($locale)){?>
            <td><?php echo $record['l10n_definition']?>
                <textarea  name='l10n_definition'><?php echo $field_help_text['l10n_definition']; ?></textarea>
            </td>
            <?php } ?>
        </tr>
        <tr <?php echo ($i++%2==1)?'class="odd"':''; ?>>
            <td><?php echo(_t('GUIDELINES')); ?></td>
            <td>
                <textarea  name='guidelines'><?php echo $field_help_text['guidelines']; ?></textarea>
            </td>
            <?php if(isset($locale)){?>
            <td><?php echo $record['l10n_definition']?>
                <textarea  name='l10n_guidelines'><?php echo $field_help_text['l10n_guidelines']; ?></textarea>
            </td>
            <?php } ?>
        </tr>
        <tr <?php echo ($i++%2==1)?'class="odd"':''; ?>>
            <td><?php echo(_t('ENTRY')); ?></td>
            <td>
                <textarea  name='entry'><?php echo $field_help_text['entry']; ?></textarea>
            </td>
            <?php if(isset($locale)){?>
            <td><?php echo $record['l10n_definition']?>
                <textarea  name='l10n_entry'><?php echo $field_help_text['l10n_entry']; ?></textarea>
            </td>
            <?php } ?>
        </tr>
        <tr <?php echo ($i++%2==1)?'class="odd"':''; ?>>
            <td><?php echo(_t('EXAMPLE')); ?></td>
            <td>
                <textarea  name='examples'><?php echo $field_help_text['examples']; ?></textarea>
            </td>
            <?php if(isset($locale)){?>
            <td><?php echo $record['l10n_definition']?>
                <textarea  name='l10n_examples'><?php echo $field_help_text['l10n_examples']; ?></textarea>
            </td>
            <?php } ?>
        </tr>
    </tbody>
</table>
<br />
    <center>
    <button type='submit' class='btn' ><i class="icon-remove-circle"></i> <?php echo _t('CANCEL') ?></button>
    <button type='submit' class='btn btn-primary' name='save_help' ><i class="icon-ok icon-white"></i> <?php echo _t('SAVE_HELP_TEXT') ?></button>
    </center>
</div>
<?php
}
else{
?>
<div id="browse">
<?php $help_text_pager->render_pages(); ?>
<table class='table table-bordered table-striped table-hover'>
    <thead>
        <tr>
            <th><?php echo(_t('FIELD_LABEL')); ?></th>
            <th><?php echo _t('SECTION') ?></th>
            <th><?php echo $conf['fb_locale'] ?></th>
            <?php if(isset($locale)){?><td><?php echo $locale?></td><?php } ?>
        </tr>
    </thead>
    <tbody>
    <?php 
        $res = $help_text_pager->get_page_data();
        foreach($res as $record){  
?>
            <tr <?php echo ($i++%2==1)?'class="odd"':''; ?> >
                <td rowspan='4'>
                    <a href="<?php get_url('admin','field_customization',null,array('entity_select'=>$entity_select,'sub_act'=>'help','fno'=>$record['field_number'])) ?>"><?php echo $record['field_label']; ?></a></td>
                <td><?php echo(_t('DEFINITION')); ?></td>
                <td><?php echo $record['definition']; ?></td>
                <?php if(isset($locale)){?><td><?php echo $record['l10n_definition']?></td><?php } ?>
            </tr>
            <tr <?php echo ($i++%2==1)?'class="odd"':''; ?>>
                <td><?php echo(_t('GUIDELINES')); ?></td>
                <td><?php echo $record['guidelines']; ?></td>
                <?php if(isset($locale)){?><td><?php echo $record['l10n_guidelines']?></td><?php } ?>
            </tr>
            <tr <?php echo ($i++%2==1)?'class="odd"':''; ?>>
                <td><?php echo(_t('ENTRY')); ?></td>
                <td><?php echo $record['entry']; ?></td>
                <?php if(isset($locale)){?><td><?php echo $record['l10n_entry']?></td><?php } ?>
            </tr>
            <tr <?php echo ($i++%2==1)?'class="odd"':''; ?>>
                <td><?php echo(_t('EXAMPLE')); ?></td>
                <td><?php echo $record['examples']; ?></td>
                <?php if(isset($locale)){?><td><?php echo $record['l10n_examples']?></td><?php } ?>
            </tr>
<?php   } ?>
    </tbody>
</table>
<?php $help_text_pager->render_pages(); ?>
</div>
<?php 
} 
?>
