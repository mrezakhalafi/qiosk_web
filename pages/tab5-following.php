<?php

	// KONEKSI

	include_once($_SERVER['DOCUMENT_ROOT'] . '/qiosk_web/logics/chat_dbconn.php');
	$dbconn = paliolite();
	session_start();

  // GET USER FROM SESSION

  if (!isset($_SESSION['user_f_pin'])){
    $id_user = $_GET['f_pin'];
    $_SESSION['user_f_pin'] = $id_user;
  }else{
    $id_user = $_SESSION["user_f_pin"];
  }

  // CHECK USER

  if (!isset($id_user)) {
    die("ID User Tidak Diset.");
  }

	// SELECT USER FOLLOWING

  // IF SEARCH IS ACTIVE

  if (isset($_GET['query'])){

    $query = $_GET['query'];

    $query = $dbconn->prepare("SELECT * FROM SHOP_FOLLOW LEFT JOIN SHOP ON SHOP.CODE = 
                                SHOP_FOLLOW.STORE_CODE WHERE SHOP_FOLLOW.F_PIN = '$id_user' AND 
                                SHOP.NAME LIKE '%".$query."%' ORDER BY SHOP_FOLLOW.FOLLOW_DATE DESC");
    $query->execute();
    $user_following = $query->get_result();
    $query->close();

  }else{

    $query = $dbconn->prepare("SELECT * FROM SHOP_FOLLOW LEFT JOIN SHOP ON SHOP.CODE = 
                              SHOP_FOLLOW.STORE_CODE WHERE SHOP_FOLLOW.F_PIN = '$id_user' 
                              ORDER BY SHOP_FOLLOW.FOLLOW_DATE DESC");
    $query->execute();
    $user_following = $query->get_result();
    $query->close();
  
  }
	
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
  <script src="../assets/js/wishlist.js?v=<?php echo time(); ?>"></script>
</head>

<body class="bg-white-background" style="display:none">

  <!-- NAVBAR -->

  <nav class="navbar navbar-light bg-purple">
    <div class="container">
      <a href="tab5.php">
        <img src="../assets/img/tab5/Back-(White).png" class="navbar-back-white">
      </a>
      <p class="navbar-title" data-translate="tab5following-1">Following</p>
      <div id="searchBar" class="col-9 col-md-9 col-lg-9 d-flex align-items-center justify-content-center text-white pl-2 pr-2">
        <form id="searchFilterForm-a" action="tab5-following" method="GET" style="width: 95%;">

          <?php
            $query = "";
            if (isset($_REQUEST['query'])) {
              $query = $_REQUEST['query'];
              
            }
          ?>

          <input id="query" placeholder="Search" type="text" class="search-query" name="query">
          <img class="d-none" id="delete-query" src="../assets/img/icons/X-fill.png">
          <img id="voice-search" onclick="voiceSearch()" src="../assets/img/icons/Voice-Command.png">
        </form>
      </div>
      <div class="navbar-brand pt-2 navbar-brand-slot">
        <img src="../assets/img/tab5/Search-(White).png" class="search-white-right">
      </div>
    </div>
  </nav>

  <!-- SECTION RECENT ACTIVITIES -->

  <div class="section-recent-activities">
    <div class="container recent-activities-title">
      <p class="text-purple small-text" data-translate="tab5following-2">Recent Activities</p>
    </div>
    <div class="container">

      <!-- IF USER HAVE FOLLOWERS -->

      <?php if (mysqli_num_rows($user_following) > 0): ?>

        <?php foreach($user_following as $follow): ?>

        <?php $images = explode('|', $follow['THUMB_ID'] ); ?>

        <div class="row small-text one-followers">
          <div class="col-1 col-md-1 col-lg-1" style="margin-right: 10px;">
            <a href="tab3-profile.php?store_id=<?= $follow['CODE'] ?>&f_pin=<?= $id_user ?>">
              <img src="../images/<?= $images[0] ?>" class="followers-ava" style="object-fit: cover; height: 30px; border-radius: 50%">
            </a>
          </div>
          <div class="col-7 col-md-7 col-lg-7" style="margin-right: 20px;">
            <a href="tab3-profile.php?store_id=<?= $follow['CODE'] ?>&f_pin=<?= $id_user ?>">
              <div><?= $follow['NAME'] ?></div>
            </a>
            <div class="smallest-text text-grey"><?= date('d/m/y', $follow['FOLLOW_DATE']/1000) ?></div>
            </div>
          <div class="col-3 col-md-3 col-lg-3">
            <input type="hidden" name="f_pin" value="<?= $id_user ?>">
            <input type="hidden" name="shop_id" value="<?= $follow['CODE'] ?>">
            <button class="btn-follow" onclick="openModal('<?= $follow['NAME'] ?>','<?= $follow['CODE'] ?>')" type="button" style="padding-left:5px; padding-right:5px" data-translate="tab5following-3" data-toggle="modal" data-target="#unfollowModal">Unfollow</button>
          </div>
        </div>

        <?php endforeach; ?>

      <?php else: ?>
      
        <p class="text-center small-text mt-5" data-translate="tab5following-4">You havent followed anyone yet.</p>

      <?php endif; ?>

      </div>
    </div>
  </div>

  <!-- UNFOLLOW CONFIRMATION MODAL -->

  <div class="modal fade" id="unfollowModal" tabindex="-1" role="dialog" aria-labelledby="unfollowModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content" style="height:100%">
        <div class="modal-body">
          <span data-translate="tab5following-5" style="font-size: 14px">Are you sure to unfollow</span><span style="font-size: 14px" id="text-shop-name"></span><span style="font-size: 14px">?</span>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" onclick="closeModal()" style="font-size: 14px; color: #6945A5; border: 1px solid #6945A5; background-color: #FFFFFF" data-dismiss="modal" data-translate="tab5following-7">No</button>
          <form action="../logics/tab5/unfollow_shop" method="POST">
            <input type="hidden" name="f_pin" value="<?= $id_user ?>">
            <input type="hidden" name="shop_id" id="shop_id" value="">
            <button type="submit" class="btn" style="font-size: 14px; color: #FFFFFF; background-color: #6945A5" data-translate="tab5following-6">Yes</button>
          </form>
        </div>
      </div>
    </div>
  </div>

</body>

<!-- FOOTER -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="module" src="../assets/js/translate.js"></script>

<script>

  //  SCRIPT CHANGE LANGUAGE

	$(document).ready(function() {
    function changeLanguage() {

      var lang = localStorage.lang;
      change_lang(lang);

    }
    changeLanguage();
    $('body').show();
  });

  // OPEN & CLOSE MODAL

  function openModal(name, code){
    $('#unfollowModal').modal('show');
    $('#text-shop-name').text(name);
    $('#shop_id').val(code);
  }

  function closeModal(){
    $('#unfollowModal').modal('hide');
  }

  // SCRIPT SEARCH

  $('#searchBar').attr('style','display:none !important');

  $(".search-white-right").click(function() {
    $('.navbar-title').hide();
    $('#searchBar').attr('style','display:block !important');
  });

  <?php
    if (isset($_GET['query'])){
      echo("
      $('.navbar-title').hide();
      $('#searchBar').attr('style','display:block !important');

      $('#query').val(localStorage.getItem('search_keyword'));
      $('#delete-query').removeClass('d-none');
      ");
    }
  ?>

  // FUNCTION SAVE SEARCH

  $('#query').on('change', function() {
    localStorage.setItem("search_keyword", this.value);
  });

  // FUNCTION X ON SEARCH

  $("#delete-query").click(function () {
    $('#query').val('');
    // localStorage.setItem("search_keyword", "");
    // $('#delete-query').addClass('d-none');
    window.location = 'tab5-following.php';
  })

  $('#query').keyup(function () {

    console.log('is typing: ' + $(this).val());

    if ($(this).val() != '') {
      $('#delete-query').removeClass('d-none');
    } else {
      $('#delete-query').addClass('d-none');
    }

  })

  // FUNCTION VOICE SEARCH

	function voiceSearch(){
		Android.toggleVoiceSearch();
	}

	function submitVoiceSearch(searchQuery){
		$('#query').val(searchQuery);
    $('#delete-query').removeClass('d-none');
	}

</script>
</html>