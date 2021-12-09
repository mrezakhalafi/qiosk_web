<?php

	// KONEKSI

	include_once($_SERVER['DOCUMENT_ROOT'] . '/qiosk_web/logics/chat_dbconn.php');
	$dbconn = paliolite();
	session_start();

	// ID SHOP GET

	if (isset($_GET['id'])){
		$id_shop = $_GET['id'];
	}else if(isset($_SESSION['id_shop'])){
		$id_shop = $_SESSION["id_shop"];
	}else if(isset($_COOKIE['id_shop'])){
		$id_shop = $_COOKIE['id_shop'];
	}

	// CHECK SHOP ID

	if (!isset($id_shop)) {
		die("ID Shop Tidak Diset.");
	}

  $active_ads = null;
  $past_ads = null;

?>

<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Qiosk</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
  <link href="../assets/css/tab5-style.css" rel="stylesheet">
  <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
</head>

<body class="bg-white-background" style="display:none">

  <!-- NAVBAR -->

  <nav class="navbar navbar-light navbar-shop-manager">
    <div class="container">
      <a href="tab5-shop-manager.php">
        <img src="../assets/img/tab5/Back-(Black).png" class="navbar-back-black">
      </a>
      <p class="navbar-title-2" data-translate="tab5ads-1">Ads</p>
      <div class="navbar-brand pt-2 navbar-brand-slot">
        <a href="tab5-create-an-ad.php">
          <img src="../assets/img/tab5/Add-(Purple).png" class="navbar-ads-add">
        </a>
      </div>
    </div>
  </nav>

  <!-- SECTION ACTIVE PROMOTION -->

  <div class="section-active-promotion">
    <div class="ads-single">
        <div class="ads-title-bar">
          <div class="container">
            <a href="tab5-ads-active.php">
              <div class="row">
                <div class="col-11 col-md-11 col-lg-11">
                  <p class="small-text" data-translate="tab5ads-2">Active Promotions</p>
                </div>
                <div class="col-1 col-md-1 col-lg-1">
                  <b>></b>
                </div>
              </div>
            </a>
          </div>
        </div>
      </div>
      <!-- <div class="ads-product">
        <div class="container">
          <div class="row">
            <div class="col-6 col-md-6 col-lg-6">
              <span class="badge rounded-pill purple-badge text-light">
                <span data-translate="tab5ads-6">Ongoing</span>
              </span>								
            </div>
            <div class="col-6 col-md-6 col-lg-6 d-flex justify-content-end">
              <a href="tab5-promotion-insight.php">
                <p class="ads-text-small">
                  <b data-translate="tab5ads-14">View Insights ></b>
                </p>
              </a>
            </div>
          </div>
          <div class="row">
            <div class="col-3 col-md-3 col-lg-2">
              <img src="../assets/img/tab5/product-3.jpg" class="ads-image">
            </div>
            <div class="col-9 col-md-9 col-lg-7">
              <div class="ads-text text-purple">
                <b>Rak Sudut Campaign</b>
              </div>
                <div class="row">
                  <div class="col-6 col-md-6 col-lg-6">
                    <div class="ads-text text-grey" data-translate="tab5ads-8">Views</div>
                    <div class="ads-text text-grey" data-translate="tab5ads-9">Budget</div>
                    <div class="ads-text text-grey" data-translate="tab5ads-10">Period</div>
                  </div>
                  <div class="col-6 col-md-6 col-lg-6">
                    <div class="ads-text text-grey d-flex justify-content-end">10,500</div>
                    <div class="ads-text text-grey d-flex justify-content-end">Rp 4,500,000</div>
                    <div class="ads-text text-grey d-flex justify-content-end">10 August - 30 August</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div> -->
      <?php if (isset($active_ads)): ?>

      <?php else: ?>
        <p class="text-center small-text mt-3" data-translate="tab5ads-4">There are no active promotions</p>
      <?php endif; ?>
    </div>
  </div>

  <!-- SECTION POST PROMOTION -->

  <div class="section-post-promotion">
    <div class="ads-single">
      <div class="ads-title-bar-2">
        <div class="container">
          <a href="tab5-ads-past.php">
            <div class="row">
              <div class="col-11 col-md-11 col-lg-11">
                <p class="small-text" data-translate="tab5ads-3">Past Promotions</p>
              </div>
              <div class="col-1 col-md-1 col-lg-1">
                <b>></b>
              </div>
            </div>  
          </a>
        </div>
      </div>
    <!-- <div class="ads-product">
        <div class="container">
          <div class="row">
            <div class="col-6 col-md-6 col-lg-6">
              <span class="badge rounded-pill orange-badge-2 text-light">
                <span data-translate="tab5ads-7">Completed</span>
              </span>									
            </div>
            <div class="col-6 col-md-6 col-lg-6 d-flex justify-content-end">
              <a href="tab5-promotion-insight.php">
                <p class="ads-text-small">
                  <b data-translate="tab5ads-14">View Insights ></b>
                </p>
              </a>
            </div>
          </div>
          <div class="row">
            <div class="col-3 col-md-3 col-lg-2">
              <img src="../assets/img/tab5/product-3.jpg" class="ads-image">
            </div>
            <div class="col-9 col-md-9 col-lg-7">
              <div class="ads-text text-purple">
                <b>Rak Sudut Campaign</b>
              </div>
                <div class="row">
                  <div class="col-6 col-md-6 col-lg-6">
                    <div class="ads-text text-grey" data-translate="tab5ads-8">Views</div>
                    <div class="ads-text text-grey" data-translate="tab5ads-9">Budget</div>
                    <div class="ads-text text-grey" data-translate="tab5ads-10">Period</div>
                  </div>
                  <div class="col-6 col-md-6 col-lg-6">
                    <div class="ads-text text-grey d-flex justify-content-end">10,500</div>
                    <div class="ads-text text-grey d-flex justify-content-end">Rp 4,500,000</div>
                    <div class="ads-text text-grey d-flex justify-content-end">10 August - 30 August</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="ads-product">
        <div class="container">
          <div class="row">
            <div class="col-6 col-md-6 col-lg-6">
              <span class="badge rounded-pill orange-badge-2 text-light">
                <span data-translate="tab5ads-7">Completed</span>	
              </span>								
            </div>
            <div class="col-6 col-md-6 col-lg-6 d-flex justify-content-end">
              <a href="tab5-promotion-insight.php">
                <p class="ads-text-small">
                  <b data-translate="tab5ads-14">View Insights ></b>
                </p>
              </a>
            </div>
          </div>
          <div class="row">
            <div class="col-3 col-md-3 col-lg-2">
              <img src="../assets/img/tab5/product-3.jpg" class="ads-image">
            </div>
            <div class="col-9 col-md-9 col-lg-7">
              <div class="ads-text text-purple">
                <b>Rak Sudut Campaign</b>
              </div>
                <div class="row">
                  <div class="col-6 col-md-6 col-lg-6">
                    <div class="ads-text text-grey" data-translate="tab5ads-8">Views</div>
                    <div class="ads-text text-grey" data-translate="tab5ads-9">Budget</div>
                    <div class="ads-text text-grey" data-translate="tab5ads-10">Period</div>
                  </div>
                  <div class="col-6 col-md-6 col-lg-6">
                    <div class="ads-text text-grey d-flex justify-content-end">10,500</div>
                    <div class="ads-text text-grey d-flex justify-content-end">Rp 4,500,000</div>
                    <div class="ads-text text-grey d-flex justify-content-end">10 August - 30 August</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div> -->
      <?php if (isset($active_ads)): ?>

      <?php else: ?>
        <p class="text-center small-text mt-3" data-translate="tab5ads-5">There are no promotion ends</p>
      <?php endif; ?>

    </div>
  </div>

  <!-- Modal -->

  <!-- Dinyalakan Jika Ada Data Backend -->

  <div class="modal fade" id="congratsAdsModal" tabindex="-1" aria-labelledby="successAdsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="align-items: flex-end;">
      <div class="modal-content">
        <div class="row d-flex justify-content-center">
          <hr class="shop-modal-line">
        </div>
        <div class="modal-body text-center">
          <img src="../assets/img/tab5/Ad-Created-Successfully.png" class="success-ads-image">
          <div class="text-purple"><b data-translate="tab5ads-11">Success!</b></div>
          <div class="small-text text-grey" data-translate="tab5ads-12">Your ad is now live.</div>
          <div class="btn-continue-ads" data-bs-dismiss="modal" data-translate="tab5ads-13">Continue</div>
        </div>
      </div>
    </div>
  </div>
</body>

<!-- FOOTER -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script type="module" src="../assets/js/translate.js"></script>

<script>

	// SCRIPT CHANGE LANGUAGE

	$(document).ready(function(){
		function changeLanguage(){

		var lang = localStorage.lang;	
		change_lang(lang);
		
		}

		changeLanguage();
    $('body').show();
	});

  // SHOW MODAL AFTER SUCCESS ADS

  <?php

    if ($_GET['success'] =='true'){
      echo('$(function() {
      $("#congratsAdsModal").modal("show");
      });');
    }

  ?>

</script>
</html>