<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/qiosk_web/logics/chat_dbconn.php');

$dbconn = paliolite();
if(!isset($store_id) && isset($_GET['store_id'])){
    $store_id = $_GET['store_id'];
}
if(isset($store_id)){
    $query = $dbconn->prepare("SELECT s.CODE, (SELECT COUNT(*) FROM PRODUCT pr WHERE pr.SHOP_CODE = ?) as PRODUCT_COUNT,
    s.PALIO_ID FROM SHOP s 
    where s.CODE = ?");
    $query->bind_param("ss", $store_id, $store_id);
}

// SELECT USER PROFILE
$query->execute();
$groups  = $query->get_result()->fetch_assoc();
$product_count = $groups["PRODUCT_COUNT"];
$query->close();

if(is_null($groups["PALIO_ID"])){
    $product_count = -1;
}

echo json_encode($product_count);
?>