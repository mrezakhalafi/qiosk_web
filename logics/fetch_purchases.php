<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/qiosk_web/logics/chat_dbconn.php');

if (!isset($_GET['f_pin'])) {
    die();
}

$dbconn = paliolite();

$f_pin = $_GET["f_pin"];

// FETCH COLLECTIONS
$query = $dbconn->prepare("SELECT p.*, s.NAME AS MERCHANT_NAME, pr.NAME as PRODUCT_NAME
            FROM PURCHASE p
            left join SHOP s on p.MERCHANT_ID = s.CODE
            LEFT JOIN PRODUCT pr on p.PRODUCT_ID = pr.CODE
            WHERE p.FPIN = '$f_pin'");
$query->execute();
$results = $query->get_result();
$query->close();

$collections = array();
while ($result = $results->fetch_assoc()) {
    $collections[] = $result;
};

echo json_encode($collections);

?>