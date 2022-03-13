<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: m.index.php');
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
$recommendations = $out['recommendation'];
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
            <a href="m.workarea.php" class="SaveScroll btn btn-warning btn-sm btn-block mt-1 mb-2">ВЕРНУТЬСЯ К СПИСКУ ЗАКАЗОВ</a>
<!--            <div style="text-align: right;">Вы работаете в "LOGIC: Мастерская"<br>под именем: <u>--><?//= $_SESSION['user']['full_name'] ?><!--</u> <small>(ID: --><?//= $_SESSION['user']['id'] ?><!--)</small> <a href="logout.php">Выход</a></div>-->

            <div class="row">
                <div class="col-sm-12">
                    <h5>Причина обращения (<strong>G<?php echo $order_num; ?>):</strong></h5>
                    <div class="w-100 rounded-top chat-info-primary"><strong><?php echo $comment_order['full_name']; ?> (ID: </strong> <?php echo $comment_order['creator']; ?>) <?php echo $comment_order['data_open']; ?></div>
                    <div class="chat-plate-primary rounded-bottom"><em><?php echo $comment_order['comment_order'] ?></em></div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-sm-12">
                    <!-- начало формы добавления комментариев к заказу -->
                    <div class="form-group">
                        <h5>Наши комментарии:</h5>
                        <form action="m.add_post.php" method="post">
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
                        <textarea name="chat_post" rows="3" class="form-control" placeholder="Комментарий к заказу"></textarea>
                        <input name="order_num" type="hidden" value="<?php echo $order_num; ?>">
                        <button type="submit" class="SaveScroll btn btn-success btn-lg btn-block mt-1">Ok</button>
                        </form>
                    </div>
                    <!-- конец формы добавления комментариев к заказу -->
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <!-- начало области с лентой комментариев к заказу -->
                    <?php
                    while($out = mysqli_fetch_assoc($order_posts))
                    {
                    ?>
                    <p><div class="w-100 rounded-top chat-info"><strong><?php echo $out['user_full_name']; ?></strong> (ID: <?php echo $out['user_id']; ?>) в <?php echo $out['time_create_post']; ?></div>
                    <div class="chat-plate rounded-bottom"><em><strong><?php echo $out['id'] == 1 ? "" : $out['full_name']."," ?> </strong><?php echo $out['chat_post']; ?></em></div>
                    <?php
                    }
                    ?>
                    <!-- конец области с лентой комментариев к заказу -->
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-sm-12">
                    <h5>Рекомендации для клиента:</h5>
                    <form action="m.workarea_upd_script.php" method="post">
                        <input type="hidden" name="order_num" value="<?php echo $order_num; ?>">
                        <textarea class="form-control" rows="3" name="recommendation"><?php echo $recommendations; ?></textarea>
                        <button type="submit" class="SaveScroll btn btn-danger btn-sm btn-block mt-1">Сохранить рекомендации</button>
                    </form>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-sm-12">
                    <h5>Чек-лист к заказу № G<?php echo $order_num; ?></h5>
                    <div class="form-group p-2 m-2 plate-checklist">
                    <!-- Форма чек-листа -->
                    <?php include 'form_m.checklist.php'; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>
<!-- Футер -->
<!-- Футер -->
<?php include 'footer.php'?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/select2.min.js"></script>
<script src="js/main.js"></script>
<script>
    var pos = localStorage.getItem('my-scroll-pos', 0);
    $(window).scrollTop(pos);
    $(document).ready(function() {
        $('.SaveScroll').on("click", function() {

            localStorage.setItem('my-scroll-pos', $(window).scrollTop());

        });

    });
</script>
</body>
</html>
