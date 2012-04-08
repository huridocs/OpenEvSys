<div id="browse">
<table class='browse'>
    <thead>
        <tr>
            <td><?php echo(_t('FIELD_NUMBER')); ?></td>
            <td><?php echo(_t('FIELD_NAME')); ?></td>
            <td><?php echo(_t('FIELD_TYPE')); ?></td>
            <td><?php echo(_t('LABEL')); ?></td>
            <!--  <td><?php echo(_t('SEARCHABLE')); ?></td>             -->
            <td><?php echo(_t('IN_SEARCH_RESULTS')); ?></td>
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
            <!--<td> <?php echo $fields1['reset_visible_search'] ?></td>
            -->
            <td> <?php echo $fields1['reset_visible_search_display'] ?></td>  
        </tr>
    </tbody>
</table>
</div>

<input type="hidden" name="search_results" id="search_results" value="used"/>

