<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: m.index.php');
}
require_once "db.php";
$order_num = mysqli_real_escape_string($link, $_POST['order_num']);

$query = "UPDATE `admin_logic_gw`.`orders_list` SET `statusclarity`='2', `status`='2' WHERE `orders_list`.`order_num` = ?";
$stmt = mysqli_stmt_init($link);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_bind_param($stmt, "s", $order_num);
    mysqli_stmt_execute($stmt);
}
$entered_user = $_SESSION['user']['rank'];
if ($entered_user == 2) {
    header('Location: m.towork.php');
} else {
    header('Location: m.workarea.php');
}


