<?php 

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/qiosk_web/logics/chat_dbconn.php');
$dbconn = paliolite();

// GET ID SHOP

session_start();
$id_shop = $_SESSION['id_shop'];

// GET FROM DETAIL ORDERS FORM

$transaction_id = $_POST['transaction_id'];

// UPDATE PURCHASE

$query = "UPDATE PURCHASE SET STATUS = '1' WHERE TRANSACTION_ID = '".$transaction_id."'";

if (mysqli_query($dbconn, $query)){
    header("Location: ../../pages/tab5-your-orders.php");
}else{
    echo("ERROR: Data gagal diubah. $sql. " . mysqli_error($dbconn));
}

?>