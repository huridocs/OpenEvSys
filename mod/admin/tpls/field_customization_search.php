<div id="browse">
<table class='table table-bordered table-striped table-hover'>
    <thead>
        <tr>
            <th><?php echo(_t('FIELD_NUMBER')); ?></th>
            <th><?php echo(_t('FIELD_NAME')); ?></th>
            <th><?php echo(_t('FIELD_TYPE')); ?></th>
            <th><?php echo(_t('LABEL')); ?></th>
            <!--  <th><?php echo(_t('SEARCHABLE')); ?></th>             -->
            <th><?php echo(_t('IN_SEARCH_RESULTS')); ?></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($res as $record){  ?>
        <tr <?php echo ($i++%2==1)?'class="odd"':''; ?>>
            <td><?php echo $record['field_number']; ?></td>
            <td><?php echo $record['field_name']; ?></td>
            <td><?php echo $record['field_type']; ?></td>
            <td><?php echo $record['field_label'];?></td>
            <!--
            <td align="center">
                <?php $name = 'searchable_'.$record['field_number'];?>
                <input type="checkbox" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value='y' 
                <?php 
                if(strtolower( $record['visible_search'] ) =='y')echo "checked='true'";
                if(  $record['visible_search']  =='Y' || $record['visible_search']  =='N') echo ' disabled="disabled"' ;
                ?> 
                />
            </td>
            -->
            <td align="center">
                <?php $name = 'search_results_'.$record['field_number'];?>
                <input type="checkbox" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value='y' 
                <?php 
                               
                if(strtolower( $record['visible_search_display'] ) =='y')echo "checked='true'";
                if(  $record['visible_search_display']  =='Y' || $record['visible_search_display']  =='N') echo ' disabled="disabled"' ;
                ?> 
                />
            </td>
        </tr>

    <?php } ?>
    		
        <tr  <?php echo ($i++%2==1)?'class="odd"':''; ?>>
            <td>  </td> 
            <td>  </td> 
            <td>  </td>
            <td>  </td>
            <!--<td>
            <button type="submit" name="reset_visible_search" class='btn'  ><i class="icon-remove"></i> <?php echo _t('RESET') ?></button></td>
            </td>
            -->
            <td>
            <button type="submit" name="reset_visible_search_display" class='btn'  ><i class="icon-remove"></i> <?php echo _t('RESET') ?></button></td>
            </td>  
        </tr>
    </tbody>
</table>
</div>

<input type="hidden" name="search_results" id="search_results" value="used"/>

