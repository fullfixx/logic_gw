<?php

?>


<div class="row">
    <div class="col-sm-12">

        <!-- начало формы добавления комментариев к заказу -->
        <form action="add_post.php" method="post" class="form-inline">
            <div class="form-group mb-1">
                <textarea name="chat_post" cols="40" rows="2" placeholder="У нас не ругаются матом!" id="mess" onkeydown="keyDown(event)" onkeyup="keyUp(event)"></textarea>
                <input name="order_num" type="hidden" value="<?php echo $order_num; ?>">
            </div>
            <div class="form-group">
                <select class="form-control" type="number" name="from_user">
                    <?php
                    while($out = mysqli_fetch_assoc($users))
                    {
                        ?>
                        <option value="<?php echo $out['id']; ?>"><?php echo $out['full_name']; ?></option>
                        <?php
                    }
                    ?>
                </select>
                <button type="submit" class="btn btn-success ml-2" id="go">Ok</button>
            </div>
        </form>
        <!-- конец формы добавления комментариев к заказу -->

    </div>
</div>
<hr>
<!-- начало области с главным комментарием к заказу -->
<h6>Причина обращения со слов клиента:</h6>
<div class="w-100 rounded-top chat-info-primary"><strong><?php echo $comment_order['full_name']; ?> (ID: </strong> <?php echo $comment_order['creator']; ?>) <?php echo $comment_order['data_open']; ?></div>
<div class="chat-plate-primary rounded-bottom"><em><?php echo $comment_order['comment_order'] ?></em></div>
</p>
<!-- конец области с главным комментарием к заказу -->

<!-- начало области с лентой комментариев к заказу -->
<p>
<h6>Выполнение работ:</h6>
<?php
while($out = mysqli_fetch_assoc($order_posts))
{
    ?>
    <p><div class="w-100 rounded-top chat-info"><strong><?php echo $out['user_full_name']; ?></strong> (ID: <?php echo $out['user_id']; ?>) в <?php echo $out['time_create_post']; ?></div>
    <div class="chat-plate rounded-bottom"><em><strong><?php echo $out['id'] == 1 ? "" : $out['full_name']."," ?> </strong><?php echo $out['chat_post']; ?></em></div>
    <?php
}
?>
<!-- конец области с лентой комментариев к заказу -->
