<?php 

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/qiosk_web/logics/chat_dbconn.php');
$dbconn = paliolite();

// GET DATA FROM FORM

$query = $_POST['query'];
$shop_id = $_POST['shop_id'];

// SELECT SHOP DATA ACTIVE

$query = $dbconn->prepare("SELECT * FROM PRODUCT WHERE SHOP_CODE = '$shop_id' AND NAME LIKE
                            '%$query%' AND IS_SHOW = 1 AND IS_DELETED = 0 ORDER BY CREATED_DATE DESC");
$query->execute();
$tagged_product = $query->get_result();
$query->close();

// CONVERT OBJECT INTO ARRAY

$rows = [];
while ($row = $tagged_product->fetch_assoc()){
    $rows[] = $row;
}

// IF DATA EXIST RETURN DATA

if (isset($rows)){
    echo(json_encode($rows));
}else{
    echo("");
}

?>