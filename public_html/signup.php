<?php
session_start();
require_once 'db.php';

$full_name = mysqli_real_escape_string($link, $_POST['full_name']);
$login = mysqli_real_escape_string($link, $_POST['login']);
$email = mysqli_real_escape_string($link, $_POST['email']);
$password = mysqli_real_escape_string($link, $_POST['password']);
$password_confirm = mysqli_real_escape_string($link, $_POST['password_confirm']);

if ($password === $password_confirm) {

    $path = 'uploads/' . time() . $_FILES['avatar']['name'];
    if (!move_uploaded_file($_FILES['avatar']['tmp_name'], '../' . $path)) {
        $_SESSION['message'] = 'Ошибка при загрузке сообщения';
        header('Location: register.php');
    }

    $password = md5($password);

    $query = "INSERT INTO `admin_logic_gw`.`users` (`id`, `full_name`, `login`, `email`, `password`, `avatar`, `user_visible`, `rank`) VALUES (NULL, ?, ?, ?, ?, ?, '1', '1')";
    $stmt = mysqli_stmt_init($link);
    if (mysqli_stmt_prepare($stmt, $query)) {
        mysqli_stmt_bind_param($stmt, "sssss", $full_name, $login, $email, $password, $path);
        mysqli_stmt_execute($stmt);
    } else {
        echo "STMT error";
    }

    $_SESSION['message'] = 'Регистрация прошла успешно!';
    header('Location: index.php');


} else {
    $_SESSION['message'] = 'Пароли не совпадают';
    header('Location: register.php');
}

?>
