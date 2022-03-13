<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: index.php');
}
require_once 'db.php';
$order_num = mysqli_real_escape_string($link, $_GET['id']);

$out = mysqli_query($link, "SELECT * FROM `orders_list` 
    INNER JOIN `order_type_list` ON `orders_list`.`order_type`=`order_type_list`.`order_type_id`
    INNER JOIN `device_type_list` ON `orders_list`.`device_type`=`device_type_list`.`device_type_id`
    INNER JOIN `device_brand_list` ON `orders_list`.`device_brand`=`device_brand_list`.`device_brand_id`
    INNER JOIN `device_model_list` ON `orders_list`.`device_model`=`device_model_list`.`device_model_id`
    INNER JOIN `users` ON `orders_list`.`executor`=`users`.`id`
    INNER JOIN `status_list` ON `orders_list`.`status`=`status_list`.`status_id`
    INNER JOIN `statusclarity_list` ON `orders_list`.`statusclarity`=`statusclarity_list`.`statusclarity_id`
    WHERE `order_num`='$order_num'");
$out = mysqli_fetch_assoc($out);

$executor = mysqli_query($link, "SELECT * FROM `users` WHERE `rank` = '3'");
$device_type = mysqli_query($link, "SELECT * FROM `device_type_list` ORDER BY `device_type_list`.`device_type_name` ASC");
$device_brand = mysqli_query($link, "SELECT * FROM `device_brand_list` ORDER BY `device_brand_list`.`device_brand_name` ASC");
$device_model = mysqli_query($link, "SELECT * FROM `device_model_list` ORDER BY `device_model_list`.`device_model_name` ASC");
$order_type = mysqli_query($link, "SELECT * FROM `order_type_list`");
$status = mysqli_query($link, "SELECT * FROM `status_list`");
$statusclarity = mysqli_query($link, "SELECT * FROM `statusclarity_list`");

$comment_order = mysqli_query($link, "SELECT * FROM `orders_list` INNER JOIN `users` ON `orders_list`.`creator`=`users`.`id` WHERE `order_num`='$order_num'");
$comment_order = mysqli_fetch_assoc($comment_order);
$order_posts = mysqli_query($link, "SELECT * FROM `chat` WHERE `order_num`='$order_num' ORDER BY `chat`.`time_create_post` ASC");
$order_gas = mysqli_query($link, "SELECT * FROM `gas` WHERE `order_num`='$order_num' ORDER BY `gas`.`gas_time_add` ASC");

$amount = mysqli_query($link, "SELECT SUM(`amount`) AS `sum` FROM `gas` WHERE `order_num`='$order_num'");
$amount = mysqli_fetch_assoc($amount);

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
            <a class="btn btn-primary btn-lg btn-block" role="button" href="towork.php">Вернуться в Logic GW</a>
            <div id="mydiv">


                <table style="width: 1002px; border-collapse: collapse; height: 1075px;" border="1">
                    <tbody>
                    <tr style="height: 597px;">
                        <td style="width: 997.6470336914063px; height: 597px;">
                            <p class="MsoNormal" style="margin-top: 6.0pt;"><span style="font-size: 14pt;"><strong>Гарантийный талон № <?php echo $out['order_num']; ?> от &lt;ДатаВыдачи&gt;</strong></span></p>
                            <hr />
                            <p class="MsoNormal" style="margin-top: 6pt; line-height: 1;"><span style="font-size: 10pt;"><strong>Исполнитель</strong>: &lt;НазваниеМагазина&gt;&lt;ШтрихкодСправа&gt;<br />Адрес:&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &lt;АдресМагазина&gt;<br />Телефон:&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&lt;ТелефонМагазина&gt;<br /><br /><strong>Заказчик</strong>:&nbsp; &nbsp; &nbsp; &nbsp;&lt;Контрагент&gt;<br /><br /><strong>Марка/модель</strong>: &lt;Марка&gt; &lt;Модель&gt; (&lt;СерийныйНомер&gt;)</span></p>
                            <p>&nbsp;</p>
                            <table style="width: 0%; border-collapse: collapse; border-style: none; height: 88px;" border="1">
                                <tbody>
                                <tr style="height: 18px;">
                                    <td style="width: 7.10594%; height: 18px; text-align: center;"><span style="font-size: 9pt;"><strong>№</strong></span></td>
                                    <td style="width: 8.13953%; height: 18px; text-align: center;"><span style="font-size: 9pt;"><strong>Код</strong></span></td>
                                    <td style="width: 29.845%; height: 18px; text-align: center;"><span style="font-size: 9pt;"><strong>Наименование товаров и услуг</strong></span></td>
                                    <td style="width: 11.7571%; height: 18px; text-align: center;"><span style="font-size: 9pt;"><strong>Гар-тия</strong></span></td>
                                    <td style="width: 10.3359%; height: 18px; text-align: center;"><span style="font-size: 9pt;"><strong>Кол-во</strong></span></td>
                                    <td style="width: 13.5659%; height: 18px; text-align: center;"><span style="font-size: 9pt;"><strong>Цена</strong></span></td>
                                    <td style="width: 19.1214%; height: 18px; text-align: center;"><span style="font-size: 9pt;"><strong>Сумма</strong></span></td>
                                </tr>
                                <tr id="work-product" style="height: 17px;">
                                    <td style="width: 7.10594%; height: 16px; text-align: center;"><span style="font-size: 9pt;">&lt;№&gt;</span></td>
                                    <td style="width: 8.13953%; height: 16px; padding-left: 8px;"><span style="font-size: 9pt;">&lt;Код&gt;</span></td>
                                    <td style="width: 29.845%; height: 16px; padding-left: 8px;"><span style="font-size: 9pt;">&lt;Наименование&gt;</span></td>
                                    <td style="width: 11.7571%; height: 16px; text-align: center;"><span style="font-size: 9pt;">&lt;Гарант&gt;</span></td>
                                    <td style="width: 10.3359%; height: 16px; padding-right: 8px; text-align: right;"><span style="font-size: 9pt;">&lt;Кол&gt; &lt;Ед&gt;</span></td>
                                    <td style="width: 13.5659%; height: 16px; padding-right: 8px; text-align: right;"><span style="font-size: 9pt;">&lt;Цена(#.##)&gt;</span></td>
                                    <td style="width: 19.1214%; height: 16px; padding-right: 8px; text-align: right;"><span style="font-size: 9pt;">&lt;Сумма(#.##)&gt;</span></td>
                                </tr>
                                <tr style="height: 18px;">
                                    <td style="width: 7.10594%; height: 18px; border-style: none;">&nbsp;</td>
                                    <td style="width: 8.13953%; height: 18px; border-style: none;">&nbsp;</td>
                                    <td style="width: 29.845%; height: 18px; border-style: none;">&nbsp;</td>
                                    <td style="width: 11.7571%; height: 18px; border-style: none;">&nbsp;</td>
                                    <td style="width: 23.9018%; height: 18px; padding-right: 8px; text-align: right;" colspan="2"><span style="font-size: 9pt;">Сумма чека:</span></td>
                                    <td style="width: 19.1214%; height: 18px; padding-right: 8px; text-align: right;"><span style="font-size: 9pt;">&lt;ИтогоБезСкидки(#.##)&gt;</span></td>
                                </tr>
                                <tr style="height: 18px; text-align: right;">
                                    <td style="width: 7.10594%; height: 18px; border-style: none;">&nbsp;</td>
                                    <td style="width: 8.13953%; height: 18px; border-style: none;">&nbsp;</td>
                                    <td style="width: 29.845%; height: 18px; border-style: none;">&nbsp;</td>
                                    <td style="width: 11.7571%; height: 18px; border-style: none;">&nbsp;</td>
                                    <td style="width: 23.9018%; height: 18px; padding-right: 8px;" colspan="2"><span style="font-size: 9pt;">Скидка:</span></td>
                                    <td style="width: 19.1214%; height: 18px; padding-right: 8px; text-align: right;"><span style="font-size: 9pt;">&lt;ИтогоСкидки(#.##)&gt;</span></td>
                                </tr>
                                <tr style="height: 18px;">
                                    <td style="width: 7.10594%; height: 18px; text-align: right; border-style: none;">&nbsp;</td>
                                    <td style="width: 8.13953%; height: 18px; text-align: right; border-style: none;">&nbsp;</td>
                                    <td style="width: 29.845%; height: 18px; text-align: right; border-style: none;">&nbsp;</td>
                                    <td style="width: 11.7571%; height: 18px; text-align: right; border-style: none;">&nbsp;</td>
                                    <td style="width: 23.9018%; height: 18px; padding-right: 8px; text-align: right;" colspan="2"><span style="font-size: 9pt;">Итого:</span></td>
                                    <td style="width: 19.1214%; height: 18px; padding-right: 8px; text-align: right;"><span style="font-size: 9pt;">&lt;Итого(#.##)&gt;</span></td>
                                </tr>
                                </tbody>
                            </table>
                            <p><strong>РЕКОМЕНДАЦИИ</strong>:&nbsp;<span style="font-size: 11pt;">&lt;Рекомендации1&gt;</span></p>
                            <p class="MsoNormal" style="margin-top: 6pt; line-height: 0.9;"><span style="font-size: 8pt;">1. Гарантийный ремонт распространяется только на неисправности, возникшие в результате произведённого ремонта.<br />2. Гарантия устанавливается на каждый товар или услугу отдельно.<br />3. Гарантийный ремонт не распространяется на аксессуары поставляемые с оборудованием.<br />4. Сервисный центр может отказать в гарантийном ремонте в следующих случаях:<br />&nbsp;-&nbsp; нарушения сохранности гарантийных пломб.<br />&nbsp;-&nbsp; использования оборудования вместе с аксессуарами, не одобренных предприятием изготовителя.<br />&nbsp;-&nbsp; повреждений вызванных нарушением условий эксплуатации и хранения;<br />&nbsp;-&nbsp; несанкционированного вмешательства;<br />&nbsp;-&nbsp; наличия механических, химических или тепловых воздействий;<br />&nbsp;-&nbsp; стихийными бедствиями, водой.<br />Данные услуги по желанию клиента могут быть выполнены за отдельную плату.<br />5. Устройство (изделия) проверено во всех режимах.</span></p>
                            <hr />
                            <table style="height: 15px; width: 100%; border-style: none;" border="0">
                                <tbody>
                                <tr style="height: 15px;">
                                    <td style="width: 50%; height: 15px;">Исполнитель: _______________ / &lt;ФИОМенеджера&gt;/</td>
                                    <td style="width: 50%; height: 15px; text-align: right;">
                                        <p>________________ / &lt;Контрагент&gt;/<br /><span style="font-size: 9pt;">с условием гарантии ознакомлен и согласен.&nbsp;</span><span style="font-size: 9pt;">электротранспорт, комплектующие получил, претензий не имею</span></p>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr style="height: 478px;">
                        <td style="width: 997.6470336914063px; height: 478px;">
                            <p class="MsoNormal" style="margin-top: 6.0pt;"><span style="font-size: 14pt;"><strong>Гарантийный талон № &lt;НомерДокумента&gt; от &lt;ДатаВыдачи&gt;</strong></span></p>
                            <hr />
                            <p class="MsoNormal" style="margin-top: 6pt; line-height: 1;"><span style="font-size: 10pt;"><strong>Исполнитель</strong>: &lt;НазваниеМагазина&gt;&lt;ШтрихкодСправа&gt;<br />Адрес:&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &lt;АдресМагазина&gt;<br />Телефон:&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&lt;ТелефонМагазина&gt;<br /><br /><strong>Заказчик</strong>:&nbsp; &nbsp; &nbsp; &nbsp;&lt;Контрагент&gt;<br /><br /><strong>Марка/модель</strong>: &lt;Марка&gt; &lt;Модель&gt; (&lt;СерийныйНомер&gt;)</span></p>
                            <p>&nbsp;</p>
                            <table style="width: 0%; border-collapse: collapse; border-style: none; height: 88px;" border="1">
                                <tbody>
                                <tr style="height: 18px;">
                                    <td style="width: 7.10594%; height: 18px; text-align: center;"><span style="font-size: 9pt;"><strong>№</strong></span></td>
                                    <td style="width: 8.13953%; height: 18px; text-align: center;"><span style="font-size: 9pt;"><strong>Код</strong></span></td>
                                    <td style="width: 29.845%; height: 18px; text-align: center;"><span style="font-size: 9pt;"><strong>Наименование товаров и услуг</strong></span></td>
                                    <td style="width: 11.7571%; height: 18px; text-align: center;"><span style="font-size: 9pt;"><strong>Гар-тия</strong></span></td>
                                    <td style="width: 10.3359%; height: 18px; text-align: center;"><span style="font-size: 9pt;"><strong>Кол-во</strong></span></td>
                                    <td style="width: 13.5659%; height: 18px; text-align: center;"><span style="font-size: 9pt;"><strong>Цена</strong></span></td>
                                    <td style="width: 19.1214%; height: 18px; text-align: center;"><span style="font-size: 9pt;"><strong>Сумма</strong></span></td>
                                </tr>
                                <tr id="work-product" style="height: 17px;">
                                    <td style="width: 7.10594%; height: 16px; text-align: center;"><span style="font-size: 9pt;">&lt;№&gt;</span></td>
                                    <td style="width: 8.13953%; height: 16px; padding-left: 8px;"><span style="font-size: 9pt;">&lt;Код&gt;</span></td>
                                    <td style="width: 29.845%; height: 16px; padding-left: 8px;"><span style="font-size: 9pt;">&lt;Наименование&gt;</span></td>
                                    <td style="width: 11.7571%; height: 16px; text-align: center;"><span style="font-size: 9pt;">&lt;Гарант&gt;</span></td>
                                    <td style="width: 10.3359%; height: 16px; padding-right: 8px; text-align: right;"><span style="font-size: 9pt;">&lt;Кол&gt; &lt;Ед&gt;</span></td>
                                    <td style="width: 13.5659%; height: 16px; padding-right: 8px; text-align: right;"><span style="font-size: 9pt;">&lt;Цена(#.##)&gt;</span></td>
                                    <td style="width: 19.1214%; height: 16px; padding-right: 8px; text-align: right;"><span style="font-size: 9pt;">&lt;Сумма(#.##)&gt;</span></td>
                                </tr>
                                <tr style="height: 18px;">
                                    <td style="width: 7.10594%; height: 18px; border-style: none;">&nbsp;</td>
                                    <td style="width: 8.13953%; height: 18px; border-style: none;">&nbsp;</td>
                                    <td style="width: 29.845%; height: 18px; border-style: none;">&nbsp;</td>
                                    <td style="width: 11.7571%; height: 18px; border-style: none;">&nbsp;</td>
                                    <td style="width: 23.9018%; height: 18px; padding-right: 8px; text-align: right;" colspan="2"><span style="font-size: 9pt;">Сумма чека:</span></td>
                                    <td style="width: 19.1214%; height: 18px; padding-right: 8px; text-align: right;"><span style="font-size: 9pt;">&lt;ИтогоБезСкидки(#.##)&gt;</span></td>
                                </tr>
                                <tr style="height: 18px; text-align: right;">
                                    <td style="width: 7.10594%; height: 18px; border-style: none;">&nbsp;</td>
                                    <td style="width: 8.13953%; height: 18px; border-style: none;">&nbsp;</td>
                                    <td style="width: 29.845%; height: 18px; border-style: none;">&nbsp;</td>
                                    <td style="width: 11.7571%; height: 18px; border-style: none;">&nbsp;</td>
                                    <td style="width: 23.9018%; height: 18px; padding-right: 8px;" colspan="2"><span style="font-size: 9pt;">Скидка:</span></td>
                                    <td style="width: 19.1214%; height: 18px; padding-right: 8px; text-align: right;"><span style="font-size: 9pt;">&lt;ИтогоСкидки(#.##)&gt;</span></td>
                                </tr>
                                <tr style="height: 18px;">
                                    <td style="width: 7.10594%; height: 18px; text-align: right; border-style: none;">&nbsp;</td>
                                    <td style="width: 8.13953%; height: 18px; text-align: right; border-style: none;">&nbsp;</td>
                                    <td style="width: 29.845%; height: 18px; text-align: right; border-style: none;">&nbsp;</td>
                                    <td style="width: 11.7571%; height: 18px; text-align: right; border-style: none;">&nbsp;</td>
                                    <td style="width: 23.9018%; height: 18px; padding-right: 8px; text-align: right;" colspan="2"><span style="font-size: 9pt;">Итого:</span></td>
                                    <td style="width: 19.1214%; height: 18px; padding-right: 8px; text-align: right;"><span style="font-size: 9pt;">&lt;Итого(#.##)&gt;</span></td>
                                </tr>
                                </tbody>
                            </table>
                            <p><strong>РЕКОМЕНДАЦИИ</strong>:&nbsp;<span style="font-size: 11pt;">&lt;Рекомендации1&gt;</span></p>
                            <p class="MsoNormal" style="margin-top: 6pt; text-align: justify; line-height: 0.9;"><span style="font-size: 8pt;">1. Гарантийный ремонт распространяется только на неисправности, возникшие в результате произведённого ремонта.<br />2. Гарантия устанавливается на каждый товар или услугу отдельно.<br />3. Гарантийный ремонт не распространяется на аксессуары поставляемые с оборудованием.<br />4. Сервисный центр может отказать в гарантийном ремонте в следующих случаях:<br />&nbsp;-&nbsp; нарушения сохранности гарантийных пломб.<br />&nbsp;-&nbsp; использования оборудования вместе с аксессуарами, не одобренных предприятием изготовителя.<br />&nbsp;-&nbsp; повреждений вызванных нарушением условий эксплуатации и хранения;<br />&nbsp;-&nbsp; несанкционированного вмешательства;<br />&nbsp;-&nbsp; наличия механических, химических или тепловых воздействий;<br />&nbsp;-&nbsp; стихийными бедствиями, водой.<br />Данные услуги по желанию клиента могут быть выполнены за отдельную плату.<br />5. Устройство (изделия) проверено во всех режимах.</span></p>
                            <hr />
                            <table style="height: 15px; width: 100%; border-style: none;" border="0">
                                <tbody>
                                <tr style="height: 15px;">
                                    <td style="width: 50%; height: 15px;">Исполнитель: _______________ / &lt;ФИОМенеджера&gt;/</td>
                                    <td style="width: 50%; height: 15px; text-align: right;">
                                        <p>________________ / &lt;Контрагент&gt;/<br /><span style="font-size: 9pt;">с условием гарантии ознакомлен и согласен.&nbsp;</span><span style="font-size: 9pt;">электротранспорт, комплектующие получил, претензий не имею</span></p>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    </tbody>
                </table>


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

