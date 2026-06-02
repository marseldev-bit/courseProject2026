<?php
    $errors = [];
    $sql = "SELECT * FROM ordersDelivery";
    $stmt = $connect->prepare($sql);
    $stmt->execute([]);
    $ordersDelivery = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sql = "SELECT * FROM ordersPickup";
    $stmt = $connect->prepare($sql);
    $stmt->execute([]);
    $ordersPickup = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $active = [];
    $arrived = [];
    $completed = [];
    $canceled = [];

    if(isset($_POST['search']) and (!empty($_POST['itemId']) or !empty($_POST['userId']))) {
        if(!empty($_POST['itemId']) and empty($_POST['userId'])) {
            foreach($ordersDelivery as $order) {
                if($order['item_id'] == $_POST['itemId']) {
                    if($order['status'] == 'В пути' or $order['status'] == 'Задерживается') array_push($active, $order);
                    elseif($order['status'] == 'Завершен')  array_push($completed, $order);
                    elseif($order['status'] == 'Отменен')  array_push($canceled, $order);
                }
            }

            foreach($ordersPickup as $order) {
                if($order['item_id'] == $_POST['itemId']) {
                    if($order['status'] == 'В пути' or $order['status'] == 'Задерживается') array_push($active, $order);
                    elseif($order['status'] == 'Завершен')  array_push($completed, $order);
                    elseif($order['status'] == 'Отменен')  array_push($canceled, $order);
                    elseif($order['status'] == 'Можно забирать')  array_push($arrived, $order);
                }
            }
        }
        elseif(!empty($_POST['itemId']) and !empty($_POST['userId'])) {
            foreach($ordersDelivery as $order) {
                if($order['item_id'] == $_POST['itemId'] and $order['user_id'] == $_POST['userId']) {
                    if($order['status'] == 'В пути' or $order['status'] == 'Задерживается') array_push($active, $order);
                    elseif($order['status'] == 'Завершен')  array_push($completed, $order);
                    elseif($order['status'] == 'Отменен')  array_push($canceled, $order);
                }
            }

            foreach($ordersPickup as $order) {
                if($order['item_id'] == $_POST['itemId'] and $order['user_id'] == $_POST['userId']) {
                    if($order['status'] == 'В пути' or $order['status'] == 'Задерживается') array_push($active, $order);
                    elseif($order['status'] == 'Завершен')  array_push($completed, $order);
                    elseif($order['status'] == 'Отменен')  array_push($canceled, $order);
                    elseif($order['status'] == 'Можно забирать')  array_push($arrived, $order);
                }
            }
        }
        elseif(empty($_POST['itemId']) and !empty($_POST['userId'])) {
            foreach($ordersDelivery as $order) {
                if($order['user_id'] == $_POST['userId']) {
                    if($order['status'] == 'В пути' or $order['status'] == 'Задерживается') array_push($active, $order);
                    elseif($order['status'] == 'Завершен')  array_push($completed, $order);
                    elseif($order['status'] == 'Отменен')  array_push($canceled, $order);
                }
            }

            foreach($ordersPickup as $order) {
                if($order['user_id'] == $_POST['userId']) {
                    if($order['status'] == 'В пути' or $order['status'] == 'Задерживается') array_push($active, $order);
                    elseif($order['status'] == 'Завершен')  array_push($completed, $order);
                    elseif($order['status'] == 'Отменен')  array_push($canceled, $order);
                    elseif($order['status'] == 'Можно забирать')  array_push($arrived, $order);
                }
            }
        }
    }
    else {
        foreach($ordersDelivery as $order) {
            if($order['status'] == 'В пути' or $order['status'] == 'Задерживается') array_push($active, $order);
            elseif($order['status'] == 'Завершен')  array_push($completed, $order);
            elseif($order['status'] == 'Отменен')  array_push($canceled, $order);
        }

        foreach($ordersPickup as $order) {
            if($order['status'] == 'В пути' or $order['status'] == 'Задерживается') array_push($active, $order);
            elseif($order['status'] == 'Завершен')  array_push($completed, $order);
            elseif($order['status'] == 'Отменен')  array_push($canceled, $order);
            elseif($order['status'] == 'Можно забирать')  array_push($arrived, $order);
        }
    }

    if(isset($_POST['cancelD'])) {
        if(empty($_POST['cancelReason'])) $errors['cancelReason'] = 'Укажите причину отмены заказа';
        elseif(empty($errors)) {
            $sql = "UPDATE ordersDelivery SET status = ?, cancelReason = ? WHERE id = ?";
            $stmt = $connect->prepare($sql);
            $stmt->execute(['Отменен', $_POST['cancelReason'], $_POST['cancelOrderId']]);
            echo '<script>location.href="?page=managePanel&orders"</script>';
        }
    }

    if(isset($_POST['cancelP'])) {
        if(empty($_POST['cancelReason'])) $errors['cancelReason'] = 'Укажите причину отмены заказа';
        elseif(empty($errors)) {
            $sql = "UPDATE ordersPickup SET status = ?, cancelReason = ? WHERE id = ?";
            $stmt = $connect->prepare($sql);
            $stmt->execute(['Отменен', $_POST['cancelReason'], $_POST['cancelOrderId']]);
            echo '<script>location.href="?page=managePanel&orders"</script>';
        }
    }
?>

<div class="manageOrders cont manageSection">
    <div class="catalogHeader">
        <form class="filters" method="post">
            <div class="search">
                <input name="itemId" type="text" placeholder="ID товара..."
                <?php if(!empty($_POST['itemId'])) { ?>value="<?= $_POST['itemId'] ?>"<?php } ?>>
            </div>

            <div class="search">
                <input name="userId" type="number" placeholder="ID пользователя..."
                <?php if(!empty($_POST['userId'])) { ?>value="<?= $_POST['userId'] ?>"<?php } ?>>
            </div>

            <button name="search">Применить</button>
        </form>
    </div>
<?php if(empty($active) and empty($completed) and empty($canceled) and empty($arrived)) { ?>
<div class="emptyOrders cont">
        <h2>Заказов нет</h2>
</div>
<?php }

elseif(!empty($arrived)) { $count = 0; ?>
<div class="userOrders">
    <p class="orderStatus">Можно забирать</p>
    <div class="ordersHistory">
        <?php foreach($arrived as $a) {
            $sql = "SELECT * FROM items WHERE id = ?";
            $stmt = $connect->prepare($sql);
            $stmt->execute([$a['item_id']]);
            $item = $stmt->fetch(PDO::FETCH_ASSOC);
            if(isset($a['time'])) $orderType = true;
            else $orderType = false;

            $sql = "SELECT imgPath FROM itemGallery WHERE item_id = ?";
            $stmt = $connect->prepare($sql);
            $stmt->execute([$a['item_id']]);
            $img = $stmt->fetch(PDO::FETCH_ASSOC);

            $sql = "SELECT name FROM users WHERE id = ?";
            $stmt = $connect->prepare($sql);
            $stmt->execute([$a['user_id']]);
            $username = $stmt->fetch(PDO::FETCH_ASSOC);
            if($count > 0) { ?>
                <div class="separateLine"></div>
            <?php } ?>
        <div class="cartItem">
         <div class="cartItemBlock">
                <img src="<?= $img['imgPath'] ?>" alt="">
               <div class="cartItemInfo">
                   <h1><span class="id"><?= $item['id'] ?></span><?= $item['name'] ?></h1>
                   <div class="cartItemPrice">
                       <p><?= $item['price'] * $a['quanity'] ?> ₽</p>
                       <h2><?= $a['quanity'] ?> шт</h2>
                       <h1>Заказ от <?= $a['date'] ?></h1>
                   </div>
               </div>
           </div>

         <div class="cartItemOptions">
                <a href="?page=managePanel&orders&code=<?= $a['id'] ?>">Код</a>
                <?php if($orderType) { ?>
                    <a href="?page=managePanel&orders&delivery=<?= $a['id'] ?>">Подробности</a>
                <?php } else { ?>
                    <a href="?page=managePanel&orders&pickup=<?= $a['id'] ?>">Подробности</a>
                <?php } ?>
           </div>

           <h1 class="customerTablet">Заказчик:<span class="id"><?= $a['user_id'] ?></span><?= $username['name'] ?></h1>
       </div>
        <h1 class="customer">Заказчик:<span class="id"><?= $a['user_id'] ?></span><?= $username['name'] ?></h1>

        <div class="orderMobile">
                <div class="orderMobileHeader">
                    <img src="<?= $img['imgPath'] ?>" alt="">
                    <h1><span class="id"><?= $item['id'] ?></span><?= $item['name'] ?></h1>
                </div>
                <div class="orderMobileInfo">
                    <p>Заказчик: <span class="id"><?= $a['user_id'] ?></span><?= $username['name'] ?></p>
                    <p><span>Стоимость:</span> <?= $item['price'] * $a['quanity'] ?> ₽</p>
                    <p><span>Количество:</span> <?= $a['quanity'] ?> шт</p>
                    <p><span>Дата заказа:</span> <?= $a['date'] ?></p>
                </div>
                <div class="orderMobileOptions">
                    <?php if($orderType) { ?>
                        <a href="?page=managePanel&orders&delivery=<?= $a['id'] ?>">Подробности</a>
                    <?php } else { ?>
                        <a href="?page=managePanel&orders&pickup=<?= $a['id'] ?>">Подробности</a>
                    <?php } 
                    if($orderType) { ?>
                    <a class="cancel" href="?page=managePanel&orders&cancelD=<?= $a['id'] ?>">Отменить</a>
                    <?php } else { ?>
                    <a class="cancel" href="?page=managePanel&orders&cancelP=<?= $a['id'] ?>">Отменить</a>
                    <?php } ?>
                </div>
        </div>
        <?php $count += 1; } ?>
    </div>
</div>
<?php }

if(!empty($active)) { $count = 0; ?>
<div class="userOrders">
    <p class="orderStatus">В пути</p>
    <div class="ordersHistory">
        <?php foreach($active as $a) {
            $sql = "SELECT * FROM items WHERE id = ?";
            $stmt = $connect->prepare($sql);
            $stmt->execute([$a['item_id']]);
            $item = $stmt->fetch(PDO::FETCH_ASSOC);
            if(isset($a['time'])) $orderType = true;
            else $orderType = false;

            $sql = "SELECT imgPath FROM itemGallery WHERE item_id = ?";
            $stmt = $connect->prepare($sql);
            $stmt->execute([$a['item_id']]);
            $img = $stmt->fetch(PDO::FETCH_ASSOC);

            $sql = "SELECT name FROM users WHERE id = ?";
            $stmt = $connect->prepare($sql);
            $stmt->execute([$a['user_id']]);
            $username = $stmt->fetch(PDO::FETCH_ASSOC);
            if($count > 0) { ?>
                <div class="separateLine"></div>
            <?php } ?>
        <div class="cartItem">
         <div class="cartItemBlock">
                <img src="<?= $img['imgPath'] ?>" alt="">
               <div class="cartItemInfo">
                   <h1><span class="id"><?= $item['id'] ?></span><?= $item['name'] ?></h1>
                   <div class="cartItemPrice">
                       <p><?= $item['price'] * $a['quanity'] ?> ₽</p>
                       <h2><?= $a['quanity'] ?> шт</h2>
                       <h1>Заказ от <?= $a['date'] ?></h1>
                   </div>
               </div>
           </div>

         <div class="cartItemOptions">
                <?php if($orderType) { ?>
                    <a href="?page=managePanel&orders&delivery=<?= $a['id'] ?>">Подробности</a>
                <?php } else { ?>
                    <a href="?page=managePanel&orders&pickup=<?= $a['id'] ?>">Подробности</a>
                <?php } 
                if($orderType) { ?>
                    <a class="cancel" href="?page=managePanel&orders&cancelD=<?= $a['id'] ?>">Отменить</a>
                <?php } else { ?>
                    <a class="cancel" href="?page=managePanel&orders&cancelP=<?= $a['id'] ?>">Отменить</a>
                <?php } ?>
           </div>

           <h1 class="customerTablet">Заказчик:<span class="id"><?= $a['user_id'] ?></span><?= $username['name'] ?></h1>
       </div>
        <h1 class="customer">Заказчик:<span class="id"><?= $a['user_id'] ?></span><?= $username['name'] ?></h1>

        <div class="orderMobile">
                <div class="orderMobileHeader">
                    <img src="<?= $img['imgPath'] ?>" alt="">
                    <h1><span class="id"><?= $item['id'] ?></span><?= $item['name'] ?></h1>
                </div>
                <div class="orderMobileInfo">
                    <p>Заказчик: <span class="id"><?= $a['user_id'] ?></span><?= $username['name'] ?></p>
                    <p><span>Стоимость:</span> <?= $item['price'] * $a['quanity'] ?> ₽</p>
                    <p><span>Количество:</span> <?= $a['quanity'] ?> шт</p>
                    <p><span>Дата заказа:</span> <?= $a['date'] ?></p>
                </div>
                <div class="orderMobileOptions">
                    <?php if($orderType) { ?>
                        <a href="?page=managePanel&orders&delivery=<?= $a['id'] ?>">Подробности</a>
                    <?php } else { ?>
                        <a href="?page=managePanel&orders&pickup=<?= $a['id'] ?>">Подробности</a>
                    <?php } 
                    if($orderType) { ?>
                    <a class="cancel" href="?page=managePanel&orders&cancelD=<?= $a['id'] ?>">Отменить</a>
                    <?php } else { ?>
                    <a class="cancel" href="?page=managePanel&orders&cancelP=<?= $a['id'] ?>">Отменить</a>
                    <?php } ?>
                </div>
        </div>
        <?php $count += 1; } ?>
    </div>
</div>
<?php }

if(!empty($completed)) { $count = 0; ?>
<div class="userOrders">
    <p class="orderStatus">Завершенные</p>
    <div class="ordersHistory">
        <?php foreach($completed as $c) {
            $sql = "SELECT * FROM items WHERE id = ?";
            $stmt = $connect->prepare($sql);
            $stmt->execute([$c['item_id']]);
            $item = $stmt->fetch(PDO::FETCH_ASSOC);
            if(isset($c['time'])) $orderType = true;
            else $orderType = false;

            $sql = "SELECT imgPath FROM itemGallery WHERE item_id = ?";
            $stmt = $connect->prepare($sql);
            $stmt->execute([$c['item_id']]);
            $img = $stmt->fetch(PDO::FETCH_ASSOC);

            $sql = "SELECT name FROM users WHERE id = ?";
            $stmt = $connect->prepare($sql);
            $stmt->execute([$c['user_id']]);
            $username = $stmt->fetch(PDO::FETCH_ASSOC);
            if($count > 0) { ?>
                <div class="separateLine"></div>
            <?php } ?>
        <div class="cartItem">
         <div class="cartItemBlock">
                <img src="<?= $img['imgPath'] ?>" alt="">
               <div class="cartItemInfo">
                   <h1><span class="id"><?= $item['id'] ?></span><?= $item['name'] ?></h1>
                   <div class="cartItemPrice">
                       <p><?= $item['price'] * $c['quanity'] ?> ₽</p>
                       <h2><?= $c['quanity'] ?> шт</h2>
                       <h1>Заказ от <?= $c['date'] ?></h1>
                   </div>
               </div>
           </div>

         <div class="cartItemOptions">
                <p>Доставлен: <?= $c['delivery_date'] ?></p>
                <?php if($orderType) { ?>
                        <a href="?page=managePanel&orders&delivery=<?= $c['id'] ?>">Подробности</a>
                    <?php } else { ?>
                        <a href="?page=managePanel&orders&pickup=<?= $c['id'] ?>">Подробности</a>
                    <?php } ?>
           </div>

           <h1 class="customerTablet">Заказчик:<span class="id"><?= $c['user_id'] ?></span><?= $username['name'] ?></h1>
       </div>
        <h1 class="customer">Заказчик:<span class="id"><?= $c['user_id'] ?></span><?= $username['name'] ?></h1>

        <div class="orderMobile">
                <div class="orderMobileHeader">
                    <img src="../assets/images/cartItem2.png" alt="">
                    <h1><span class="id"><?= $item['id'] ?></span><?= $item['name'] ?></h1>
                </div>
                <div class="orderMobileInfo">
                    <p>Заказчик: <span class="id"><?= $c['user_id'] ?></span><?= $username['name'] ?></p>
                    <p><span>Стоимость:</span> <?= $item['price'] * $c['quanity'] ?> ₽</p>
                    <p><span>Количество:</span> <?= $c['quanity'] ?> шт</p>
                    <p><span>Дата заказа:</span> <?= $c['date'] ?></p>
                    <p><span>Доставлен:</span> <?= $c['delivery_date'] ?></p>
                </div>
                <div class="orderMobileOptions">
                    <?php if($orderType) { ?>
                        <a href="?page=managePanel&orders&delivery=<?= $c['id'] ?>">Подробности</a>
                    <?php } else { ?>
                        <a href="?page=managePanel&orders&pickup=<?= $c['id'] ?>">Подробности</a>
                    <?php } ?>
                </div>
            </div>
        <?php $count += 1; } ?>
    </div>
</div>
<?php }

if(!empty($canceled)) { $count = 0; ?>
<div class="userOrders">
    <p class="orderStatus">Отмененные</p>
    <div class="ordersHistory">
        <?php foreach($canceled as $c) {
            $sql = "SELECT * FROM items WHERE id = ?";
            $stmt = $connect->prepare($sql);
            $stmt->execute([$c['item_id']]);
            $item = $stmt->fetch(PDO::FETCH_ASSOC);
            if(isset($c['time'])) $orderType = true;
            else $orderType = false;

            $sql = "SELECT imgPath FROM itemGallery WHERE item_id = ?";
            $stmt = $connect->prepare($sql);
            $stmt->execute([$c['item_id']]);
            $img = $stmt->fetch(PDO::FETCH_ASSOC);

            $sql = "SELECT name FROM users WHERE id = ?";
            $stmt = $connect->prepare($sql);
            $stmt->execute([$c['user_id']]);
            $username = $stmt->fetch(PDO::FETCH_ASSOC);
            if($count > 0) { ?>
                <div class="separateLine"></div>
            <?php } ?>
        <div class="cartItem">
         <div class="cartItemBlock">
                <img src="<?= $img['imgPath'] ?>" alt="">
               <div class="cartItemInfo">
                   <h1><span class="id"><?= $item['id'] ?></span><?= $item['name'] ?></h1>
                   <div class="cartItemPrice">
                       <p><?= $item['price'] * $c['quanity'] ?> ₽</p>
                       <h2><?= $c['quanity'] ?> шт</h2>
                       <h1>Заказ от <?= $c['date'] ?></h1>
                   </div>
               </div>
           </div>

         <div class="cartItemOptions">
                <?php if($orderType) { ?>
                        <a href="?page=managePanel&orders&delivery=<?= $c['id'] ?>">Подробности</a>
                    <?php } else { ?>
                        <a href="?page=managePanel&orders&pickup=<?= $c['id'] ?>">Подробности</a>
                    <?php } ?>
           </div>

           <h1 class="customerTablet">Заказчик:<span class="id"><?= $c['user_id'] ?></span><?= $username['name'] ?></h1>
       </div>
        <h1 class="customer">Заказчик:<span class="id"><?= $c['user_id'] ?></span><?= $username['name'] ?></h1>

        <div class="orderMobile">
                <div class="orderMobileHeader">
                    <img src="../assets/images/cartItem2.png" alt="">
                    <h1><span class="id"><?= $item['id'] ?></span><?= $item['name'] ?></h1>
                </div>
                <div class="orderMobileInfo">
                    <p>Заказчик: <span class="id"><?= $c['user_id'] ?></span><?= $username['name'] ?></p>
                    <p><span>Стоимость:</span> <?= $item['price'] * $c['quanity'] ?> ₽</p>
                    <p><span>Количество:</span> <?= $c['quanity'] ?> шт</p>
                    <p><span>Дата заказа:</span> <?= $c['date'] ?></p>
                    <p><span>Доставлен:</span> <?= $c['delivery_date'] ?></p>
                </div>
                <div class="orderMobileOptions">
                    <?php if($orderType) { ?>
                        <a href="?page=managePanel&orders&delivery=<?= $c['id'] ?>">Подробности</a>
                    <?php } else { ?>
                        <a href="?page=managePanel&orders&pickup=<?= $c['id'] ?>">Подробности</a>
                    <?php } ?>
                </div>
            </div>
        <?php $count += 1; } ?>
    </div>
</div>
<?php } ?>

<?php if(isset($_GET['delivery'])) {
    $sql = "SELECT * FROM ordersDelivery WHERE id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$_GET['delivery']]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $sql = "SELECT * FROM items WHERE id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$order['item_id']]);
    $itemDelivery = $stmt->fetch(PDO::FETCH_ASSOC); ?>
    <div class="editName orderModal">
        <form method="post" class="editNameWindow">
            <a href="?page=managePanel&orders"><p>⨉</p></a>
            <div class="editNameBody">
                <h1>Информация о заказе</h1>
                <div class="orderInfoModal">
                    <h2>ID заказа: <span><?= $order['id'] ?></span></h2>
                    <h2>Тип заказа: Доставка</h2>
                    <h2>ID пользователя: <span><?= $order['user_id'] ?></span></h2>
                    <h2>ID товара: <span><?= $order['item_id'] ?></span></h2>
                    <h2>Товар: <span><?= $itemDelivery['name'] ?></span></h2>
                    <h2>Цена: <span><?= $itemDelivery['price'] * $order['quanity'] ?>₽</span></h2>
                    <h2>Количество: <span><?= $order['quanity'] ?></span></h2>
                    <h2>Дата заказа: <span><?= $order['date'] ?></span></h2>
                    <h2>Выбранная дата: <span><?= $order['picked_date'] ?></span></h2>
                    <h2>Выбранное время: <span><?= $order['time'] ?></span></h2>
                    <?php if(isset($orders['delivery_date'])) { ?>
                    <h2>Дата получения: <span><?= $order['delivery_date'] ?></span></h2>
                    <?php } ?>
                    <h2>Адрес получателя: <span><?= $order['address'] ?></span></h2>
                    <h2>Телефон получателя: <span><?= $order['phone'] ?></span></h2>
                    <?php if(!empty($order['comment'])) { ?>
                    <h2>Комментарий: <span><?= $order['comment'] ?></span></h2>
                    <?php } ?>
                    <h2>Статус: <span><?= $order['status'] ?></span></h2>
                    <?php if(!empty($order['cancelReason'])) { ?>
                    <h2>Причина отмены заказа: <span><?= $order['cancelReason'] ?></span></h2>
                    <?php } ?>
                </div>
                <a href="?page=editOrder&delivery=<?= $order['id'] ?>">Редактировать данные</a>
            </div>
        </form>
    </div>
    <?php } ?>

    <?php if(isset($_GET['pickup'])) {
    $sql = "SELECT * FROM ordersPickup WHERE id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$_GET['pickup']]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $sql = "SELECT * FROM items WHERE id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$order['item_id']]);
    $itemPickup = $stmt->fetch(PDO::FETCH_ASSOC); ?>
    <div class="editName orderModal">
        <form method="post" class="editNameWindow">
            <a href="?page=managePanel&orders"><p>⨉</p></a>
            <div class="editNameBody">
                <h1>Информация о заказе</h1>
                <div class="orderInfoModal">
                    <h2>ID заказа: <span><?= $order['id'] ?></span></h2>
                    <h2>Тип заказа: Самовывоз</h2>
                    <h2>ID пользователя: <span><?= $order['user_id'] ?></span></h2>
                    <h2>ID товара: <span><?= $order['item_id'] ?></span></h2>
                    <h2>Товар: <span><?= $itemPickup['name'] ?></span></h2>
                    <h2>Цена: <span><?= $itemPickup['price'] * $order['quanity'] ?>₽</span></h2>
                    <h2>Количество: <span><?= $order['quanity'] ?></span></h2>
                    <h2>Дата заказа: <span><?= $order['date'] ?></span></h2>
                    <h2>Примерная дата поступления: <span><?= $order['predict_date'] ?></span></h2>
                    <?php if(isset($orders['delivery_date'])) { ?>
                    <h2>Дата поступления: <span><?= $order['delivery_date'] ?></span></h2>
                    <?php } ?>
                    <h2>Адрес филиала: <span><?= $order['storeAddress'] ?></span></h2>
                    <h2>Телефон получателя: <span><?= $order['phone'] ?></span></h2>
                    <?php if(!empty($order['comment'])) { ?>
                    <h2>Комментарий: <span><?= $order['comment'] ?></span></h2>
                    <?php } ?>
                    <h2>Статус: <span><?= $order['status'] ?></span></h2>
                    <?php if(!empty($order['cancelReason'])) { ?>
                    <h2>Причина отмены заказа: <span><?= $order['cancelReason'] ?></span></h2>
                    <?php } ?>
                </div>
                <a href="?page=editOrder&pickup=<?= $order['id'] ?>">Редактировать данные</a>
            </div>
        </form>
    </div>
    <?php } ?>

<?php if(isset($_GET['cancelD'])) {
    $sql = "SELECT * FROM ordersDelivery WHERE id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$_GET['cancelD']]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    $phoneLink = $order['phone'];
    $phoneLink[0] = '7'; ?>
<div class="modalExit active auth cancelOrder">
        <form method="post" class="modalExitWindow">
            <a href="?page=managePanel&orders"><p>⨉</p></a>
            <div class="modalExitBody">
                <h1>Отмена заказа</h1>
                <h2>При отмене заказа обязательно свяжитесь с заказчиком и сообщите ему о причине отмены заказа.<br>Номер телефона заказчика: <a href="tel:+<?= $phoneLink ?>"><?= $order['phone'] ?></a></h2>
                <div class="cancelReason">
                    <h3>Укажите причину отмены заказа:</h3>
                    <?php if(isset($_POST['cancelReason'])) { ?>
                    <textarea name="cancelReason" placeholder="Причина отмены..."><?= $_POST['cancelReason'] ?></textarea>
                    <?php } else { ?>
                    <textarea name="cancelReason" placeholder="Причина отмены..."></textarea>
                    <?php }
                    if(isset($errors['cancelReason'])) { ?>
                    <h4><?= $errors['cancelReason'] ?></h4>
                    <?php } ?>
                </div>
                <div class="modalExitOptions">
                    <a href="?page=managePanel&orders">Вернуться к заказам</a>
                    <input type="hidden" name="cancelOrderId" value="<?= $order['id'] ?>">
                    <button type="submit" name="cancelD">Отменить заказ</button>
                </div>
            </div>
        </form>
    </div>
<?php } 
if(isset($_GET['cancelP'])) {
$sql = "SELECT * FROM ordersPickup WHERE id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$_GET['cancelD']]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    $phoneLink = $order['phone'];
    $phoneLink[0] = '7'; ?>
<div class="modalExit active auth cancelOrder">
        <form method="post" class="modalExitWindow">
            <a href="?page=managePanel&orders"><p>⨉</p></a>
            <div class="modalExitBody">
                <h1>Отмена заказа</h1>
                <h2>При отмене заказа обязательно свяжитесь с заказчиком и сообщите ему о причине отмены заказа.<br>Номер телефона заказчика: <a href="tel:+<?= $phoneLink ?>"><?= $order['phone'] ?></a></h2>
                <div class="cancelReason">
                    <h3>Укажите причину отмены заказа:</h3>
                    <?php if(isset($_POST['cancelReason'])) { ?>
                    <textarea name="cancelReason" placeholder="Причина отмены..."><?= $_POST['cancelReason'] ?></textarea>
                    <?php } else { ?>
                    <textarea name="cancelReason" placeholder="Причина отмены..."></textarea>
                    <?php } 
                    if(isset($errors['cancelReason'])) { ?>
                    <h4><?= $errors['cancelReason'] ?></h4>
                    <?php } ?>
                </div>
                <div class="modalExitOptions">
                    <a href="?page=managePanel&orders">Вернуться к заказам</a>
                    <input type="hidden" name="cancelOrderId" value="<?= $order['id'] ?>">
                    <button type="submit" name="cancelP">Отменить заказ</button>
                </div>
            </div>
        </form>
    </div>
<?php } ?>

<?php if(isset($_GET['code'])) { 
    $sql = "SELECT * FROM ordersPickup WHERE id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$_GET['code']]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC); ?>
<div class="modalExit active auth orderCode">
        <form method="post" class="modalExitWindow">
            <a href="?page=managePanel&orders"><p>⨉</p></a>
            <div class="modalExitBody">
                <h1>Код для получения заказа</h1>
                <h3><?= $order['code'] ?></h3>
            </div>
        </form>
    </div>
<?php } ?>
</div>