
<?php include_once('tabs.php')?>

<div class="panel">
<a class="btn btn-primary" href="<?php echo get_url($_GET['mod'],'subformat_new', null, array('subformat' => $subformat_name)) ?>"><i class="icon-plus icon-white"></i> <?php echo _t('ADD')?></a>
<br />
<br />
<?php
  if((is_array($subformats_list) && count($subformats_list) != 0)){
?>
    <table class="table table-bordered table-striped table-hover">
      <thead>
        <tr>
          <?php
              $count = 0;
              foreach($fields as $field){
            ?>
                <th>
                  <?php echo $field['label'];?>
                </th>
            <?php
                $count++;
            }?>
            <th><?php echo _t('ACTIONS') ?></th>
        </tr>
      </thead>
      <tbody>
       <?php
          $count = 0;
          foreach($subformats_list as $subformat){
            $odd = ($i++%2==1) ? "odd " : '' ;
        ?>
            <tr class="<?php echo $odd ?>">
              <?php
              $first_field = true;
              foreach($fields as $field){
              ?>
                <td>
                  <?php 
                    if(is_array($subformat->$field['map']['field'])){
                      echo "<ul>";
                      foreach ($subformat->$field['map']['field'] as $value) {
                        echo "<li>$value</li>";
                      }
                      echo "</ul>";
                    }
                    else {
                      echo $subformat->$field['map']['field'];
                    }
                  ?>
                </td>
              <?php
              $first_field = false;
              } ?>
              <td>
                <?php echo '<a class="btn btn-info btn-mini" href="'. get_url($_GET['mod'],'subformat_edit', null, array('subformat' => $subformat_name, 'subid' => $subformat->vocab_number), null, true ).'"><i class="icon-edit icon-white"></i> '._t('EDIT').'</a>'; ?>
                <?php echo '<a class="btn btn-danger btn-mini" href="'. get_url($_GET['mod'],'subformat_delete', null, array('subformat' => $subformat_name, 'subid' => $subformat->vocab_number), null, true ).'"><i class="icon-trash icon-white"></i> '._t('DELETE').'</a>'; ?>
              </td>
              
            </tr>
        <?php
            $count++;
        }?>
        </tbody>
      </table>
  <?php
  }else{
    echo '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">×</button>';
      echo _t('NO_RECORDS_WERE_FOUND_');
    echo "</div>";
  }
  ?>
</div>
