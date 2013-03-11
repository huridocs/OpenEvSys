<?php
/**
 * This library contain functions to display the data in entity forms
 *
 * Copyright (C) 2009
 *   Respere Lanka (PVT) Ltd. http://respere.com, info@respere.com
 * Copyright (C) 2009
 *   Human Rights Information and Documentation Systems,
 *   HURIDOCS), http://www.huridocs.org/, info@huridocs.org
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @auther  H J P Fonseka <jo@respere.com>
 * @package Framework
 * 
 */

function shn_view_hidden($label,$value,$extra_opts)
{
    $class = "class='{$extra_opts['class']}'";
?>
    <tr <?php echo $class ?>><td class='title' align='right'><?php echo $label ?></td><td class='text'><?php echo $value; ?></td></tr>
<?php	
}

function shn_view_text($label,$value,$extra_opts)
{
    $class = "class='{$extra_opts['class']}'";
    if(is_array($extra_opts['clari_value']))
    $clari = array_pop($extra_opts['clari_value']);
?>
    <tr <?php echo $class ?>>
        <td class='title' align='right'><?php echo $label ?></td>
        <td class='text'>
            <span><?php echo htmlspecialchars($value); ?></span>
            <?php if(isset($clari)){ ?>
            <p class='clarify_note'><em><?php echo _t('CLARIFYING_NOTE')?><span><?php echo ' : '.$clari  ?></span></em><p>
            <?php } ?>
        </td>
    </tr>
<?php
}

function shn_view_textarea($label,$value,$extra_opts)
{
    $class = "class='{$extra_opts['class']}'";
    if(is_array($extra_opts['clari_value']))
    $clari = array_pop($extra_opts['clari_value']);
?>
    <tr <?php echo $class ?>>
        <td class='title' align='right'><?php echo $label ?></td>
        <td class='text'>
            <?php 
                $p = explode("\n",htmlspecialchars($value)); 
                foreach( $p as $para)
                    echo "<p>$para</p>"; 
            ?>
            <?php if(isset($clari)){ ?>
            <p class='clarify_note'><em><?php echo _t('CLARIFYING_NOTE')?><span><?php echo ' : '.$clari  ?></span></em><p>
            <?php } ?>
        </td>
    </tr>
<?php
}

function shn_view_date($label,$value,$extra_opts)
{
    $value = ($value=='0000-00-00')?'':$value;
    shn_view_text($label,$value,$extra_opts);
}

function shn_view_select($label,$value,$extra_opts)
{
    shn_view_text($label,$value,$extra_opts);
}


function shn_view_mt_select($label,$value,$extra_opts)
{
    shn_view_text($label,get_mt_term($value),$extra_opts);
}

function shn_view_mt_tree($label,$value,$extra_opts)
{
    shn_view_text($label,get_mt_term($value),$extra_opts);
}


function shn_view_mt_select_mlt($label,$value,$extra_opts)
{
    $class = "class='{$extra_opts['class']}'";
?>
    <tr <?php echo $class ?>>
        <td class='title' align='right'><?php echo $label ?></td>
        <td class='text'>
        <div style='margin-left:20px;'>
<?php   
        if(is_array($value)&&count($value)>0){
            echo "<ul>\n";
            foreach($value as $val){ 
                echo "\t<li>".htmlspecialchars(get_mt_term($val))."</li>\n";
                $clari = $extra_opts['clari_value'][$val];
                if(isset($clari)){ ?>
                <p class='clarify_note'><em><?php echo _t('CLARIFYING_NOTE')?><span><?php echo ' : '.$clari  ?></span></em><p>
<?php
                } 
            }
            echo "</ul>\n";
        }
?>
        </div>
        </td>
    </tr>
<?php
}



function shn_view_document($label,$value,$extra_opts)
{
    if($value!=null){
        $class = "class='{$extra_opts['class']}'";
    ?>
        <tr <?php echo $class ?>>
            <td class='title' align='left'><?php echo $label ?></td>
        </tr>
        <tr <?php echo $class ?>>
            <td class='text'>
            <div style='margin-left:20px;'>
    <?php   
            $doc_obj = new SupportingDocsMeta();
            if(is_array($value)&&count($value)>0){
                echo "<ul>\n";
                foreach($value as $val){
                    $doc_obj->LoadFromRecordNumber($val);
                  ?><li><a href='<?php get_url('docu','view_document',null,array('doc_id'=>$val)); ?>' ><?php
                    echo htmlspecialchars($doc_obj->title);
                  ?></a>
                  <?php  place_download_icon($doc_obj);
                  ?>
                  </li><?php
                }
                echo "</ul>\n";
            }
    ?>
            </div>
            </td>
        </tr>
<?php
    }
}


function shn_view_upload($label,$value,$extra_opts)
{
	$doc = new SupportingDocs();
	$doc->LoadFromRecordNumber($value);
	
	if($doc->uri != null && file_exists($doc->uri)){		
	    $class = "class='{$extra_opts['class']}'";
?>
		<tr <?php echo $class ?>>
	    <td class='title' align='right'><?php echo $label ?></td>
	  	<td class='text'>
	        <img src="<?php get_url('docu','download',null,array('doc_id'=>$value)); ?>" alt="<?php echo $value;?>" style="width: 100px; height: 100px;"/>
	    	<br/>
	    	<a href="<?php get_url('docu','download',null,array('doc_id'=>$value)); ?>" target="_blank"><?php echo _t('SEE_FULL_SIZE_IMAGE')?></a>            
	 	</td>
	 	</tr>
<?php	
	}
}

function shn_view_related_victim($label,$value,$extra_opts)
{
	$person = New Person();
	$person->LoadfromRecordNumber($value); 
	
    $class = "class='{$extra_opts['class']}'";   
?>
    <tr <?php echo $class ?>>
        <td class='title' align='right'><?php echo $label ?></td>
        <td class='text'>
            <span><?php echo htmlspecialchars($person->person_name); ?></span>           
        </td>
    </tr>
<?php
}

function place_download_icon($doc_obj){
	$fileType = $doc_obj->format;
	
	if( $fileType != null || trim( $fileType ) != ''){
		include APPROOT . 'mod/docu/mimeTypes.php' ;
		
		$key = array_search( $fileType , $mimeTypes);
		if($key != false){
			$fileType = $key;
		}
		
		$filetypeicon =  strtoupper( $fileType) . '.png';
		if(!file_exists('theme/default/images/filetypes/' . $filetypeicon )){
			$filetypeicon =  'NOTYPE.png';		
		}
		?>
		<a href='<?php get_url('docu','download',null,array('doc_id'=>$doc_obj->doc_id)); ?>' >
		<img width="32" height="32" title="download_<?php echo $doc_obj->title ?>" src="theme/default/images/filetypes/<?php echo $filetypeicon ?>" class="icon"/>
		</a>
		<?php 
	}
}

function shn_view_mt_tree_mlt($label,$value,$extra_opts)
{
    shn_view_mt_select_mlt($label,$value,$extra_opts);
}


function shn_view_address($label,$value,$extra_opts)
{ 
	$class = "class='{$extra_opts['class']}'";
    if(is_array($extra_opts['clari_value']))
    $clari = array_pop($extra_opts['clari_value']);
?>
    <tr <?php echo $class ?>>
        <td class='title' align='right'><?php echo $label ?></td>
        <td class='text'></td>
    </tr>		
	<tr>
		<td></td>
		<td>
		<div style='margin-left:20px;'>	
		<ul>		
<?php		
		$address = new Address();		
		foreach($value as $add){
			$address->LoadfromRecordNumber($add);
?>
			<li><?php echo _t('ADDRESS_TYPE')?>: <?php echo get_mt_term($address->address_type); ?></li>
			<li><?php echo _t('ADDRESS')?>: <?php echo $address->address; ?></li>
			<li><?php echo _t('COUNTRY')?>:<?php echo get_mt_term($address->country); ?></li>
			<li><?php echo _t('PHONE')?>:<?php echo $address->phone; ?> | <?php echo _t('CELLULAR')?>:<?php echo $address->cellular; ?> | <?php echo _t('FAX')?> : <?php echo $address->fax; ?></li>
			<li><?php echo _t('E_MAIL')?>:<?php echo $address->email; ?> | <?php echo _t('WEB')?>:<?php echo $address->web; ?></li>
			<li><?php echo _t('START_DATE')?>:<?php echo $address->start_date; ?> | <?php echo _t('END_DATE')?>:<?php echo $address->end_date; ?></li>						
<?php
			echo "<br /><br />";
		}		
?>			
		</ul>
		</div>
		</td>
	</tr>	
<?php
}

function shn_view_radio($label,$value,$extra_opts)
{ 
	$class = "class='{$extra_opts['class']}'";
    if(is_array($extra_opts['clari_value']))
    $clari = array_pop($extra_opts['clari_value']);
?>
    <tr <?php echo $class ?>>
        <td class='title' align='right'><?php echo $label ?></td>
        <td class='text'> <?php  if(strtolower( $value ) == 'y'){
            echo  'Yes' ;
        }elseif(strtolower( $value ) == 'n'){
            echo 'No'; } ?></td>
    </tr>		

<?php
}

function shn_view_checkbox($label,$value,$extra_opts)
{ 
	$class = "class='{$extra_opts['class']}'";
    if(is_array($extra_opts['clari_value']))
    $clari = array_pop($extra_opts['clari_value']);
?>
    <tr <?php echo $class ?>>
        <td class='title' align='right'><?php echo $label ?></td>
        <td class='text'> 
            <span><?php  echo (strtolower( $value ) == 'y' ? 'Yes' : 'No');  ?></span>
        <?php if(isset($clari)){ ?>
            <p class='clarify_note'><em><?php echo _t('CLARIFYING_NOTE')?><span><?php echo ' : '.$clari  ?></span></em><p>
            <?php } ?>
        </td>
    </tr>	
<?php
}

function shn_view_location($label,$value,$extra_opts)
{
    
    global $global;
    $global["js"][] = "map";
    
    $class = "class='{$extra_opts['class']}'";
    if(is_array($extra_opts['clari_value']))
    $clari = array_pop($extra_opts['clari_value']);
    //var_dump($value);exit;
    
?>
    <tr <?php echo $class ?>>
        <td class='title' align='right'><?php echo $label ?></td>
        <td class='text'> 
           
<?php

    //to prevent warnings set $options to array() if it is null
    $options=($options==null)?array():$options;
    $name = rand(0,10000000);
    if($value['latitude'] && $value['longitude']){
     ?>
    <div class="locationFieldsConatiner">
     		
    <div id="<?php echo $name?>_mapContainer" class="mapContainer"></div>
  
    </div>
    <script type="text/javascript">
        jQuery(window).load(function() {
            
        <?php if(isset($value)){
            $value["mapContainer"] = $name."_mapContainer";
    
            ?>
                    initViewMap(<?php echo json_encode($value)?>);
        <?php
        }
        ?>
    });
</script>
   <?php if(isset($clari)){ ?>
            <p class='clarify_note'><em><?php echo _t('CLARIFYING_NOTE')?><span><?php echo ' : '.$clari  ?></span></em><p>
            <?php } 
    }?>
        </td>
    </tr>
<?php
  
}
