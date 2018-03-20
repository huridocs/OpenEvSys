<?php

function export_database () {
    global $conf;
    $dbname = $conf['db_name'];
    $user = $conf['db_user'];
    $pass = $conf['db_pass'];
    $filename = tempnam('/tmp', 'oedb');
    shell_exec("mysqldump {$dbname} --user={$user} --password={$pass} > {$filename}");
    $contents = file_get_contents($filename);
    if (!$contents) {
        return false;
    }
    $outname = "oe_{$dbname}-".date('Y-m-d').".sql";
    header("Content-Description: File Transfer");
    header("Content-disposition: attachment; filename={$outname}");
    header("Content-type:application/sql;charset=utf-8");
    header("Content-Length:". strlen($contents));
    echo $contents;
    return true;
}

function import_database () {
    global $conf;
    $destination = APPROOT . 'db_restore/backup.sql';
    $tmpname = $_FILES['file']['tmpname'];
    if (move_uploaded_file($tmpname, $destination)) {
        return true;
    }
    return false;
}

