<?php 
class ImportLog extends DomainEntity{
    
    public $file_name;
    public $file_path ;
    public $date;
    public $time;
    public $status;
    public $export_instance;
    public $export_date;
    public $export_time;
    
    
    
    private $pkey = array('time');
    
    public function __construct($table = false, $pkeyarr=false, $db=false, $options=array()){
        parent::__construct('import_log_report', $pkey ,$db , $options); 
              
    }
    
}