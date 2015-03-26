<?php if (acl_vp_entity_add_is_allowed()) { ?>
    <a class="btn btn-primary" href="<?php echo get_url('events', 'add_victim', null, array('eid' => $event_id)) ?>"><i class="icon-plus icon-white"></i> <?php echo _t('ADD_VICTIM') ?></a>
    <a class="btn" href="#" id="add_act_btn" ><i class="icon-plus"></i> <?php echo _t('ADD_ACT') ?></a>
    <a class="btn" href="#" id="add_perp_btn" ><i class="icon-plus"></i> <?php echo _t('ADD_PERPETRATOR_S_') ?></a>
<?php } ?>
<div id="errordiv"  style="display:none" ><br/>
    <div class='alert alert-error'  > 
        <?php echo _t('Please select some checkbox to perform action.') ?>
    </div>
</div>  
<br/><br/>
<?php if (!isset($vp_list)) { ?>
    <div class='alert alert-info'> <button type="button" class="close" data-dismiss="alert">×</button> 
        <?php echo _t('THERE_IS_NO_INFORMATION_ABOUT_VICTIMS_AND_PERPETRATORS_YET__YOU_SHOULD_ADD_SOME') ?>
    </div>
<?php } else { ?>
    <form class="form-horizontal"  action="<?php get_url('events', 'delete_act') ?>" method="post" id="acts_form">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th width='16px'><input type='checkbox' onchange='$("input.delete_act").attr("checked",this.checked)' /></th>
                    <th><?php echo _t('INITIAL_DATE') ?></th>
                    <th><?php echo _t('VICTIM_NAME') ?></th>
                    <th><?php echo _t('TYPE_OF_ACT') ?></th>
                    <th width='16px'><input type='checkbox' onchange='$("input.delete_inv").attr("checked",this.checked)' /></th>
                    <th><?php echo _t('PERPETRATOR_NAME_S_') ?></th>
                    <th><?php echo _t('INVOLVEMENT') ?></th>
                    <th><?php echo _t('Duplicate') ?></th>
                </tr>
            </thead>
            <tbody>	
                <?php
                foreach ($vp_list as $record) {

                    $class = ($act->act_record_number == $record['act_record_number']) ? ' active ' : '';
                    ?>
                    <tr class='<?php echo $class ?>'>
                        <?php if ($skip != $record['act_record_number']) { ?>
                            <?php $odd = ($i++ % 2 == 1) ? "odd " : ''; ?>
                            <td rowspan=<?php echo ($record['inv_count'] == 0) ? 1 : $record['inv_count']; ?> class="<?php echo $odd . $class ?>">
                                <input name="acts[]" type='checkbox' value='<?php echo $record['act_record_number'] ?>' class='delete_act'/>
                            </td>
                            <td rowspan=<?php echo ($record['inv_count'] == 0) ? 1 : $record['inv_count']; ?> class="<?php echo $odd . $class ?>">
                                <?php echo $record['initial_date'] ?>
                            </td>
                            <td rowspan=<?php echo ($record['inv_count'] == 0) ? 1 : $record['inv_count']; ?> class="<?php echo $odd . $class ?>">
                                <a href="<?php get_url('events', 'vp_list', null, array('act_id' => $record['act_record_number'], 'type' => 'victim')) ?>"><?php echo $record['vname'] ?></a>
                            </td>
                            <td rowspan=<?php echo ($record['inv_count'] == 0) ? 1 : $record['inv_count']; ?> class="<?php echo $odd . $class ?>">
                                <a href="<?php get_url('events', 'vp_list', null, array('act_id' => $record['act_record_number'], 'type' => 'act')) ?>"><?php echo get_mt_term($record['type_of_act']) ?></a>
                            </td>
                            <?php
                            $skip = $record['act_record_number'];
                        }
                        ?>
                        <?php $class .= ($inv->involvement_record_number == $record['involvement_record_number'] && $record['involvement_record_number'] != '') ? ' sub_active' : ''; ?>
                        <td class="<?php echo $odd . $class ?>">
                            <?php if ($record['involvement_record_number'] != null) { ?>
                                <input name="invs[]" type='checkbox' value='<?php echo $record['involvement_record_number'] ?>' class='delete_inv'/>
                            <?php }else echo "&nbsp;" ?>
                        </td>
                        <td class="<?php echo $odd . $class ?>">
                            <a href="<?php get_url('events', 'vp_list', null, array('inv_id' => $record['involvement_record_number'], 'type' => 'perter')) ?>"><?php echo $record['pname'] ?></a>
                        </td>


                        <td class="<?php echo $odd . $class ?>">
                            <a href="<?php get_url('events', 'vp_list', null, array('inv_id' => $record['involvement_record_number'], 'type' => 'inv')) ?>"><?php echo get_mt_term($record['degree_of_involvement']) ?></a>
                        </td>
                        <td class="<?php echo $odd . $class ?>">
                            <a class="btn btn-mini" href="<?php get_url('events', 'duplicate_act', null, array('act_id' => $record['act_record_number'])) ?>"><i class="icon-repeat"></i> <?php echo _t('Duplicate') ?></a>
                        </td>
                    </tr>
                <?php } ?>
                <tr class='actions'>
                    <td colspan='8'>                
                        <button type='submit' class='btn btn-grey' name='delete' ><i class="icon-trash"></i> <?php echo _t('DELETE') ?></button>
                    </td>
                </tr>		
            </tbody>
        </table>
    </form>

<?php } ?>

<script>
    $(document).ready(function(){
        $('#add_perp_btn').click(function(){
            var count = $('.delete_act:checked').length;
            if(count > 0){
                $('#acts_form').attr('action','<?php echo str_replace("&amp;","&",get_url('events', 'add_perpetrator', null, array('eid' => $event_id),null ,true)) ?>');
                $('#acts_form').submit();
            }else{
                $('#errordiv').show();
            }
           
            return false;
        });
        $('#add_act_btn').click(function(){
            var count = $('.delete_act:checked').length;
            if(count > 0){
    <?php
    $url = get_url('events', 'add_act', null, array('eid' => $event_id), null, true);
    $url = str_replace("&amp;", "&", $url);
    ?>
                                        $('#acts_form').attr('action','<?php echo $url ?>');
                                        $('#acts_form').submit();
                                    }else{
                                        $('#errordiv').show();
                                    }
           
                                    return false;
                                });
                            });
</script>