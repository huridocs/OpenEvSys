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

function upload_database () {
    global $conf;
    if (lock_file_exists()) {
        return false;
    }
    $destination = get_backup_file_path();
    if (file_exists($destination)) {
        unlink($destination);
    }
    $tmpname = $_FILES['file']['tmp_name'];
    if (move_uploaded_file($tmpname, $destination)) {
        return true;
    }
    return false;
}

function create_restore_lock_file () {
    $path = get_lock_file_path();
    $contents = "Date: ".date('Y-m-d')
        ."\nThis file is autogenerated for teh Database restore process."
        ."\nIts presence indicates that a database restore is underway or"
        ." a previous import was not terminated successfully."
        ."\nThis file is deleted after successful completion of the restore."
        ."\nUsers cannot initiate a new database restore until this file is deleted.";
    return file_put_contents($path, $contents);
}

function lock_file_exists () {
    $path = get_lock_file_path();
    return file_exists($path);
}

function delete_lock_file () {
    $path = get_lock_file_path();
    unlink($path);
}

function import_database () {
    global $conf;
    create_restore_lock_file();
    $filename = get_lock_file_path();
    if (file_exists($filename)) {
        $dbname = $conf['db_name'];
        $user = $conf['db_user'];
        $pass = $conf['db_pass'];
        
        shell_exec("mysql --user={$user} --password={$pass} {$dbname} < {$filename}");
    }
    delete_lock_file();
}

function get_lock_file_path () {
    return APPROOT . 'db_restore/LOCK';
}

function get_backup_file_path () {
    return APPROOT . 'db_restore/backup.sql';
}