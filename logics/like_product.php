<?php 

    include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . '/qiosk_web/logics/chat_dbconn.php');

    $dbconn = paliolite();

    $product_code = $_POST['product_code'];
    $flag_like = $_POST['flag_like'];
    $last_update = $_POST['last_update'];
    $f_pin = $_POST['f_pin'];

    try {
        $query = $dbconn->prepare("INSERT INTO PRODUCT_REACTION (PRODUCT_CODE, F_PIN, FLAG, LAST_UPDATE) VALUES (?,?,?,?) ON DUPLICATE KEY UPDATE FLAG = ?, LAST_UPDATE = ?");
        $query->bind_param("ssssss", $product_code, $f_pin, $flag_like, $last_update, $flag_like, $last_update);
        $status = $query->execute();
        $query->close();

        if($flag_like == '1'){
            $query = $dbconn->prepare("UPDATE PRODUCT SET TOTAL_LIKES=TOTAL_LIKES+1 WHERE CODE = ?");
            $query->bind_param("s", $product_code);
        } else {
            $query = $dbconn->prepare("UPDATE PRODUCT SET TOTAL_LIKES=IF(TOTAL_LIKES<=0,0,TOTAL_LIKES-1) WHERE CODE = ?");
            $query->bind_param("s", $product_code);
        }
        $status = $query->execute();
        $query->close();

        echo ' Success ';

    } catch (\Throwable $th) {
        //throw $th;
        echo $th->getMessage();
    }
?>