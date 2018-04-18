<?php

function export_database () {
    global $conf;
    $dbname = $conf['db_name'];
    $user = $conf['db_user'];
    $pass = $conf['db_pass'];
    $mysqldump_script = isset($conf['mysqldump_bin_path'])? $conf['mysqldump_bin_path']: 'mysqldump';
    $filename = tempnam(APPROOT."db_restore", 'tmp');
    $exitCode = 0;
    $output = [];
    exec("$mysqldump_script {$dbname} --ignore-table={$dbname}.sessions2 --user={$user} --password={$pass} > {$filename}", $output, $exitCode);
    $contents = file_get_contents($filename);
    unlink($filename);
    if (!$contents) {
        $output_str = implode("\n", $output);
        $output_str = $output_str? "Output: $output_str": "";
        throw new Exception("Error exporting database. Exit code: ".$exitCode." $output_str");
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
    throw new Exception("Error uploading database. Upload error code: ".$_FILES['file']['error']);
}

function create_restore_lock_file () {
    $path = get_lock_file_path();
    $contents = "Date: ".date('Y-m-d')
        ."\nThis file is auto-generated for the Database restore process."
        ."\nIts presence indicates that a database restore is underway or"
        ." a previous import was not terminated successfully."
        ."\nThis file is deleted after successful completion of the restore."
        ."\nUsers cannot initiate a new database restore until this file is deleted.";
    return file_put_contents($path, $contents);
}

function import_database () {
    global $conf;
    if (lock_file_exists()) {
        return;
    }
    create_restore_lock_file();
    $filename = get_backup_file_path();
    if (file_exists($filename)) {
        $dbname = $conf['db_name'];
        $user = $conf['db_user'];
        $pass = $conf['db_pass'];
        $mysql_script = isset($conf['mysql_bin_path'])? $conf['mysql_bin_path']: 'mysql';
        $output = [];
        $exitCode = 0;
        exec("$mysql_script --user={$user} --password={$pass} {$dbname} < {$filename}", $output, $exitCode);
        delete_backup_file();
        $log = "Exit code: $exitCode. Output: ".implode(" ", $output);
        write_import_log($log);
    }
    delete_lock_file();
}

function get_lock_file_path () {
    return APPROOT . 'db_restore/LOCK';
}

function get_backup_file_path () {
    return APPROOT . 'db_restore/backup.sql';
}

function get_log_path () {
    return APPROOT . 'db_restore/import.log';
}

function lock_file_exists () {
    $path = get_lock_file_path();
    return file_exists($path);
}

function delete_lock_file () {
    $path = get_lock_file_path();
    unlink($path);
}

function delete_backup_file () {
    $path = get_backup_file_path();
    unlink($path);
}

function write_import_log ($entry) {
    $entry = date('Y-m-d').": $entry\n";
    $f = fopen(get_log_path(), 'a');
    fwrite($f, $entry);
    fclose($f);
}