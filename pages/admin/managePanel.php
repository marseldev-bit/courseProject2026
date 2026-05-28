<body class="managePage">
    <!-- Шапка -->
    <?php include('pages/components/header.php'); ?>
    <!-- Конец шапки -->

    <main>
        <!-- Панель управления -->
        <h1 class="title cont">Панель управления</h1>
        <div class="manageOptions cont">
            <a href="?page=managePanel" id="manageItems" class="active">Товары</a>
            <a href="?page=managePanel&promotions" id="managePromotions">Акции</a>
            <a href="?page=managePanel&reviews" id="manageReviews">Отзывы</a>
            <a href="?page=managePanel&orders" id="manageOrders">Заказы</a>
            <a href="?page=managePanel&categories" id="manageCategories">Категории</a>
        </div>

        <?php 
            if(isset($_GET['promotions'])) include('pages/admin/managePromotions.php');
            elseif(isset($_GET['reviews'])) include('pages/admin/manageReviews.php');
            elseif(isset($_GET['orders'])) include('pages/admin/manageOrders.php');
            elseif(isset($_GET['categories'])) include('pages/admin/manageCategories.php');
            else include('pages/admin/manageItems.php');
        ?>
        <!-- Конец "Панель управления" -->
    </main>

    <!-- Подвал -->
    <?php include('pages/components/footerBlock.php'); ?>
    <!-- Конец подвала -->
</body>