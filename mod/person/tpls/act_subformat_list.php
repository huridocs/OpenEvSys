<?php include_once('tabs.php')?>
<?php include_once('person_name.php')?>

<div class="panel">
<a class="btn btn-primary" href="<?php echo get_url('person','subformat_new', null, array('subformat' => $subformat_name)) ?>"><i class="icon-plus icon-white"></i><?php echo _t('ADD')?></a>
<br />
<br />
<?php
  if((is_array($subformats_list) && count($subformats_list) != 0)){
?>
    <form class="form-horizontal"  action="<?php get_url('person','subformat_delete', null, array('subformat' => $subformat_name))?>" method="post">
    <table class="table table-bordered table-striped table-hover">
      <thead>
        <tr>
          <th width='16px'>
            <input type='checkbox' onchange='$("input.delete").attr("checked", this.checked)' />
          </th>
          <?php
              $count = 0;
              foreach($subformats_list[0] as $property => $value){
                if($property == 'vocab_number' || $property == 'record_number') continue
            ?>
                <th>
                  <?php echo $property; ?>
                </th>
            <?php
                $count++;
            }?>
        </tr>
      </thead>
      <tbody>
       <?php
          $count = 0;
          foreach($subformats_list as $subformat){
            $odd = ($i++%2==1) ? "odd " : '' ;
        ?>
            <tr class="<?php echo $odd ?>">
              <td width='16px'>
                <input name="delete_subformats[]" type='checkbox' value='<?php echo $subformat['vocab_number'] ?>' class='delete'/>
              </td>
              <?php
              foreach($subformat as $property => $value){
                if($property == 'vocab_number' || $property == 'record_number') continue
              ?>
                <td>
                  <?php echo $value; ?>
                </td>
              <?php } ?>
            </tr>
        <?php
            $count++;
        }?>
            <tr class='actions'>
              <td colspan='11'>
                <button type='submit' class='btn btn-grey' name='delete' >
                  <i class="icon-trash"></i> <?php echo _t('DELETE') ?>
                </button>
              </td>
            </tr>
            </tbody>
          </table>
        </form>
	<?php
	}else{
          echo '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">×</button>';
            echo _t('NO_RESULTS_FOUND');
          echo "</div>";
	}
	?>
</div>
