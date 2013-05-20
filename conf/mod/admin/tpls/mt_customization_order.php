<script type="text/javascript" src="res/jquery/jquery.nestable.js"></script>

<div class="dd" id="nestable" style="width:100%">
    <ol class="dd-list">
        <?php
        $count = count($res);
        $levelsarray = array(-1=>0);
        
        for ($i = 0; $i < $count;) {
            
            $element1 = $res[$i];
            $element2 = $res[++$i];
            $levelsarray[$level] = $element1['vocab_number'];
            $level = $element1['term_level'];
            ?>
            <li class="dd-item" data-id="<?php echo $element1['vocab_number']; ?>">

                <div class="dd-handle nestableorderbg"><?php echo $element1['label']; ?></div>
                <?php
                if($element2['parent_vocab_number'] == $element1['vocab_number']){
                    $level++;
                    ?>
                <ol class="dd-list">
                    <?php
                }elseif($element2['parent_vocab_number'] == $element1['parent_vocab_number']){
                   ?>
                    </li>
                    <?php
                }else{
                    $level2 = $element2['term_level'];
                    //$key = array_search($element2['parent_vocab_number'], $levelsarray);
                    echo str_repeat("</li></ol>", $level-$level2); 
                        
                    /*if($key !== false){
                        echo str_repeat("</li></ol>", $level-$key-1); 
                        $level = $key+1;
                    }*/                   
                }
                ?>
            
        <?php }
        echo str_repeat("</li></ol>", $level);
        echo "</li>";
        ?>
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
            maxDepth :100,
            group:1
        }).on('change', updateHidden);
        
        updateHidden($('#nestable').data('output', $('#itemsorder')));
    
    });
</script>