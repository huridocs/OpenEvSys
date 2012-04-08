<?php 
    $return = "[";
    foreach($terms as $term){
        $return .= "{";
        $return .= '"text" : "'.$term['label'].'" ';
		$return .= ', "huricode" : "'.$term['vocab_number'].'" ';
		$return .= ', "id" : "'.$term['vocab_number'].'" ';	
        if ($term['children'] > 0)	
		$return .= ', "hasChildren": true';
        $return .= "},";
    }
    $return = rtrim($return,',');
    $return .= "]";
    echo $return;

