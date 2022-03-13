<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: index.php');
}
require_once 'db.php';
$order_num = mysqli_real_escape_string($link, $_POST['order_num']);
$sum = mysqli_real_escape_string($link, $_POST['sum']);

$q_orders_list = "SELECT * FROM `orders_list` 
    INNER JOIN `order_type_list` ON `orders_list`.`order_type`=`order_type_list`.`order_type_id`
    INNER JOIN `device_type_list` ON `orders_list`.`device_type`=`device_type_list`.`device_type_id`
    INNER JOIN `device_brand_list` ON `orders_list`.`device_brand`=`device_brand_list`.`device_brand_id`
    INNER JOIN `device_model_list` ON `orders_list`.`device_model`=`device_model_list`.`device_model_id`
    INNER JOIN `users` ON `orders_list`.`executor`=`users`.`id`
    INNER JOIN `status_list` ON `orders_list`.`status`=`status_list`.`status_id`
    INNER JOIN `statusclarity_list` ON `orders_list`.`statusclarity`=`statusclarity_list`.`statusclarity_id`
    WHERE `order_num`='$order_num'";
$out = mysqli_query($link, "$q_orders_list");
$out = mysqli_fetch_assoc($out);

if ($out['status'] == 1) {
    $q_order_complete = "UPDATE `admin_logic_gw`.`orders_list` SET `data_close` = CURRENT_TIME(), `status` = 5, `statusclarity` = 1, `visible` = 2 WHERE `orders_list`.`order_num` = ?";
} else {
    header('Location: error_order_status.php?id='.$order_num);
}

$q_gas_sold = "UPDATE `admin_logic_gw`.`gas` SET `gas_sold` ='2', `gas_time_sold` = CURRENT_TIME() WHERE `gas`.`order_num` = '$order_num'";
$query_s = "SELECT * FROM `gas` WHERE `gas_type`='s' AND `order_num`='$order_num' ORDER BY `gas`.`gas_time_add` ASC";
$query_p = "SELECT * FROM `gas` WHERE `gas_type`='p' AND `order_num`='$order_num' ORDER BY `gas`.`gas_time_add` ASC";

$stmt_order_complete = mysqli_stmt_init($link);
if (mysqli_stmt_prepare($stmt_order_complete, $q_order_complete)) {
    mysqli_stmt_bind_param($stmt_order_complete, "s", $order_num);
    mysqli_stmt_execute($stmt_order_complete);
} else {
    echo "STMT error";
}

mysqli_query($link, "$q_gas_sold");

$first_name = $out['first_name'];
$phone_n = $out['phone_n'];
$data_close = $out['data_close'];
$device_type_name = $out['device_type_name'];
$device_brand_name = $out['device_brand_name'];
$device_model_name = $out['device_model_name'];
$comment_order = $out['comment_order'];
$recommendation = $out['recommendation'];

// Получаем сумму оплаты за товары и услуги к текущему заказу
$amount_s = mysqli_query($link, "SELECT SUM(`amount`) AS `sum` FROM `gas` WHERE `order_num`='$order_num' AND `gas_type` = 's'");
$amount_s = mysqli_fetch_assoc($amount_s);
$amount_p = mysqli_query($link, "SELECT SUM(`amount`) AS `sum` FROM `gas` WHERE `order_num`='$order_num' AND `gas_type` = 'p'");
$amount_p = mysqli_fetch_assoc($amount_p);

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/bootstrap.min.css" >
</head>
<body>
<header></header>
<main>
<input type="button" class="btn btn-danger btn-lg btn-block" value="PDF Print" onclick="PrintElem('#mydiv')">
<a class="btn btn-primary btn-lg btn-block" role="button" href="towork.php">Вернуться в Logic GW</a>
<div id="mydiv" style="">


<table style="width: 1002px; border-collapse: collapse; height: 1075px;" border="0">
    <tbody>
    <tr style="height: 597px; border-bottom: 1px dotted black;">
        <td style="width: 997.6470336914063px; height: 597px;">
            <p class="MsoNormal" style="margin-top: 6.0pt;"><span style="font-size: 18pt;"><strong>Гарантийный талон № <?php echo $order_num; ?> от <?php echo $data_close; ?></strong></span></p>
            <hr />
            <p class="MsoNormal" style="margin-top: 6pt; line-height: 1;"><span style="font-size: 14pt;">
                    <strong>Исполнитель</strong>: Gowheel-Service &#128386; г.Санкт-Петербург, ул.Кронверская, 23П &#9990; +7 (812) 243-1-872<br />
                    <strong>Заказчик</strong>: <?php echo $first_name; ?> &#9990; <?php echo $phone_n; ?><br />
                    <strong>Устройство</strong>: <?php echo $device_type_name; ?> <?php echo $device_brand_name; ?> <?php echo $device_model_name; ?><br />
                    <strong>Причина обращения</strong>: <?php echo $comment_order; ?> </span></p>

            <table style="width: 100%; border-collapse: collapse; border-style: none; height: 88px;" border="1">
                <thead>
                <tr style="height: 18px;">
                    <th style="height: 18px; padding: 0px 8px; text-align: center;"><span style="font-size: 12pt;"><strong>№</strong></span></th>
                    <th style="width: 100%; height: 18px; padding: 5px 8px; text-align: left;"><span style="font-size: 12pt;"><strong>Наименование работ и услуг</strong></span></th>
                    <th style="height: 18px; padding: 5px 8px; text-align: right;"><span style="font-size: 12pt;"><strong>Н-ч</strong></span></th>
                    <th style="height: 18px; padding: 5px 8px; text-align: right;"><span style="font-size: 12pt;"><strong>Цена</strong></span></th>
                    <th style="height: 18px; padding: 5px 8px; text-align: right;"><span style="font-size: 12pt;"><strong>Кол.во</strong></span></th>
                    <th style="height: 18px; padding: 5px 8px; text-align: right;"><span style="font-size: 12pt;"><strong>Скидка</strong></span></th>
                    <th style="height: 18px; padding: 5px 8px 5px 25px; text-align: right;"><span style="font-size: 12pt;"><strong>Сумма</strong></span></th>
                </tr>
                </thead>
                <tbody>

        <?php
        if ($result_s = mysqli_query($link, $query_s)) {
            for($i=1; $out_s = mysqli_fetch_assoc($result_s); $i++) {
                ?>
                <tr id="work-product" style="height: 17px;">
                    <td style="height: 16px; padding: 0px 8px; text-align: center;"><span style="font-size: 12pt;"><?php echo $i; ?></span></td>
                    <td style="width: 100%; height: 16px; padding: 0px 8px;"><span style="font-size: 12pt;"><?php echo $out_s['gas_name']; ?></span></td>
                    <td style="height: 18px; padding: 0px 8px; text-align: right;"><span style="font-size: 12pt;"><?php echo $out_s['gas_time_rate']; ?></span></td>
                    <td style="height: 16px; padding: 0px 8px; text-align: right;"><span style="font-size: 12pt;"><?php echo $out_s['price']; ?></span></td>
                    <td style="height: 16px; padding: 0px 8px; text-align: right;"><span style="font-size: 12pt;"><?php echo $out_s['gas_qty']; ?></span></td>
                    <td style="height: 16px; padding: 0px 8px; text-align: right;"><span style="font-size: 12pt;"><?php echo $out_s['discont']; ?>%</span></td>
                    <td style="height: 16px; padding: 0px 8px; text-align: right;"><span style="font-size: 12pt;"><?php echo $out_s['amount']; ?></span></td>
                </tr>
                <?php
            }
            mysqli_free_result($result_s);
        }
        ?>

                <tr style="height: 18px;">
                    <td style="height: 18px; text-align: right; border-style: none;">&nbsp;</td>
                    <td style="height: 18px; text-align: right; border-style: none;">&nbsp;</td>
                    <td style="height: 18px; padding: 0px 8px; text-align: right;" colspan="4"><span style="font-size: 12pt;">Итого за работы и услуги:</span></td>
                    <td style="height: 18px; padding: 0px 8px; text-align: right;"><span style="font-size: 12pt;"><?php echo $amount_s['sum']; ?></span></td>
                </tr>
                </tbody>
            </table>
            <p>

            <p>
            <table style="width: 100%; border-collapse: collapse; border-style: none; height: 88px;" border="1">
                <thead>
                <tr style="height: 18px;">
                    <th style="height: 18px; padding: 0px 8px; text-align: center;"><span style="font-size: 12pt;"><strong>№</strong></span></th>
                    <th style="height: 18px; padding: 0px 8px; text-align: center;"><span style="font-size: 12pt;"><strong>Кат.номер</strong></span></th>
                    <th style="width: 100%; height: 18px; padding: 5px 8px; text-align: left;"><span style="font-size: 12pt;"><strong>Наименование запчасти</strong></span></th>
                    <th style="height: 18px; padding: 5px 8px; text-align: right;"><span style="font-size: 12pt;"><strong>Цена</strong></span></th>
                    <th style="height: 18px; padding: 5px 8px; text-align: right;"><span style="font-size: 12pt;"><strong>Кол.во</strong></span></th>
                    <th style="height: 18px; padding: 5px 8px; text-align: right;"><span style="font-size: 12pt;"><strong>Скидка</strong></span></th>
                    <th style="height: 18px; padding: 5px 8px 5px 25px; text-align: right;"><span style="font-size: 12pt;"><strong>Сумма</strong></span></th>
                </tr>
                </thead>
                <tbody>

                <?php
                if ($result_p = mysqli_query($link, $query_p)) {
                    for($i=1; $out_p = mysqli_fetch_assoc($result_p); $i++) {
                        ?>
                        <tr id="work-product" style="height: 17px;">
                            <td style="height: 16px; padding: 0px 8px; text-align: center;"><span style="font-size: 12pt;"><?php echo $i; ?></span></td>
                            <td style="height: 16px; padding: 0px 8px; text-align: center;"><span style="font-size: 12pt;"><?php echo $out_p['gas_num']; ?></span></td>
                            <td style="width: 100%; height: 16px; padding: 0px 8px;"><span style="font-size: 12pt;"><?php echo $out_p['gas_name']; ?></span></td>
                            <td style="height: 16px; padding: 0px 8px; text-align: right;"><span style="font-size: 12pt;"><?php echo $out_p['price']; ?></span></td>
                            <td style="height: 16px; padding: 0px 8px; text-align: right;"><span style="font-size: 12pt;"><?php echo $out_p['gas_qty']; ?></span></td>
                            <td style="height: 16px; padding: 0px 8px; text-align: right;"><span style="font-size: 12pt;"><?php echo $out_p['discont']; ?>%</span></td>
                            <td style="height: 16px; padding: 0px 8px; text-align: right;"><span style="font-size: 12pt;"><?php echo $out_p['amount']; ?></span></td>
                        </tr>
                        <?php
                    }
                    mysqli_free_result($result_p);
                }
                ?>

                <tr style="height: 18px;">
                    <td style="height: 18px; text-align: right; border-style: none;">&nbsp;</td>
                    <td style="height: 18px; text-align: right; border-style: none;">&nbsp;</td>
                    <td style="height: 18px; text-align: right; border-style: none;">&nbsp;</td>
                    <td style="height: 18px; padding: 0px 8px; text-align: right;" colspan="3"><span style="font-size: 12pt;">Итого за запчасти:</span></td>
                    <td style="height: 18px; padding: 0px 8px; text-align: right;"><span style="font-size: 12pt;"><?php echo $amount_p['sum']; ?></span></td>
                </tr>
                </tbody>
            </table>
            <p>
            <p style="font-size: 18pt; text-align: right;">Итого за заказ: <strong><?php echo number_format(($amount_s['sum']+$amount_p['sum']), "2", ".", " "); ?> руб.</strong></p>
            <strong>РЕКОМЕНДАЦИИ</strong>:&nbsp;<span style="font-size: 11pt;"><?php echo $recommendation; ?></span>
            <p class="MsoNormal" style="margin-top: 6pt; line-height: 0.9;"><span style="font-size: 11pt;">1. Гарантийный ремонт распространяется только на неисправности, возникшие в результате произведённого ремонта.<br />2. Гарантия устанавливается на каждый товар или услугу отдельно.<br />3. Гарантийный ремонт не распространяется на аксессуары поставляемые с оборудованием.<br />4. Сервисный центр может отказать в гарантийном ремонте в следующих случаях:<br />&nbsp;-&nbsp; нарушения сохранности гарантийных пломб.<br />&nbsp;-&nbsp; использования оборудования вместе с аксессуарами, не одобренных предприятием изготовителя.<br />&nbsp;-&nbsp; повреждений вызванных нарушением условий эксплуатации и хранения;<br />&nbsp;-&nbsp; несанкционированного вмешательства;<br />&nbsp;-&nbsp; наличия механических, химических или тепловых воздействий;<br />&nbsp;-&nbsp; стихийными бедствиями, водой.<br />Данные услуги по желанию клиента могут быть выполнены за отдельную плату.<br />5. Устройство (изделия) проверено во всех режимах.</span></p>
            <hr />
            <table style="height: 15px; width: 100%; border-style: none;" border="0">
                <tbody>
                <tr style="height: 15px;">
                    <td style="width: 50%; height: 15px;">Исполнитель: _______________ / <?php echo $_SESSION['user']['full_name'] ?>/</td>
                    <td style="width: 50%; height: 15px; text-align: right;">
                        <p>________________ / <?php echo $first_name; ?>/<br /><span style="font-size: 12pt;">С условием гарантии ознакомлен и согласен.&nbsp;</span><span style="font-size: 12pt;">Электротранспорт, комплектующие получил, претензий не имею</span></p>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>

    <tr style="height: 597px;">
        <td style="width: 997.6470336914063px; height: 597px;">
            <p class="MsoNormal" style="margin-top: 6.0pt;"><span style="font-size: 18pt;"><strong>Гарантийный талон № <?php echo $order_num; ?> от <?php echo $data_close; ?></strong></span></p>
            <hr />
            <p class="MsoNormal" style="margin-top: 6pt; line-height: 1;"><span style="font-size: 14pt;">
                        <strong>Исполнитель</strong>: Gowheel-Service &#128386; г.Санкт-Петербург, ул.Кронверская, 23П &#9990; +7 (812) 243-1-872<br />
                        <strong>Заказчик</strong>: <?php echo $first_name; ?> &#9990; <?php echo $phone_n; ?><br />
                        <strong>Устройство</strong>: <?php echo $device_type_name; ?> <?php echo $device_brand_name; ?> <?php echo $device_model_name; ?><br />
                        <strong>Причина обращения</strong>: <?php echo $comment_order; ?> </span></p>

            <table style="width: 100%; border-collapse: collapse; border-style: none; height: 88px;" border="1">
                <thead>
                <tr style="height: 18px;">
                    <th style="height: 18px; padding: 0px 8px; text-align: center;"><span style="font-size: 12pt;"><strong>№</strong></span></th>
                    <th style="width: 100%; height: 18px; padding: 5px 8px; text-align: left;"><span style="font-size: 12pt;"><strong>Наименование работ и услуг</strong></span></th>
                    <th style="height: 18px; padding: 5px 8px; text-align: right;"><span style="font-size: 12pt;"><strong>Н-ч</strong></span></th>
                    <th style="height: 18px; padding: 5px 8px; text-align: right;"><span style="font-size: 12pt;"><strong>Цена</strong></span></th>
                    <th style="height: 18px; padding: 5px 8px; text-align: right;"><span style="font-size: 12pt;"><strong>Кол.во</strong></span></th>
                    <th style="height: 18px; padding: 5px 8px; text-align: right;"><span style="font-size: 12pt;"><strong>Скидка</strong></span></th>
                    <th style="height: 18px; padding: 5px 8px 5px 25px; text-align: right;"><span style="font-size: 12pt;"><strong>Сумма</strong></span></th>
                </tr>
                </thead>
                <tbody>

                <?php
                if ($result_s = mysqli_query($link, $query_s)) {
                    for($i=1; $out_s = mysqli_fetch_assoc($result_s); $i++) {
                        ?>
                        <tr id="work-product" style="height: 17px;">
                            <td style="height: 16px; padding: 0px 8px; text-align: center;"><span style="font-size: 12pt;"><?php echo $i; ?></span></td>
                            <td style="width: 100%; height: 16px; padding: 0px 8px;"><span style="font-size: 12pt;"><?php echo $out_s['gas_name']; ?></span></td>
                            <td style="height: 18px; padding: 0px 8px; text-align: right;"><span style="font-size: 12pt;"><?php echo $out_s['gas_time_rate']; ?></span></td>
                            <td style="height: 16px; padding: 0px 8px; text-align: right;"><span style="font-size: 12pt;"><?php echo $out_s['price']; ?></span></td>
                            <td style="height: 16px; padding: 0px 8px; text-align: right;"><span style="font-size: 12pt;"><?php echo $out_s['gas_qty']; ?></span></td>
                            <td style="height: 16px; padding: 0px 8px; text-align: right;"><span style="font-size: 12pt;"><?php echo $out_s['discont']; ?>%</span></td>
                            <td style="height: 16px; padding: 0px 8px; text-align: right;"><span style="font-size: 12pt;"><?php echo $out_s['amount']; ?></span></td>
                        </tr>
                        <?php
                    }
                    mysqli_free_result($result_s);
                }
                ?>

                <tr style="height: 18px;">
                    <td style="height: 18px; text-align: right; border-style: none;">&nbsp;</td>
                    <td style="height: 18px; text-align: right; border-style: none;">&nbsp;</td>
                    <td style="height: 18px; padding: 0px 8px; text-align: right;" colspan="4"><span style="font-size: 12pt;">Итого за работы и услуги:</span></td>
                    <td style="height: 18px; padding: 0px 8px; text-align: right;"><span style="font-size: 12pt;"><?php echo $amount_s['sum']; ?></span></td>
                </tr>
                </tbody>
            </table>
            <p>

            <p>
            <table style="width: 100%; border-collapse: collapse; border-style: none; height: 88px;" border="1">
                <thead>
                <tr style="height: 18px;">
                    <th style="height: 18px; padding: 0px 8px; text-align: center;"><span style="font-size: 12pt;"><strong>№</strong></span></th>
                    <th style="height: 18px; padding: 0px 8px; text-align: center;"><span style="font-size: 12pt;"><strong>Кат.номер</strong></span></th>
                    <th style="width: 100%; height: 18px; padding: 5px 8px; text-align: left;"><span style="font-size: 12pt;"><strong>Наименование запчасти</strong></span></th>
                    <th style="height: 18px; padding: 5px 8px; text-align: right;"><span style="font-size: 12pt;"><strong>Цена</strong></span></th>
                    <th style="height: 18px; padding: 5px 8px; text-align: right;"><span style="font-size: 12pt;"><strong>Кол.во</strong></span></th>
                    <th style="height: 18px; padding: 5px 8px; text-align: right;"><span style="font-size: 12pt;"><strong>Скидка</strong></span></th>
                    <th style="height: 18px; padding: 5px 8px 5px 25px; text-align: right;"><span style="font-size: 12pt;"><strong>Сумма</strong></span></th>
                </tr>
                </thead>
                <tbody>

                <?php
                if ($result_p = mysqli_query($link, $query_p)) {
                    for($i=1; $out_p = mysqli_fetch_assoc($result_p); $i++) {
                        ?>
                        <tr id="work-product" style="height: 17px;">
                            <td style="height: 16px; padding: 0px 8px; text-align: center;"><span style="font-size: 12pt;"><?php echo $i; ?></span></td>
                            <td style="height: 16px; padding: 0px 8px; text-align: center;"><span style="font-size: 12pt;"><?php echo $out_p['gas_num']; ?></span></td>
                            <td style="width: 100%; height: 16px; padding: 0px 8px;"><span style="font-size: 12pt;"><?php echo $out_p['gas_name']; ?></span></td>
                            <td style="height: 16px; padding: 0px 8px; text-align: right;"><span style="font-size: 12pt;"><?php echo $out_p['price']; ?></span></td>
                            <td style="height: 16px; padding: 0px 8px; text-align: right;"><span style="font-size: 12pt;"><?php echo $out_p['gas_qty']; ?></span></td>
                            <td style="height: 16px; padding: 0px 8px; text-align: right;"><span style="font-size: 12pt;"><?php echo $out_p['discont']; ?>%</span></td>
                            <td style="height: 16px; padding: 0px 8px; text-align: right;"><span style="font-size: 12pt;"><?php echo $out_p['amount']; ?></span></td>
                        </tr>
                        <?php
                    }
                    mysqli_free_result($result_p);
                }
                ?>

                <tr style="height: 18px;">
                    <td style="height: 18px; text-align: right; border-style: none;">&nbsp;</td>
                    <td style="height: 18px; text-align: right; border-style: none;">&nbsp;</td>
                    <td style="height: 18px; text-align: right; border-style: none;">&nbsp;</td>
                    <td style="height: 18px; padding: 0px 8px; text-align: right;" colspan="3"><span style="font-size: 12pt;">Итого за запчасти:</span></td>
                    <td style="height: 18px; padding: 0px 8px; text-align: right;"><span style="font-size: 12pt;"><?php echo $amount_p['sum']; ?></span></td>
                </tr>
                </tbody>
            </table>
            <p>
            <p style="font-size: 18pt; text-align: right;">Итого за заказ: <strong><?php echo number_format(($amount_s['sum']+$amount_p['sum']), "2", ".", " "); ?> руб.</strong></p>

            <p><strong>РЕКОМЕНДАЦИИ</strong>:&nbsp;<span style="font-size: 11pt;"><?php echo $recommendation; ?></span></p>
            <p class="MsoNormal" style="margin-top: 6pt; line-height: 0.9;"><span style="font-size: 11pt;">1. Гарантийный ремонт распространяется только на неисправности, возникшие в результате произведённого ремонта.<br />2. Гарантия устанавливается на каждый товар или услугу отдельно.<br />3. Гарантийный ремонт не распространяется на аксессуары поставляемые с оборудованием.<br />4. Сервисный центр может отказать в гарантийном ремонте в следующих случаях:<br />&nbsp;-&nbsp; нарушения сохранности гарантийных пломб.<br />&nbsp;-&nbsp; использования оборудования вместе с аксессуарами, не одобренных предприятием изготовителя.<br />&nbsp;-&nbsp; повреждений вызванных нарушением условий эксплуатации и хранения;<br />&nbsp;-&nbsp; несанкционированного вмешательства;<br />&nbsp;-&nbsp; наличия механических, химических или тепловых воздействий;<br />&nbsp;-&nbsp; стихийными бедствиями, водой.<br />Данные услуги по желанию клиента могут быть выполнены за отдельную плату.<br />5. Устройство (изделия) проверено во всех режимах.</span></p>
            <hr />
            <table style="height: 15px; width: 100%; border-style: none;" border="0">
                <tbody>
                <tr style="height: 15px;">
                    <td style="width: 50%; height: 15px;">Исполнитель: _______________ / <?php echo $_SESSION['user']['full_name'] ?>/</td>
                    <td style="width: 50%; height: 15px; text-align: right;">
                        <p>________________ / <?php echo $first_name; ?>/<br /><span style="font-size: 12pt;">С условием гарантии ознакомлен и согласен.&nbsp;</span><span style="font-size: 12pt;">Электротранспорт, комплектующие получил, претензий не имею</span></p>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>



    </tbody>
</table>


            </div>

</main>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/select2.min.js"></script>
<script src="js/main.js"></script>
<script type="text/javascript">


    function PrintElem(elem)
    {
        Popup($(elem).html());
    }

    function Popup(data)
    {
        var orderNum = $("span#order-num").html();
        var mywindow = window.open('', 'my div', 'height=700,width=1050');
        /*optional stylesheet*/ //mywindow.document.write('<link rel="stylesheet" href="main.css" type="text/css" />');
        mywindow.document.write('<!DOCTYPE html><body><html lang="ru"><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title>');
        mywindow.document.write(orderNum);
        mywindow.document.write('</title></head><body>');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');

        // mywindow.document.close(); // necessary for IE >= 10
        // mywindow.focus(); // necessary for IE >= 10

        mywindow.print();
        // mywindow.close();

        return true;
    }

</script>
</body>
</html>

