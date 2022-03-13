<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: index.php');
}
require_once 'db.php';
$post_id = $_GET['post_id'];

$query = "UPDATE `admin_logic_gw`.`chat` SET `post_read`='2' WHERE `chat`.`post_id`=?";
$stmt = mysqli_stmt_init($link);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_bind_param($stmt, "s", $post_id);
    mysqli_stmt_execute($stmt);
} else {
    echo "STMT error";
}
header('Location: chat_personal.php');