<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: m.index.php');
}
require_once 'db.php';

$chat_post = mysqli_real_escape_string($link, str_replace(array("\r\n", "\r", "\n"), '<br>', $_POST['chat_post']));
$order_num = mysqli_real_escape_string($link, $_POST['order_num']);
$from_user = mysqli_real_escape_string($link, $_POST['from_user']);
$user_full_name = $_SESSION['user']['full_name'];
$user_id = $_SESSION['user']['id'];

$query = "INSERT INTO `admin_logic_gw`.`chat` (`order_num`, `time_create_post`, `chat_post`, `user_full_name`, `user_id`, `from_user`, `post_read`) VALUES (?, CURRENT_TIME, ?, ?, ?, ?, '1')";
$stmt = mysqli_stmt_init($link);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_bind_param($stmt, "sssss", $order_num, $chat_post, $user_full_name, $user_id, $from_user);
    mysqli_stmt_execute($stmt);
} else {
    echo "STMT error";
}
$entered_user = $_SESSION['user']['rank'];
if ($entered_user == 2) {
    header('Location: m.update.php?id='.$order_num);
} else {
    header('Location: m.workarea_update.php?id='.$order_num);
}

