<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: index.php');
}
require_once 'db.php';

$order_num = mysqli_real_escape_string($link, $_POST['order_num']);
$search_ss = mysqli_real_escape_string($link, $_POST['search_ss']);
$search_s = mysqli_real_escape_string($link, $_POST['search_s']);
$search_sp = mysqli_real_escape_string($link, $_POST['search_sp']);
$gas_type = mysqli_real_escape_string($link, $_POST['gas_type']);
$gas_name = mysqli_real_escape_string($link, $_POST['gas_name']);
$executor = mysqli_real_escape_string($link, $_POST['executor']);
$gas_qty = mysqli_real_escape_string($link, $_POST['gas_qty']);
$cost = mysqli_real_escape_string($link, $_POST['cost']);
$discont = mysqli_real_escape_string($link, $_POST['discont']);
$time_rate = mysqli_real_escape_string($link, $_POST['time_rate']);
$time_rate = str_replace(',','.',$time_rate);
$time_rate = floatval($time_rate);
$class_price = mysqli_real_escape_string($link, $_POST['class_price']);
$price = $class_price*$time_rate;
$discont_ratio = -$discont/100+1;
$amount = $price*$gas_qty*$discont_ratio;

mysqli_query($link, "INSERT INTO `admin_logic_gw`.`gas` (`gas_id`, `gas_type`, `order_num`, `gas_num`, `gas_name`, `gas_supplier`, `executor`, `gas_time_rate`, `gas_qty`, `cost` , `discont`, `price`, `amount`, `gas_time_add`, `gas_sold`, `transaction`, `gas_comment`)
VALUES (NULL, '$gas_type', '$order_num', NULL, '$gas_name', 1, '$executor', '$time_rate', '$gas_qty', '$cost', '$discont', '$price', '$amount', CURRENT_TIME, '1', NULL, NULL)");
header('Location: update.php?id='.$order_num.'&search_ss='.$search_ss.'&search_s='.$search_s.'&search_sp='.$search_sp);