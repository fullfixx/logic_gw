<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: index.php');
}
if ($user_id == 1) {
    echo "<button type=\"submit\" title=\"Сменить статус на 'Выдан', сформировать гарантийный талон и убрать заказ в архив\" class=\"btn btn-dark btn-sm mt-2 float-sm-right\" disabled>Выдать заказ</button>";
} elseif ($status_id != 1) {
    echo "<button type=\"submit\" title=\"Сменить статус на 'Выдан', сформировать гарантийный талон и убрать заказ в архив\" class=\"btn btn-dark btn-sm mt-2 float-sm-right\" disabled>Выдать заказ</button>";
} else {
    echo "<button type=\"submit\" title=\"Сменить статус на 'Выдан', сформировать гарантийный талон и убрать заказ в архив\" class=\"btn btn-success btn-sm mt-2 float-sm-right\">Выдать заказ</button>";
}
?>