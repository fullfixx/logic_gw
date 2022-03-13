<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: index.php');
}
if ($user_id == 1) {
    echo "<button type=\"submit\" title=\"Сменить статус на 'Готов'\" class=\"btn btn-dark btn-sm mt-2 mr-1 float-sm-right\" disabled>Заказ готов!</button>";
} elseif ($livesklad_num == 'GO') {
    echo "<button type=\"submit\" title=\"Сменить статус на 'Готов'\" class=\"btn btn-dark btn-sm mt-2 mr-1 float-sm-right\" disabled>Заказ готов!</button>";
} elseif ($status_id == 1) {
    echo "<button type=\"submit\" title=\"Сменить статус на 'Готов'\" class=\"btn btn-outline-success btn-sm mt-2 mr-1 float-sm-right\">Заказ готов!</button>";
} else {
    echo "<button type=\"submit\" title=\"Сменить статус на 'Готов'\" class=\"btn btn-success btn-sm mt-2 mr-1 float-sm-right\">Заказ готов!</button>";
}
?>