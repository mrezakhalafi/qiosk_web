<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/qiosk_web/logics/chat_dbconn.php');

$dbconn = paliolite();
$store_id = $_GET['store_id'];

// SELECT USER PROFILE
if(!isset($store_id) && isset($_GET['store_id'])){
    $store_id = $_GET['store_id'];
}
if (isset($store_id)) {
    $query = $dbconn->prepare("SELECT p.THUMB_ID, p.IS_SHOW, s.CODE as STORE_CODE FROM PRODUCT p join SHOP s on p.SHOP_CODE = s.CODE WHERE p.SHOP_CODE = ? AND s.IS_QIOSK = 2 ORDER BY p.IS_SHOW, p.SCORE DESC, p.CREATED_DATE DESC");
    $query->bind_param("s", $store_id);
}
else {
    $query = $dbconn->prepare("SELECT p.THUMB_ID, p.IS_SHOW, s.CODE as STORE_CODE FROM PRODUCT p join SHOP s on p.SHOP_CODE = s.CODE WHERE s.IS_QIOSK = 2 ORDER BY p.IS_SHOW, p.SCORE DESC, p.CREATED_DATE DESC");
};
$query->execute();
$groups  = $query->get_result();
$query->close();

$rows = array();
while ($group = $groups->fetch_assoc()) {
    $rows[] = $group;
};

echo json_encode($rows);
?>