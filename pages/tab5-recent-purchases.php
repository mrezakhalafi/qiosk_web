<?php

    // KONEKSI

    include_once($_SERVER['DOCUMENT_ROOT'] . '/qiosk_web/logics/chat_dbconn.php');
    $dbconn = paliolite();
    session_start();

    // GET F_PIN FROM SESSION

    if (!isset($_SESSION['user_f_pin'])) {
        $id_user = $_GET['f_pin'];
    }else{
        $id_user = $_SESSION["user_f_pin"];
    }

    $collection_code = $_GET['collection_code'];

    // CHECK USER

    if (!isset($id_user)) {
        die("ID User Tidak Diset.");
    }

    // SELECT USER

    $query = $dbconn->prepare("SELECT * FROM USER_LIST WHERE F_PIN = '$id_user'");
    $query->execute();
    $user = $query->get_result()->fetch_assoc();
    $query->close();

    // SELECT RECENT PURCHASES

    $query = $dbconn->prepare("SELECT PURCHASE.*, PRODUCT.*, SHOP.NAME a, SHOP.IS_VERIFIED b FROM 
                                PURCHASE LEFT JOIN PRODUCT ON PURCHASE.PRODUCT_ID = PRODUCT.CODE 
                                LEFT JOIN SHOP ON PURCHASE.MERCHANT_ID = SHOP.CODE WHERE FPIN = 
                                '$id_user' AND STATUS = 4 AND PRODUCT.CODE NOT IN ( SELECT PRODUCT_CODE FROM COLLECTION_PRODUCT WHERE COLLECTION_CODE = '" . $collection_code . "')");
    $query->execute();
    $recent_purchase = $query->get_result();
    $query->close();

?>

<!doctype html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Qiosk</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link href="../assets/css/tab5-style.css" rel="stylesheet">
    <link href="../assets/css/tab5-collection-style.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
</head>

<body class="bg-white-background" style="display:none">

    <style>
        html,
        body {
            max-width: 100%;
            overflow-x: hidden;
        }
    </style>

    <!-- NAVBAR -->

    <div id="header" class="container-fluid">
        <div class="col-12">
            <div class="row align-items-center" style="background-color: #6945A5; padding: 10px 0 30px 0; padding-bottom: 55px">
                <div class="col-1">
                    <a href="tab5-collection-self.php?collection_code=<?= $collection_code ?>">
                        <img src="../assets/img/icons/Back-(White).png" style="width:30px">
                    </a>
                </div>
                <div id="searchFilter-a" class="col-9 d-flex align-items-center justify-content-center text-white pl-2 pr-2">
                    <form autocomplete="off" id="searchFilterForm-a" action="search-result" method=GET style="width: 90%;">

                        <?php
                            $query = "";
                            if (isset($_REQUEST['query'])) {
                                $query = $_REQUEST['query'];
                            }
                        ?>

                        <input id="query" placeholder="Search" type="text" class="search-query" name="query" value="<?= $query; ?>">
                        <img class="d-none" id="delete-query" src="../assets/img/icons/X-fill.png">
                        <img id="voice-search" onclick="voiceSearch()" src="../assets/img/icons/Voice-Command.png">

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

    <!-- SECTION RECENT PURCHASE -->

    <div class="section-recent-purchase" style="border-top-right-radius: 35px; background-color: #FAFAFF">
        <div class="container">
            <p class="small-text text-grey mb-3"><b data-translate="tab5recentpurchases-1">Recent Purchases</b></p>

            <!-- LOOPING RECENT PURCHASE -->

            <?php if (mysqli_num_rows($recent_purchase)): ?>

                <?php foreach ($recent_purchase as $recent): ?>

                    <div class="row recent-purchase-single-product mt-3">
                        <div class="col-3">

                            <?php 
                                $product_image = explode('|', $recent['THUMB_ID']);
                                $order_date = date_create($recent['CREATED_AT']); 
                            ?>

                            <!-- IF ARRAY IS VIDEO, MOVE TO NEXT ARRAY TO GET IMAGE THUMBNAIL -->

                            <?php $i = 0; ?>

                            <?php if (substr($product_image[$i], -3) == "mp4"): ?>

                               <!-- LOOP VIDEO FILE UNTIL GET PHOTO FOR THUMBNAIL -->

								<?php 
                                while (substr($product_image[$i], -3) == "mp4"):
                                    $product_image_video = $product_image[$i+1];
                                    $i++;
								endwhile; 
                                ?>

								<!-- IF ALL MEDIA IS VIDEO, OKAY THEN MAKE COVER IS VIDEO THUMBNAIL -->

								<?php if ($product_image_video): ?>
								    <img src="../images/<?= str_replace("http://202.158.33.26/qiosk_web/images/", "", $product_image_video) ?>" class="recent-purchase-image">
								<?php else: ?>
							    	<video src="../images/<?= str_replace("http://202.158.33.26/qiosk_web/images/", "", $product_image[0]) ?>#t=0.5" style="object-fit: none" type="video/mp4" class="recent-purchase-image"></video>
								<?php endif; ?>

                            <?php else: ?>

                                <img src="../images/<?= str_replace("http://202.158.33.26/qiosk_web/images/", "", $product_image[$i]) ?>" class="recent-purchase-image">

                            <?php endif; ?>

                        </div>
                        <div class="col-7" style="padding-left: 9px; margin-top: -7px">

                            <?php if ($recent['b'] == 1) : ?>
                                <img src="../assets/img/tab5/Verified.png" class="recent-purchase-verified">
                                <span class="smallest-text"><?= $recent['a'] ?></span>
                            <?php else: ?>
                                <span class="smallest-text"><?= $recent['a'] ?></span>
                            <?php endif; ?>

                            <div class="small-text"><?= $recent['NAME'] ?></div>

                            <div class="small-text text-grey mt-1">
                                Rp <?= number_format($recent['PRICE'], 0, ",", ",") ?>
                            </div>

                            <div class="smallest-text text-grey mt-2"><span data-translate="tab5recentpurchases-2"> purchased on</span> <?= date_format($order_date, "d/m/Y"); ?></div>
                        </div>
                        <div class="col-2 my-auto">
                            <form action="../logics/tab5/add_product_collection" method="POST">
                                <input type="hidden" name="product_code" value="<?= $recent['CODE'] ?>">
                                <input type="hidden" name="collection_code" value="<?= $collection_code ?>">
                                <button style="outline:none; border:none; background-color: #FAFAFF" type="submit"><img src="../assets/img/tab5/Add-(Purple).png" class="recent-purchase-add"></button>
                            </form>
                        </div>
                    </div>

                <?php endforeach; ?>

            <?php else: ?>
                <p class="text-center small-text mt-5 text-grey" data-translate="tab5recentpurchases-3">Anda belum memiliki riwayat transaksi.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

<!-- FOOTER -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="module" src="../assets/js/translate.js"></script>
<script src="../assets/js/update_counter.js"></script>

<script>

    //  SCRIPT CHANGE LANGUAGE

    $(document).ready(function() {
        function changeLanguage() {

            var lang = localStorage.lang;
            change_lang(lang);

        }

        changeLanguage();

        if (localStorage.lang == 1) {
            $('#query').attr('placeholder', 'Pencarian');
        } else if (localStorage.lang == 0) {
            $('#query').attr('placeholder', 'Search');
        }

        $('body').show();
    });

    // FUNCTION VOICE SEARCH

	function voiceSearch(){
		Android.toggleVoiceSearch();
	}

	function submitVoiceSearch(searchQuery){
		$('#query').val(searchQuery);
        $('#delete-query').removeClass('d-none');
	}

    // FUNCTION SAVE SEARCH

	$('#query').on('change', function(){
		localStorage.setItem("search_keyword", this.value);
	});

	// FUNCTION X ON SEARCH

	$("#delete-query").click(function (){
		$('#query').val('');
        $('#delete-query').addClass('d-none');
	})

	$('#query').keyup(function (){
		console.log('is typing: ' + $(this).val());

		if ($(this).val() != '') {
		$('#delete-query').removeClass('d-none');
		} else {
		$('#delete-query').addClass('d-none');
		}
	})

</script>
</html>