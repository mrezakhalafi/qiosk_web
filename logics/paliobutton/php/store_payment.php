<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once($_SERVER['DOCUMENT_ROOT'] . '/qiosk_web/logics/chat_dbconn.php');
$dbconn = paliolite();

$fpin = $_POST['fpin'];
$method = $_POST['method'];
$status = $_POST['status'];
$transaction_id = md5(date('Y-m-d H:i:s') . $fpin);
$cart = json_decode(base64_decode($_POST['cart']));

foreach ($cart as $c) {
    # code...
    $p_code = $c->p_code;
    $price = $c->price;
    $amount = $c->amount;

    // get store products
    $query = $dbconn->prepare("SELECT SHOP_CODE FROM PRODUCT WHERE CODE = ?");
    $query->bind_param("s", $p_code);
    $query->execute();
    $merchant_id  = $query->get_result()->fetch_assoc()['SHOP_CODE'];
    $query->close();

    // insert to purchase table
    $query = $dbconn->prepare("INSERT INTO PURCHASE (TRANSACTION_ID, MERCHANT_ID, PRODUCT_ID, PRICE, AMOUNT, METHOD, FPIN) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $query->bind_param("sssiiss", $transaction_id, $merchant_id, $p_code, $price, $amount, $method, $fpin);
    $query->execute();
    $query->close();
}

// insert to notification table
$query_shop_owner = $dbconn->prepare("SELECT CREATED_BY FROM SHOP WHERE CODE = '$merchant_id'");
$query_shop_owner->execute();
$fpin_shop_owner = $query_shop_owner->get_result()->fetch_assoc()['CREATED_BY'];
$query_shop_owner->close();

$type = '2';
$time = time() * 1000;
$notif_id_user = $fpin . $time;
$notif_id_merchant = $fpin_shop_owner . $time;
$query = $dbconn->prepare("INSERT INTO USER_NOTIFICATION (NOTIF_ID, TYPE, F_PIN, ENTITY_ID, TIME) VALUES (?, ?, ?, ?, ?)");
$query->bind_param("ssssi", $notif_id_user, $type, $fpin, $transaction_id, $time);
$query->execute();
$query->close();

$query = $dbconn->prepare("INSERT INTO USER_NOTIFICATION (NOTIF_ID, TYPE, F_PIN, ENTITY_ID, TIME) VALUES (?, ?, ?, ?, ?)");
$query->bind_param("ssssi", $notif_id_merchant, $type, $fpin_shop_owner, $transaction_id, $time);
$query->execute();
$query->close();

// echo "success";
header("location: /qiosk_web/pages/payment.php?id=" . $transaction_id, true, 301);
