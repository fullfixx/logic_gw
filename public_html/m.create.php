<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: m.index.php');
}
include 'db.php';
$order_type = mysqli_real_escape_string($link, $_POST['order_type']);
$order_priority = mysqli_real_escape_string($link, $_POST['order_priority']);

if ($order_type == 1) {
    $status = 2;
} else {
    $status = mysqli_real_escape_string($link, $_POST['status']);
}

$statusclarity = mysqli_real_escape_string($link, $_POST['statusclarity']);
$phone_n = mysqli_real_escape_string($link, $_POST['phone_n']);
$first_name = mysqli_real_escape_string($link, $_POST['first_name']);
$device_type = mysqli_real_escape_string($link, $_POST['device_type']);
$device_brand = mysqli_real_escape_string($link, $_POST['device_brand']);
$device_model = mysqli_real_escape_string($link, $_POST['device_model']);

$executor = mysqli_real_escape_string($link, $_POST['executor']);
$comment_order = mysqli_real_escape_string($link, str_replace(array("\r\n", "\r", "\n"), '<br>', $_POST['comment_order']));
//if ($executor == 1) {
//    if (strpos($comment_order, 'запас') !== false) {
//        $executor = 17;
//    }
//    if (strpos($comment_order, 'акб') !== false) {
//        $executor = 17;
//    }
//    if (strpos($comment_order, 'батар') !== false) {
//        $executor = 17;
//    }
//    if (strpos($comment_order, 'сгоре') !== false) {
//        $executor = 17;
//    }
//    if (strpos($comment_order, 'заря') !== false) {
//        $executor = 17;
//    }
//    if (strpos($comment_order, 'вкл') !== false) {
//        $executor = 17;
//    }
//    if (strpos($comment_order, 'аккум') !== false) {
//        $executor = 17;
//    }
//    if (strpos($comment_order, 'прокол') !== false) {
//        $executor = 19;
//    }
//    if (strpos($comment_order, 'спущ') !== false) {
//        $executor = 19;
//    }
//    if (strpos($comment_order, 'аква') !== false) {
//        $executor = 19;
//    }
//}



$visible = mysqli_real_escape_string($link, $_POST['visible']);
$color = mysqli_real_escape_string($link, $_POST['color']);
$user_id = $_SESSION['user']['id'];

$query = "INSERT INTO `admin_logic_gw`.`orders_list` (`livesklad_num`, `order_type`, `order_priority`, `status`, `statusclarity`, `phone_n`, `first_name`, `device_type`, `device_brand`, `device_model`, `data_open`, `data_close`, `creator`, `executor`, `comment_order`, `visible`, `color`, `recommendation`) VALUES ('GO', ?, ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIME(), '', ?, ?, ?, ?, ?, NULL)";
$stmt = mysqli_stmt_init($link);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_bind_param($stmt, "ssssssssssssss", $order_type, $order_priority, $status, $statusclarity, $phone_n, $first_name, $device_type, $device_brand, $device_model, $user_id, $executor, $comment_order, $visible, $color);
    mysqli_stmt_execute($stmt);
} else {
    echo "STMT error";
}
header('Location: m.last_entry.php');

?>