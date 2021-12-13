<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once($_SERVER['DOCUMENT_ROOT'] . '/qiosk_web/logics/chat_dbconn.php');

if (isset($_GET['product_code'])) {
    $product_code = $_GET['product_code'];
}

if (isset($_GET['f_pin'])) {
    $f_pin = $_GET['f_pin'];
} else {
    $f_pin = '';
}

$dbconn = paliolite();

$sql = "SELECT 
p.*, 
s.CODE AS STORE_CODE, 
s.NAME AS STORE_NAME, 
s.THUMB_ID AS STORE_THUMB_ID, 
s.LINK AS STORE_LINK, 
p.CATEGORY AS CATEGORY, 
s.TOTAL_FOLLOWER AS TOTAL_FOLLOWER, 
s.IS_VERIFIED AS IS_STORE_VERIFIED 
FROM 
PRODUCT p 
JOIN SHOP s ON p.SHOP_CODE = s.CODE
WHERE 
p.CODE = '$product_code'";

$query = $dbconn->prepare($sql);
$query->execute();
$res = $query->get_result()->fetch_assoc();
$query->close();

$imgs = explode('|', $res['STORE_THUMB_ID']);
if (substr($imgs[0], 0, 4) !== "http") {
    $store_thumb = "/qiosk_web/images/" . $imgs[0];
} else {
    $store_thumb = $imgs[0];
}

// check wishlist
if ($query = $dbconn->prepare("SELECT PRODUCT_CODE FROM WISHLIST_PRODUCT WHERE FPIN = ?")) {
    $query->bind_param('s', $f_pin);
    $query->execute();
    $wishlist = $query->get_result();
    $query->close();
} else {
    //error !! don't go further
    var_dump($dbconn->error);
}

$wishlists = array();
while ($wish = $wishlist->fetch_assoc()) {
    $wishlists[] = $wish['PRODUCT_CODE'];
};

?>

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Qiosk - Product Comment</title>

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="../assets/css/tab3-style.css?v=<?php echo time(); ?>" />
    <!-- <link rel="stylesheet" href="../assets/css/roboto.css" /> -->
    <link rel="stylesheet" href="../assets/css/tab1-style.css?random=<?= time(); ?>" />
    <!-- custom css -->
    <link href="../assets/css/style-cart.css?v=<?= time(); ?>" rel="stylesheet">

    <!-- font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400;1,500;1,600&display=swap" rel="stylesheet">

    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/cart.js?v=<?= time(); ?>"></script>

    <script src="../assets/js/wishlist.js?v=<?php echo time(); ?>"></script>
    <script src="../assets/js/script-data-comment.js?v=<?php echo time(); ?>"></script>

    <style>
        #header {
            padding: 4px 0;
        }

        span.prod-desc {
            display: unset;
        }

        .row {
            --bs-gutter-x: 0;
        }
    </style>

</head>

<body>
    <div class="container-fluid nav-bar bg-white">
        <div class=" bg-purple mb-3" id="header">
            <div class="row" style="background-color: #6945A5; padding: 10px 0 10px 0;">
                <div class="col-2">
                    <a onclick="window.location = document.referrer;">
                        <img src="../assets/img/tab5/Back-(White).png" style="width:30px">
                    </a>
                </div>
                <div class="col-8 text-center d-flex align-items-center justify-content-center text-white">
                    <img class="rounded-circle me-2" style="width:25px; height: auto;" src="<?php echo $store_thumb; ?>" style> <img class="me-1" src="/qiosk_web/assets/img/icons/Verified-white.png" style="height: 12px; width:auto;"> <?php echo $res['STORE_NAME']; ?>
                </div>
                <div class="col-2"></div>
            </div>
        </div>

    </div>

    <div class="container-fluid bg-white">

        <?php

        $products_liked_raw = include_once($_SERVER['DOCUMENT_ROOT'] . '/qiosk_web/logics/fetch_products_liked_raw.php');
        $products_liked = array();
        foreach ($products_liked_raw as $product_liked) {
            $products_liked[] = $product_liked["PRODUCT_CODE"];
        }

        $stores_followed_raw = include_once($_SERVER['DOCUMENT_ROOT'] . '/qiosk_web/logics/fetch_stores_followed_raw.php');
        $stores_followed = array();
        foreach ($stores_followed_raw as $store_followed) {
            $stores_followed[] = $store_followed["STORE_CODE"];
        }

        $products_commented_raw = include_once($_SERVER['DOCUMENT_ROOT'] . '/qiosk_web/logics/fetch_products_commented_raw.php');
        $products_commented = array();
        foreach ($products_commented_raw as $product_commented) {
            $products_commented[] = $product_commented["PRODUCT_CODE"];
        }

        $image_type_arr = array("jpg", "jpeg", "png", "webp");
        $video_type_arr = array("mp4", "mov", "wmv", 'flv', 'webm', 'mkv', 'gif', 'm4v', 'avi', 'mpg');
        $shop_blacklist = array("17b0ae770cd"); //isi manual 

        $code = $res["CODE"];

        $name = $res["NAME"];
        $created_date = $res["CREATED_DATE"];
        $category = $res["CATEGORY"];
        $seconds = intval(intval($created_date) / 1000);
        // // $printed_date = date("H:i", $seconds);

        $lazy = ' loading=lazy';

        // print date
        $date_diff = round((time() - $seconds) / (60 * 60 * 24));
        if ($date_diff == 0) {
            $printed_date = "Hari ini";
        } else if ($date_diff == 1) {
            $printed_date = "Kemarin";
        } else if ($date_diff == 2) {
            $printed_date = "2 hari lalu";
        } else if ($date_diff == 3) {
            $printed_date = "3 hari lalu";
        } else if ($date_diff == 4) {
            $printed_date = "4 hari lalu";
        } else if ($date_diff == 5) {
            $printed_date = "5 hari lalu";
        } else if ($date_diff == 6) {
            $printed_date = "6 hari lalu";
        } else if ($date_diff == 7) {
            $printed_date = "7 hari lalu";
        } else if ($date_diff > 7 && $date_diff < 365) {
            $printed_date = date("j M Y", $seconds);
        } else if ($date_diff >= 365) {
            $printed_date = date("j M Y", $seconds);
        }

        $store_id = $res["SHOP_CODE"];
        $desc = nl2br($res["DESCRIPTION"]);
        $thumb_id = $res["THUMB_ID"];
        $thumb_ids = explode("|", $thumb_id);
        $store_thumb_id = $res["STORE_THUMB_ID"];
        $store_name = $res["STORE_NAME"];
        $store_link = $res["STORE_LINK"];
        $total_likes = $res["TOTAL_LIKES"];
        $total_follower = $res["TOTAL_FOLLOWER"];
        $total_comment = count(include($_SERVER['DOCUMENT_ROOT'] . '/qiosk_web/logics/fetch_products_comments.php'));
        // $use_adblock = $res["USE_ADBLOCK"];
        $is_verified = $res["IS_STORE_VERIFIED"];
        if ($category == "4") {
            echo '<div class="product-row my-2" id="product-' . $code . '" data-iscontent="true">';
        } else {
            echo '<div class="product-row my-2" id="product-' . $code . '">';
        }
        echo '<div class="col-sm">';
        echo '<div class="timeline-post-header media">';
        echo '<a class="d-flex pe-2" href="tab3-profile.php?store_id=' . $store_id . '&f_pin=' . $f_pin . '">';
        echo '<img src="' . $store_thumb . '" class="align-self-start rounded-circle mr-2">';
        echo '</a>';
        echo '<div class="media-body">';
        echo '<a class="d-flex pe-2" href="tab3-profile.php?store_id=' . $store_id . '&f_pin=' . $f_pin . '">';
        if ($is_verified > 0) {
            echo '<h5 class="store-name"><img src="/qiosk_web/assets/img/ic_official_flag.webp"/>' . $store_name . '</h5>';
        } else {
            echo '<h5 class="store-name">' . $store_name . '</h5>';
        }
        echo '</a>';
        echo '<p class="prod-timestamp">' . $printed_date . '</p>';
        echo '</div>';
        echo '<div class="post-status d-none">';
        echo '<img src="../assets/img/ic_public.png" height="20" width="20"/>';
        echo '</div>';
        echo '<div class="post-status d-none">';
        echo '<img src="../assets/img/ic_user.png" height="20" width="20"/>';
        echo '</div>';
        echo '<div class="post-status" onclick="openProductMenu(\'' . $code . '\')">';
        echo '<img src="../assets/img/icons/More.png" height="25" width="25"/>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '<div class="col-sm mt-2 timeline-image">';
        // echo '<a class="timeline-main" onclick="openStore(\'' . $store_id . '\',\'' . $store_link . '\');">';
        echo '<a class="timeline-main" id="detail-product-' . $code . '" onclick="showAddModal(\'' . $code . '\');">';
        if (count($thumb_ids) == 1) {
            // echo '<img class="single-image img-fluid rounded" src="' . $thumb_id . '">';
            $thumb_ext = pathinfo($thumb_ids[0], PATHINFO_EXTENSION);
            $image_name = str_replace($thumb_ext, "", $thumb_ids[0]);
            if (in_array($thumb_ext, $image_type_arr)) {
                echo '<img src="' . $thumb_ids[0] . '" class="img-fluid rounded"' . $lazy . '>';
                echo '<div class="timeline-product-tag">';
                echo '<img src="../assets/img/icons/Tagged-Product.png" />';
                echo '</div>';
            } else if (in_array($thumb_ext, $video_type_arr)) {
                echo '<div class="video-wrap">';
                echo '<video muted playsinline class="myvid" preload="metadata" poster="' . $image_name . 'webp">';
                echo '<source src="' . $thumb_ids[0] . '" type="video/' . $thumb_ext . '">';
                echo '</video>';
                echo '<div class="timeline-product-tag-video">';
                echo '<img src="../assets/img/icons/Tagged-Product.png" />';
                echo '</div>';
                echo '<div class="video-sound">';
                echo '<img src="../assets/img/video_mute.png" />';
                echo '</div>';
                echo '<div class="video-play d-none">';
                echo '<img src="../assets/img/video_play.png" />';
                echo '</div></div>';
            }
        } else {
            $count_thumb_id = count($thumb_ids);
            echo '<div id="carousel-' . $code . '" class="carousel slide pointer-event" data-ride="carousel" data-touch="true">';
            echo '<ol id="ci-' . $code . '" class=' . '"carousel-indicators">';
            for ($j = 0; $j < $count_thumb_id; $j++) {
                if ($j == 0) {
                    echo '<li data-bs-target="#carousel-' . $code . '" data-bs-slide-to="' . $j . '" class="active"></li>';
                } else {
                    echo '<li data-bs-target="#carousel-' . $code . '" data-bs-slide-to="' . $j . '"></li>';
                }
            }
            echo '</ol>';
            echo '<div class="carousel-inner">';
            for ($j = 0; $j < count($thumb_ids); $j++) {
                if ($j == 0) {
                    echo '<div class="carousel-item active">';
                } else {
                    echo '<div class="carousel-item">';
                }
                echo '<div class="carousel-item-wrap">';
                $thumb_ext = pathinfo($thumb_ids[$j], PATHINFO_EXTENSION);
                $image_name = str_replace($thumb_ext, "", $thumb_ids[$j]);
                if (in_array($thumb_ext, $image_type_arr)) {
                    echo '<img src="' . $thumb_ids[$j] . '" class="img-fluid rounded"' . $lazy . '>';
                    echo '<div class="timeline-product-tag">';
                    echo '<img src="../assets/img/icons/Tagged-Product.png" />';
                    echo '</div>';
                } else if (in_array($thumb_ext, $video_type_arr)) {
                    echo '<div class="video-wrap">';
                    echo '<video playsinline muted class="myvid" preload="metadata" poster="' . $image_name . 'webp">';
                    echo '<source src="' . $thumb_ids[$j] . '" type="video/' . $thumb_ext . '">';
                    echo '</video>';
                    echo '<div class="timeline-product-tag-video">';
                    echo '<img src="../assets/img/icons/Tagged-Product.png" />';
                    echo '</div>';
                    echo '<div class="video-sound">';
                    echo '<img src="../assets/img/video_mute.png" />';
                    echo '</div>';
                    echo '<div class="video-play d-none">';
                    echo '<img src="../assets/img/video_play.png" />';
                    echo '</div></div>';
                }

                echo '</div></div>';
            }
            echo '</div>';
            echo '<a class="carousel-control-prev" data-bs-target="#carousel-' . $code . '" data-bs-slide="prev">';
            echo '<span class="carousel-control-prev-icon"></span>';
            echo '</a>';
            echo '<a class="carousel-control-next" data-bs-target="#carousel-' . $code . '" data-bs-slide="next">';
            echo '<span class="carousel-control-next-icon"></span>';
            echo '</a>';
            echo '</div>';
        }
        echo '</a>';
        echo '</div>';
        echo '<div class="col-sm mt-2 d-flex align-items-center" class="like-comment-container">';
        echo '<div class="like-button" onClick="likeProduct(\'' . $code . '\')">';
        if (in_array($code, $products_liked)) {
            echo '<img id=like-' . $code . ' src="../assets/img/icons/Heart-fill.png" height="25" width="25"/>';
        } else {
            echo '<img id=like-' . $code . ' src="../assets/img/icons/Heart.png" height="30" width="30"/>';
        }
        echo '<div id=like-counter-' . $code . ' class="like-comment-counter">';
        echo $total_likes;
        echo '</div>';
        echo '</div>';
        echo '<div class="comment-button">';
        echo '<a href="comment?product_code=' . $code . '">';
        if (in_array($code, $products_commented)) {
            echo '<img class="comment-icon-' . $code . '" src="../assets/img/icons/Comment.png" height="30" width="30"/>';
        } else {
            echo '<img class="comment-icon-' . $code . '" src="../assets/img/icons/Comment.png" height="30" width="30"/>';
        }
        echo '</a>';
        echo '<div class="like-comment-counter">';
        echo $total_comment;
        echo '</div>';
        echo '</div>';
        echo '<div class="follower-button" onClick="addWishlist(\'' . $code . '\',this)">';
        if (in_array($code, $wishlists)) {
            echo '<img class="follow-icon-' . $store_id . '" src="../assets/img/icons/Wishlist-fill.png" height="30" width="30"/>';
        } else {
            echo '<img class="follow-icon-' . $store_id . '" src="../assets/img/icons/Wishlist.png" height="30" width="30"/>';
        }
        echo '<div id=follow-counter-post-' . $code . ' class="d-none like-comment-counter follow-counter-store-' . $store_id . '">';
        echo $total_follower . ' pengikut';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '<div class="col-sm mt-2">';
        echo '<span class="prod-name"><img class="verified-icon-prod" src="../assets/img/icons/Verified.png">' . $store_name . '</span>&emsp;';
        echo '<span class="prod-desc">' . mb_strimwidth($desc,0,40,"...") . '</span>';
        echo '</div>';
        echo '</div>';
        ?>

        <hr style="border-top:2px solid lightgray; max-width: 90%; margin: 15px auto;">
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-10 mx-auto">

                <?php

                $sql = "SELECT 
    pc.*, 
    CONCAT(ul.FIRST_NAME, ' ', ul.LAST_NAME) AS USERNAME 
  FROM 
    PRODUCT_COMMENT pc 
    LEFT JOIN USER_LIST ul ON pc.F_PIN = ul.F_PIN 
  WHERE 
    pc.PRODUCT_CODE = '$product_code'";

                $query = $dbconn->prepare($sql);
                $query->execute();
                $result = $query->get_result();
                $query->close();

                $comments = array();
                while ($res = $result->fetch_assoc()) {
                    $comments[] = $res;
                }

                // echo "<pre>";
                // print_r($rows);
                // echo "</pre>";

                if (count($comments) < 4) {
                    $cut_amt = count($comments);
                } else {
                    $cut_amt = 3;
                }


                if (count($comments) > 0) {
                    for ($i = 0; $i <= $cut_amt; $i++) {
                        $comment_id = $comments[$i]["COMMENT_ID"];
                        $product_code = $comments[$i]["PRODUCT_CODE"];
                        $f_pin = $comments[$i]["F_PIN"];
                        $comment_text = $comments[$i]["COMMENT"];
                        $created_date = $comments[$i]["CREATED_DATE"];
                        echo '<div style="font-weight: bold;" class="my-1"><span id="user-name-' . $i . '" style="margin-right:.5rem;"></span><span style="font-weight: 300;"> ' . $comment_text . '</span></div>';
                        echo ('<script>getDisplayName("' . $f_pin . '","' . $i . '")</script>');
                        echo ('<script>getThumbId("' . $f_pin . '","' . $i . '")</script>');
                    }


                    if (count($comments) > 4) {
                        echo '<a href="comment?product_code=' . $code . '" style="font-size:.75rem; color:grey;">View all ' . count($comments) . ' comments</a>';
                    }
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>