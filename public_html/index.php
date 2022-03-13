<?php
session_start();
if ($_SESSION['user']) {
    header('Location: towork.php');
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/bootstrap.min.css" >
    <link rel="stylesheet" href="css/style.css">
    <title>Logic GW</title>
</head>
<body>

<!-- Форма авторизации -->

<div class="container-fluid">



    <div class="row mt-sm-5">
        <div class="col-sm-4">
        </div>
        <div class="col-sm-4">
            <form action="signin.php" method="post">
                <label>Логин</label>
                <input type="text" class="form-control" autocomplete="off" name="login" placeholder="Введите свой логин" maxlength="">
                <label>Пароль</label>
                <input type="password" class="form-control" name="password" placeholder="Введите пароль">
                <button class="btn btn-primary mt-3 btn-lg btn-block" type="submit">Войти</button>
                <p>
                    У вас нет аккаунта? - <a href="register.php">зарегистрируйтесь</a>!
                </p>
                <?php
                    if ($_SESSION['message']) {
                        echo '<p class="msg"> ' . $_SESSION['message'] . ' </p>';
                    }
                    unset($_SESSION['message']);
                ?>
            </form>
        </div>
        <div class="col-sm-4">
        </div>
    </div>

</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/main.js"></script>

</body>
</html>