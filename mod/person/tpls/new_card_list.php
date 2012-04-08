<?php

function draw_card_list(){
?>    
    <div class="card_list">
<?php
       	echo "<a class='active' >" ._t('PERSON_DETAIL_S_') . "</a>";
       	echo "<a class='inactive' >" . _t('PERSON_ADDRESS_ES_') . "</a>";      
        echo "<a class='inactive' >" . _t('BIOGRAPHY_DETAIL_S_') . "</a>";       
        echo "<a class='inactive' >" . _t('ROLE_LIST') . "</a>";
        echo "<a class='inactive' >". _t('AUDIT_LOG') . "</a>";
?>
    </div>
<?php

}

?>
