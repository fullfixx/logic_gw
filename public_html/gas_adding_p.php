<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: index.php');
}
require_once 'db.php';

if (isset($_POST['add'])) {
    $gas_sold = 1;
} elseif(isset($_POST['backorder'])) {
    $gas_sold = 3;
}

$order_num = mysqli_real_escape_string($link, $_POST['order_num']);
$search_sp = mysqli_real_escape_string($link, $_POST['search_sp']);
$search_ss = mysqli_real_escape_string($link, $_POST['search_ss']);
$transaction_id = mysqli_real_escape_string($link, $_POST['transaction_id']);
$qty_now = mysqli_real_escape_string($link, $_POST['qty_now']);
$gas_type = mysqli_real_escape_string($link, $_POST['gas_type']);
$gas_num = mysqli_real_escape_string($link, $_POST['gas_num']);
$gas_name = mysqli_real_escape_string($link, $_POST['gas_name']);
$executor = mysqli_real_escape_string($link, $_POST['executor']);
$gas_qty = mysqli_real_escape_string($link, $_POST['gas_qty']);
$cost = mysqli_real_escape_string($link, $_POST['cost']);
$price = mysqli_real_escape_string($link, $_POST['price']);
$discont = mysqli_real_escape_string($link, $_POST['discont']);
$discont_ratio = -$discont/100+1;
$amount = $price*$gas_qty*$discont_ratio;
$stock_correct = $qty_now-$gas_qty;

mysqli_query($link, "INSERT INTO `admin_logic_gw`.`gas` (`gas_id`, `gas_type`, `order_num`, `gas_num`, `gas_name`, `gas_supplier`, `executor`, `gas_time_rate`, `gas_qty`, `cost` , `discont`, `price`, `amount`, `gas_time_add`, `gas_sold`, `transaction`, `gas_comment`)
VALUES (NULL, '$gas_type', '$order_num', '$gas_num', '$gas_name', 1, '$executor', '1', '$gas_qty', '$cost', '$discont', '$price', '$amount', CURRENT_TIME, '$gas_sold', '$transaction_id', NULL)");
header('Location: update.php?id='.$order_num.'&search_sp='.$search_sp.'&search_ss='.$search_ss);

mysqli_query($link, "UPDATE `admin_logic_gw`.`spareparts_stock` SET `qty_now` = '$stock_correct' WHERE `spareparts_stock`.`transaction_id` = '$transaction_id';");
