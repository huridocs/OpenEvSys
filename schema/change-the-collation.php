<?php
/*
Usage: php change-the-collation.php HOST USERNAME PASS DBA
Source: http://www.holisticsystems.co.uk/blog/?p=931
*/

if($argc != 5)
    print_usage($argv);

$server = $argv[1];
$username = $argv[2];
$password = $argv[3];
$database = $argv[4];

$new_charset = 'utf8';
$new_collation = 'utf8_general_ci';

// Connect to database
$db = mysql_connect($server, $username, $password); if(!$db) die("Cannot connect to database server -".mysql_error());
$select_db = mysql_select_db($database); if (!$select_db) die("could not select $database: ".mysql_error());

// Change database collation
mysql_query("ALTER DATABASE $database DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");

// Loop through all tables changing collation
$result=mysql_query('show tables');
while($tables = mysql_fetch_array($result)) {
    $table = $tables[0];
    mysql_query("ALTER TABLE $table DEFAULT CHARACTER SET $new_charset COLLATE $new_collation");

    // Loop through each column changing collation
    $columns = mysql_query("SHOW FULL COLUMNS FROM $table where collation is not null");
    while($cols = mysql_fetch_array($columns)) {
        $column = $cols[0];
        $type = $cols[1];
        mysql_query("SET FOREIGN_KEY_CHECKS=0;");
        mysql_query("ALTER TABLE $table MODIFY $column $type CHARACTER SET $new_charset COLLATE $new_collation;");
        mysql_query("SET FOREIGN_KEY_CHECKS=1;");
    }

    print "changed collation of $table to $new_collation\n";
}
print "\n\nThe collation of your database has been successfully changed!";

function print_usage($argv)
{
    echo "Usage: php " . $argv[0] . " HOST USERNAME PASS DBA\n";
    exit;
}