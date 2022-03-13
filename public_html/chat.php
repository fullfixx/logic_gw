<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: index.php');
}
require_once 'db.php';
$order_num = mysqli_real_escape_string($link, $_GET['id']);

$comment_order = mysqli_query($link, "SELECT * FROM `orders_list` INNER JOIN `users` ON `orders_list`.`creator`=`users`.`id` WHERE `order_num`='$order_num'");
$comment_order = mysqli_fetch_assoc($comment_order);
$order_posts = mysqli_query($link, "SELECT * FROM `chat` WHERE `order_num`='$order_num' ORDER BY `chat`.`time_create_post` ASC");
//mysqli_real_escape_string($db, $_POST['username]);
//$order_num = $_GET['id'];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/bootstrap.min.css" >
    <link rel="stylesheet" href="css/style.css">
    <title>Logic GW</title>

</head>
<body>
<header></header>
<main>
    <div class="chat-title">
    <div class="container-fluid">

        <div class="row">
            <div class="col-sm-12">

        <a class="btn btn-success btn-sm mt-2" role="button" href="towork.php">Активные заказы</a>
        <a class="btn btn-secondary btn-sm mt-2" role="button" href="archive.php">Архивные заказы</a>
        <a class="btn btn-warning btn-sm mt-2" target="_blank" role="button" href="../queue/">Эл.очередь</a>
        <div style="text-align: right;">Вы работаете в системе по именем: <mark><?= $_SESSION['user']['full_name'] ?></mark> <small>(ID: <?= $_SESSION['user']['id'] ?>)</small> | <a href="logout.php" class="logout">Выход</a></div>

        <h3><?php echo $order_num; ?></h3>
        <p class="h3"><a href="update.php?id=<?php echo $order_num; ?>">Изменение заказа</a> / Комментарии к заказу</p>

            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">

                <p><form action="add_post.php" method="post">
                    <div class="form-group">
                        <textarea name="chat_post" cols="80" rows="3" id="mess" onkeydown="keyDown(event)" onkeyup="keyUp(event)"></textarea>
                        <input name="order_num" type="hidden" value="<?php echo $order_num; ?>">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" id="go">Написать</button>
                    </div>
                </form></p>

            </div>
            <div class="col-sm-6">

                <p>
                <h6>Комментарий при создании заказа:</h6>
                <div class="w-50 rounded-top chat-info"><strong><?php echo $comment_order['full_name']; ?> (ID: </strong> <?php echo $comment_order['creator']; ?>) <?php echo $comment_order['data_open']; ?></div>
                <div class="chat-plate rounded-bottom"><em><?php echo $comment_order['comment_order'] ?></em></div>
                </p>

                <p>
                <h6>Сообщения при выполнении заказа:</h6>
                <?php
                while($out = mysqli_fetch_assoc($order_posts))
                {
                ?>
                <p><div class="w-50 chat-info"><strong><?php echo $out['user_full_name']; ?></strong> (ID: <?php echo $out['user_id']; ?>) в <?php echo $out['time_create_post']; ?></div>
                <div class="chat-plate"><em><?php echo $out['chat_post']; ?></em></div></p>
                    <?php
                }
                ?>


            </div>
        </div>

    </div>
    </div>
</main>
<footer></footer>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
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