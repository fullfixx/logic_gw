<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: index.php');
}
require_once 'db.php';
$order_num = mysqli_real_escape_string($link, $_POST['order_num']);
$livesklad_num = mysqli_real_escape_string($link, $_POST['livesklad_num']);
$order_type = mysqli_real_escape_string($link, $_POST['order_type']);
$order_priority = mysqli_real_escape_string($link, $_POST['order_priority']);
$status = mysqli_real_escape_string($link, $_POST['status']);
$statusclarity = mysqli_real_escape_string($link, $_POST['statusclarity']);
$phone_n = mysqli_real_escape_string($link, $_POST['phone_n']);
$first_name = mysqli_real_escape_string($link, $_POST['first_name']);
$device_type = mysqli_real_escape_string($link, $_POST['device_type']);
$device_brand = mysqli_real_escape_string($link, $_POST['device_brand']);
$device_model = mysqli_real_escape_string($link, $_POST['device_model']);
$creator = mysqli_real_escape_string($link, $_POST['creator']);
$executor = mysqli_real_escape_string($link, $_POST['executor']);
$comment_order = mysqli_real_escape_string($link, $_POST['comment_order']);
$color = mysqli_real_escape_string($link, $_POST['color']);
$recommendation = mysqli_real_escape_string($link, $_POST['recommendation']);

$query = "UPDATE `admin_logic_gw`.`orders_list` SET `livesklad_num` = ?, `order_type` = ?, `order_priority` = ?, `status` = ?, `statusclarity` = ?, `phone_n` = ?, `first_name` = ?, `device_type` = ?, `device_brand` = ?, `device_model` = ?, `executor` = ?, `comment_order` = ?, `color` = ? , `recommendation` = ? WHERE `orders_list`.`order_num` = ?";
$stmt = mysqli_stmt_init($link);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_bind_param($stmt, "sssssssssssssss", $livesklad_num, $order_type, $order_priority, $status, $statusclarity, $phone_n, $first_name, $device_type, $device_brand, $device_model, $executor, $comment_order, $color, $recommendation, $order_num);
    mysqli_stmt_execute($stmt);
} else {
    echo "STMT error";
}

header('Location: update.php?id='.$order_num);

