<?php
session_start();
require_once 'db.php';

$login = mysqli_real_escape_string($link, $_POST['login']);
$password = md5($_POST['password']);
$password = mysqli_real_escape_string($link, $password);

$query = "SELECT * FROM `users` WHERE `login` = ? AND `password` = ?";
$stmt = mysqli_stmt_init($link);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_bind_param($stmt, "ss", $login, $password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    echo "STMT error";
}


    if (mysqli_num_rows($result) > 0) {

        $row = mysqli_fetch_assoc($result);

        $_SESSION['user'] = [
            "id" => $row['id'],
            "full_name" => $row['full_name'],
            "avatar" => $row['avatar'],
            "email" => $row['email'],
            "rank" => $row['rank']
        ];

        if ($row['rank'] == 2) {
            header('Location: towork.php');
        } else {
            header('Location: workarea.php');
        }

    } else {
        $_SESSION['message'] = 'Не верный логин или пароль';
        header('Location: index.php');
    }
    ?>

<pre>
    <?php
    print_r($result);
    print_r($row);
    ?>
</pre>
