<?php


// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/qiosk_web/logics/chat_dbconn.php');

$dbconn = paliolite();

// SELECT USER PROFILE
if (isset($_GET['store_id'])) {
    $store_id = $_GET['store_id'];
} else {
    die();
}

$query = $dbconn->prepare("SELECT p.*, s.CODE as STORE_CODE, s.NAME as STORE_NAME, s.THUMB_ID as STORE_THUMB_ID, s.LINK as STORE_LINK, s.TOTAL_FOLLOWER as TOTAL_FOLLOWER FROM PRODUCT p join SHOP s on p.SHOP_CODE = s.CODE WHERE p.SHOP_CODE = '$store_id' AND p.IS_DELETED = 0 ORDER BY p.SCORE DESC, p.CREATED_DATE DESC");

$query->execute();
$groups = $query->get_result();
$query->close();

$rows = array();
while ($group = $groups->fetch_assoc()) {
    $rows[] = $group;
};

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

echo json_encode(utf8ize($rows));
