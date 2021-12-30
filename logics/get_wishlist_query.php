<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once($_SERVER['DOCUMENT_ROOT'] . '/qiosk_web/logics/chat_dbconn.php');

$fpin = $_POST['fpin'];
$search_query = $_POST['query'];

$dbconn = paliolite();

// get wishlist
if($query = $dbconn->prepare("SELECT PRODUCT_CODE FROM WISHLIST_PRODUCT WHERE FPIN = ?")){
    $query->bind_param('s', $fpin);
    $query->execute();
    $wishlist = $query->get_result();
    $query->close();
} else {
    //error !! don't go further
    var_dump($dbconn->error);
}

$rows = array();
while ($wish = $wishlist->fetch_assoc()) {
    
        // get product details
        if ($query = $dbconn->prepare("SELECT CODE, NAME, THUMB_ID, PRICE FROM PRODUCT WHERE CODE = ? AND NAME LIKE '%".$search_query."%'")) {
            $query->bind_param('s', $wish['PRODUCT_CODE']);
            $query->execute();
            $product = $query->get_result()->fetch_assoc();
            $query->close();

            if ($product){
                $rows[] = $product;
            }
        
        } else {
            //error !! don't go further
            var_dump($dbconn->error);
        }
};

echo json_encode($rows);
