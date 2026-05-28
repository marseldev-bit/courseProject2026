<body class="orderPage">
    <!-- Шапка -->
    <?php include('pages/components/header.php'); ?>
    <!-- Конец шапки -->

    <main>
        <!-- Оформление заказа -->
        <h1 class="title cont">Оформление заказа</h1>
        <div class="cart cont">
            <div class="cartItem">
                <div class="cartItemBlock">
                    <img src="../assets/images/cartItem1.png" alt="">
                    <div class="cartItemInfo">
                        <h1>Турнирные шахматы «Грандмастер»</h1>
                        <div class="cartItemInfoBlock">
                            <p>7 990 ₽</p>
                            <h3>1 шт</h3>
                        </div>
                    </div>
                </div>

                <h2>1 шт</h2>
            </div>

            <div class="separateLine"></div>

            <div class="cartItem">
                <div class="cartItemBlock">
                    <img src="../assets/images/cartItem2.png" alt="">
                    <div class="cartItemInfo">
                        <h1>Шахматные часы «Тайм-контроль»</h1>
                        <div class="cartItemInfoBlock">
                            <p>8 980 ₽</p>
                            <h3>2 шт</h3>
                        </div>
                    </div>
                </div>

                <h2>2 шт</h2>
            </div>

            <div class="separateLine"></div>

            <div class="cartItem">
                <div class="cartItemBlock">
                    <img src="../assets/images/cartItem3.png" alt="">
                    <div class="cartItemInfo">
                        <h1>Шахматная доска «Классика»</h1>
                        <div class="cartItemInfoBlock">
                            <p>4 990 ₽</p>
                            <h3>1 шт</h3>
                        </div>
                    </div>
                </div>

                <h2>1 шт</h2>
            </div>
        </div>

        <h1 class="title cont">Данные заказа</h1>
        <div class="orderOptions cont">
            <button id="delivery" class="active">Доставка</button>
            <button id="pickup">Самовывоз</button>
        </div>
        
        <?php
            include('orderDelivery.php');
            include('orderPickup.php');
        ?>

        <div class="confirmOrder cont">
            <p>К оплате: <span>21 960 ₽</span></p>
            <button>Оформить заказ</button>
        </div>
        <!-- Конец "Оформление заказа" -->
    </main>

    <!-- Подвал -->
    <?php include('pages/components/footerBlock.php'); ?>
    <!-- Конец подвала -->
</body>