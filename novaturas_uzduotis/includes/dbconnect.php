<?php
function db_connect(){
    static $mysqli;
    if (!isset($mysqli)){
        $config = parse_ini_file('../private/config.ini');
        $mysqli = mysqli_connect($config['servername'],$config['username'],$config['password'],$config['dbname']);
    }
    if ($mysqli === false){
        return mysqli_connect_error();
    }
    return $mysqli;
}

$mysqli = db_connect();
if ($mysqli->connect_error){
    die ("Connection failed: " . $mysqli->connect_error);
}
?>