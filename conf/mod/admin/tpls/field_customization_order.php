<script type="text/javascript" src="res/jquery/jquery.nestable.js"></script>

<div class="dd" id="nestable">
    <ol class="dd-list">
        <?php foreach ($res as $record) { ?>
            <li class="dd-item" data-id="<?php echo $record['field_number']; ?>">

                <div class="dd-handle nestableorderbg"><?php echo $record['field_label']; ?></div>

            </li>
        <?php } ?>
    </ol>
</div>
<div style="clear:both;"></div> 
<input type="hidden" name="order" id="order" value="used"/>

<input type="hidden" name="itemsorder" id="itemsorder" value=""/>
<br/>
<script>

    $(document).ready(function()
    {
        var updateHidden = function(e)
        {
        
            if (window.JSON) {
                $('#itemsorder').val(window.JSON.stringify($('#nestable').nestable('serialize')));//, null, 2));
            }
        };
        $('#nestable').nestable({
            maxDepth :1,
            group:1
        }).on('change', updateHidden);
        
        updateHidden($('#nestable').data('output', $('#itemsorder')));
    
    });
</script>