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
      <p class="navbar-title" data-translate="tab5wishlist-1">Wishlist</p>
      <div id="searchBar" class="col-9 col-md-9 col-lg-9 d-flex align-items-center justify-content-center text-white pl-2 pr-2">
        <form id="searchFilterForm-a" action="tab5-wishlist" method="GET" style="width: 95%;">

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

  <!-- SECTION WISHLIST -->

  <div class="white-space"></div>

  <div class="section-wishlist">
    <div id="wishlist-products" class="row gx-0 text-center"></div>
  </div>

</body>

<!-- FOOTER -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="module" src="../assets/js/translate.js"></script>

<script>

	//  SCRIPT CHANGE LANGUAGE

	$(document).ready(function(){
		function changeLanguage(){

		var lang = localStorage.lang;	
		change_lang(lang);
		
		}

		changeLanguage();

    if(localStorage.lang == 1){
      $('#query').attr('placeholder','Pencarian...');
    }

    $('body').show();
  });

  // SCRIPT SEARCH

  $('#searchBar').attr('style','display:none !important');

  $(".search-white-right").click(function(){
    $('.navbar-title').hide();

    $('#searchBar').attr('style','display:block !important');
  });

  // FUNCTION SAVE SEARCH

  $('#query').on('change', function(){
    localStorage.setItem("search_keyword", this.value);
  });

  // FUNCTION X ON SEARCH

  $("#delete-query").click(function (){
    $('#query').val('');
    // localStorage.setItem("search_keyword", "");
    // $('#delete-query').addClass('d-none');
    window.location = 'tab5-wishlist.php';
  })

  $('#query').keyup(function (){
    console.log('is typing: ' + $(this).val());

    if ($(this).val() != '') {
      $('#delete-query').removeClass('d-none');
    } else {
      $('#delete-query').addClass('d-none');
    }
  })

  // LOAD WISHLIST

  document.addEventListener("DOMContentLoaded", function (event){
    // Your code to run since DOM is loaded and ready

    <?php

      if (isset($_GET['query'])){
        echo "
        $('.navbar-title').hide();
        $('#searchBar').attr('style','display:block !important');

        $('#query').val(localStorage.getItem('search_keyword'));
        $('#delete-query').removeClass('d-none');

        getWishlistQuery('".$_GET['query']."');
        ";
      }else{
        echo "
        getWishlist();
        ";
      }

    ?>

    // getWishlist();

  });

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