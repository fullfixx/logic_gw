<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: index.php');
}
require_once "on-call_service.php";
// Склад запчастей
// Получаем данные для основной таблицы
if (count($_GET) > 0) {
    $hard_filter = mysqli_real_escape_string($link, $_GET['hard_filter']);
    $query = "SELECT * FROM `spareparts_stock`
        INNER JOIN `spareparts` ON `spareparts_stock`.`sparepart`=`spareparts`.`sparepart_id`
        INNER JOIN `spareparts_names_list` ON `spareparts`.`sparepart_name`=`spareparts_names_list`.`spareparts_names_id`
        INNER JOIN `spareparts_brands_list` ON `spareparts`.`sparepart_brand`=`spareparts_brands_list`.`spareparts_brands_id`
        INNER JOIN `supplier_list` ON `spareparts_stock`.`supplier`=`supplier_list`.`supplier_id`
        WHERE `sparepart_num` LIKE '%$hard_filter%' OR `spareparts_names_name` LIKE '%$hard_filter%'
        ORDER BY `qty_now` DESC";
} else {
    $query = "SELECT * FROM `spareparts_stock`
        INNER JOIN `spareparts` ON `spareparts_stock`.`sparepart`=`spareparts`.`sparepart_id`
        INNER JOIN `spareparts_names_list` ON `spareparts`.`sparepart_name`=`spareparts_names_list`.`spareparts_names_id`
        INNER JOIN `spareparts_brands_list` ON `spareparts`.`sparepart_brand`=`spareparts_brands_list`.`spareparts_brands_id`
        INNER JOIN `supplier_list` ON `spareparts_stock`.`supplier`=`supplier_list`.`supplier_id`
        WHERE `qty_now`>0
        ORDER BY `spareparts_stock`.`transaction_date` DESC
";
}

//Получаем справочники
$suppliers = mysqli_query($link, "SELECT * FROM `supplier_list`");
$spareparts = mysqli_query($link, "SELECT * FROM `spareparts` 
INNER JOIN `spareparts_names_list` ON `spareparts`.`sparepart_name`=`spareparts_names_list`.`spareparts_names_id`
INNER JOIN `spareparts_brands_list` ON `spareparts`.`sparepart_brand`=`spareparts_brands_list`.`spareparts_brands_id`
ORDER BY `spareparts_names_name` ASC");
//$sp_names = mysqli_query($link, "SELECT * FROM `spareparts_names_list` ORDER BY `spareparts_names_list`.`spareparts_names_name` ASC");
//$sp_brands = mysqli_query($link, "SELECT * FROM `spareparts_brands_list` ORDER BY `spareparts_brands_list`.`spareparts_brands_name` ASC");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/bootstrap.min.css" >
    <link rel="stylesheet" href="css/select2.min.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Logic: Склад</title>
</head>
<body>

<div class="modal fade" id="addService" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Оприходование на склад</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="spareparts_inc.php" method="post">
                    <div class="form-row mt-1">
                        <div class="col-12">
                            <select name="supplier" class="form-control">
                                <option value="3">Коррекция склада</option>
                                <?php foreach ($suppliers as $item) {
                                    ?>
                                    <option value="<?php echo $item['supplier_id'] ?>"><?php echo $item['supplier_penname'] ?></option>
                                <?
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row mt-1">
                        <div class="col-12">
                            <input type="text" name="invoice_num" class="form-control" placeholder="Номер накладной">
                        </div>
                    </div>
                    <div class="form-row mt-1">
                        <div class="col-12">
                            <input type="date" name="invoice_date" class="form-control" placeholder="Дата накладной">
                        </div>
                    </div>
                    <div class="form-row mt-1">
                        <div class="col-12">
                            <select name="sparepart" class="form-control">
                                <?php foreach ($spareparts as $item) {
                                    ?>
                                    <option value="<?php echo $item['sparepart_id'] ?>"><?php echo $item['sparepart_num'] ?> : <?php echo $item['spareparts_names_name'] ?> : <?php echo $item['spareparts_brands_name'] ?></option>
                                <?
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row mt-1">
                        <div class="col-12">
                            <input type="text" name="cost" class="form-control" placeholder="Цена закуп.">
                        </div>
                    </div>
                    <div class="form-row mt-1">
                        <div class="col-12">
                            <input type="text" name="price" class="form-control" placeholder="Цена прод.">
                        </div>
                    </div>
                    <div class="form-row mt-1">
                        <div class="col-12">
                            <input type="text" name="qty_inc" class="form-control" placeholder="Кол-во">
                        </div>
                    </div>
                    <div class="form-row mt-1">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Оприходовать</button>
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

            <?php include "control_panel_top.php"?>

            <div class="form-row">
                <div class="col-6">
                    <?php
                    $entered_user = $_SESSION['user']['rank'];
                    if ($entered_user == 2) {
                        ?>
                        <button type="button" class="btn btn-warning my-1" data-toggle="modal" data-target="#addService">&#10010;</button>
                        <?php
                    }
                    ?>

                </div>
                <div class="col-3">
                    <form action="spareparts_stock.php" method="get">
                        <input type="text" class="form-control my-1" name="hard_filter" placeholder="Жесткий фильтр">
                    </form>
                </div>
                <div class="col-3">
                    <input type="text" class="form-control my-1" placeholder="Живой поиск" id="filter-input">
                </div>
            </div>

            <table class="table table-bordered table-striped table-hover">
                <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Артикул запчасти</th>
                    <th>Бренд</th>
                    <th>Наименование запчасти</th>
                    <th>Остаток</th>
                    <th>Цена закуп.</th>
                    <th>Цена прод.</th>
                    <th>Поставщик</th>
                    <th>Накладная</th>
                </tr>
                </thead>

                <tbody id="filter-list">

                <?php
                if ($result = mysqli_query($link, $query)) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                        <tr>
                            <td><?php echo $row['transaction_id']; ?></td>
                            <td><?php echo $row['sparepart_num']; ?></td>
                            <td><?php echo $row['spareparts_brands_name']; ?></td>
                            <td><?php echo $row['spareparts_names_name']; ?></td>
                            <td><?php echo $row['qty_now']; ?></td>
                            <td><?php echo $row['cost']; ?></td>
                            <td><?php echo $row['price']; ?></td>
                            <td><?php echo $row['supplier_penname']; ?></td>
                            <td><?php echo $row['invoice_num']; ?></td>
                        </tr>
                        <?php
                    }
                    mysqli_free_result($result);
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
<script src="js/select2.min.js"></script>
<script src="js/main.js"></script>
<script>

$("#search-input").on('keyup', function)

</script>

</body>
</html>
