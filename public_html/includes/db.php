<?php

$server = "localhost";
$dbuser = "admin_fix";
$dbpass = "kerber17OS#";
$dbname = "admin_logic_gw";


$link = mysqli_connect($server, $dbuser, $dbpass, $dbname);
$link->set_charset("utf8");

if ($link == false)
{
    echo "Соединение с базой не удалось";
}

 ?>