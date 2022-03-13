<?php

?>
<h5>Поиск работ и услуг (<mark><?php echo $device_class['class_num']; ?></mark>):</h5>
<p>
<div class="form-inline">
    <div class="form-group mb-2 mr-2">
        <!-- Форма поиска услуг SearchServiceSet -->
        <form action="update.php" method="get">
            <input class="form-control" type="text" placeholder="Поиск услуги" value="<?php echo $search_ss; ?>" name="search_ss">
            <input type="hidden" name="id" value="<?php echo $order_num; ?>">
            <input type="hidden" name="search_sp" value="<?php echo $search_sp; ?>">
            <button type="submit" class="btn btn-primary SaveScroll">Поиск</button>
        </form>
    </div>
    <div class="form-group mb-2">
        <!-- Форма поиска работ SearchService -->
        <form action="update.php" method="get">
            <input class="form-control" type="text" placeholder="Поиск работ" value="<?php echo $search_s; ?>" name="search_s">
            <input type="hidden" name="id" value="<?php echo $order_num; ?>">
            <input type="hidden" name="search_sp" value="<?php echo $search_sp; ?>">
            <button type="submit" class="btn btn-primary SaveScroll">Поиск</button>
        </form>
    </div>
</div>

<!-- Вывод результов поиска SearchServiceSet -->
<?php
    while ($search_ss_out = mysqli_fetch_assoc($search_ss_result)) {
        ?>
        <form action="gas_adding_s.php" method="post">
            <div class="form-row">
                <div class="form-group col-sm-6">
                    <input class="form-control form-control-sm" type="text" value="<?php echo $search_ss_out['service_names_name']; ?>" name="gas_name">
                </div>
                <div class="form-group col-sm-1">
                    <input class="form-control form-control-sm" type="text" value="<?php echo $search_ss_out['time_rate']; ?>" name="time_rate">
                </div>
                <div class="form-group col-sm-1">
                    <input class="form-control form-control-sm" type="text" value="<?php echo $search_ss_out['serviceset_element_qty']; ?>" name="gas_qty">
                </div>
                <div class="form-group col-sm-1">
                    <input class="form-control form-control-sm" type="text" placeholder="0%" name="discont">
                </div>
                <div class="form-group col-sm-2">
                    <select name="executor" class="form-control form-control-sm">
                        <option value="<?php echo $user_id; ?>" selected><?php echo $full_name; ?></option>
                        <?php foreach ($executor as $out_exe) {
                            ?>
                            <option value="<?php echo $out_exe['id']; ?>"><?php echo $out_exe['full_name']; ?></option>
                            <?php
                        } ?>
                    </select>
                </div>
                <div class="form-group col-sm-1">
                    <input type="hidden" name="gas_type" value="s">
                    <input type="hidden" name="class_price" value="<?php echo $class_price; ?>">
                    <input type="hidden" name="order_num" value="<?php echo $order_num; ?>">
                    <input type="hidden" name="search_ss" value="<?php echo $search_ss; ?>">
                    <input type="hidden" name="search_s" value="<?php echo $search_s; ?>">
                    <input type="hidden" name="search_sp" value="<?php echo $search_sp; ?>">
                    <input type="hidden" name="cost" value="0">
                    <button type="submit" class="btn btn-sm btn-primary SaveScroll">+</button>
                </div>
            </div>
        </form>
        <?php
    }
    mysqli_free_result($search_ss_result);
?>
<!-- Вывод результов поиска SearchService -->
<?php
while ($search_s_out = mysqli_fetch_assoc($search_s_result)) {
    ?>
    <form action="gas_adding_s.php" method="post">
        <div class="form-row">
            <div class="form-group col-sm-6">
                <input class="form-control form-control-sm" type="text" value="<?php echo $search_s_out['service_names_name']; ?>" name="gas_name">
            </div>
            <div class="form-group col-sm-1">
                <input class="form-control form-control-sm" type="text" value="<?php echo $search_s_out['time_rate']; ?>" name="time_rate">
            </div>
            <div class="form-group col-sm-1">
                <input class="form-control form-control-sm" type="text" value="1" name="gas_qty">
            </div>
            <div class="form-group col-sm-1">
                <input class="form-control form-control-sm" type="text" placeholder="0%" name="discont">
            </div>
            <div class="form-group col-sm-2">
                <select name="executor" class="form-control form-control-sm">
                    <option value="<?php echo $user_id; ?>" selected><?php echo $full_name; ?></option>
                    <?php foreach ($executor as $out_exe) {
                        ?>
                        <option value="<?php echo $out_exe['id']; ?>"><?php echo $out_exe['full_name']; ?></option>
                        <?php
                    } ?>
                </select>
            </div>
            <div class="form-group col-sm-1">
                <input type="hidden" name="gas_type" value="s">
                <input type="hidden" name="class_price" value="<?php echo $class_price; ?>">
                <input type="hidden" name="order_num" value="<?php echo $order_num; ?>">
                <input type="hidden" name="search_s" value="<?php echo $search_s; ?>">
                <input type="hidden" name="search_sp" value="<?php echo $search_sp; ?>">
                <input type="hidden" name="cost" value="0">
                <button type="submit" class="btn btn-sm btn-primary SaveScroll">+</button>
            </div>
        </div>
    </form>
    <?php
}
mysqli_free_result($search_s_result);
?>

<!-- Свободная форма добавления работ -->
<form action="gas_adding_s.php" method="post">
    <div class="form-row">
        <div class="form-group col-sm-6">
            <input class="form-control form-control-sm" type="text" placeholder="Наименование дополнительной работы" name="gas_name" maxlength="40">
        </div>
        <div class="form-group col-sm-1">
            <input class="form-control form-control-sm" type="text" placeholder="Нч" name="time_rate">
        </div>
        <div class="form-group col-sm-1">
            <input class="form-control form-control-sm" type="text" placeholder="Qty" name="gas_qty">
        </div>
        <div class="form-group col-sm-1">
            <input class="form-control form-control-sm" type="text" placeholder="0%" name="discont">
        </div>
        <div class="form-group col-sm-2">
            <select name="executor" class="form-control form-control-sm">
                <option value="<?php echo $user_id; ?>" selected><?php echo $full_name; ?></option>
                <?php foreach ($executor as $out_exe) {
                    ?>
                    <option value="<?php echo $out_exe['id']; ?>"><?php echo $out_exe['full_name']; ?></option>
                    <?php
                } ?>
            </select>
        </div>
        <div class="form-group col-sm-1">
            <input type="hidden" name="order_num" value="<?php echo $order_num; ?>">
            <input type="hidden" name="gas_type" value="s">
            <input type="hidden" name="class_price" value="<?php echo $class_price; ?>">
            <input type="hidden" name="search_ss" value="<?php echo $search_ss; ?>">
            <input type="hidden" name="search_sp" value="<?php echo $search_sp; ?>">
            <button type="submit" class="btn btn-sm btn-primary SaveScroll">+</button>
        </div>
    </div>
</form>

<hr>

<!-- Вывод добавленных работ и услуг -->
<h5>Услуги в квитанции (<mark><?php echo $device_class['class_num']; ?></mark>):</h5>
<p>
<table class="table table-bordered table-striped table-hover table-sm">
    <thead class="thead-dark">
    <tr>
        <th>Наименование</th>
        <th>Нч</th>
        <th>Цена</th>
        <th>Qty</th>
        <th>%</th>
        <th>Мастер</th>
        <th>#</th>
    </tr>
    </thead>

    <tbody>

<?php
while($out_s = mysqli_fetch_assoc($order_gas_s))
{
    ?>
    <tr>
        <td class="table-light"><?php echo $out_s['gas_name']; ?></td>
        <td class="table-light text-right"><?php echo $out_s['gas_time_rate']; ?></td>
        <td class="table-light text-right"><?php echo $out_s['price']; ?></td>
        <td class="table-light text-right"><?php echo $out_s['gas_qty']; ?></td>
        <td class="table-primary text-right"><?php echo $out_s['discont']; ?></td>
        <td class="table-light"><?php echo $full_name; ?></td>
        <form action="gas_del_s.php" method="post">
            <input type="hidden" name="gas_name" value="<?php echo $out_s['gas_name']; ?>">
            <input type="hidden" value="<?php echo $out_s['gas_time_rate']; ?>">
            <input type="hidden" name="price" value="<?php echo $out_s['price']; ?>">
            <input type="hidden" name="gas_qty" value="<?php echo $out_s['gas_qty']; ?>">
            <input type="hidden" name="discont" value="<?php echo $out_s['discont']; ?>">
            <input type="hidden" name="executor" value="<?php echo $full_name; ?>">
            <input type="hidden" name="order_num" value="<?php echo $order_num; ?>">
            <input type="hidden" name="search_ss" value="<?php echo $search_ss; ?>">
            <input type="hidden" name="search_s" value="<?php echo $search_s; ?>">
            <input type="hidden" name="search_sp" value="<?php echo $search_sp; ?>">
            <input type="hidden" name="gas_id" value="<?php echo $out_s['gas_id']; ?>">
            <td class="table-light"><button type="submit" class="btn btn-sm btn-danger SaveScroll">x</button></form></td>
    </tr>
    <?php
}
?>
    </tbody>
</table>
<h5 class="text-right">Итого за услуги: <?php echo number_format($amount_s['sum'], "2", ".", " "); ?> ₽</h5>
<hr>
<h5>Поиск запчастей (<mark><?php echo $device_class['class_num']; ?></mark>):</h5>
<p>
<div class="form-inline">
    <div class="form-group mb-2">
        <form action="update.php" method="get"> <!-- Форма поиска товаров SearchSet -->
            <input class="form-control" type="text" placeholder="Поиск запчасти" value="<?php echo $search_sp; ?>" name="search_sp">
            <input type="hidden" name="id" value="<?php echo $order_num; ?>">
            <input type="hidden" name="search_ss" value="<?php echo $search_ss; ?>">
            <button type="submit" class="btn btn-primary SaveScroll">Поиск</button>
        </form>
    </div>
</div>

<!-- Свободная форма добавления запчастей -->
<form action="gas_adding_p.php" method="post">
    <div class="form-row">
        <div class="form-group col-sm-2">
            <input class="form-control form-control-sm" type="text" placeholder="Артикул запчасти" name="gas_num">
        </div>
        <div class="form-group col-sm-3">
            <input class="form-control form-control-sm" type="text" placeholder="Свободное наименование з/ч" name="gas_name" maxlength="40">
        </div>
        <div class="form-group col-sm-1">
            <input class="form-control form-control-sm" type="text" placeholder="Цена" name="price">
        </div>
        <div class="form-group col-sm-1">
            <input class="form-control form-control-sm" type="text" placeholder="--" readonly>
        </div>
        <div class="form-group col-sm-1">
            <input class="form-control form-control-sm" type="text" placeholder="Qty" name="gas_qty">
        </div>
        <div class="form-group col-sm-1">
            <input class="form-control form-control-sm" type="text" placeholder="0%" name="discont">
        </div>
        <div class="form-group col-sm-2">
            <select name="executor" class="form-control form-control-sm">
                <option value="<?php echo $user_id; ?>" selected><?php echo $full_name; ?></option>
                <?php foreach ($executor as $out_exe) {
                    ?>
                    <option value="<?php echo $out_exe['id']; ?>"><?php echo $out_exe['full_name']; ?></option>
                    <?php
                } ?>
            </select>
        </div>
        <div class="form-group col-sm-1">
            <input type="hidden" name="order_num" value="<?php echo $order_num; ?>">
            <input type="hidden" name="gas_type" value="p">
            <input type="hidden" name="cost" value="0">
            <input type="hidden" name="search_ss" value="<?php echo $search_ss; ?>">
            <input type="hidden" name="search_sp" value="<?php echo $search_sp; ?>">
            <button type="submit" name="add" class="btn btn-sm btn-primary SaveScroll">+</button>
            <button type="submit" name="backorder" class="btn btn-sm btn-warning SaveScroll">&#9719;</button>
        </div>
    </div>
</form>

<!-- Вывод результов поиска SpareParts -->
<?php
while ($search_sp_out = mysqli_fetch_assoc($search_sp_result)) {
    ?>
    <form action="gas_adding_p.php" method="post">
        <div class="form-row">
            <div class="form-group col-sm-2">
                <input class="form-control form-control-sm" type="text" value="<?php echo $search_sp_out['sparepart_num']; ?>" name="gas_num">
            </div>
            <div class="form-group col-sm-3">
                <input class="form-control form-control-sm" type="text" value="<?php echo $search_sp_out['spareparts_names_name']; ?>" name="gas_name">
            </div>
            <div class="form-group col-sm-1">
                <input class="form-control form-control-sm" type="text" value="<?php echo $search_sp_out['price']; ?>" name="price">
            </div>
            <div class="form-group col-sm-1">
                <input class="form-control form-control-sm" type="text" value="<?php echo $search_sp_out['qty_now']; ?>" name="qty_now" readonly>
            </div>
            <div class="form-group col-sm-1">
                <input class="form-control form-control-sm" type="text" value="1" name="gas_qty">
            </div>
            <div class="form-group col-sm-1">
                <input class="form-control form-control-sm" type="text" placeholder="0%" name="discont">
            </div>
            <div class="form-group col-sm-2">
                <select name="executor" class="form-control form-control-sm">
                    <option value="<?php echo $user_id; ?>" selected><?php echo $full_name; ?></option>
                    <?php foreach ($executor as $out_exe) {
                        ?>
                        <option value="<?php echo $out_exe['id']; ?>"><?php echo $out_exe['full_name']; ?></option>
                        <?php
                    } ?>
                </select>
            </div>
            <div class="form-group col-sm-1">
                <input type="hidden" name="gas_type" value="p">
                <input type="hidden" name="order_num" value="<?php echo $order_num; ?>">
                <input type="hidden" name="search_sp" value="<?php echo $search_sp; ?>">
                <input type="hidden" name="search_ss" value="<?php echo $search_ss; ?>">
                <input type="hidden" name="transaction_id" value="<?php echo $search_sp_out['transaction_id']; ?>">
                <input type="hidden" name="qty_now" value="<?php echo $search_sp_out['qty_now']; ?>">
                <input type="hidden" name="cost" value="<?php echo $search_sp_out['cost']; ?>">
                <button type="submit" name="add" class="btn btn-sm btn-primary SaveScroll">+</button>
                <button type="submit" name="backorder" class="btn btn-sm btn-warning SaveScroll">&#9719;</button>
            </div>
        </div>
    </form>
    <?php
}
mysqli_free_result($search_sp_result);
?>

<hr>
<!-- Вывод добавленных запчастей -->
<h5>Товары в квитанции (<mark><?php echo $device_class['class_num']; ?></mark>):</h5>
<p>
<table class="table table-bordered table-striped table-hover table-sm">
    <thead class="thead-dark">
    <tr>
        <th>Артикул</th>
        <th>Наименование</th>
        <th>Цена</th>
        <th>Qty</th>
        <th>%</th>
        <th>Мастер</th>
        <th>#</th>
    </tr>
    </thead>

    <tbody>

<?php
while($out_p = mysqli_fetch_assoc($order_gas_p))
{
    ?>
    <tr>
        <td class="align-middle table-light"><?php echo $out_p['gas_num']; ?></td>
        <td class="align-middle table-<?php
                            if ($out_p['gas_sold'] == 3) {
                                echo "danger";
                            } elseif ($out_p['gas_sold'] == 4) {
                                echo "success";
                            } else {
                                echo "light";
                            }?>"><?php echo $out_p['gas_name']; ?></td>
        <td class="align-middle table-light text-right"><?php echo $out_p['price']; ?></td>
        <td class="align-middle table-light text-right"><?php echo $out_p['gas_qty']; ?></td>
        <td class="align-middle table-warning text-right"><?php echo $out_p['discont']; ?></td>
        <td class="align-middle table-light"><?php echo $full_name; ?></td>
        <form action="gas_del_p.php" method="post">
        <input type="hidden" name="gas_name" value="<?php echo $out_p['gas_name']; ?>">
        <input type="hidden" value="<?php echo $out_p['gas_time_rate']; ?>">
        <input type="hidden" name="price" value="<?php echo $out_p['price']; ?>">
        <input type="hidden" name="gas_qty" value="<?php echo $out_p['gas_qty']; ?>">
        <input type="hidden" name="discont" value="<?php echo $out_p['discont']; ?>">
        <input type="hidden" name="executor" value="<?php echo $full_name; ?>">
        <input type="hidden" name="order_num" value="<?php echo $order_num; ?>">
        <input type="hidden" name="transaction" value="<?php echo $out_p['transaction']; ?>">
        <input type="hidden" name="search_sp" value="<?php echo $search_sp; ?>">
            <input type="hidden" name="search_ss" value="<?php echo $search_ss; ?>">
        <input type="hidden" name="gas_id" value="<?php echo $out_p['gas_id']; ?>">
        <td class="table-light"><button type="submit" class="btn btn-sm btn-danger SaveScroll">x</button></form></td>
    </tr>
    <?php
}
?>
    </tbody>
</table>

<h5 class="text-right">Итого за запчасти: <?php echo number_format($amount_p['sum'], "2", ".", " "); ?> ₽</h5>

<h5 class="text-right">Всего за заказ: <?php echo number_format(($amount_s['sum']+$amount_p['sum']), "2", ".", " "); ?> ₽</h5>
<hr>