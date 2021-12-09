<?php
if (isset($_REQUEST['store_id'])) {
    $store_id = $_REQUEST['store_id'];
}

if (isset($_REQUEST['f_pin'])) {
    $f_pin = $_REQUEST['f_pin'];
}

$products_final = include_once($_SERVER['DOCUMENT_ROOT'] . '/qiosk_web/logics/fetch_products_raw.php');


// shuffle the timeline
shuffle($products_final);



if (empty($products_final)) {
    echo '<div class="my-2" id="product-null">';
    echo '<div class="col-sm mt-2">';
    echo '<h5 class="prod-name" style="text-align:center;">Tidak ada produk</h5>';
    echo '</div>';
    echo '</div>';
} else {

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
    // end wishlist

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
    $shop_blacklist = array("17b0ae770cd");//isi manual 

    for ($i = 0; $i < count($products_final); $i++) {
        
        $code = $products_final[$i]["CODE"];
        
        $name = $products_final[$i]["NAME"];
        $created_date = $products_final[$i]["CREATED_DATE"];
        $category = $products_final[$i]["CATEGORY"];
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

        $store_id = $products_final[$i]["SHOP_CODE"];
        $desc = nl2br($products_final[$i]["DESCRIPTION"]);
        $thumb_id = $products_final[$i]["THUMB_ID"];
        $thumb_ids = explode("|", $thumb_id);
        $store_thumb_id = $products_final[$i]["STORE_THUMB_ID"];
        $store_name = $products_final[$i]["STORE_NAME"];
        $store_link = $products_final[$i]["STORE_LINK"];
        $total_likes = $products_final[$i]["TOTAL_LIKES"];
        $total_follower = $products_final[$i]["TOTAL_FOLLOWER"];
        $total_comment = count(include($_SERVER['DOCUMENT_ROOT'] . '/qiosk_web/logics/fetch_products_comments.php'));
        $use_adblock = $products_final[$i]["USE_ADBLOCK"];
        $is_verified = $products_final[$i]["IS_STORE_VERIFIED"];


        if (in_array($store_id,$shop_blacklist)){
            continue;
        }

        $imgs = explode('|', $store_thumb_id);
        if (substr($imgs[0], 0, 4) !== "http") {
            $thumb = "/qiosk_web/images/" . $imgs[0];
        } else {
            $thumb = $imgs[0];
        }

        // if ($i > 0) {
            echo '<hr class="my-0">';
        // }

        if($category == "4"){
            echo '<div class="product-row my-2" id="product-' . $code . '" data-iscontent="true">';
        } else {
            echo '<div class="product-row my-2" id="product-' . $code . '">';
        }
        echo '<div class="col-sm">';
        echo '<div class="timeline-post-header media">';
        echo '<a class="d-flex pe-2" href="tab3-profile.php?store_id=' . $store_id . '&f_pin=' . $f_pin . '">';
        echo '<img src="' . $thumb . '" class="align-self-start rounded-circle mr-2">';
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
                echo '<source src="' . $thumb_ids[0] . '" type="video/'. $thumb_ext .'">';
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
                    echo '<source src="' . $thumb_ids[$j] . '" type="video/'. $thumb_ext .'">';
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
        echo '<span class="prod-desc">' . strip_tags($desc) . '</span>';
        echo '</div>';
        echo '</div>';
        
    }
}
