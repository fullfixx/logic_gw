<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: m.index.php');
}
require_once 'db.php';
$order_num = mysqli_real_escape_string($link, $_GET['id']);
$query = "SELECT * FROM `orders_list`
    INNER JOIN `device_type_list` ON `orders_list`.`device_type`=`device_type_list`.`device_type_id`
    INNER JOIN `device_brand_list` ON `orders_list`.`device_brand`=`device_brand_list`.`device_brand_id`
    INNER JOIN `device_model_list` ON `orders_list`.`device_model`=`device_model_list`.`device_model_id`
    WHERE `order_num` = ?
";
$stmt = mysqli_stmt_init($link);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_bind_param($stmt, "s", $order_num);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    echo "STMT error";
}
$out = mysqli_fetch_assoc($result);
$phone_n = $out['phone_n'];
$data_open = $out['data_open'];
$first_name = $out['first_name'];
$device_type_name = $out['device_type_name'];
$device_brand_name = $out['device_brand_name'];
$device_model_name = $out['device_model_name'];
$comment_order = $out['comment_order'];
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
    <div class="towork-title">
        <div class="container-fluid">
            <input type="button" class="btn btn-danger btn-lg btn-block" value="PDF Print" onclick="PrintElem('#mydiv')">
            <a class="btn btn-success btn-lg btn-block" target="_blank" role="button" href="https://wa.me/<?php echo $phone_n; ?>">Отправить в WhatsApp</a>
            <a class="btn btn-primary btn-lg btn-block" role="button" href="update.php?id=<?php echo $order_num; ?>">Перейти к заказу G<?php echo $order_num; ?></a>
            <div id="mydiv">


                <p class="MsoNormal" style="margin-top: 6.0pt;"><span style="font-size: 18pt;"><strong><span id="order-num">Талон предварительной записи № <?php echo $order_num; ?></span> от <?php echo $data_open; ?></strong></span></p>
                <hr />
                <div style="float: right; text-align: center; margin-bottom: 5px; margin-left: 5px;">
                    <div style="border: 1px solid black; padding: 0px 5px;"><span style="font-size: 16pt;">НОМЕР ЗАКАЗА</span></div>
                    <div style="border: 1px solid black; font-size: 50pt; font-family: 'Arial Black'; padding: 0px 5px;">G<?php echo $order_num; ?></div>
                    <div style="border: 1px solid black;"><img style="width: 150px; height: 150px; margin: 0" src="http://qrcoder.ru/code/?http%3A%2F%2Fpartsgowheel.ru%2Fs%2F&6&0" /></div>
                </div>
                <p class="MsoNormal" style="margin-top: 6pt; line-height: 0.9;"><span style="font-size: 14pt;"><strong>Исполнитель</strong>: GoWheel-Service<br />
                Адрес: г.Санкт-Петербург, ул.Кронверская, 23П<br />Телефон: +7 (812) 243-1-872<br /></span></p>
                <p class="MsoNormal" style="margin-top: 6pt; line-height: 0.9;"><span style="font-size: 14pt;"><strong>Заказчик</strong>: <?php echo $first_name; ?><br />Телефон: <?php echo $phone_n; ?></span></p>
                <p class="MsoNormal" style="margin-top: 6pt; line-height: 0.9;"><span style="font-size: 14pt;"><strong>Устройство</strong>:&nbsp; <?php echo $device_type_name; ?> <?php echo $device_brand_name; ?> <?php echo $device_model_name; ?><br />
                <p class="MsoNormal" style="margin-top: 6pt; line-height: 0.9;"><span style="font-size: 14pt;"><strong>Причина обращения со слов заказчика</strong>: <?php echo $comment_order; ?><br /><strong>Предоплата</strong>: нет<br /></p>
                <p class="MsoNormal" style="margin-top: 6pt; line-height: 0.9;"><span style="font-size: 10pt;">
                        1. Технический центр не несёт ответственности за возможную потерю данных в памяти устройства, связанную с заменой плат, установкой программного обеспечения.<br />
                        2. Заказчик принимает на себя риск возможной полной или частичной утраты работоспособности устройства в процессе ремонта (тепловой обработки), в случае грубых нарушений пользователем условий эксплуатации, наличий следов попадания токопроводящей жидкости (коррозии), либо механических повреждений.<br />
                        3. На восстановленные после попадания жидкости на устройство гарантия не распространяется и не продлевается.<br />
                        4. В случае отказа заказчика от ремонта устройства стоимость диагностики неисправности платная.<br />
                        5. В случае утери квитанции, устройство выдаётся по предъявлению паспорта на имя заказчика.</span></p>
                <table style="height: 15px; width: 100%; border-style: none;" border="0">
                    <tbody>
                    <tr style="height: 15px;">
                        <td style="width: 50%; height: 15px;"><span style="font-size: 14pt;"><img style="position: absolute; z-index: 1; top: 410px; left: 60px; width: 150px; height: 150px;" src="img/GWstamp.png" alt="">Исполнитель: <u>ООО "ГоуВил"</u></span></td>
                        <td style="width: 50%; height: 15px; text-align: right;"><span style="font-size: 11pt;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="width: 50%; border-bottom: 1pt dashed;" colspan="2">&nbsp;</td>
                    </tr>
                    </tbody>
                </table>
                <a href="http://partsgowheel.ru/s"><div style="font-size: 36pt; text-align: center; padding-top: 20px;"><span>ОТКРЫТЬ СТРАНИЦУ<br>ДЛЯ ПРОСМОТРА ОЧЕРЕДИ</span></div></a>

            </div>


        </div>
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
        var mywindow = window.open('', 'my div', 'height=400,width=600');
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

