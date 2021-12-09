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
  <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
</head>

<body class="bg-white-background" style="display:none; background-color: #FFFFFF">

  <!-- NAVBAR -->

  <nav class="navbar navbar-light bg-purple">
    <div class="container">
      <a href="tab5-main.php">
        <img src="../assets/img/tab5/Back-(White).png" class="navbar-back-white">
      </a>
      <div class="navbar-brand pt-2 tab5-navbar">
        <img src="" class="store-icon">
        <!-- <span class="navbar-shop-text" data-translate="tab5shop-1">Shop</span> -->
      </div>
  </div>
  </nav>

  <!-- SECTION OPEN YOUR SHOP -->

  <div class="section-shop container text-center">
      <img width="300px" style="margin-top: -50px" src="../assets/img/tab5/new_open_shop.png">
      <h3 class="shop-title"><b data-translate="tab5shop-2">Open Your Shop on Q</b></h3>
      <div class="shop-sub-text small-text">
          <span class="text-purple" data-translate="tab5shop-3">Showcase Your Products</span>
          <p data-translate="tab5shop-4">Allow people to early shop with your branded photos and videos across Qiosk</p>
      </div>
      <div class="shop-sub-text small-text">
      <span class="text-purple" data-translate="tab5shop-6">Engage with Customers</span>
          <p data-translate="tab5shop-4">Allow people to early shop with your branded photos and videos across Qiosk</p>
      </div>
      <div class="shop-sub-text small-text">
      <span class="text-purple" data-translate="tab5shop-7">Easily Track Your Sales</span>
          <p data-translate="tab5shop-4">Allow people to early shop with your branded photos and videos across Qiosk</p>
      </div>
      <a href="tab5-open-shop.php"><button class="btn-get-started" data-translate="tab5shop-5">Get Started</button></a>
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

    if ((localStorage.lang) == 1){
      $('#shop_title').attr('placeholder', 'Nama Toko');
      $('#shop_desc').attr('placeholder', 'Deskripsi Toko');
    }

    $('body').show();
  });
  
</script>
</html>