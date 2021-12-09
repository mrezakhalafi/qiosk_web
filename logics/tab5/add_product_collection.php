<?php 

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/qiosk_web/logics/chat_dbconn.php');
$dbconn = paliolite();

// GET ID SHOP

session_start();
$id_shop = $_SESSION['id_shop'];

// GET FROM DETAIL ORDERS FORM

$product_code = $_POST['product_code'];
$collection_code = $_POST['collection_code'];

// INSERT TO COLLECTION PRODUCT

$query = "INSERT INTO COLLECTION_PRODUCT (COLLECTION_CODE, PRODUCT_CODE) VALUES ('".$collection_code."',
            '".$product_code."')";

if (mysqli_query($dbconn, $query)){
    header("Location: ../../pages/tab5-collection-self?collection_code=".$collection_code);
}else{
    echo("ERROR: Data gagal diubah. $sql. " . mysqli_error($dbconn));
}

?>