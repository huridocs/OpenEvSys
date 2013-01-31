<?php

class shnMessageQueue
{
    

    public static function renderMessages()
    {
        if(!isset($_SESSION['messages']))return "";
        ob_start();
        foreach($_SESSION['messages'] as $type=>$messages){
            if(count($type) < 1)
                continue;
            if($type == "error" ){
                echo "<div class='alert alert-error'>";
            }else{
                echo "<div class='alert alert-info'>";
            }
            echo "<strong>".ucfirst($type)."</strong>";
            echo '<button type="button" class="close" data-dismiss="alert">×</button><ul>';
            foreach($messages as $message){
                echo "<li>".htmlspecialchars($message/*,ENT_COMPAT,'UTF-8',false*/)."</li>";
            }
            echo "</div>";
            echo "<br class='clearfix'/>";
            //once a message is printed out remove from the queue
            unset($_SESSION['messages'][$type]);
        }
        return ob_get_clean();
    }

    public static function addInformation($text)
    {
        if(!is_array($_SESSION['messages']['information']))
            $_SESSION['messages']['information']=array();
        array_push($_SESSION['messages']['information'],$text);
    }

    public static function addError($text)
    {
        if(!is_array($_SESSION['messages']['error']))
            $_SESSION['messages']['error']=array();
        array_push($_SESSION['messages']['error'],$text);
    }

    public static function clear()
    {
        unset($_SESSION['messages']);
    }
}
