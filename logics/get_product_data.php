<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once($_SERVER['DOCUMENT_ROOT'] . '/qiosk_web/logics/chat_dbconn.php');

$id = $_POST['product_id'];
$dbconn = paliolite();

// get store products
$query = $dbconn->prepare("SELECT p.CODE, p.NAME AS PRODUCT_NAME, p.SHOP_CODE, p.PRICE, p.THUMB_ID, p.DESCRIPTION, s.ID AS SHOP_ID, s.NAME AS SHOP_NAME, s.THUMB_ID AS SHOP_THUMBNAIL FROM PRODUCT p LEFT JOIN SHOP s ON p.SHOP_CODE = s.CODE WHERE p.CODE = '$id'");
$query->execute();
$product  = $query->get_result()->fetch_assoc();
$query->close();

function utf8ize($d) {
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else if (is_string ($d)) {
        return utf8_encode($d);
    }
    return $d;
}

echo json_encode(utf8ize($product));