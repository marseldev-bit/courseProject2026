<?php
    $sql = "SELECT * FROM ordersDelivery WHERE user_id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$USER['id']]);
    $ordersDelivery = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sql = "SELECT * FROM ordersPickup WHERE user_id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$USER['id']]);
    $ordersPickup = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $active = [];
    $arrived = [];
    $completed = [];
    $canceled = [];

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

    if(isset($_POST['createReview'])) {
        if(empty($_POST['rate'])) $errors['rate'] = 'Укажите оценку товара';
        if(empty($_POST['text'])) $errors['text'] = 'Заполните текст отзыва';
        if(empty($errors)) {
            $sql = "INSERT INTO reviews (user_id, item_id, rate, text, status) VALUES (?, ?, ?, ?, ?)";
            $stmt = $connect->prepare($sql);
            $stmt->execute([$USER['id'], $_GET['review'], $_POST['rate'], $_POST['text'], 'На модерации']);
            echo '<script>location.href="?page=profile&orders"</script>';
        }
    }
?>

<div class="userOrdersBlock profileSection">
    <h1 class="title userOrdersTitle cont">Мои заказы</h1>
    <?php if(empty($active) and empty($completed) and empty($canceled)) { ?>
    <div class="emptyOrders cont">
        <h2>Здесь пусто, самое время оформить заказ!</h2>
        <a href="?page=catalog">В каталог</a>
    </div>
    <?php } else {

if(!empty($arrived)) { $count = 0; ?>
    <div class="userOrders cont">
        <p class="orderStatus">Можно забирать</p>
        <div class="ordersHistory">
            <?php 
                foreach($arrived as $a) {
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
                    if($count > 0) { ?>
                    <div class="separateLine"></div>
                <?php } ?>
            <div class="cartItem">
                <div class="cartItemBlock">
                    <img src="<?= $img['imgPath'] ?>" alt="">
                    <div class="cartItemInfo">
                        <h1><?= $item['name'] ?></h1>
                        <div class="cartItemPrice">
                            <p><?= $item['price'] * $a['quanity'] ?>₽</p>
                            <h2><?= $a['quanity'] ?> шт</h2>
                            <h1>Заказ от <?= $a['date'] ?></h1>
                        </div>
                    </div>
                </div>

                <div class="cartItemOptions">
                    <?php if($orderType) { ?>
                    <a href="?page=profile&orders&delivery=<?= $a['id'] ?>">Подробности</a>
                    <?php } else { ?>
                    <a href="?page=profile&orders&code=<?= $a['id'] ?>">Код</a>
                    <a href="?page=profile&orders&pickup=<?= $a['id'] ?>">Подробности</a>
                    <?php } ?>
                    <a href="?page=item&id=<?= $item['id'] ?>">К товару</a>
                </div>
            </div>

            <div class="orderMobile">
                <div class="orderMobileHeader">
                    <img src="<?= $img['imgPath'] ?>" alt="">
                    <h1><?= $item['name'] ?></h1>
                </div>
                <div class="orderMobileInfo">
                    <p><span>Стоимость:</span> <?= $item['price'] * $a['quanity'] ?> ₽</p>
                    <p><span>Количество:</span> <?= $a['quanity'] ?> шт</p>
                    <p><span>Дата заказа:</span> <?= $a['date'] ?></p>
                </div>
                <div class="orderMobileOptions">
                    <?php if($orderType) { ?>
                    <a href="?page=profile&orders&delivery=<?= $a['id'] ?>">Подробности</a>
                    <?php } else { ?>
                    <a href="?page=profile&orders&code=<?= $a['id'] ?>">Код</a>
                    <a href="?page=profile&orders&pickup=<?= $a['id'] ?>">Подробности</a>
                    <?php } ?>
                    <a href="?page=item&id=<?= $item['id'] ?>">К товару</a>
                </div>
            </div>
            <?php $count += 1; } ?>
        </div>
    </div>
    <?php }

        if(!empty($active)) { $count = 0; ?>
    <div class="userOrders cont">
        <p class="orderStatus">В пути</p>
        <div class="ordersHistory">
            <?php
                foreach($active as $a) {
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
                    if($count > 0) { ?>
                    <div class="separateLine"></div>
                <?php } ?>
            <div class="cartItem">
                <div class="cartItemBlock">
                    <img src="<?= $img['imgPath'] ?>" alt="">
                    <div class="cartItemInfo">
                        <h1><?= $item['name'] ?></h1>
                        <div class="cartItemPrice">
                            <p><?= $item['price'] * $a['quanity'] ?>₽</p>
                            <h2><?= $a['quanity'] ?> шт</h2>
                            <h1>Заказ от <?= $a['date'] ?></h1>
                        </div>
                    </div>
                </div>

                <div class="cartItemOptions">
                    <?php if($orderType) { ?>
                    <a href="?page=profile&orders&delivery=<?= $a['id'] ?>">Подробности</a>
                    <?php } else { ?>
                    <a href="?page=profile&orders&pickup=<?= $a['id'] ?>">Подробности</a>
                    <?php } ?>
                    <a href="?page=item&id=<?= $item['id'] ?>">К товару</a>
                </div>
            </div>

            <div class="orderMobile">
                <div class="orderMobileHeader">
                    <img src="<?= $img['imgPath'] ?>" alt="">
                    <h1><?= $item['name'] ?></h1>
                </div>
                <div class="orderMobileInfo">
                    <p><span>Стоимость:</span> <?= $item['price'] * $a['quanity'] ?> ₽</p>
                    <p><span>Количество:</span> <?= $a['quanity'] ?> шт</p>
                    <p><span>Дата заказа:</span> <?= $a['date'] ?></p>
                </div>
                <div class="orderMobileOptions">
                    <?php if($orderType) { ?>
                    <a href="?page=profile&orders&delivery=<?= $a['id'] ?>">Подробности</a>
                    <?php } else { ?>
                    <a href="?page=profile&orders&pickup=<?= $a['id'] ?>">Подробности</a>
                    <?php } ?>
                    <a href="?page=item&id=<?= $item['id'] ?>">К товару</a>
                </div>
            </div>
            <?php $count += 1; } ?>
        </div>
    </div>
    <?php }
    
    if(!empty($completed)) { $count = 0; ?>
    <div class="userOrders cont">
        <p class="orderStatus">Завершенные</p>
        <div class="ordersHistory">
            <?php
                foreach($completed as $c) {
                    $sql = "SELECT * FROM items WHERE id = ?";
                    $stmt = $connect->prepare($sql);
                    $stmt->execute([$c['item_id']]);
                    $item = $stmt->fetch(PDO::FETCH_ASSOC);

                    if(isset($a['time'])) $orderType = true;
                    else $orderType = false;

                    $sql = "SELECT imgPath FROM itemGallery WHERE item_id = ?";
                    $stmt = $connect->prepare($sql);
                    $stmt->execute([$c['item_id']]);
                    $img = $stmt->fetch(PDO::FETCH_ASSOC);

                    if($count > 0) { ?>
                    <div class="separateLine"></div>
                <?php } ?>
            <div class="cartItem">
                <div class="cartItemBlock">
                    <img src="<?= $img['imgPath'] ?>" alt="">
                    <div class="cartItemInfo">
                        <h1><?= $item['name'] ?></h1>
                        <div class="cartItemPrice">
                            <p><?= $item['price'] * $c['quanity'] ?>₽</p>
                            <h2><?= $c['quanity'] ?> шт</h2>
                            <h1>Заказ от <?= $c['date'] ?></h1>
                        </div>
                    </div>
                </div>

                <div class="cartItemOptions">
                    <?php if($orderType) { ?>
                    <a href="?page=profile&orders&delivery=<?= $c['id'] ?>">Подробности</a>
                    <?php } else { ?>
                    <a href="?page=profile&orders&pickup=<?= $c['id'] ?>">Подробности</a>
                    <?php } ?>
                    <a href="?page=item&id=<?= $item['id'] ?>">К товару</a>
                    <?php 
                        $sql = "SELECT * FROM ordersDelivery WHERE item_id = ? AND user_id = ? AND status = ?";
                        $stmt = $connect->prepare($sql);
                        $stmt->execute([$item['id'], $USER['id'], 'Завершен']);
                        $ordersDelivery = $stmt->fetch(PDO::FETCH_ASSOC);

                        $sql = "SELECT * FROM ordersPickup WHERE item_id = ? AND user_id = ? AND status = ?";
                        $stmt = $connect->prepare($sql);
                        $stmt->execute([$item['id'], $USER['id'], 'Завершен']);
                        $ordersPickup = $stmt->fetch(PDO::FETCH_ASSOC);

                        $sql = "SELECT * FROM reviews WHERE item_id = ? AND user_id = ?";
                        $stmt = $connect->prepare($sql);
                        $stmt->execute([$item['id'], $USER['id']]);
                        $issetReview = $stmt->fetch(PDO::FETCH_ASSOC);
                        if(empty($issetReview) and (!empty($ordersDelivery) or !empty($ordersPickup))) {
                    ?>
                    <a href="?page=profile&orders&review=<?= $item['id'] ?>">Отзыв</a>
                    <?php } ?>
                </div>
            </div>
            <h3>Доставлен <?= $c['delivery_date'] ?></h3>

            <div class="orderMobile">
                <div class="orderMobileHeader">
                    <img src="<?= $img['imgPath'] ?>" alt="">
                    <h1><?= $item['name'] ?></h1>
                </div>
                <div class="orderMobileInfo">
                    <p><span>Стоимость:</span> <?= $item['price'] * $c['quanity'] ?> ₽</p>
                    <p><span>Количество:</span> <?= $c['quanity'] ?> шт</p>
                    <p><span>Дата заказа:</span> <?= $c['date'] ?></p>
                    <p><span>Доставлен:</span> <?= $c['deliveryDate'] ?></p>
                </div>
                <div class="orderMobileOptions">
                    <?php if($orderType) { ?>
                    <a href="?page=profile&orders&delivery=<?= $c['id'] ?>">Подробности</a>
                    <?php } else { ?>
                    <a href="?page=profile&orders&pickup=<?= $c['id'] ?>">Подробности</a>
                    <?php } ?>
                    <a href="?page=item&id=<?= $item['id'] ?>">К товару</a>
                    <?php 
                        $sql = "SELECT * FROM ordersDelivery WHERE item_id = ? AND user_id = ? AND status = ?";
                        $stmt = $connect->prepare($sql);
                        $stmt->execute([$item['id'], $USER['id'], 'Завершен']);
                        $ordersDelivery = $stmt->fetch(PDO::FETCH_ASSOC);

                        $sql = "SELECT * FROM ordersPickup WHERE item_id = ? AND user_id = ? AND status = ?";
                        $stmt = $connect->prepare($sql);
                        $stmt->execute([$item['id'], $USER['id'], 'Завершен']);
                        $ordersPickup = $stmt->fetch(PDO::FETCH_ASSOC);

                        $sql = "SELECT * FROM reviews WHERE item_id = ? AND user_id = ?";
                        $stmt = $connect->prepare($sql);
                        $stmt->execute([$item['id'], $USER['id']]);
                        $issetReview = $stmt->fetch(PDO::FETCH_ASSOC);
                        if(empty($issetReview) and (!empty($ordersDelivery) or !empty($ordersPickup))) {
                    ?>
                    <a href="?page=profile&orders&review=<?= $item['id'] ?>">Отзыв</a>
                    <?php } ?>
                </div>
            </div>
            <?php $count += 1; } ?>
        </div>
    </div>
    <?php } 

    if(!empty($canceled)) { $count = 0; ?>
    <div class="userOrders cont">
        <p class="orderStatus">Отмененные</p>
        <div class="ordersHistory">
            <?php
                foreach($canceled as $c) {
                    $sql = "SELECT * FROM items WHERE id = ?";
                    $stmt = $connect->prepare($sql);
                    $stmt->execute([$c['item_id']]);
                    $item = $stmt->fetch(PDO::FETCH_ASSOC);

                    if(isset($a['time'])) $orderType = true;
                    else $orderType = false;

                    $sql = "SELECT imgPath FROM itemGallery WHERE item_id = ?";
                    $stmt = $connect->prepare($sql);
                    $stmt->execute([$c['item_id']]);
                    $img = $stmt->fetch(PDO::FETCH_ASSOC);
                    if($count > 0) { ?>
                    <div class="separateLine"></div>
                <?php } ?>
            <div class="cartItem">
                <div class="cartItemBlock">
                    <img src="<?= $img['imgPath'] ?>" alt="">
                    <div class="cartItemInfo">
                        <h1><?= $item['name'] ?></h1>
                        <div class="cartItemPrice">
                            <p><?= $item['price'] * $c['quanity'] ?>₽</p>
                            <h2><?= $c['quanity'] ?> шт</h2>
                            <h1>Заказ от <?= $c['date'] ?></h1>
                        </div>
                    </div>
                </div>

                <div class="cartItemOptions">
                    <?php if($orderType) { ?>
                    <a href="?page=profile&orders&delivery=<?= $c['id'] ?>">Подробности</a>
                    <?php } else { ?>
                    <a href="?page=profile&orders&pickup=<?= $c['id'] ?>">Подробности</a>
                    <?php } ?>
                    <a href="?page=item&id=<?= $item['id'] ?>">К товару</a>
                </div>
            </div>

            <div class="orderMobile">
                <div class="orderMobileHeader">
                    <img src="<?= $img['imgPath'] ?>" alt="">
                    <h1><?= $item['name'] ?></h1>
                </div>
                <div class="orderMobileInfo">
                    <p><span>Стоимость:</span> <?= $item['price'] * $c['quanity'] ?> ₽</p>
                    <p><span>Количество:</span> <?= $c['quanity'] ?> шт</p>
                    <p><span>Дата заказа:</span> <?= $c['date'] ?></p>
                </div>
                <div class="orderMobileOptions">
                    <?php if($orderType) { ?>
                    <a href="?page=profile&orders&delivery=<?= $c['id'] ?>">Подробности</a>
                    <?php } else { ?>
                    <a href="?page=profile&orders&pickup=<?= $c['id'] ?>">Подробности</a>
                    <?php } ?>
                    <a href="?page=item&id=<?= $item['id'] ?>">К товару</a>
                </div>
            </div>
            <?php $count += 1; } ?>
        </div>
    </div>
    <?php } ?>
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
            <a href="?page=profile&orders"><p>⨉</p></a>
            <div class="editNameBody">
                <h1>Информация о заказе</h1>
                <div class="orderInfoModal">
                    <h2>ID заказа: <span><?= $order['id'] ?></span></h2>
                    <h2>Тип заказа: <span>Доставка</span></h2>
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
                <h3>Если вы хотите отменить заказ или изменить данные, обратитесь в поддержку по номеру <a href="tel:+79023335811">8 (902)-333-58-11</a></h3>
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
            <a href="?page=profile&orders"><p>⨉</p></a>
            <div class="editNameBody">
                <h1>Информация о заказе</h1>
                <div class="orderInfoModal">
                    <h2>ID заказа: <span><?= $order['id'] ?></span></h2>
                    <h2>Тип заказа: <span>Самовывоз</span></h2>
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
                <h3>Если вы хотите отменить заказ или изменить данные, обратитесь в поддержку по номеру <a href="tel:+79023335811">8 (902)-333-58-11</a></h3>
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
            <a href="?page=profile&orders"><p>⨉</p></a>
            <div class="modalExitBody">
                <h1>Получение заказа</h1>
                <h2>Для получения заказа в магазине Вас попросят назвать специальный четырехзначный код:</h2>
                <h3><?= $order['code'] ?></h3>
                <h2>Не сообщайте код никому постороннему!</h2>
            </div>
        </form>
    </div>
<?php } ?>

<?php if(isset($_GET['review'])) { ?>
<div class="modalExit active writeReview">
        <form method="post" class="modalExitWindow">
            <a href="?page=profile&orders"><p>⨉</p></a>
            <div class="modalExitBody">
                <h1>Ваш отзыв</h1>
                <div class="myReview">
                    <div class="rating">
                        <h2>Оценка: </h2>
                        <div class="stars">
                            <div class="star">
                                <svg width="27" height="26" viewBox="0 0 27 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M13.0254 0.94043C13.1116 0.914564 13.2021 0.920888 13.2998 0.979492L13.3301 0.99707L13.3623 1.01367C13.4375 1.05129 13.4441 1.06324 13.4336 1.05273C13.4231 1.0422 13.435 1.04875 13.4727 1.12402L16.8848 7.94629L17.1016 8.38184L17.584 8.44922L25.0625 9.49902L25.127 9.50781H25.1914C25.1344 9.50781 25.1767 9.48102 25.2549 9.5918C25.2882 9.63896 25.3124 9.69346 25.3252 9.74512C25.338 9.79682 25.3357 9.83028 25.333 9.84375L25.3184 9.90918L25.3037 9.91797L25.2002 10.0186L19.8213 15.2666L19.4717 15.6084L19.5557 16.0898L20.8662 23.5596V23.5605C20.9073 23.8072 20.7684 23.9933 20.6074 24.0566H20.4678C20.3994 24.0566 20.3487 24.0562 20.3027 24.0547C20.2957 24.0545 20.2893 24.053 20.2832 24.0527L20.2451 24.0332L13.5537 20.4902L13.1201 20.2607L12.6875 20.4902L5.99609 24.0332L5.97363 24.0449L5.95215 24.0576C5.85477 24.1159 5.7647 24.1214 5.67871 24.0957C5.59138 24.0695 5.48966 24.0016 5.40723 23.8789C5.39621 23.8405 5.39019 23.7734 5.38867 23.6074L6.68555 16.2207L6.76953 15.7393L6.41992 15.3975L1.04785 10.1572H1.04883C0.88575 9.9941 0.885712 9.79398 1.04883 9.63086L1.1543 9.52441L1.16113 9.50879C1.16723 9.50864 1.17373 9.50781 1.18066 9.50781H1.24512L1.30957 9.49902L8.78809 8.44922L9.2793 8.38086L9.49414 7.93359L12.7471 1.16602C12.8304 1.03745 12.9359 0.967357 13.0254 0.94043Z" stroke="#4E3822" stroke-width="1.85231"/>
                                </svg>
                            </div>

                            <div class="star">
                                <svg width="27" height="26" viewBox="0 0 27 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M13.0254 0.94043C13.1116 0.914564 13.2021 0.920888 13.2998 0.979492L13.3301 0.99707L13.3623 1.01367C13.4375 1.05129 13.4441 1.06324 13.4336 1.05273C13.4231 1.0422 13.435 1.04875 13.4727 1.12402L16.8848 7.94629L17.1016 8.38184L17.584 8.44922L25.0625 9.49902L25.127 9.50781H25.1914C25.1344 9.50781 25.1767 9.48102 25.2549 9.5918C25.2882 9.63896 25.3124 9.69346 25.3252 9.74512C25.338 9.79682 25.3357 9.83028 25.333 9.84375L25.3184 9.90918L25.3037 9.91797L25.2002 10.0186L19.8213 15.2666L19.4717 15.6084L19.5557 16.0898L20.8662 23.5596V23.5605C20.9073 23.8072 20.7684 23.9933 20.6074 24.0566H20.4678C20.3994 24.0566 20.3487 24.0562 20.3027 24.0547C20.2957 24.0545 20.2893 24.053 20.2832 24.0527L20.2451 24.0332L13.5537 20.4902L13.1201 20.2607L12.6875 20.4902L5.99609 24.0332L5.97363 24.0449L5.95215 24.0576C5.85477 24.1159 5.7647 24.1214 5.67871 24.0957C5.59138 24.0695 5.48966 24.0016 5.40723 23.8789C5.39621 23.8405 5.39019 23.7734 5.38867 23.6074L6.68555 16.2207L6.76953 15.7393L6.41992 15.3975L1.04785 10.1572H1.04883C0.88575 9.9941 0.885712 9.79398 1.04883 9.63086L1.1543 9.52441L1.16113 9.50879C1.16723 9.50864 1.17373 9.50781 1.18066 9.50781H1.24512L1.30957 9.49902L8.78809 8.44922L9.2793 8.38086L9.49414 7.93359L12.7471 1.16602C12.8304 1.03745 12.9359 0.967357 13.0254 0.94043Z" stroke="#4E3822" stroke-width="1.85231"/>
                                </svg>
                            </div>

                            <div class="star">
                                <svg width="27" height="26" viewBox="0 0 27 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M13.0254 0.94043C13.1116 0.914564 13.2021 0.920888 13.2998 0.979492L13.3301 0.99707L13.3623 1.01367C13.4375 1.05129 13.4441 1.06324 13.4336 1.05273C13.4231 1.0422 13.435 1.04875 13.4727 1.12402L16.8848 7.94629L17.1016 8.38184L17.584 8.44922L25.0625 9.49902L25.127 9.50781H25.1914C25.1344 9.50781 25.1767 9.48102 25.2549 9.5918C25.2882 9.63896 25.3124 9.69346 25.3252 9.74512C25.338 9.79682 25.3357 9.83028 25.333 9.84375L25.3184 9.90918L25.3037 9.91797L25.2002 10.0186L19.8213 15.2666L19.4717 15.6084L19.5557 16.0898L20.8662 23.5596V23.5605C20.9073 23.8072 20.7684 23.9933 20.6074 24.0566H20.4678C20.3994 24.0566 20.3487 24.0562 20.3027 24.0547C20.2957 24.0545 20.2893 24.053 20.2832 24.0527L20.2451 24.0332L13.5537 20.4902L13.1201 20.2607L12.6875 20.4902L5.99609 24.0332L5.97363 24.0449L5.95215 24.0576C5.85477 24.1159 5.7647 24.1214 5.67871 24.0957C5.59138 24.0695 5.48966 24.0016 5.40723 23.8789C5.39621 23.8405 5.39019 23.7734 5.38867 23.6074L6.68555 16.2207L6.76953 15.7393L6.41992 15.3975L1.04785 10.1572H1.04883C0.88575 9.9941 0.885712 9.79398 1.04883 9.63086L1.1543 9.52441L1.16113 9.50879C1.16723 9.50864 1.17373 9.50781 1.18066 9.50781H1.24512L1.30957 9.49902L8.78809 8.44922L9.2793 8.38086L9.49414 7.93359L12.7471 1.16602C12.8304 1.03745 12.9359 0.967357 13.0254 0.94043Z" stroke="#4E3822" stroke-width="1.85231"/>
                                </svg>
                            </div>

                            <div class="star">
                                <svg width="27" height="26" viewBox="0 0 27 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M13.0254 0.94043C13.1116 0.914564 13.2021 0.920888 13.2998 0.979492L13.3301 0.99707L13.3623 1.01367C13.4375 1.05129 13.4441 1.06324 13.4336 1.05273C13.4231 1.0422 13.435 1.04875 13.4727 1.12402L16.8848 7.94629L17.1016 8.38184L17.584 8.44922L25.0625 9.49902L25.127 9.50781H25.1914C25.1344 9.50781 25.1767 9.48102 25.2549 9.5918C25.2882 9.63896 25.3124 9.69346 25.3252 9.74512C25.338 9.79682 25.3357 9.83028 25.333 9.84375L25.3184 9.90918L25.3037 9.91797L25.2002 10.0186L19.8213 15.2666L19.4717 15.6084L19.5557 16.0898L20.8662 23.5596V23.5605C20.9073 23.8072 20.7684 23.9933 20.6074 24.0566H20.4678C20.3994 24.0566 20.3487 24.0562 20.3027 24.0547C20.2957 24.0545 20.2893 24.053 20.2832 24.0527L20.2451 24.0332L13.5537 20.4902L13.1201 20.2607L12.6875 20.4902L5.99609 24.0332L5.97363 24.0449L5.95215 24.0576C5.85477 24.1159 5.7647 24.1214 5.67871 24.0957C5.59138 24.0695 5.48966 24.0016 5.40723 23.8789C5.39621 23.8405 5.39019 23.7734 5.38867 23.6074L6.68555 16.2207L6.76953 15.7393L6.41992 15.3975L1.04785 10.1572H1.04883C0.88575 9.9941 0.885712 9.79398 1.04883 9.63086L1.1543 9.52441L1.16113 9.50879C1.16723 9.50864 1.17373 9.50781 1.18066 9.50781H1.24512L1.30957 9.49902L8.78809 8.44922L9.2793 8.38086L9.49414 7.93359L12.7471 1.16602C12.8304 1.03745 12.9359 0.967357 13.0254 0.94043Z" stroke="#4E3822" stroke-width="1.85231"/>
                                </svg>
                            </div>

                            <div class="star">
                                <svg width="27" height="26" viewBox="0 0 27 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M13.0254 0.94043C13.1116 0.914564 13.2021 0.920888 13.2998 0.979492L13.3301 0.99707L13.3623 1.01367C13.4375 1.05129 13.4441 1.06324 13.4336 1.05273C13.4231 1.0422 13.435 1.04875 13.4727 1.12402L16.8848 7.94629L17.1016 8.38184L17.584 8.44922L25.0625 9.49902L25.127 9.50781H25.1914C25.1344 9.50781 25.1767 9.48102 25.2549 9.5918C25.2882 9.63896 25.3124 9.69346 25.3252 9.74512C25.338 9.79682 25.3357 9.83028 25.333 9.84375L25.3184 9.90918L25.3037 9.91797L25.2002 10.0186L19.8213 15.2666L19.4717 15.6084L19.5557 16.0898L20.8662 23.5596V23.5605C20.9073 23.8072 20.7684 23.9933 20.6074 24.0566H20.4678C20.3994 24.0566 20.3487 24.0562 20.3027 24.0547C20.2957 24.0545 20.2893 24.053 20.2832 24.0527L20.2451 24.0332L13.5537 20.4902L13.1201 20.2607L12.6875 20.4902L5.99609 24.0332L5.97363 24.0449L5.95215 24.0576C5.85477 24.1159 5.7647 24.1214 5.67871 24.0957C5.59138 24.0695 5.48966 24.0016 5.40723 23.8789C5.39621 23.8405 5.39019 23.7734 5.38867 23.6074L6.68555 16.2207L6.76953 15.7393L6.41992 15.3975L1.04785 10.1572H1.04883C0.88575 9.9941 0.885712 9.79398 1.04883 9.63086L1.1543 9.52441L1.16113 9.50879C1.16723 9.50864 1.17373 9.50781 1.18066 9.50781H1.24512L1.30957 9.49902L8.78809 8.44922L9.2793 8.38086L9.49414 7.93359L12.7471 1.16602C12.8304 1.03745 12.9359 0.967357 13.0254 0.94043Z" stroke="#4E3822" stroke-width="1.85231"/>
                                </svg>
                            </div>
                        </div>
                        <?php if(isset($errors['rate'])) { ?><h3><?= $errors['rate'] ?></h3><?php } ?>
                        <input type="hidden" name="rate" 
                        <?php if(isset($_POST['rate'])) { ?>value="<?= $_POST['rate'] ?>"<?php } ?>>
                    </div>

                    <h2>Текст отзыва:</h2>
                    <textarea placeholder="Опишите впечатления..." name="text"><?php if(isset($_POST['text'])) { ?><?= $_POST['text'] ?><?php } ?></textarea>
                    <?php if(isset($errors['text'])) { ?><h3><?= $errors['text'] ?></h3><?php } ?>
                </div>
                <div class="modalExitOptions">
                    <a href="?page=profile&orders">Отмена</a>
                    <button name="createReview">Подтвердить</button>
                </div>
            </div>
        </form>
    </div>
<?php } ?>