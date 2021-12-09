<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/qiosk_web/logics/chat_dbconn.php');

$dbconn = paliolite();

// SELECT USER PROFILE
$query = $dbconn->prepare("SELECT * FROM SHOP_CATEGORY ORDER BY SORT_ORDER ASC");
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