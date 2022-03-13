<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: index.php');
}
require_once 'db.php';

$user_id = $_SESSION['user']['id'];
$gas_sold = 3;
$gas_num = mysqli_real_escape_string($link, $_POST['gas_num']);
$gas_name = mysqli_real_escape_string($link, $_POST['gas_name']);
$gas_supplier = mysqli_real_escape_string($link, $_POST['gas_supplier']);
$gas_qty = mysqli_real_escape_string($link, $_POST['gas_qty']);
$cost = mysqli_real_escape_string($link, $_POST['cost']);
$price = mysqli_real_escape_string($link, $_POST['price']);
$phone_n = mysqli_real_escape_string($link, $_POST['phone_n']);
$first_name = mysqli_real_escape_string($link, $_POST['first_name']);
$comment_order = mysqli_real_escape_string($link, $_POST['comment_order']);

$query_auto_order = "INSERT INTO `admin_logic_gw`.`orders_list` (
`livesklad_num`, `order_num`, `order_type`, `order_priority`, `status`, `statusclarity`, `phone_n`, `first_name`, `device_type`, `device_brand`, `device_model`, `data_open`, `data_close`, `creator`, `executor`, `comment_order`, `visible`, `color`, `recommendation`) VALUES (
'MK', NULL, '4', '2', '4', '1', '$phone_n', '$first_name', '1', '1', '1', CURRENT_TIME, NULL, '$user_id', '1', '$comment_order', 3, NULL, NULL)";
mysqli_query($link, $query_auto_order);
$result_last_order = mysqli_query($link, "SELECT * FROM `orders_list` ORDER BY `order_num` DESC LIMIT 0,1");
$last_order = mysqli_fetch_assoc($result_last_order);
$order_num = $last_order['order_num'];

$query_new_free_backorder = "INSERT INTO `admin_logic_gw`.`gas` (
`gas_id`, `gas_type`, `order_num`, `gas_num`, `gas_name`, `gas_supplier`, `executor`, `gas_time_rate`, `gas_qty`, `discont`, `cost`, `price`, `amount`, `gas_time_add`, `gas_sold`, `gas_time_sold`, `transaction`, `gas_comment`) VALUES (
NULL, 'p', '$order_num', '$gas_num', '$gas_name', '$gas_supplier', '1', '1.00', '$gas_qty', NULL, '$cost', '$price', NULL, CURRENT_TIME, '$gas_sold', NULL, NULL, NULL)";
mysqli_query($link, $query_new_free_backorder);
header('Location: backorders.php?gas_supplier='.$gas_supplier);