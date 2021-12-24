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

  // GET SHOP CATEGORY

  $query = $dbconn->prepare("SELECT * FROM PRODUCT_CATEGORY ORDER BY ID DESC");
  $query->execute();
  $categoryData = $query->get_result();
  $query->close();

  // GET USER DATA

  $query = $dbconn->prepare("SELECT * FROM USER_LIST LEFT JOIN USER_LIST_EXTENDED ON USER_LIST.F_PIN =
                            USER_LIST_EXTENDED.F_PIN WHERE USER_LIST.F_PIN = '".$id_user."'");
  $query->execute();
  $user = $query->get_result()->fetch_assoc();
  $query->close();
  
  $arr = explode("@", $user['EMAIL'], 2);
  $username = $arr[0];

?>

<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />

  <title>Qiosk</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
  <link href="../assets/css/tab5-style.css" rel="stylesheet">
  <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
</head>
  
<style>

  .form-group{
    position: relative;
    /* margin-left:-10px; */
  }

  .form-control:focus {
    background-color: #FAFAFF;
    box-shadow: none;
  }

  .palceholder{
    position: absolute;
    color: #8a898b;
    display: none;
  }

  .star{
    color: red;
    padding-left: -10px;
    position: absolute;
  }

  .red-dot{
    color: red;
    position: absolute;
    margin-top:-10px;
    margin-left: 50px;
    font-size: 10px;
  }

  .red-dot-3{
    color: red;
    position: absolute;
    margin-top: -28px;
    margin-left: 55px;
    font-size: 10px;
  }

</style>

<body class="bg-white-background" style="display:none">

  <!-- NAVBAR -->

  <nav class="navbar navbar-light container">
    <div class="navbar-brand navbar-brand-slot">
      <a href="tab5-shop.php"><span class="small-text" data-translate="tab5openshop-1">Cancel</span></a>
    </div>
  </nav>

  <!-- SECTION OPEN YOUR SHOP TITLE AVA -->

  <form action="../logics/tab5/register_merchant" method="POST" enctype="multipart/form-data" style="margin-bottom: 100px">
    <div class="section-open-your-shop text-center">
      <span>
        <b data-translate="tab5openshop-2">Open Your Shop on Qiosk</b>
      </span>
      <p class="text-grey smallest-text" data-translate="tab5openshop-3">Learn More</p>
      <div class="image-upload">
        <label for="file-input">
          <img class="rounded-circle upload-image-shop m-auto" id="image-preview-1">
          <img src="../assets/img/tab5/Plus-(White).png" class="upload-image-shop-add">
        </label>
        <input id="file-input" type="file" onchange="loadFile(event)" name="file"/>
      </div>
      <small id="shop_thumbnail_err" class="smallest-text text-danger text-center" style="margin-top: -20px" data-translate="tab5openshop-10">
        Foto toko harus diisi.
      </small>
    </div>

    <!-- SECTION OPEN YOUR SHOP SHOP ACCOUNT DETAILS -->

    <div class="section-shop-account-details text-center container" style="margin-bottom:23px">
      <div class="smallest-text d-flex justify-content-start mb-3">
        <b data-translate="tab5openshop-4">ACCOUNT DETAILS</b>
      </div>

      <div class="form-group" style="margin-left:0px">
        <div class="palceholder" id="palce_name" style="font-size: 10px; margin-top:10px; margin-left: 5px">
          <label for="store_name" data-translate="tab5openshop-5">Store Name</label>
          <span class="star">*</span>
        </div>
        <input type="text" class="form-control open-shop-input" autocomplete="off" name="companyname" id="store_name" required>
      </div>

      <small id="store_name_err" class="smallest-text text-danger float-start mb-2" data-translate="tab5openshop-11">
        Store Name harus diisi.
      </small>

      <div class="form-group" style="margin-left:0px">
        <div class="palceholder" id="palce_phone" style="font-size: 10px; margin-top:10px; margin-left: 5px">
          <label for="phone_number" data-translate="tab5openshop-6">Phone Number</label>
          <span class="star">*</span>
        </div>  
        <input type="text" maxlength="14" oninput="this.value=this.value.replace(/[^0-9]/g,'');" class="form-control open-shop-input" autocomplete="off" name="phone_number" id="phone_number" required>
      </div>

      <small id="phone_number_err" class="smallest-text text-danger float-start mb-2" data-translate="tab5openshop-12">
        Phone Number harus diisi.
      </small>

      <div class="form-group" style="margin-left:0px">
        <div class="palceholder" id="palce_location" style="font-size: 10px; margin-top:10px; margin-left: 5px">
          <label for="location" data-translate="tab5openshop-7">Location</label>
          <span class="star">*</span>
        </div>
        <input type="text" class="form-control open-shop-input" autocomplete="off" name="location" id="location" required>
      </div>

      <small id="location_err" class="smallest-text text-danger float-start mb-2" data-translate="tab5openshop-13">
        Location harus diisi.
      </small>

      <input type="text" class="open-shop-input" name="shop_link" id="website" autocomplete="off" placeholder="Website (Not Required)">
      
      <div class="form-group" style="margin-left:0px">
        <div class="palceholder" id="palce_desc" style="font-size: 10px; margin-top:10px; margin-left: 5px">
          <label for="description" data-translate="tab5openshop-8">Description</label>
          <span class="star">*</span>
        </div>
        <textarea class="form-control open-shop-input-desc" name="description" id="description" rows="5" required></textarea>
      </div>

      <small id="description_err" class="smallest-text text-danger float-start mb-2" data-translate="tab5openshop-14">
        Description harus diisi.
      </small>

      <div class="form-group" style="margin-left:0px">
        <div class="dropdown" style="margin-top:-7px; margin-bottom: 5px;">
          <button class="dropdown-toggle text-grey" style="margin-left: -6px;" type="button" id="category-new-shop-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <span class="shop-category-select" style="float:left;" data-translate="tab5openshop-16">Category</span>
          </button>
          <ul class="dropdown-menu" aria-labelledby="dropdownMenuSelectCategory">

          <!-- LOOPING FROM CATEGORY TABLE -->

          <?php foreach($categoryData as $category): ?>
              
            <li>
              <a class="dropdown-item" data-id="<?= $category['ID'] ?>">
                <?= $category["NAME"] ?>
              </a>
            </li>
          
          <?php endforeach; ?>

          <input type="hidden" id="category" class="category" name="category" value="">

          </ul>
        </div>
        <div class="red-dot-3">*</div>
        <img src="../assets/img/tab5/Down-arrow-2.png" class="down-arrow-upload-listing">
      </div>

      <small id="category_err" class="smallest-text text-danger float-start mb-2" data-translate="tab5openshop-15">
        Category harus diisi.
      </small>
      
      <input type="hidden" id="email" name="email" value="<?= $user['EMAIL'] ?>">
      <input type="hidden" id="pwd" name="pwd" value="<?= $user['PASSWORD'] ?>">
      <input type="hidden" id="pwdcheck" name="pwdcheck" value="<?= $user['PASSWORD'] ?>">
      <input type="hidden" id="created_by" name="created_by" value="<?= $_SESSION["user_f_pin"] ?>">
      <input type="hidden" id="sent_time" name="sent_time" value="<?= time()*1000 ?>">
      <input type="hidden" id="is_artist" name="is_artist" value="0">
      <input type="hidden" id="country_code" name="country_code" value="ID">
      <input type="hidden" id="sign_up" name="sign_up" value="0">

      <?php 
      
        $bytes = random_bytes(8);
        $hexbytes = strtoupper(bin2hex($bytes));
        $order_number = substr($hexbytes, 0, 15);

      ?>

      <input type="hidden" id="hex" name="hex" value="<?= $order_number ?>">
      <input type="hidden" id="username" name="username" value="<?= $username ?>">

      <button class="btn-next-shop mx-auto" style="margin-top: 25px;"; type="button" onclick="checkForm()" data-translate="tab5openshop-9">Next</button>

    </div>
  </form>
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

    if (localStorage.lang == 1){
      $('#website').attr('placeholder','Website (Jika Ada)');
    }

    $('body').show();
	});

  // CHANGE IMAGE AS CHOOSE

  var loadFile = function(event){
    var reader = new FileReader();
    reader.onload = function(){
      var output = document.getElementById('image-preview-1');
      output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
  };

  $('#store_name_err').hide();
  $('#phone_number_err').hide();
  $('#location_err').hide();
  $('#description_err').hide();
  $('#shop_thumbnail_err').hide();
  $('#category_err').hide();

  // FORM VALIDATION CHECK

  function checkForm(){

    var check = 0;

    $('#store_name_err').hide();
    $('#phone_number_err').hide();
    $('#location_err').hide();
    $('#description_err').hide();
    $('#shop_thumbnail_err').hide();

    if($('#store_name').val()==""){
      $('#store_name_err').show();
      $('#palce_phone').css('margin-top','30px');
    }else{
      check += 1;
    }

    if ($('#phone_number').val()==""){
      $('#phone_number_err').show();
      $('#palce_location').css('margin-top','30px');
    }else{
      check += 1;
    }

    if ($('#location').val()==""){
      $('#location_err').show();
    }else{
      check += 1;
    }

    if ($('#description').val()==""){
      $('#description_err').show();
    }else{
      check += 1;
    }

    if ($('#category').val()==""){
      $('#category_err').show();
    }else{
      check += 1;
    }

    if ($('#file-input').get(0).files.length === 0){
      $('#shop_thumbnail_err').show();
    }else{
      check += 1;
    }

    // IF ALL FORM ALREADY FILLED = 6

    if (check == 6){
      $('.btn-next-shop ').removeAttr("type").attr("type", "submit");
    }
  }

  // GET USER DATA FROM ANDROID

  // function getEmail(email){
  //   $('#email').val(email);

  //   var username = email.substring(0, email.lastIndexOf("@"));
  //   $('#username').val(username);
  //   console.log(username);
  // }

  // function getPwd(pwd){
  //   $('#pwd').val(pwd);
  //   $('#pwdcheck').val(pwd);
  // }

  // SCRIPT RED DOT INPUT

  $('.palceholder').click(function(){
    $(this).siblings('textarea').focus();
  });

  $('.form-control').focus(function(){
    $(this).siblings('.palceholder').hide();
  });

  $('.form-control').blur(function(){

    var $this = $(this);
    
    if ($this.val().length == 0)
      $(this).siblings('.palceholder').show();
    });
    
  $('.form-control').blur();

  // CHANGE DROPDOWN AS NAME AS CLICK

  $('.dropdown-item').click(function(){
    $('.shop-category-select').text($(this).text());
    $('.red-dot-3').hide();
    $('#category').val($(this).data('id'));
  });

</script>
</html>