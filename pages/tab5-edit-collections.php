<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
include_once($_SERVER['DOCUMENT_ROOT'] . '/qiosk_web/logics/chat_dbconn.php');

if (!isset($_GET['f_pin'])) {
    die();
}

$dbconn = paliolite();

$f_pin = $_GET["f_pin"];

// FETCH COLLECTIONS
$query = $dbconn->prepare("SELECT c.COLLECTION_CODE, c.NAME, c.DESCRIPTION, c.TOTAL_VIEWS, c.STATUS, MAX(p.THUMB_ID) AS COLLECTION_THUMB,
(SELECT COUNT(*) FROM `COLLECTION_PRODUCT` cp WHERE c.COLLECTION_CODE = cp.COLLECTION_CODE) AS `PRODUCT_COUNT`
FROM `COLLECTION` c
LEFT JOIN COLLECTION_PRODUCT cp ON c.COLLECTION_CODE = cp.COLLECTION_CODE
LEFT JOIN PRODUCT p ON cp.PRODUCT_CODE = p.CODE
WHERE c.F_PIN = '$f_pin'
GROUP BY c.COLLECTION_CODE, c.ID");
$query->execute();
$results = $query->get_result();
$query->close();

$collections = array();
while ($result = $results->fetch_assoc()) {
    $collections[] = $result;
};

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Project</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link href="../assets/css/tab5-edit-collections.css?random=<?= time(); ?>" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/jQueryRotate.js"></script>
    <script src="../assets/js/jquery.validate.js"></script>
    <script src="../assets/js/isInViewport.min.js"></script>
    <link rel="stylesheet" href="../assets/css/style-store_list.css?random=<?= time(); ?>">
    <link rel="stylesheet" href="../assets/css/gridstack.min.css" />
    <link rel="stylesheet" href="../assets/css/gridstack-extra.min.css" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <script type="text/javascript" src="../assets/js/gridstack-static.js"></script>
    <!-- <script type="text/javascript" src="../assets/js/pulltorefresh.js"></script> -->
    <script type="text/javascript" src="../assets/js/profile-shop.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="../assets/js/jquery.ui.touch-punch.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    

</head>

<body>
    <div id="header" class="container-fluid sticky-top">
        <div class="col-12">
            <div class="row align-items-center" style="background-color: #6945A5; padding: 10px 0 25px 0;">
                <div class="col-1">
                    <img src="../assets/img/icons/Back-(White).png" style="width:30px" onclick="window.location=document.referrer;">
                </div>
                <div id="searchFilter-a" class="col-9 d-flex align-items-center justify-content-center text-white pl-2 pr-2">
                    <form id="searchFilterForm-a" action="search-result" method=GET style="width: 90%;">
                        <?php
                        $query = "";
                        if (isset($_REQUEST['query'])) {
                            $query = $_REQUEST['query'];
                        }
                        ?>
                        <input id="query" placeholder="Search" type="text" class="search-query" name="query" onclick="onFocusSearch()" value="<?= $query; ?>">
                        <img class="d-none" id="delete-query" src="../assets/img/icons/X-fill.png">
                        <img id="voice-search" src="../assets/img/icons/Voice-Command.png">
                        <!-- </div> -->
                    </form>
                </div>
                <a class="col-1" href="cart.php?v=<?= time(); ?>">
                    <div class="position-relative">
                        <img class="float-end" src="../assets/img/icons/Shopping-Cart-(White).png" style="width:30px">
                        <span id="counter-here"></span>
                    </div>
                </a>
                <div class="col-1">
                    <a href="notifications.php">
                    <div class="position-relative">
                        <img class="float-end" src="../assets/img/icons/Shop Manager/App-Notification-(white).png" style="width:30px">
                        <span id='counter-notifs'></span>
                    </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid pt-3 main-container">
        <div class="row px-1 mt-1">
            <div class="col-12 text-purple">
                <a id="to-new-collection">
                    <img class="me-1" src="../assets/img/icons/Add-(Purple).png" style="height:15px; width: auto">
                    New Collection
                </a>
            </div>
        </div>

        <!-- COLLECTIONS LIST -->
        <?php if (count($collections) > 0) {
            foreach ($collections as $col) { ?>
                <div class="row mt-4 collection-row ps-2 slick-row" id="collection-<?= $col['COLLECTION_CODE'] ?>">
                    <div class="col-4 py-3 collections-left d-flex justify-content-center align-items-center">
                        <?php
                        // $collection_thumb = explode('|', $col['COLLECTION_THUMB']);
                        // echo '<img class="collections-img" src="' . $collection_thumb[0] . '">';
                        $thumb_arr = explode('|', $col['COLLECTION_THUMB']);
                        // var_dump($thumb_arr);

                        $thumb_ext = pathinfo($thumb_arr[0], PATHINFO_EXTENSION);
                        // echo $thumb_ext;

                        $image_type_arr = array("jpg", "jpeg", "png", "webp");
                        $video_type_arr = array("mp4", "mov", "wmv", 'flv', 'webm', 'mkv', 'gif', 'm4v', 'avi', 'mpg');

                        if (in_array($thumb_ext, $image_type_arr)) {
                            // echo 'img';
                            echo '<img class="collections-img" src="' . $thumb_arr[0] . '">';
                        } else if (in_array($thumb_ext, $video_type_arr)) {
                            // echo 'video';
                            $image_name = str_replace($thumb_ext, "", $thumb_arr[0]);
                            echo '<video muted class="collections-img" preload="metadata" poster="' . $image_name . 'webp">';
                            echo '<source src="' . $thumb_arr[0] . '" type="video/' . $thumb_ext . '">';
                            echo '</video>';
                        }
                        ?>
                    </div>
                    <div class="col-9 my-3 collections-right">
                        <div class="row mb-1">
                            <div class="col-12 views text-purple">
                                <img class="me-1" src="../assets/img/icons/Eye-Icon.png" style="height:18px; width: auto">
                                <?= $col['TOTAL_VIEWS'] ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <h6 class="collection-title">
                                    <?= $col['NAME'] ?>
                                </h6>
                                <p class="collection-desc">
                                    <?= strip_tags(mb_strimwidth($col['DESCRIPTION'], 0, 40, "...")); ?>
                                </p>
                            </div>
                        </div>

                        <div class="row collection-stats">
                            <div class="col-12">
                                <span>
                                    <?= $col['PRODUCT_COUNT'] ?> items | Updated
                                    <?php

                                    $seconds = strtotime($col['CREATED_AT']);

                                    // print date
                                    $date_diff = round((time() - $seconds) / (60 * 60 * 24));
                                    if ($date_diff == 0) {
                                        $printed_date = round((time() - $seconds) / 3600);
                                        if ($printed_date < 0) {
                                            $printed_date = "0 hours ago";
                                        } else {
                                            $printed_date .= " hours ago";
                                        }
                                        // $printed_date = "today";
                                    } else if ($date_diff == 1) {
                                        $printed_date = "yesterday";
                                    } else if ($date_diff == 2) {
                                        $printed_date = "2 days ago";
                                    } else if ($date_diff == 3) {
                                        $printed_date = "3 days ago";
                                    } else if ($date_diff == 4) {
                                        $printed_date = "4 days ago";
                                    } else if ($date_diff == 5) {
                                        $printed_date = "5 days ago";
                                    } else if ($date_diff == 6) {
                                        $printed_date = "6 days ago";
                                    } else if ($date_diff == 7) {
                                        $printed_date = "7 days ago";
                                    } else if ($date_diff > 7 && $date_diff < 365) {
                                        $printed_date = date("j M Y", $seconds);
                                    } else if ($date_diff >= 365) {
                                        $printed_date = date("j M Y", $seconds);
                                    }

                                    echo $printed_date;
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 visibility-status text-center d-flex align-items-center" id="status-<?= $col['COLLECTION_CODE'] ?>" onclick="changeVisibilityStatus('<?= $col['COLLECTION_CODE'] ?>');">
                        <div class="m-auto">
                            <?php if ($col['STATUS'] == 1) {
                                echo '<img src="../assets/img/icons/Eye-Icon-purple.png">';
                                echo '<p class="text-purple">Public</p>';
                            } else {
                                echo '<img src="../assets/img/icons/Security-darkgrey.png">';
                                echo '<p>Private</p>';
                            }
                            ?>
                        </div>
                    </div>
                    <div class="col-4 delete-collection text-center d-flex align-items-center" id="delete-<?= $col['COLLECTION_CODE'] ?>" onclick="deleteCollection('<?= $col['COLLECTION_CODE'] ?>');">
                        <div class="m-auto">
                            <img src="../assets/img/icons/Delete-white.png">
                            <p>
                                Delete
                            </p>
                        </div>
                    </div>
                </div>
            <?php }
        } else { ?>
            <div class="row mt-4">
                <div class="col-12 text-center">
                    You haven't made any collections. <br>Let's create a collection and start sharing!
                </div>
            </div>
        <?php } ?>
    </div>

    <div class="modal fade" id="update-visibility-success" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <h6>Collection visibility updated.</h6>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-addcart" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="delete-collection-prompt" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-addcart" data-bs-dismiss="modal" id="delete-collection-cancel">Cancel</button>
                    <button type="button" class="btn btn-addcart" id="delete-collection-ok">OK</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="delete-success" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <h6>Collection deleted.</h6>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-addcart" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="delete-error" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-addcart" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="no-purchases" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <h6>You haven't made any purchases yet.</h6>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-addcart" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
<script src="../assets/js/update_counter.js"></script>
<script type="text/javascript" src="../assets/js/script-edit-collection.js?random=<?= time() ?>"></script>
<script>

</script>

</html>