document.captureEvents(Event.CLICK);

document.onclick = function(event){
    if(event.ctrlKey) {
        event.stopPropagation();
        var $obj = $(event.target);
        if( $obj.children('*').length == 0 ){
            if(event.target.tagName=="INPUT" && $obj.attr('type')=='submit'){
                var text = $obj.attr('value');              
                var lv = translate(text,$obj);
            }else{
                var text = $obj.text();
                var lv = translate(text, $obj);
            }
        }
        else{
            alert('You need to click on a text to translate.');
        }
        return false;
    }
};


function translate(text, $obj){
    var lv = prompt(text, "");
    //send the data to server
    $.post("index.php?mod=admin&act=update_po&stream=text", { msgid: text , msgstr : lv },
    function(data){
        if(data == "true"){
            if($obj.tagName=="INPUT" && $obj.attr('type')=='submit'){
                $obj.attr('value',lv);
            }else{
                $obj.text(lv);
            }           
        }
      }, "text");
}

