<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/images/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="pages/style.css">
    <script src="js/header.js" defer></script>
    <script src="js/order.js" defer></script>
    <script src="js/profile.js" defer></script>
    <script src="js/managePanel.js" defer></script>
    <script src="js/main.js" defer></script>
    <script src="js/review.js" defer></script>
    <title>Кинг</title>
</head>

<?php
    session_start();
    include('php/connectDB.php');
    include('php/loginControl.php');
    if(isset($_GET['page'])) {
        if(isset($_SESSION['userID'])) {
            if($_GET['page'] == 'profile') include('pages/user/profile.php');
            if($_GET['page'] == 'order') include('pages/order/order.php');
            if($_GET['page'] == 'cart') include('pages/order/cart.php');
            if($_GET['page'] == 'orderSuccess') include('pages/order/orderSuccess.php');
            elseif($USER['isAdmin']) {
                if($_GET['page'] == 'managePanel') include('pages/admin/managePanel.php');
                if($_GET['page'] == 'editOrder') include('pages/admin/editOrder.php');
                if($_GET['page'] == 'editItem') include('pages/admin/editItem.php');
                if($_GET['page'] == 'editPromotion') include('pages/admin/editPromotion.php');
                if($_GET['page'] == 'createPromotion') include('pages/admin/createPromotion.php');
                if($_GET['page'] == 'createItem') include('pages/admin/createItem.php');
            }
        }
        elseif(!isset($_SESSION['userID'])) {
            if($_GET['page'] == 'reg') include('pages/user/reg.php');
            elseif($_GET['page'] == 'login') include('pages/user/login.php');
        }
        if($_GET['page'] == 'main') include('pages/components/main.php');
        if($_GET['page'] == 'catalog') include('pages/items/catalog.php');
        if($_GET['page'] == 'item') include('pages/items/item.php');
    }
    else include('pages/components/main.php');
?>

</html>