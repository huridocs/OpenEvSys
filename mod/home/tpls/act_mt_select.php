<?php 
    foreach($terms as $term){
        if($_GET['selected']==$term['vocab_number'])
            echo "<option selected='true' value='{$term['vocab_number']}'>{$term['label']}</option>";
        else 
            echo "<option value='{$term['vocab_number']}'>{$term['label']}</option>";
    }

