<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: m.index.php');
}
require_once "db.php";
$order_num = mysqli_real_escape_string($link, $_POST['order_num']);
$recommendation = mysqli_real_escape_string($link, $_POST['recommendation']);
$query = "UPDATE `admin_logic_gw`.`orders_list` SET `recommendation`= ? WHERE `orders_list`.`order_num` = ?";
$stmt = mysqli_stmt_init($link);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_bind_param($stmt, "ss", $recommendation, $order_num);
    mysqli_stmt_execute($stmt);
}
header('Location: m.workarea_update.php?id='.$order_num);



