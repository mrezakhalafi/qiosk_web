<!doctype html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Project</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link href="../assets/css/tab3-style.css?v=<?= time(); ?>" rel="stylesheet">
    <link href="../assets/css/circle-loader.css?v=<?= time(); ?>" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>

    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/jQueryRotate.js"></script>
    <script src="../assets/js/jquery.validate.js"></script>
    <script src="../assets/js/isInViewport.min.js?v=<?= time(); ?>"></script>
    <link rel="stylesheet" href="../assets/css/style-store_list.css?random=<?= time(); ?>">
    <link rel="stylesheet" href="../assets/css/gridstack.min.css" />
    <link rel="stylesheet" href="../assets/css/gridstack-extra.min.css" />
    <script type="text/javascript" src="../assets/js/gridstack-static.js"></script>
    <script type="text/javascript" src="../assets/js/pulltorefresh.js"></script>
    

<body class="tab3">
    <div class="container-fluid">
        <div id="header-layout" class="col-12 sticky-top">
            <div class="row d-flex align-items-center justify-content-between" style="background-color: #6945A5; padding: 10px 0 10px 0;">
                <div class="col-1">
                    <img src="../assets/img/icons/Sort-(White).png" style="width:30px" onclick="myFunction();">
                </div>
                <div id="searchFilter-a" class="col-9 d-flex align-items-center justify-content-center text-white">
                    <form id="searchFilterForm-a" action="search-result" method=GET style="width: 90%;">
                        <!-- <div class="d-flex align-items-center div-search"> -->
                        <?php
                        $query = "";
                        if (isset($_REQUEST['query'])) {
                            $query = $_REQUEST['query'];
                        }
                        ?>
                        <input id="query" placeholder="Search" type="text" class="search-query" name="query" onclick="onFocusSearch()" value="<?= $query; ?>">
                        <img class="d-none" id="delete-query" src="../assets/img/icons/X-fill.png">
                        <img id="voice-search" src="../assets/img/icons/Voice-Command.png" onclick="voiceSearch()">
                        <!-- </div> -->
                    </form>
                </div>
                <a class="col-1" href="cart.php?v=<?= time(); ?>" style="margin-left: -.5rem;">
                    <div class="position-relative me-3">
                        <img class="float-end" src="../assets/img/icons/Shopping-Cart-(White).png" style="width:30px">
                        <span id="counter-here"></span>
                    </div>
                </a>
                <div class="col-1" style="margin-right: .5rem;">
                    <a href="notifications.php">
                    <div class="position-relative me-4">
                        <img class="float-end" src="../assets/img/icons/Shop Manager/App-Notification-(white).png" style="width:30px">
                        <span id='counter-notifs'></span>
                    </div>
                    </a>
                </div>
            </div>
            <div id="category-tabs" class="row small-text">
                <ul class="nav nav-tabs horizontal-slide" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link" id="categoryFilter-all">All</a>
                    </li>
                    <?php

                    $filters = include_once($_SERVER['DOCUMENT_ROOT'] . '/qiosk_web/logics/fetch_products_category.php');

                    for ($i = 0; $i < count($filters); $i++) {

                        $idFilter = $filters[$i]["ID"];
                        $nameFilter = $filters[$i]["NAME"];
                        echo '<li class="nav-item">';

                        // echo '<a class="nav-link" id="categoryFilter-' . $idFilter . '" href="tab3-main.php?filter=' . $idFilter . '">' . $nameFilter . '</a>';
                        echo '<a class="nav-link" id="categoryFilter-' . $idFilter . '">' . $nameFilter . '</a>';
                        echo '</li>';
                    }

                    ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="bg-white pt-3 box">
        <div id="container">
            <div id="loading" class="d-none">
                <!-- <div class="col-sm mt-2">
                    <h5 class="prod-name" style="text-align:center;">Sedang memuat. Tunggu sebentar...</h5>
                </div> -->
                <div class="loader-1 center"><span></span></div>
            </div>
            <div class="d-none" id="no-stores">
                <div class="col-sm mt-2">
                    <h5 class="prod-name" style="text-align:center;">Tidak ada toko yang sesuai kriteria</h5>
                </div>
            </div>
            <div id="content-grid" class="grid-stack grid-stack-3">
                <div id="grid-overlay" class="overlay d-none"></div>
            </div>
        </div>
        <script>
            const search = <?php if (isset($_GET['query'])) {
                                echo '"' . $_GET['query'] . '"';
                            } else {
                                echo "null";
                            } ?>;
            const filter = <?php if (isset($_GET['filter'])) {
                                echo '"' . $_GET['filter'] . '"';
                            } else {
                                echo "null";
                            } ?>;
        </script>
    </div>
    <div class="bg-grey stack-top" style="display: none;" id="stack-top">
        <div class="container small-text">
            <div id="sort-store-popular" class="bg-white row py-3">
                <div class="col-6" style="font-weight:500;">Popular</div>
                <div class="col-6 check-mark">
                    <img class="float-end" src="../assets/img/icons/Check-(Orange).png" style="width: 15px; height: 15px;"></img>
                </div>
            </div>
            <div id="sort-store-date" class="bg-white row py-3" style="margin-top: 1px;">
                <div class="col-6" style="font-weight:500;">Date Added (New to Old)</div>
                <div class="col-6 check-mark d-none">
                    <img class="float-end" src="../assets/img/icons/Check-(Orange).png" style="width: 15px; height: 15px;"></img>
                </div>
            </div>
            <div id="sort-store-follower" class="bg-white row py-3" style="margin-top: 1px;">
                <div class="col-6" style="font-weight:500;">Followers</div>
                <div class="col-6 check-mark d-none">
                    <img class="float-end" src="../assets/img/icons/Check-(Orange).png" style="width: 15px; height: 15px;"></img>
                </div>
            </div>
        </div>
    </div>
    <!-- FOOTER -->

</body>

<!-- <script type="text/javascript" src="../assets/js/script-filter.js?random=<?= time(); ?>"></script> -->
<script src="../assets/js/update_counter.js?random=<?= time(); ?>"></script>
<script type="text/javascript" src="../assets/js/script-store_list.js?random=<?= time(); ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
<script>
    function myFunction() {
        var x = document.getElementById("stack-top");
        if (x.style.display === "none") {
            x.style.display = "block";
            $('#grid-overlay').removeClass('d-none');
        } else {
            x.style.display = "none";
            $('#grid-overlay').addClass('d-none');
        }
    }
</script>

</html>