<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: m.index.php');
}
include 'db.php';
$statusclarity = mysqli_real_escape_string($link, $_POST['statusclarity']);
$phone_n = mysqli_real_escape_string($link, $_POST['phone_n']);
$first_name = mysqli_real_escape_string($link, $_POST['first_name']);
$device_type = mysqli_real_escape_string($link, $_POST['device_type']);
$device_brand = mysqli_real_escape_string($link, $_POST['device_brand']);
$device_model = mysqli_real_escape_string($link, $_POST['device_model']);
$comment_order = mysqli_real_escape_string($link, $_POST['comment_order']);
$visible = mysqli_real_escape_string($link, $_POST['visible']);
$user_id = $_SESSION['user']['id'];


$query = "INSERT INTO `admin_logic_gw`.`orders_list` (`livesklad_num`, `order_type`, `order_priority`, `status`, `statusclarity`, `phone_n`, `first_name`, `device_type`, `device_brand`, `device_model`, `data_open`, `data_close`, `creator`, `executor`, `comment_order`, `visible`, `color`, `recommendation`) VALUES ('PRE', '4', '2', '3', ?, ?, ?, ?, ?, ?, CURRENT_TIME(), '', ?, '1', ?, ?, 'цвет не указан', NULL)";
$stmt = mysqli_stmt_init($link);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_bind_param($stmt, "sssssssss", $statusclarity, $phone_n, $first_name, $device_type, $device_brand, $device_model, $user_id, $comment_order, $visible);
    mysqli_stmt_execute($stmt);
} else {
    echo "STMT error";
}
header('Location: m.last_pre.php');

?>
