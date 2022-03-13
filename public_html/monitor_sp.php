<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: index.php');
}
require_once "on-call_service.php";
$query = "SELECT * FROM `gas` INNER JOIN `supplier_list` ON `gas`.`gas_supplier`=`supplier_list`.`supplier_id` WHERE `gas_sold` = 3 OR `gas_sold` = 4";
$backorders = mysqli_query($link, $query);
$query_supplier_list = "SELECT * FROM `supplier_list` ORDER BY `supplier_id` ASC";
$result_supplier_list = mysqli_query($link, $query_supplier_list);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/bootstrap.min.css" >
    <link rel="stylesheet" href="css/select2.min.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Logic: Заказы запчастей</title>
</head>
<body>
<header></header>
<main>
    <div class="towork-title">
        <div class="container-fluid">

            <!-- Панель управления (верхняя) -->
            <?php include 'control_panel_top.php'?>

            <table class="table table-bordered table-striped table-hover mt-1">
                <thead class="thead-dark sticky-top">
                <tr>
                    <th>*</th>
                    <th class="w-25">Наименование</th>
                    <th class="text-right">Qty</th>
                    <th>Бренд</th>
                    <th>Модель</th>
                    <th>Logic</th>
                    <th>Заказано</th>
                </tr>
                </thead>

                <tbody id="filter-list">
                <?php
                for($i=1; $out = mysqli_fetch_assoc($backorders); $i++)
                {
                    $gas_id = $out['gas_id'];
                    ?>
                    <tr class="table-<?php if ($out['gas_sold'] == 3) {
                        echo "danger";
                    } elseif ($out['gas_sold'] == 4) {
                        echo "success";
                    } ?>">
                        <td title="ID: <?php echo $gas_id; ?>"><small><?php echo $i; ?></small></td>
                        <td class="w-25" style="white-space: nowrap"><?php echo $out['gas_name']; ?></td>
                        <td class="text-right"><?php echo $out['gas_qty']; ?></td>
                            <?php
                            $order_n = $out['order_num'];
                            $query_orders_list = "SELECT * FROM `orders_list` INNER JOIN `device_type_list` ON `orders_list`.`device_type`=`device_type_list`.`device_type_id`
    INNER JOIN `device_brand_list` ON `orders_list`.`device_brand`=`device_brand_list`.`device_brand_id`
    INNER JOIN `device_model_list` ON `orders_list`.`device_model`=`device_model_list`.`device_model_id` WHERE `order_num` = '$order_n'";
                            $result_orders_list = mysqli_query($link, $query_orders_list);
                            foreach ($result_orders_list as $item) {
                                ?>
                                <td><?php echo $item['device_brand_name']; ?></td>
                                <td><?php echo $item['device_model_name']; ?></td>
                                <?
                            }
                            ?>
                            <td><strong>#<?php echo $out['order_num']; ?></strong></td>
                            <td><?php echo date('d.m', strtotime($out['gas_time_add'])); ?></td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>

        </div>
    </div>
</main>
<!-- Футер -->
<?php include 'footer.php'?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/main.js"></script>

</body>
</html>
