<ul>
    <li id="all-store" class='has-story d-none'>
        <div class="story">
            <img src="../assets/img/AllStore.png">
        </div>
        <span class="user">All Shops</span>
    </li>
    <?php

    // ini_set('display_errors', 1);
    // ini_set('display_startup_errors', 1);
    // error_reporting(E_ALL);
    $shop_blacklist = array("17b0ae770cd"); //isi manual 

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

    if (!isset($_GET['horizontal_seed'])) {
        $horizontal_seed = time();
    } else {
        $horizontal_seed = $_GET['horizontal_seed'];
    }

    $sql = "SELECT s.ID, s.CODE, s.THUMB_ID, s.NAME, s.IS_VERIFIED, s.IS_LIVE_STREAMING, be.ID AS BE_ID
    FROM PRODUCT p 
    LEFT JOIN SHOP s ON p.SHOP_CODE = s.CODE
    LEFT JOIN BUSINESS_ENTITY be ON s.PALIO_ID = be.COMPANY_ID
    WHERE  (s.IS_VERIFIED = 1 AND p.IS_DELETED = 0 AND s.IS_QIOSK = 2)";

    if (isset($_REQUEST['filter'])) {
        $filter = $_REQUEST['filter'];

        $sql .= " AND p.CATEGORY = '$filter'";

        if (isset($_REQUEST['f_pin'])) {
            $f_pin = $_REQUEST['f_pin'];
            $sql .= " OR s.CREATED_BY = '$f_pin' OR s.CREATED_BY IN (SELECT fl.L_PIN from SHOP sp LEFT JOIN FRIEND_LIST fl on sp.CREATED_BY = fl.L_PIN WHERE fl.F_PIN = '$f_pin' AND sp.CATEGORY = '$filter')";
        }
    }

    $sql .= " GROUP BY (s.CODE) ORDER BY RAND($horizontal_seed)";
    $query = $dbconn->prepare($sql);
    $query->execute();
    $groups  = $query->get_result();
    $query->close();
    

    $stores_final = array();
    while ($group = $groups->fetch_assoc()) {
        if ($showLinkless == 2 || ($showLinkless == 1 && empty($group["LINK"])) || ($showLinkless == 0 && !empty($group["LINK"]))) {
            $stores_final[] = $group;
        }
    };

    for ($i = 0; $i < count($stores_final); $i++) {
        $idStore = $stores_final[$i]["ID"];
        $codeStore = $stores_final[$i]["CODE"];
        $urlStore = $stores_final[$i]["THUMB_ID"];
        $nameStore = $stores_final[$i]["NAME"];
        $is_verified = $stores_final[$i]["IS_VERIFIED"];
        $is_live_streaming = $stores_final[$i]["IS_LIVE_STREAMING"];

        if (in_array($codeStore, $shop_blacklist)) {
            continue;
        }

        $imgs = explode('|', $urlStore);
        if (substr($imgs[0], 0, 4) !== "http") {
            $thumb = "/qiosk_web/images/" . $imgs[0];
        } else {
            $thumb = $imgs[0];
        }

        $lazy = "";

        if ($i > 5) {
            $lazy = " loading='lazy'";
        }

        echo '<li id="store-' . $codeStore .  '" class="has-story">';
        // echo "<a href='timeline.php?store_id=" . $idStore . "'>";
        echo "<div class='story'>";
        echo "<img src='$thumb' $lazy>";

        if ($is_live_streaming > 0) {
            // echo '<div class="icon-live">';
            echo '<img class="icon-live" src="/qiosk_web/assets/img/live_indicator.png"/>';
            // echo '</div>';
        }

        echo "</div>";
        // echo "</a>";
        if ($is_verified == 1) {
            echo "<span class='user'><img src='/qiosk_web/assets/img/ic_official_flag.webp'/>" . $nameStore . "</span>";
        } else {
            echo "<span class='user'>" . $nameStore . "</span>";
        }
        echo "</li>";
    }


    ?>
</ul>