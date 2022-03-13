<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: m.index.php');
}
require_once 'db.php';
$entered_user = $_SESSION['user']['id'];
$active_rank = $_SESSION['user']['rank'];
$row_post = mysqli_fetch_array(mysqli_query($link,"SELECT count(*) FROM `chat` WHERE `from_user` = '$entered_user' AND `post_read` = 1"));
$count_post = $row_post[0];
$query = "SELECT * FROM `chat` INNER JOIN `users` ON `chat`.`from_user` = `users`.`id` WHERE `from_user`=? ORDER BY `chat`.`time_create_post` DESC LIMIT 50";
$stmt = mysqli_stmt_init($link);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_bind_param($stmt, "s", $entered_user);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    echo "STMT error";
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
    <title>Logic GW</title>
</head>
<body>
<header></header>
<main>
    <div class="towork-title">
        <div class="container-fluid">
            <a href="m.workarea.php" class="SaveScroll btn btn-warning btn-sm btn-block mt-1 mb-2">ВЕРНУТЬСЯ К СПИСКУ ЗАКАЗОВ</a>

            <h4>НОВЫЕ СООБЩЕНИЯ</h4>
            <?php
            while($out = mysqli_fetch_assoc($result))
            {
                ?>
                <p><div class="w-100 rounded-top <?php echo $out['post_read'] == 1 ? "chat-info" : "chat-info-read" ?>"><strong><?php echo $out['user_full_name']; ?></strong> (ID: <?php echo $out['user_id']; ?>) в <?php echo $out['time_create_post']; ?></div>
                <div class="rounded-bottom SaveScroll <?php echo $out['post_read'] == 1 ? "chat-plate" : "chat-plate-read" ?>">
                    <a href="m.updto_post_read.php?post_id=<?php echo $out['post_id']; ?>" title="Пометить как прочтённое">
                        <?php echo $out['post_read'] == 1 ? '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill-rule="evenodd" d="M1 12C1 5.925 5.925 1 12 1s11 4.925 11 11-4.925 11-11 11S1 18.075 1 12zm16.28-2.72a.75.75 0 00-1.06-1.06l-5.97 5.97-2.47-2.47a.75.75 0 00-1.06 1.06l3 3a.75.75 0 001.06 0l6.5-6.5z"></path></svg>' : '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M17.28 9.28a.75.75 0 00-1.06-1.06l-5.97 5.97-2.47-2.47a.75.75 0 00-1.06 1.06l3 3a.75.75 0 001.06 0l6.5-6.5z"></path><path fill-rule="evenodd" d="M12 1C5.925 1 1 5.925 1 12s4.925 11 11 11 11-4.925 11-11S18.075 1 12 1zM2.5 12a9.5 9.5 0 1119 0 9.5 9.5 0 01-19 0z"></path></svg>' ?>
                    </a> <em><strong><?php echo $out['full_name']; ?></strong>, <?php echo $out['chat_post']; ?> &laquo;<a href="<?php echo $active_rank == 3 ? "m.workarea_" : "" ?>update.php?id=<?php echo $out['order_num']; ?>">Заказ № <?php echo $out['order_num']; ?></a>&raquo;</em></div>
                <?php
            }
            ?>

        </div>
    </div>
</main>
</div>
</div>
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

