<?php
session_start();
require_once 'db.php';

$id = "19";
$password = "yui0000";

$password = md5($password);
$query = "UPDATE `admin_logic_gw`.`users` SET `password` = ? WHERE `users`.`id` = ?";
$stmt = mysqli_stmt_init($link);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_bind_param($stmt, "ss", $password, $id);
    mysqli_stmt_execute($stmt);
} else {
    echo "STMT error";
}

// Макаров 17 "qaz000"
// Мазуркевич 18 "poi0000"
// Шахов 19 "yui0000"
// Александр АКБ "aleX20xxx"
// Рашид "Rash2507"
?>


