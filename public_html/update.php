<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: index.php');
}
require_once "on-call_service.php";
if ($active_rank > 2) {
    header('Location: workarea.php');
}
$order_num = mysqli_real_escape_string($link, $_GET['id']);
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

$user_id = $out['id'];
$full_name = $out['full_name'];
$order_num = $out['order_num'];
$livesklad_num = $out['livesklad_num'];
$status_id = $out['status'];
$f_type = $out['device_type_id'];
$f_brand = $out['device_brand_id'];
$f_model = $out['device_model_id'];
$device_class = mysqli_query($link, "SELECT * FROM `device_class_list` INNER JOIN `price_class` ON `device_class_list`.`device_class_num`=`price_class`.`class_id` WHERE `device_type` = '$f_type' AND `device_brand` = '$f_brand' AND `device_model` = '$f_model'");
$device_class = mysqli_fetch_assoc($device_class);
$class_price = $device_class['price'];

$full_date = date('d.m.Y h:i:s', strtotime($out['data_open']));

// Получаем справочники
$order_type = mysqli_query($link, "SELECT * FROM `order_type_list`");
$device_type = mysqli_query($link, "SELECT * FROM `device_type_list` ORDER BY `device_type_list`.`device_type_name` ASC");
$device_brand = mysqli_query($link, "SELECT * FROM `device_brand_list` ORDER BY `device_brand_list`.`device_brand_name` ASC");
$device_model = mysqli_query($link, "SELECT * FROM `device_model_list` ORDER BY `device_model_list`.`device_model_name` ASC");
$status = mysqli_query($link, "SELECT * FROM `status_list`");
$statusclarity = mysqli_query($link, "SELECT * FROM `statusclarity_list`");
$executor = mysqli_query($link, "SELECT * FROM `users` WHERE (`rank` = 3 AND `user_visible` = 1) OR (`rank` = 2 AND `user_visible` = 1)");
$users = mysqli_query($link, "SELECT * FROM `users` WHERE `user_visible`='1' ORDER BY `users`.`id` ASC");

// Получаем главный комментарий к текущему заказу
$comment_order = mysqli_query($link, "SELECT * FROM `orders_list` INNER JOIN `users` ON `orders_list`.`creator`=`users`.`id` WHERE `order_num`='$order_num'");
$comment_order = mysqli_fetch_assoc($comment_order);

// Получаем чат к текущему заказу
$order_posts = mysqli_query($link, "SELECT * FROM `chat` INNER JOIN `users` ON `chat`.`from_user` = `users`.`id` WHERE `order_num`='$order_num' ORDER BY `chat`.`time_create_post` DESC");

// Получаем перечень товаров и услуг к текущему заказу
$order_gas_s = mysqli_query($link, "SELECT * FROM `gas` INNER JOIN `users` ON `gas`.`executor`=`users`.`id` WHERE `order_num`='$order_num' AND `gas_type`='s' ORDER BY `gas`.`gas_time_add` ASC");
$order_gas_p = mysqli_query($link, "SELECT * FROM `gas` INNER JOIN `users` ON `gas`.`executor`=`users`.`id` WHERE `order_num`='$order_num' AND `gas_type`='p' ORDER BY `gas`.`gas_time_add` ASC");

// Получаем сумму оплаты за товары и услуги к текущему заказу
$amount_s = mysqli_query($link, "SELECT SUM(`amount`) AS `sum` FROM `gas` WHERE `order_num`='$order_num' AND `gas_type` = 's'");
$amount_s = mysqli_fetch_assoc($amount_s);
$amount_p = mysqli_query($link, "SELECT SUM(`amount`) AS `sum` FROM `gas` WHERE `order_num`='$order_num' AND `gas_type` = 'p'");
$amount_p = mysqli_fetch_assoc($amount_p);


if (!empty($_GET['search_ss'])) {
    $search_ss = mysqli_real_escape_string($link, $_GET['search_ss']);
    $search_ss_result = mysqli_query($link, "SELECT * FROM `serviceset_list` 
INNER JOIN `serviceset_names_list` ON `serviceset_list`.`serviceset_name`=`serviceset_names_list`.`serviceset_names_id` 
INNER JOIN `service_list` ON `serviceset_list`.`serviceset_element`=`service_list`.`service_id` 
INNER JOIN `service_names_list` ON `service_list`.`service_name`=`service_names_list`.`service_names_id` 
WHERE `serviceset_names_name` LIKE '%$search_ss%' AND `serviceset_list`.`device_type` = '$f_type' AND `serviceset_list`.`device_brand` = '$f_brand' AND `serviceset_list`.`device_model` = '$f_model'");
}
if (!empty($_GET['search_s'])) {
    $search_s = mysqli_real_escape_string($link, $_GET['search_s']);
    $search_s_result = mysqli_query($link, "SELECT * FROM `service_list` 
INNER JOIN `service_names_list` ON `service_list`.`service_name`=`service_names_list`.`service_names_id` 
WHERE `service_names_name` LIKE '%$search_s%' AND `service_list`.`device_type` = '$f_type' AND `service_list`.`device_brand` = '$f_brand' AND `service_list`.`device_model` = '$f_model'");
}
if (!empty($_GET['search_sp'])) {
    $search_sp = mysqli_real_escape_string($link, $_GET['search_sp']);
    $search_sp_result = mysqli_query($link, "SELECT * FROM `spareparts_stock` 
INNER JOIN `spareparts` ON `spareparts_stock`.`sparepart`=`spareparts`.`sparepart_id` 
INNER JOIN `spareparts_brands_list` ON `spareparts`.`sparepart_brand`=`spareparts_brands_list`.`spareparts_brands_id` 
INNER JOIN `spareparts_names_list` ON `spareparts`.`sparepart_name`=`spareparts_names_list`.`spareparts_names_id` 
WHERE `sparepart_num` LIKE '%$search_sp%' OR `spareparts_names_name` LIKE '%$search_sp%'");
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/bootstrap.min.css" >
    <link rel="stylesheet" href="css/select2.min.css">
    <link rel="stylesheet" href="css/style.css">
    <title>G<?php echo $order_num; ?> (<?php echo $out['device_model_name']; ?>)</title>
</head>
<body>

<header></header>
<main>
    <div class="archive-bg">
    <div class="container-fluid">

        <!-- Панель управления (верхняя) -->
        <?php include 'control_panel_top.php'?>

        <!-- Блок №1 из 3-х столбцов -->
        <div class="row p-3">

            <!-- Столбец левый -->
            <div class="col-sm-2">
                <h5>Параметры заказа № <mark><?php echo $order_num; ?></mark></h5>
                <div class="row">
                    <div class="col-sm-12 plate-update px-3">
                        <!-- Форма update -->
                        <?php include 'form_update.php'; ?>
                    </div>
                </div>
            </div>

            <!-- Столбец средний -->
            <div class="col-sm-7 px-3">

                <!-- Панель добавления товаров и услуг -->
                <?php include 'gas_add_panel.php'; ?>

                <?php if ($user_id == 1) {
                    ?>
                    <span style="float: right; color: darkred; font-size: 0.9em;">Укажите мастера-исполнителя!</span><br>
                <?
                } ?>
                <?php if ($livesklad_num == 'GO') {
                    ?>
                    <span style="float: right; color: darkred; font-size: 0.9em;">Внесите номер Live-Sklad!</span><br>
                    <?
                } ?>
                <?php if ($status_id != 1) {
                    ?>
                    <span style="float: right; color: darkred; font-size: 0.9em;">Выдать можно только готовый заказ!</span><br>
                    <?
                } ?>

                <form action="order_complete.php" method="post">
                    <input type="hidden" value="<?php echo $order_num; ?>" name="order_num">
                    <input type="hidden" value="<?php echo $amount['sum']; ?>" name="sum">
                    <?php include "chekingfor_order_complete.php"; ?>
                </form>
                <form action="changeto_ready.php" method="post">
                    <input type="hidden" value="<?php echo $order_num; ?>" name="order_num">
                    <?php include "checkingfor_order_ready.php"; ?>
                </form>
                <a class="btn btn-info btn-sm my-1 mr-1" role="button" href="m.receipt.php?id=<?php echo $order_num; ?>">Квитанция (web)</a>
                <a class="btn btn-info btn-sm my-1 mr-1" role="button" href="m.ticket.php?id=<?php echo $order_num; ?>">Талон записи (web)</a>
            </div>


            <!-- Столбец правый -->
            <div class="col-sm-3">
                <h5>Комментарии:</h5>
                <!-- Модуль чата -->
                <?php include 'chat_module.php'; ?>
            </div>

        </div>

    </div>
    </div>
</main>
<!-- Футер -->
<?php include 'footer.php'?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/select2.min.js"></script>
<script src="js/js.cookie-2.2.1.min.js"></script>
<script src="js/main.js"></script>
<script>
    var pos = localStorage.getItem('my-scroll-pos', 0);
    $(window).scrollTop(pos);

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

    $(document).ready(function() {
        $('.SaveScroll').on("click", function() {

            localStorage.setItem('my-scroll-pos', $(window).scrollTop());

        });

    });
</script>
</body>
</html>