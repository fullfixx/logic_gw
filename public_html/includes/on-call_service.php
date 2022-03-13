<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: index.php');
}
require_once "db.php";
$entered_user = $_SESSION['user']['id'];
$active_rank = $_SESSION['user']['rank'];
$row_post = mysqli_fetch_array(mysqli_query($link,"SELECT count(*) FROM `chat` WHERE `from_user` = '$entered_user' AND `post_read` = 1"));
$count_post = $row_post[0];
$row_complete = mysqli_fetch_array(mysqli_query($link,"SELECT count(*) FROM `orders_list` WHERE `statusclarity` = 2"));
$count_complete = $row_complete[0];