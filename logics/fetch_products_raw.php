<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/qiosk_web/logics/chat_dbconn.php');

$dbconn = paliolite();

$showLinkless = 2;
try {
    $query = $dbconn->prepare("SELECT `VALUE` FROM `SHOP_SETTINGS` WHERE `PROPERTY` = 'SHOW_LINKLESS_STORE'");
    $query->execute();
    $geoloc = $query->get_result()->fetch_assoc();
    $showLinkless = $geoloc['VALUE'];
    $query->close();
} catch (\Throwable $th) {
}

if(!isset($store_id) && isset($_REQUEST['store_id'])){
    $store_id = $_REQUEST['store_id'];
}
if(!isset($f_pin) && isset($_REQUEST['f_pin'])){
    $f_pin = $_REQUEST['f_pin'];
}
if(!isset($que) && isset($_REQUEST['query'])){
    $que = $_REQUEST['query'];
}

$limit = 10;
$offset = 0;
$seed = $_GET['seed'];

if (isset($_GET['limit'])) {
    $limit = (intval($_GET['limit']) != 0 ) ? $_GET['limit'] : 10;
}
if (isset($_GET['offset'])) {
    $offset = (intval($_GET['offset']) != 0 ) ? $_GET['offset'] : 0;
}

if(!isset($que) && isset($_REQUEST['filter'])){
    $filter = $_REQUEST['filter'];
}

$sql = "SELECT p.*, s.CODE as STORE_CODE, s.NAME as STORE_NAME, s.THUMB_ID as STORE_THUMB_ID, s.LINK as STORE_LINK, p.CATEGORY as CATEGORY, s.TOTAL_FOLLOWER as TOTAL_FOLLOWER, s.IS_VERIFIED as IS_STORE_VERIFIED FROM PRODUCT p join SHOP s on p.SHOP_CODE = s.CODE";
if(isset($store_id) || isset($f_pin) || isset($que) || isset($filter)){
    $sql = $sql . " WHERE ";
}
if(isset($store_id)){
    $sql = $sql . "p.SHOP_CODE = ?";
    if(isset($que) || isset($f_pin) || isset($filter)){
        $sql = $sql . " AND ";
    }
}
if(isset($que)){
    $sql = $sql . "(p.NAME like ? OR p.DESCRIPTION like ? OR s.NAME like ?)";
    $quelike = "%" . $que . "%";
    if(isset($f_pin) || isset($filter)){
        $sql = $sql . " AND ";
    }
}
if(isset($filter)) {
    $sql = $sql . "p.CATEGORY = '$filter'";
    if(isset($f_pin)){
        $sql = $sql . " AND ";
    }
}
if(isset($f_pin)){
    $sql = $sql . "(s.IS_VERIFIED = 1 or s.CREATED_BY = ? or s.CREATED_BY in (SELECT fl.L_PIN FROM FRIEND_LIST fl where fl.F_PIN = ?))";
} else if(!(isset($store_id) || isset($f_pin) || isset($que) || isset($filter))) {
    $sql = $sql . " WHERE s.IS_VERIFIED = 1";
} else {
    $sql = $sql . " AND s.IS_VERIFIED = 1";
}
$sql = $sql . " AND p.IS_DELETED = 0 AND s.IS_QIOSK = 2 ORDER BY RAND($seed) LIMIT $limit OFFSET $offset";
$query = $dbconn->prepare($sql);
if (isset($store_id)) {
    if(isset($que)){
        if(isset($f_pin)){
            $query->bind_param("ssssss", $store_id, $quelike, $quelike, $quelike, $f_pin, $f_pin);
        } else {
            $query->bind_param("ssss", $store_id, $quelike, $quelike, $quelike);
        }
    } else if(isset($f_pin)){
        $query->bind_param("sss", $store_id, $f_pin, $f_pin);
    }
    else{
        $query->bind_param("s", $store_id);
    }
}
else if(isset($que)) {
    if(isset($f_pin)){
        $query->bind_param("sssss", $quelike, $quelike, $quelike, $f_pin, $f_pin);
    } else {
        $query->bind_param("sss", $quelike, $quelike, $quelike);
    }
} else if(isset($f_pin)){
    $query->bind_param("ss", $f_pin, $f_pin);
}

// SELECT USER PROFILE
$query->execute();
$groups  = $query->get_result();
$query->close();

$rows = array();
while ($group = $groups->fetch_assoc()) {
    if($showLinkless == 2 || ($showLinkless == 1 && empty($group["LINK"])) || ($showLinkless == 0 && !empty($group["LINK"]))){
        $rows[] = $group;
    }
};


return $rows;
?>