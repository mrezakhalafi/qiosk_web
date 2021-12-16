<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
?>

<!doctype html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Qiosk - Search</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link href="../assets/css/search-result-style.css?v=<?php echo time(); ?>" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>

    <script src="../assets/js/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="../assets/js/jquery.ui.touch-punch.min.js"></script>
    

<body style="background-color: white;">
    <div class="container-fluid">
        <div class="col-12">
            <div class="row align-items-center" style="background-color: #6945A5; padding: 10px 0 10px 0;">
                <div class="col-1">
                    <a href="#" id="back-btn" onclick="window.history.back();">
                        <img src="../assets/img/icons/Back-(White).png" style="width:30px">
                    </a>
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
            <div class="row small-text" style="background-color: #6945A5;">
                <div class="container">
                    <ul class="nav nav-tabs nav-fill" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link <?php
                                                if (isset($_REQUEST['query'])) {
                                                    echo 'active';
                                                }
                                                ?>" href="#" data-toggle="tab" role="tab" onclick="searchTop()">Top</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-bs-toggle="tab" role="tab" onclick="searchAccount()">Account</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-bs-toggle="tab" role="tab" onclick="searchShop()">Shops</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data--bstoggle="tab" role="tab">Tags</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="container small-text bg-white pt-4" id="section-top">
        <?php

        $result = include($_SERVER['DOCUMENT_ROOT'] . '/qiosk_web/logics/search_query.php');
        $result_users = include($_SERVER['DOCUMENT_ROOT'] . '/qiosk_web/logics/search_users.php');

        if (count($result) > 0 || count($result_users) > 0) {

            // for ($i = 0; $i < count($result); $i++) {
            foreach ($result as $res) {

                $shop_pic = explode('|', $res['THUMB_ID']);
                $shop_name = $res['NAME'];
                $shop_code = $res['CODE'];
                // $shop_desc = $res['DESCRIPTION'];
                // $shop_desc = strip_tags(mb_strimwidth($res['DESCRIPTION'], 0, 35, "..."));
                $shop_desc = "";
                if (strlen($res['DESCRIPTION']) > 35) {
                    $shop_desc = substr($res['DESCRIPTION'], 0, 35) . '...';
                } else {
                    $shop_desc = $res['DESCRIPTION'];
                }

        ?>
                <div class="row mb-3" onclick="openShopProfile('<?= $shop_code ?>');">
                    <div class="col-3 d-flex align-items-center justify-content-center">
                        <img class="logo-merchant" src="../images/<?= $shop_pic[0] ?>" height="50px" width="50px" style="object-fit: cover; object-position: center">
                    </div>
                    <div class="col-9 small-left-padding d-flex align-items-center">
                        <div class="col-7">
                            <div class="row">
                                <div class="col-1 p-0"><img src="../assets/img/icons/Verified.png" height="10px;"></div>
                                <div class="col-11 p-0"><b><?= $shop_name ?></b></div>
                            </div>
                            <div class="row"><?= $shop_desc ?></div>
                        </div>
                    </div>
                </div>
            <?php }

            foreach ($result_users as $res) {

                $user_pic = $res['IMAGE'];
                $name = $res['FIRST_NAME']." ".$res['LAST_NAME'];
                $f_pin = $res['F_PIN'];


            ?>      
                <a href="tab5-profile?id_visit=<?= $f_pin ?>">
                    <div class="row mb-3">
                        <div class="col-3 d-flex align-items-center justify-content-center">

                            <?php if ($user_pic): ?>
                                <img class="logo-merchant" src="https://qmera.io/filepalio/image/<?= $user_pic ?>" height="50px" width="50px" style="object-fit: cover; object-position: center">
                            <?php else: ?>
                                <img class="logo-merchant" src="../assets/img/tab5/no-avatar.jpg" height="50px" width="50px" style="object-fit: cover; object-position: center">
                            <?php endif; ?>

                        </div>
                        <div class="col-9 small-left-padding d-flex align-items-center">
                            <div class="col-7">
                                <div class="row">
                                    <div class="col-11 p-0"><b><?= $name ?></b></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            <?php }
            
        } else { ?>

            <div class="row mb-3">
                <div class="col-12 d-flex align-items-center justify-content-center">
                    <h6>Sorry, no results for the search term '<?= $_GET['query']; ?>'</h6>
                </div>
            </div>

        <?php } ?>
    </div>

    <div class="container small-text bg-white pt-4" id="section-profile">
        <?php

        $result = include($_SERVER['DOCUMENT_ROOT'] . '/qiosk_web/logics/search_users.php');

        if (count($result) > 0) {

            // for ($i = 0; $i < count($result); $i++) {
            foreach ($result as $res) {

                $user_pic = $res['IMAGE'];
                $name = $res['FIRST_NAME']." ".$res['LAST_NAME'];
                $f_pin = $res['F_PIN'];


        ?>      
                <a href="tab5-profile?id_visit=<?= $f_pin ?>">
                    <div class="row mb-3">
                        <div class="col-3 d-flex align-items-center justify-content-center">

                            <?php if ($user_pic): ?>
                                <img class="logo-merchant" src="https://qmera.io/filepalio/image/<?= $user_pic ?>" height="50px" width="50px" style="object-fit: cover; object-position: center">
                            <?php else: ?>
                                <img class="logo-merchant" src="../assets/img/tab5/no-avatar.jpg" height="50px" width="50px" style="object-fit: cover; object-position: center">
                            <?php endif; ?>

                        </div>
                        <div class="col-9 small-left-padding d-flex align-items-center">
                            <div class="col-7">
                                <div class="row">
                                    <div class="col-11 p-0"><b><?= $name ?></b></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            <?php }
        } else { ?>

            <div class="row mb-3">
                <div class="col-12 d-flex align-items-center justify-content-center">
                    <h6>Sorry, no results for the search term '<?= $_GET['query']; ?>'</h6>
                </div>
            </div>

        <?php } ?>
    </div>

    <div class="container small-text bg-white pt-4" id="section-shop">
        <?php

        $result = include($_SERVER['DOCUMENT_ROOT'] . '/qiosk_web/logics/search_query.php');

        if (count($result) > 0) {

            // for ($i = 0; $i < count($result); $i++) {
            foreach ($result as $res) {

                $shop_pic = explode('|', $res['THUMB_ID']);
                $shop_name = $res['NAME'];
                $shop_code = $res['CODE'];
                // $shop_desc = $res['DESCRIPTION'];
                // $shop_desc = strip_tags(mb_strimwidth($res['DESCRIPTION'], 0, 35, "..."));
                $shop_desc = "";
                if (strlen($res['DESCRIPTION']) > 35) {
                    $shop_desc = substr($res['DESCRIPTION'], 0, 35) . '...';
                } else {
                    $shop_desc = $res['DESCRIPTION'];
                }

        ?>
                <div class="row mb-3" onclick="openShopProfile('<?= $shop_code ?>');">
                    <div class="col-3 d-flex align-items-center justify-content-center">
                        <img class="logo-merchant" src="../images/<?= $shop_pic[0] ?>" height="50px" width="50px" style="object-fit: cover; object-position: center">
                    </div>
                    <div class="col-9 small-left-padding d-flex align-items-center">
                        <div class="col-7">
                            <div class="row">
                                <div class="col-1 p-0"><img src="../assets/img/icons/Verified.png" height="10px;"></div>
                                <div class="col-11 p-0"><b><?= $shop_name ?></b></div>
                            </div>
                            <div class="row"><?= $shop_desc ?></div>
                        </div>
                    </div>
                </div>
            <?php }
        } else { ?>

            <div class="row mb-3">
                <div class="col-12 d-flex align-items-center justify-content-center">
                    <h6>Sorry, no results for the search term '<?= $_GET['query']; ?>'</h6>
                </div>
            </div>

        <?php } ?>
    </div>
    <!-- FOOTER -->

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
<script src="../assets/js/script-search_result.js?random=<?= time(); ?>"></script>
<script>

    $(".nav-tabs li.nav-item a.nav-link").click(function() {
        $(".nav-tabs li.nav-item a.nav-link").removeClass('active');
    });

    $('#section-profile').hide();
    $('#section-shop').hide();

    function searchAccount(){
        $('#section-profile').show();
        $('#section-top').hide();
        $('#section-shop').hide();
    }

    function searchTop(){
        $('#section-profile').hide();
        $('#section-top').show();
        $('#section-shop').hide();
    }

    function searchShop(){
        $('#section-profile').hide();
        $('#section-top').hide();
        $('#section-shop').show();
    }

</script>
<script src="../assets/js/update_counter.js"></script>
</html>