<?php include_once('tabs.php')?>
<?php include_once('person_name.php')?>

<div class="panel">
        <div class="alert alert-error">

            <h3><?php echo _t('DO_YOU_WANT_TO_DELETE_THE_SELECTED_ELEMENTS__') ?></h3>
            <form class="form-horizontal"  action="<?php get_url('person', 'subformat_delete', null, array('subformat' => $subformat_name)) ?>" method="post">
                <br />
                <center>
                    <button type='submit' class='btn btn-grey' name='yes' ><i class="icon-trash"></i> <?php echo _t('DELETE') ?></button>
                    <a href="<?php get_url('person', 'subformat_list', null, array('subformat' => $subformat_name)) ?>" class='btn' name='no' >
                        <i class="icon-remove-circle"></i> <?php echo _t('CANCEL') ?>
                    </a>

                </center>
                <?php foreach ($_POST['delete_subformats'] as $val) { ?>
                    <input type='hidden' name='delete_subformats[]' value='<?php echo $val ?>' />
                <?php } ?>
            </form>
        </div>
    <form class="form-horizontal"  action="<?php get_url('person','subformat_list', null, array('subformat' => $subformat_name))?>" method="post">
    <table class="table table-bordered table-striped table-hover">
      <thead>
        <tr>
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
            </tbody>
          </table>
        </form>
</div>
