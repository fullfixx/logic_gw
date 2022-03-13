<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: index.php');
}
require_once "on-call_service.php";
if (count($_GET) > 0) {
    $gas_supplier = $_GET['gas_supplier'];
    $query = "SELECT * FROM `gas` INNER JOIN `supplier_list` ON `gas`.`gas_supplier`=`supplier_list`.`supplier_id` WHERE `gas_sold` = 1 AND `gas_type` = 'p' AND `gas_supplier` = '$gas_supplier'";
    $backorders = mysqli_query($link, $query);
} else {
    $query = "SELECT * FROM `gas` INNER JOIN `supplier_list` ON `gas`.`gas_supplier`=`supplier_list`.`supplier_id` WHERE `gas_sold` = 1 AND `gas_type` = 'p'";
    $backorders = mysqli_query($link, $query);
}


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
    <title>Logic: Выполенные заказы</title>
</head>
<body>
<div class="modal fade" id="addBackOrder" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Свободный заказ запчастей</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="add_free_backorder.php" method="post">
                    <div class="form-row mt-1">
                        <div class="col-12">
                            <input type="text" name="gas_num" class="form-control" placeholder="Артикул запчасти">
                        </div>
                    </div>
                    <div class="form-row mt-1">
                        <div class="col-12">
                            <input type="text" name="gas_name" maxlength="40" class="form-control" placeholder="Наименование запчасти" required>
                        </div>
                    </div>
                    <div class="form-row mt-1">
                        <div class="col-12">
                            <select type="number" name="gas_supplier" class="form-control" required>
                                <option value="1">Gowheel</option>
                                <?php while ($item = mysqli_fetch_assoc($result_supplier_list)) {
                                    ?>
                                    <option value="<?php echo $item['supplier_id']; ?>"><?php echo $item['supplier_penname']; ?></option>
                                    <?
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row mt-1">
                        <div class="col-12">
                            <input type="number" name="cost" class="form-control" placeholder="Цена закупки">
                        </div>
                    </div>
                    <div class="form-row mt-1">
                        <div class="col-12">
                            <input type="number" name="price" class="form-control" placeholder="Цена продажи">
                        </div>
                    </div>
                    <div class="form-row mt-1">
                        <div class="col-12">
                            <input type="number" name="gas_qty" class="form-control" value="1.000" required>
                        </div>
                    </div>
                    <div class="form-row mt-1">
                        <div class="col-12">
                            <input type="text" name="first_name" class="form-control" placeholder="Имя клиента">
                        </div>
                    </div>
                    <div class="form-row mt-1">
                        <div class="col-12">
                            <input type="text" name="phone_n" autocomplete="off" maxlength="11" class="form-control" placeholder="79052880811">
                        </div>
                    </div>
                    <div class="form-row mt-1">
                        <div class="col-12">
                            <textarea class="form-control" type="text" name="comment_order" placeholder="Дополнительная информация" wrap></textarea>
                        </div>
                    </div>
                    <div class="form-row mt-1">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Добавить</button>
                        </div>
                    </div>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>
<header></header>
<main>
    <div class="towork-title">
        <div class="container-fluid">

            <!-- Панель управления (верхняя) -->
            <?php include 'control_panel_top.php'?>

            <div class="row">
                <div class="col-4">
                    <h3>АРХИВ</h3>
                </div>
                <div class="col-4">
                    
                </div>
                <div class="col-4">
                    <form action="backorders.php" method="get">
                        <select name="gas_supplier" class="form-control" onchange="submit();" type="number">
                            <option value="">Фильтр по поставщику</option>
                            <?php foreach ($result_supplier_list as $item_sup) {
                                ?>
                                <option value="<?php echo $item_sup['supplier_id']; ?>"><?php echo $item_sup['supplier_penname']; ?></option>
                                <?
                            } ?>
                        </select>
                    </form>
                </div>
            </div>

            <table class="table table-bordered table-striped table-hover">
                <thead class="thead-dark sticky-top">
                <tr>
                    <th>*</th>
                    <th>Артикул</th>
                    <th>Наименование</th>
                    <th class="text-right">Цена закуп.</th>
                    <th class="text-right">Цена прод.</th>
                    <th class="text-right">Qty</th>
                    <th>Бренд</th>
                    <th>Модель</th>
                    <th>Logic</th>
                    <th>#</th>
                    <th>Заказано</th>
                    <th>Поставщик</th>
                    <th>Комментарий</th>
                </tr>
                </thead>

                <tbody id="filter-list">
                <?php
                for($i=1; $out = mysqli_fetch_assoc($backorders); $i++)
                {
                    $gas_id = $out['gas_id'];
                    ?>
                    <tr>
                        <td title="ID: <?php echo $gas_id; ?>"><small><?php echo $i; ?></small></td>
                        <form action="upd_backorders.php" method="post">
                            <input type="hidden" name="gas_id" value="<?php echo $gas_id; ?>">
                            <td><?php echo $out['gas_num']; ?></td>
                            <td class="w-25"><input type="text" name="gas_name" class="form-control form-control-sm text-left" value="<?php echo $out['gas_name']; ?>" onchange="submit();"></td>
                            <td><input type="text" name="cost" class="form-control form-control-sm text-right" value="<?php echo $out['cost']; ?>" onchange="submit();"></td>
                            <td><input type="text" name="price" class="form-control form-control-sm text-right" value="<?php echo $out['price']; ?>" onchange="submit();"></td>
                            <td><input type="text" name="gas_qty" class="form-control form-control-sm text-right" value="<?php echo $out['gas_qty']; ?>" onchange="submit();"></td>
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
                            <td class="table-<?php if ($out['gas_sold'] == 3) {
                                echo "danger";
                            } elseif ($out['gas_sold'] == 4) {
                                echo "success";
                            } ?>"><a href="update.php?id=<?php echo $out['order_num']; ?>" class="text-secondary" target="_blank"><strong>G<?php echo $out['order_num']; ?></strong></a></td>
                            <td><?php if ($out['gas_sold'] == 3) {
                                    ?>
                                    <a href="changeto_backorder_ordered.php?gas_id=<?php echo $out['gas_id']; ?>" class="SaveScroll" title="Заказ принят"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" style="fill: #0099cc"><path fill-rule="evenodd" d="M2 4.75C2 3.784 2.784 3 3.75 3h4.965a1.75 1.75 0 011.456.78l1.406 2.109a.25.25 0 00.208.111h8.465c.966 0 1.75.784 1.75 1.75v11.5A1.75 1.75 0 0120.25 21H3.75A1.75 1.75 0 012 19.25V4.75zm12.78 4.97a.75.75 0 10-1.06 1.06l1.72 1.72H6.75a.75.75 0 000 1.5h8.69l-1.72 1.72a.75.75 0 101.06 1.06l3-3a.75.75 0 000-1.06l-3-3z"></path></svg></a>
                                    <?
                                } elseif ($out['gas_sold'] == 4) {
                                    ?>
                                    <a href="changeto_backorder_done.php?gas_id=<?php echo $out['gas_id']; ?>" class="SaveScroll" title="Запчасти поступили"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" style="fill: #009900"><path fill-rule="evenodd" d="M1 12C1 5.925 5.925 1 12 1s11 4.925 11 11-4.925 11-11 11S1 18.075 1 12zm16.28-2.72a.75.75 0 00-1.06-1.06l-5.97 5.97-2.47-2.47a.75.75 0 00-1.06 1.06l3 3a.75.75 0 001.06 0l6.5-6.5z"></path></svg></a>
                                    <?
                                } ?></td>
                            <td><?php echo date('d.m', strtotime($out['gas_time_add'])); ?></td>
                            <td>
                                <select name="gas_supplier" class="form-control form-control-sm" type="text" onchange="submit();">
                                    <option value="<?php echo $out['gas_supplier']; ?>"><?php echo $out['supplier_penname']; ?></option>
                                    <?php
                                    foreach ($result_supplier_list as $sup) {
                                        ?>
                                        <option value="<?php echo $sup['supplier_id'] ?>"><?php echo $sup['supplier_penname'] ?></option>
                                        <?
                                    }
                                    ?>
                                </select>
                            </td>
                            <td><input type="text" name="gas_comment" class="form-control form-control-sm text-left" value="<?php echo $out['gas_comment']; ?>" onchange="submit();" title="<?php echo $out['gas_comment']; ?>"></td>
                        </form>
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
