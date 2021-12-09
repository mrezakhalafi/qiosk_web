<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/qiosk_web/logics/chat_dbconn.php');

$dbconn = paliolite();
$f_pin = $_GET['f_pin'];

// SELECT USER PROFILE
if(!isset($f_pin) && isset($_GET['f_pin'])){
    $f_pin = $_GET['f_pin'];
}


if (isset($f_pin)) {
    $query = $dbconn->prepare("SELECT s.*, be.ID as BE_ID, srp.AMOUNT AS REWARD_POINT, ssa.ADDRESS, ssa.VILLAGE, ssa.DISTRICT, ssa.CITY, ssa.PROVINCE, ssa.ZIP_CODE, ssa.PHONE_NUMBER, ssa.COURIER_NOTE FROM SHOP s LEFT JOIN SHOP_REWARD_POINT srp ON (s.CODE = srp.STORE_CODE AND srp.F_PIN = '$f_pin') left join BUSINESS_ENTITY be on s.PALIO_ID = be.COMPANY_ID left join SHOP_SHIPPING_ADDRESS ssa on s.CODE = ssa.STORE_CODE where (s.IS_VERIFIED = 1 AND s.IS_QIOSK = 2) or s.CREATED_BY in (SELECT fl.L_PIN FROM FRIEND_LIST fl where fl.F_PIN = '$f_pin') or s.CREATED_BY = '$f_pin' order by s.SCORE desc");
}
else {
    $query = $dbconn->prepare("SELECT s.*, be.ID as BE_ID FROM SHOP s left join BUSINESS_ENTITY be on s.PALIO_ID = be.COMPANY_ID where s.IS_VERIFIED = 1 order by s.SCORE desc");
};

$query->execute();
$groups  = $query->get_result();
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
?>