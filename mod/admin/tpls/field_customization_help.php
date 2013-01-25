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
            <td width='100px'><?php echo(_t('SECTION')); ?></td>
            <td><?php echo $conf['fb_locale'] ?></td>
            <?php if(isset($locale)){?><td width='40%'><?php echo $locale?></td><?php } ?>
        </tr>
    </thead>
    <tbody>
        <tr <?php echo ($i++%2==1)?'class="odd"':''; ?> style="border-top:1px solid #C2C2A7">
            <td><?php echo(_t('DEFINITION')); ?></td>
            <td>
                <textarea style='width:100%;height:100px;border:none' name='definition'><?php echo $field_help_text['definition']; ?></textarea>
            </td>
            <?php if(isset($locale)){?>
            <td><?php echo $record['l10n_definition']?>
                <textarea style='width:100%;height:100px;border:none' name='l10n_definition'><?php echo $field_help_text['l10n_definition']; ?></textarea>
            </td>
            <?php } ?>
        </tr>
        <tr <?php echo ($i++%2==1)?'class="odd"':''; ?>>
            <td><?php echo(_t('GUIDELINES')); ?></td>
            <td>
                <textarea style='width:100%;height:100px;border:none' name='guidelines'><?php echo $field_help_text['guidelines']; ?></textarea>
            </td>
            <?php if(isset($locale)){?>
            <td><?php echo $record['l10n_definition']?>
                <textarea style='width:100%;height:100px;border:none' name='l10n_guidelines'><?php echo $field_help_text['l10n_guidelines']; ?></textarea>
            </td>
            <?php } ?>
        </tr>
        <tr <?php echo ($i++%2==1)?'class="odd"':''; ?>>
            <td><?php echo(_t('ENTRY')); ?></td>
            <td>
                <textarea  style='width:100%;height:100px;border:none'name='entry'><?php echo $field_help_text['entry']; ?></textarea>
            </td>
            <?php if(isset($locale)){?>
            <td><?php echo $record['l10n_definition']?>
                <textarea style='width:100%;height:100px;border:none' name='l10n_entry'><?php echo $field_help_text['l10n_entry']; ?></textarea>
            </td>
            <?php } ?>
        </tr>
        <tr <?php echo ($i++%2==1)?'class="odd"':''; ?>>
            <td><?php echo(_t('EXAMPLE')); ?></td>
            <td>
                <textarea style='width:100%;height:100px;border:none' name='examples'><?php echo $field_help_text['examples']; ?></textarea>
            </td>
            <?php if(isset($locale)){?>
            <td><?php echo $record['l10n_definition']?>
                <textarea style='width:100%;height:100px;border:none' name='l10n_examples'><?php echo $field_help_text['l10n_examples']; ?></textarea>
            </td>
            <?php } ?>
        </tr>
    </tbody>
</table>
<br />
    <center>
    <input type='submit' class='btn' value="<?php echo _t('SAVE_HELP_TEXT') ?>" name='save_help' />
    <input type='submit' class='btn' value="<?php echo _t('CANCEL') ?>" />
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
            <td width='100px'><?php echo(_t('FIELD_LABEL')); ?></td>
            <td><?php echo _t('SECTION') ?></td>
            <td><?php echo $conf['fb_locale'] ?></td>
            <?php if(isset($locale)){?><td width='40%'><?php echo $locale?></td><?php } ?>
        </tr>
    </thead>
    <tbody>
    <?php 
        $res = $help_text_pager->get_page_data();
        foreach($res as $record){  
?>
            <tr <?php echo ($i++%2==1)?'class="odd"':''; ?> style="border-top:1px solid #C2C2A7">
                <td rowspan='4'>
                    <a href="<?php get_url('admin','field_customization',null,array('entity_select'=>$entity_select,'sub_act'=>'help','fno'=>$record['field_number'])) ?>"><?php echo $record['field_label']; ?></a></td>
                <td><?php echo(_t('DEFINITION')); ?></td>
                <td  style='padding:5px;'><?php echo $record['definition']; ?></td>
                <?php if(isset($locale)){?><td><?php echo $record['l10n_definition']?></td><?php } ?>
            </tr>
            <tr <?php echo ($i++%2==1)?'class="odd"':''; ?>>
                <td><?php echo(_t('GUIDELINES')); ?></td>
                <td  style='padding:5px;'><?php echo $record['guidelines']; ?></td>
                <?php if(isset($locale)){?><td><?php echo $record['l10n_guidelines']?></td><?php } ?>
            </tr>
            <tr <?php echo ($i++%2==1)?'class="odd"':''; ?>>
                <td><?php echo(_t('ENTRY')); ?></td>
                <td  style='padding:5px;'><?php echo $record['entry']; ?></td>
                <?php if(isset($locale)){?><td><?php echo $record['l10n_entry']?></td><?php } ?>
            </tr>
            <tr <?php echo ($i++%2==1)?'class="odd"':''; ?>>
                <td><?php echo(_t('EXAMPLE')); ?></td>
                <td  style='padding:5px;'><?php echo $record['examples']; ?></td>
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
