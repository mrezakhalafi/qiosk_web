<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/qiosk_web/logics/chat_dbconn.php');

$dbconn = paliolite();
if(isset($code)) {
    $product_code = $code;
} else if(isset($_REQUEST['product_code'])){
    $product_code = $_REQUEST['product_code'];
}
if(isset($sub) || isset($comment_id)) {
    if(isset($sub)) {
        $queryReply = ${"comment_id_reff" . $sub};
    } else {
        $queryReply = $comment_id;
    }
}
// if(isset($_REQUEST['product_code'])){
//     $product_code = $_REQUEST['product_code'];
// } else {
//     $product_code = $code;
// }
if(isset($code) && !isset($_REQUEST['product_code'])) {
    $query = $dbconn->prepare("SELECT c1.* FROM PRODUCT_COMMENT c1 LEFT JOIN PRODUCT_COMMENT c2 on c1.REF_COMMENT_ID = c2.COMMENT_ID WHERE (c1.REF_COMMENT_ID IS NULL OR c2.ID IS NOT NULL) AND c1.PRODUCT_CODE = '$product_code'");
}
else if(!isset($queryReply)) {
    $query = $dbconn->prepare("SELECT * FROM PRODUCT_COMMENT WHERE PRODUCT_CODE = '$product_code' AND REF_COMMENT_ID IS NULL");
} else {
    $query = $dbconn->prepare("SELECT * FROM PRODUCT_COMMENT WHERE PRODUCT_CODE = '$product_code' AND REF_COMMENT_ID = '$queryReply'");
}
$query->execute();
$groups  = $query->get_result();
$query->close();

$rows = array();
while ($group = $groups->fetch_assoc()) {
    $rows[] = $group;
};

// echo json_encode($rows);

return $rows;
?>