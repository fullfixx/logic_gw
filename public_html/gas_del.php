<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: index.php');
}
require_once 'db.php';

$order_num = mysqli_real_escape_string($link, $_POST['order_num']);
$search_ss = mysqli_real_escape_string($link, $_POST['search_ss']);
$gas_id = mysqli_real_escape_string($link, $_POST['gas_id']);

mysqli_query($link, "DELETE FROM `gas` WHERE `gas_id`='$gas_id'");
header('Location: update.php?id='.$order_num.'&search_ss='.$search_ss);


