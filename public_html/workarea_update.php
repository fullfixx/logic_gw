<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: index.php');
}
require_once 'db.php';
$entered_user = $_SESSION['user']['id'];
$active_rank = $_SESSION['user']['rank'];
$order_num = mysqli_real_escape_string($link, $_GET['id']);
$row_post = mysqli_fetch_array(mysqli_query($link,"SELECT count(*) FROM `chat` WHERE `from_user` = '$entered_user' AND `post_read` = 1"));
$count_post = $row_post[0];
// Получаем из БД значения всех полей по текущему заказу для заполения ими апдейт-формы
$query = "SELECT * FROM `orders_list` 
    INNER JOIN `order_type_list` ON `orders_list`.`order_type`=`order_type_list`.`order_type_id`
    INNER JOIN `device_type_list` ON `orders_list`.`device_type`=`device_type_list`.`device_type_id`
    INNER JOIN `device_brand_list` ON `orders_list`.`device_brand`=`device_brand_list`.`device_brand_id`
    INNER JOIN `device_model_list` ON `orders_list`.`device_model`=`device_model_list`.`device_model_id`
    INNER JOIN `users` ON `orders_list`.`executor`=`users`.`id`
    INNER JOIN `status_list` ON `orders_list`.`status`=`status_list`.`status_id`
    INNER JOIN `statusclarity_list` ON `orders_list`.`statusclarity`=`statusclarity_list`.`statusclarity_id`
    WHERE `order_num` = ?";
$stmt = mysqli_stmt_init($link);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_bind_param($stmt, "s", $order_num);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    echo "STMT error";
}
$out = mysqli_fetch_assoc($result);
$executor_id = $out['id'];
if ($executor_id != $entered_user) {
    header('Location: workarea.php');
}
$full_date = date('d.m.Y h:i:s', strtotime($out['data_open']));

// Получаем из БД справочники для наполнения Select-ов апдейт-формы
$order_type = mysqli_query($link, "SELECT * FROM `order_type_list`");
$device_type = mysqli_query($link, "SELECT * FROM `device_type_list` ORDER BY `device_type_list`.`device_type_name` ASC");
$device_brand = mysqli_query($link, "SELECT * FROM `device_brand_list` ORDER BY `device_brand_list`.`device_brand_name` ASC");
$device_model = mysqli_query($link, "SELECT * FROM `device_model_list` ORDER BY `device_model_list`.`device_model_name` ASC");
$status = mysqli_query($link, "SELECT * FROM `status_list`");
$statusclarity = mysqli_query($link, "SELECT * FROM `statusclarity_list`");
$executor = mysqli_query($link, "SELECT * FROM `users` WHERE `rank` = '3'");
$users = mysqli_query($link, "SELECT * FROM `users` WHERE `user_visible`='1' ORDER BY `users`.`id` ASC");

// Получаем главный комментарий к текущему заказу
$comment_order = mysqli_query($link, "SELECT * FROM `orders_list` INNER JOIN `users` ON `orders_list`.`creator`=`users`.`id` WHERE `order_num`='$order_num'");
$comment_order = mysqli_fetch_assoc($comment_order);

// Получаем чат к текущему заказу
$order_posts = mysqli_query($link, "SELECT * FROM `chat` INNER JOIN `users` ON `chat`.`from_user` = `users`.`id` WHERE `order_num`='$order_num' ORDER BY `chat`.`time_create_post` DESC");

// Получаем перечень товаров и услуг к текущему заказу
$order_gas = mysqli_query($link, "SELECT * FROM `gas` WHERE `order_num`='$order_num' ORDER BY `gas`.`gas_time_add` ASC");

// Получаем сумму оплаты за товары и услуги к текущему заказу
$amount = mysqli_query($link, "SELECT SUM(`amount`) AS `sum` FROM `gas` WHERE `order_num`='$order_num'");
$amount = mysqli_fetch_assoc($amount);

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/bootstrap.min.css" >
    <link rel="stylesheet" href="css/select2.min.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Logic GW</title>
</head>
<body>

<header></header>
<main>
    <div class="archive-bg">
        <div class="container-fluid">

            <!-- Панель управления (верхняя) -->
                <a class="btn btn-secondary btn-sm my-1" role="button" href="workarea.php">Список заказов</a>


            <div style="text-align: right;">Вы работаете в "LOGIC: Мастерская" под именем: <mark><?= $_SESSION['user']['full_name'] ?></mark> <small>(ID: <?= $_SESSION['user']['id'] ?>)</small> <a class="btn btn-danger btn-sm my-1 mr-1 ml-2 float-right" role="button" href="logout.php">Выход</a></div>

            <!-- Блок №1 из 2-х столбцов -->
            <div class="row p-3">

                <!-- Столбец левый -->
                <div class="col-sm-4">
                    <h5>Чек-лист к заказу № G<?php echo $order_num; ?></h5>
                    <div class="row">
                        <div class="col-sm-12 plate-checklist p-3">
                            <!-- Форма чек-листа -->
                            <?php include 'form_checklist.php'; ?>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-12">
                            <h5>Причина обращения со слов клиента:</h5>
                            <div class="w-100 rounded-top chat-info-primary"><strong><?php echo $comment_order['full_name']; ?> (ID: </strong> <?php echo $comment_order['creator']; ?>) <?php echo $comment_order['data_open']; ?></div>
                            <div class="chat-plate-primary rounded-bottom"><em><?php echo $comment_order['comment_order'] ?></em></div>
                        </div>
                    </div>
                    <div class="row mt-3 mb-5">
                        <div class="col-sm-12">
                            <h5>Рекомендации для клиента:</h5>
                            <form action="workarea_upd_script.php" method="post">
                                <input type="hidden" name="order_num" value="<?php echo $order_num; ?>">
                                <textarea class="form-control" name="recommendation"><?php echo $out['recommendation']; ?></textarea>
                                <button class="btn btn-danger mt-3 mb-3" type="submit">Сохранить рекомендации</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Столбец правый -->
                <div class="col-sm-8 px-5">
                    <h5>Комментарии:</h5>
                    <!-- Модуль чата -->
                    <!-- начало формы добавления комментариев к заказу -->
                    <form action="add_post.php" method="post">
                        <div class="form-row">
                            <div class="col-12">
                                <textarea name="chat_post" cols="60" rows="2" placeholder="Комментариев много не бывает!" id="mess" onkeydown="keyDown(event)" onkeyup="keyUp(event)"></textarea>
                                <input name="order_num" type="hidden" value="<?php echo $order_num; ?>">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-4">
                                <select class="form-control" type="number" name="from_user">
                                    <option value="1">всем сотрудникам</option>
                                    <?php
                                    while($out = mysqli_fetch_assoc($users))
                                    {
                                        ?>
                                        <option value="<?php echo $out['id']; ?>"><?php echo $out['full_name']; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-4">
                                <button type="submit" class="btn btn-success ml-2" id="go">Ok</button>
                            </div>
                            <div class="col-4">&nbsp;</div>
                        </div>
                    </form>
                    <!-- конец формы добавления комментариев к заказу -->
                    <!-- начало области с лентой комментариев к заказу -->
                    <p>
                    <?php
                    while($out = mysqli_fetch_assoc($order_posts))
                    {
                        ?>
                        <p><div class="w-75 rounded-top chat-info"><strong><?php echo $out['user_full_name']; ?></strong> (ID: <?php echo $out['user_id']; ?>) в <?php echo $out['time_create_post']; ?></div>
                        <div class="chat-plate rounded-bottom"><em><strong><?php echo $out['id'] == 1 ? "" : $out['full_name']."," ?> </strong><?php echo $out['chat_post']; ?></em></div>
                        <?php
                    }
                    ?>
                    <!-- конец области с лентой комментариев к заказу -->
                </div>

            </div>

        </div>
    </div>
</main>
<div class="navbar-fixed-bottom row-fluid">
    <div class="navbar-inner">
        <div class="container-fluid">
            <footer>
                <div class="text-white bg-dark my-3">
                    <div class="card-footer">Рабочая среда "LOGIC GW" для Parts GoWheel.Ru</div>
                    <div class="card-body">
                        <h5 class="card-title"></h5>
                        <p class="card-text">Создание заказ-нарядов и администрирование. Находится в разработке</p>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/select2.min.js"></script>
<script src="js/main.js"></script>
<script>
    function keyDown(e){
        if(e.keyCode == 17)
            ctrl = true;
        else if(e.keyCode == 13 && ctrl)
            document.getElementById("go").click();
    }
    function keyUp(e){
        if(e.keyCode == 17)
            ctrl = false;
    }


</script>
</body>
</html>
