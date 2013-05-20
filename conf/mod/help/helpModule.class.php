<?php
class helpModule extends shnModule 
{

    public function act_help_popup()
    { 
	    $id=ltrim($_GET['id'],'0');
        $id=$_GET['id'];
    	$help_array=Browse::gethelp($id);
	    $this->help_array = $help_array[0];
    }
    public function act_get_help()
    {   	
    	global $global;global $conf;
		
    	$browse= new Browse();		
		
    	$id = $_GET['id'];    	
        $sql = "SELECT * FROM help WHERE field_number='{$id}'";		
    	$res = $browse->ExecuteQuery($sql);
    	$result_data = $res[0];
		
    	
    	if($result_data['definition'] == "")
    	{
			$resultSet = $browse->ExecuteNonQuery("INSERT INTO help (field_number, definition) VALUES('".$id."', '<p style=small>No Help Available</p>')");
			echo "<p style=small>No Help Available</p>";			
    	}
    	else
    	{    		
    		echo $result_data['definition'];	
    	}    	
    	exit(0);
    }
	public function act_set_help()
    {
    	global $global;global $conf;
		
    	$browse= new Browse();		
		
    	$id = $_GET['element_id'];
    	$message = $_GET['wysiwyg'];
    	$sql = "UPDATE help SET definition='{$message}' WHERE field_number ='{$id}'";
    	$res = $browse->ExecuteNonQuery($sql);
    	
		exit(0);
    }

}    

