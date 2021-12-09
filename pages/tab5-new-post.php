<?php

	// KONEKSI

	include_once($_SERVER['DOCUMENT_ROOT'] . '/qiosk_web/logics/chat_dbconn.php');
	$dbconn = paliolite();
	session_start();

  // ID SHOP GET

  if (!isset($_SESSION['id_shop'])){
    $id_shop = $_GET['id'];
    $_SESSION['id_shop'] = $id_shop;
  }else{
    $id_shop = $_SESSION["id_shop"];
  }

  // CHECK SHOP ID

  if (!isset($id_shop)) {
    die("ID Shop Tidak Diset.");
  }

	// SELECT SHOP DATA ACTIVE

	$query = $dbconn->prepare("SELECT * FROM PRODUCT WHERE SHOP_CODE = '$id_shop' AND IS_SHOW = 1 
                            AND IS_DELETED = 0 ORDER BY CREATED_DATE DESC");
	$query->execute();
	$tagged_product = $query->get_result();
	$query->close();

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

  <style>

    /* FOR MODAL CAN BE SLIDER DOWN */

    body.modal-open {
      position: inherit;
    }

    /* FOR FULL WIDTH MODAL */

    .modal-dialog {
      max-width: 100%;
      margin: 0;
      bottom: 0;
      left: 0;
      right: 0;
      height: 100vh;
      display: flex;
      margin-top: 10px;
    }

    .modal-body{
      margin-top: -15px;
      padding-left: 0 !important;
      padding-right: 0 !important;
    }

  </style>
</head>
  
<body class="bg-white-background" style="display:none">
  <div id="header"></div>

  <!-- NAVBAR -->

  <form action="tab5-preview-new-post" method="POST">
    <nav class="navbar navbar-light navbar-shop-manager">
      <div class="container">
        <a href="tab5-shop-manager.php">
          <img src="../assets/img/tab5/Back-(Black).png" class="navbar-back-black">
        </a>
        <p class="navbar-title-2" data-translate="tab5newpost-1">New Post</p>
        <div class="navbar-brand pt-2 navbar-brand-slot">
            <button class="text-purple navbar-new-post-next" data-translate="tab5newpost-2">Next</button>
        </div>
      </div>
    </nav>

    <!-- SECTION NEW POST IMAGE -->

    <div class="section-new-post">
      <div class="row gx-0">
        <img src="../assets/img/tab5/Shop Manager/noimage-large.jpg" id="image-preview" class="new-post-image">
      </div>  
    </div>

    <!-- SECTION NEW POST FORM -->

    <div class="section-post-description">
      <div class="row gx-0 add-post">
        <textarea class="add-post-input" id="caption" required rows="5" name="caption" placeholder="Write a Caption..."></textarea>
      </div>
      <div class="row gx-0 add-post">
        <div class="col-1 col-md-1 col-lg-1">
          <img src="../assets/img/tab5/Tag.png" class="tag-icon">
        </div>
        <div class="col-11 col-md-11 col-lg-11">
          <input type="text" class="add-post-input tagged-input" id="tagged-text" name="tagged_input" readonly placeholder="Tagged Products" onclick="showModal()">
        </div>
      </div>
      <div class="row gx-0 add-post">
        <input type="text" class="add-post-input" id="hashtag" required name="hashtag" placeholder="#Hashtag1 #Hashtag2">
      </div>
      <div class="row gx-0 add-post-last">
        <input type="text" class="add-post-input" id="location" required name="location" placeholder="Add Location">
      </div>
    </div>

    <input type="hidden" class="tagged_product" id="tagged_product" name="tagged_product" value="">
    <input type="hidden" class="post_photo" name="post_photo" value="">

    <!-- Modal -->

    <div class="modal fade" id="taggedProductModal" tabindex="-1" aria-labelledby="taggedProductModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" style="align-items: flex-end;">
        <div class="modal-content">
          <div class="mod-header">
            <div class="row d-flex justify-content-center">
              <hr class="shop-modal-line">
            </div>
          </div>
          <div class="modal-body modal-add-tag">
            <div class="row new-post-search-slot"  style="width: 94%">
              <div class="col-9 col-md-9 col-lg-9">
                  <input type="text" id="search-tagged" class="tag-product-input shadow-sm" placeholder="Search">
                  <img class="d-none" id="delete-query" src="../assets/img/icons/X-fill.png" style=" width: 20px; height: 20px; margin-left: 50%; margin-top: -24px; position: absolute">
                  <img src="../assets/img/tab5/Voice-Command.png" onclick="voiceSearch()" class="new-post-voice">
                </div>
              <div class="col-3 col-md-3 col-lg-3 d-flex justify-content-end">
                <!-- <img src="../assets/img/tab5/Search-(Purple).png" class="new-post-search"> -->
                <button type="button" class="btn-submit-tagged-product" data-bs-dismiss="modal"><b data-translate="tab5newpost-3">SUBMIT</b></button>
              </div>

              <div class="row row-tagged gx-0">

                <?php foreach ($tagged_product as $tagged): ?>

                  <div class="col-6 col-md-6 col-lg-6 gx-0 shadow-sm col-tagged-product">
                    <div class="single-tagged-product">
                      <label for="myCheck<?= $tagged['CODE'] ?>">
                        <div class="form-check form-check-tagged" style="padding-left: 0 !important">
                          <input class="form-check-input check-form-input check-form-input" type="checkbox" data-code-tagged="<?= $tagged['CODE'] ?>" value="<?= $tagged['NAME'] ?>" id="myCheck<?= $tagged['CODE'] ?>">
                          
                          <?php $product_image = explode('|', $tagged['THUMB_ID']); ?>

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
                              <img src="<?= $product_image_video ?>" class="tagged-image">
                            <?php else: ?>
                              <video src="<?= $product_image[0] ?>#t=0.5" style="object-fit: none" type="video/mp4" class="tagged-image"></video>
                            <?php endif; ?>

                          <?php else: ?>

                            <img src="<?= $product_image[$i] ?>" class="tagged-image">

                          <?php endif; ?>
                          
                          <img src="../assets/img/tab5/Settings-(White).png" class="tagged-product-settings" id="dropdownMenuSelectLanguage" data-bs-toggle="dropdown" aria-expanded="false">

                          <ul class="dropdown-menu" style="min-width: auto !important; position: absolute" aria-labelledby="dropdownMenuLanguage">
                            
                            <li><a href="tab5-edit-listing.php?id=<?= $tagged['CODE'] ?>" class="dropdown-item" data-translate="tab5newpost-5">Edit</a></li>
                            <form action="../logics/tab5/delete_listing.php?id=<?= $tagged['CODE'] ?>" method="POST">  
                              <li><button type="submit" style="color:brown" class="dropdown-item" data-translate="tab5newpost-6">Delete</button></li>
                            </form>
                          
                          </ul>

                          <div class="row tagged-product-desc">
                            <span class="small-text tagged-product-title"><b><?= $tagged['NAME'] ?></b></span>
                            <div class="row tagged-product-desc-row">
                              <div class="col-6 col-md-6 col-lg-6 gx-0 d-flex justify-content-start">
                                <span class="smallest-text text-grey"><?= $tagged['QUANTITY'] ?> <span data-translate="tab5newpost-4"> in stock</span></span>
                              </div>
                              <div class="col-6 col-md-6 col-lg-6 gx-0 d-flex justify-content-end">
                                <span class="smallest-text text-grey">Rp <?= number_format($tagged['PRICE'],0,",",",") ?></span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </label>
                    </div>
                  </div>

                  <?php endforeach; ?>

                </div>
                <div class="col-tagged-product-no-result">
                  <p class="text-center small-text" style="margin-left: -30px" data-translate="tab5newpost-7">Produk tidak ditemukan.</p>
                </div>
              </div> 
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>

  <input type="hidden" id="shop_id" value="<?= $id_shop ?>">

</body>

<!-- FOOTER -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.13.0-rc.2/jquery-ui.js" integrity="sha256-bLjSmbMs5XYwqLIj5ppZFblCo0/9jfdiG/WjPhg52/M=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
<script type="module" src="../assets/js/translate.js"></script>

<script>

	// SCRIPT CHANGE LANGUAGE

	$(document).ready(function(){
		function changeLanguage(){

		var lang = localStorage.lang;	
		change_lang(lang);
		
		}

    if (localStorage.lang == 1){
      $('.navbar-title-2').css('margin-left','30px');

      $('#caption').attr('placeholder','Tulis Caption...');
      $('#location').attr('placeholder','Tambahkan Lokasi');
      $('#tagged-text').attr('placeholder','Kaitkan Produk');
      $('#search-tagged').attr('placeholder','Pencarian');
    }

		changeLanguage();
    $('body').show();
	});

  // SCRIPT SLIDER TAGGED PRODUCT

  const initPos = parseInt($('#header').offset().top + $('#header').outerHeight(true)) + "px";
  const fixedPos = JSON.parse(JSON.stringify(initPos));
  let init = parseInt(fixedPos.replace('px', ''));

  $('[data-bs-target="#taggedProductModal"]').click(function (){
    $('#taggedProductModal .modal-dialog').css('top', fixedPos);
    $('#taggedProductModal .modal-dialog').css('height', window.innerHeight - fixedPos);
  })

  $('#taggedProductModal').draggable({
    handle: ".mod-header",
    axis: "y",
    drag: function (event, ui){

      console.log('init: ' + init);
      if (ui.position.top < init){
          ui.position.top = init;
      }

      let dialog = ui.position.top + window.innerHeight;
      if (dialog - window.innerHeight > 50){
          $('#taggedProductModal').modal('hide');
      }
    }
  });
  
  function showModal(){
    $('body').css('height', '900px');
    window.scrollTo(0, document.body.scrollHeight - (document.body.scrollHeight - 1));
    $('#taggedProductModal').modal('show');
    $('.modal').css('overflow', 'hidden');
    $('.modal').css('overscroll-behavior-y', 'contain');
    $('.modal-dialog').css('margin-top', '50px');
    $('.modal-dialog').css('padding-bottom', '50px');
  }

  $('#taggedProductModal').on('hidden.bs.modal', function (){
    $('.modal').css('overflow', 'auto');
    $('.modal').css('overscroll-behavior-y', 'auto');
    $('.modal').css('top', '0px');
    $('.modal').css('left', '0px');
    $('body').css('height', '100%');
    $('.modal-dialog').css('margin-top', '-50px');
    $('.modal-dialog').css('padding-bottom', '-50px');
  })

  // CHECKBOX TAGGED PRODUCT

  $(document).on("change",":checkbox", function(e){

    var array_tagged = $('.tagged_product').val();
    var tagged_name = $('.tagged-input').val();

    if (this.checked){
        $('.tagged_product').val($(this).val());
        $('.tagged-input').val($(this).val());

        if (array_tagged.length>0){
          array_tagged = array_tagged + "|" + $(this).data("code-tagged");
          tagged_name = tagged_name + ", " + $(this).val();
        }else{
          array_tagged = $(this).data("code-tagged");
          tagged_name = $(this).val();
        }

        $('.tagged_product').val(array_tagged);
        $('.tagged-input').val(tagged_name);

        console.log(array_tagged);

        // SAVE TO LOCAL STORAGE

        localStorage.setItem('tagged_post', array_tagged);
        localStorage.setItem('tagged_post_name', $('.tagged-input').val());

    }else{

      var array_tagged = $('.tagged_product').val();
      var tagged_name = $('.tagged-input').val();

      if (array_tagged.includes("|")){

        var a = array_tagged.replace("|"+$(this).data("code-tagged"),"");
        var b = tagged_name.replace(", "+$(this).val(),"");
       
        var c = a.replace($(this).data("code-tagged")+"|","");
        var d = b.replace($(this).val()+", ","");

        $(".tagged_product").val(c);   
        $(".tagged-input").val(d);   

      }else{
        array_tagged = array_tagged.replace($(this).data("code-tagged"),"");
        tagged_name = tagged_name.replace($('.tagged-input').val(),"");

        $('.tagged_product').val(array_tagged);
        $('.tagged-input').val(tagged_name);
      }

      var array_tagged = $('.tagged_product').val();
      var tagged_name = $('.tagged-input').val();

      $('.tagged_product').val(array_tagged);
      $('.tagged-input').val(tagged_name);

      console.log(array_tagged);

      // SAVE TO LOCAL STORAGE

      localStorage.setItem('tagged_post', array_tagged);
      localStorage.setItem('tagged_post_name', $('.tagged-input').val());
      
    }
  });

  // SCRIPT SET IMAGE FROM ANDROID/IOS

  function setImage(link){

    if (window.Android){
      link = Android.getDataImage();
    }

    var strings = link.split("/");

    if (strings[0] == 'data:video'){
      $('#image-preview').replaceWith('<video src="'+link+'#t=0.5" id="video-preview" autoplay muted style="object-fit: none" type="video/mp4" class="new-post-image">');
      
      // IF DIV ALREADY VIDEO REPLACE AGAIN (CONFLICT : NO IMG REPLACED WITH VIDEO)

      $('#video-preview').replaceWith('<video src="'+link+'#t=0.5" id="video-preview'+'" autoplay muted  style="object-fit: none" type="video/mp4" class="new-post-image">');
    }else{
      var output = document.getElementById('image-preview');
      output.src = link;
    }

    // SAVE TO LOCAL STORAGE
    
    localStorage.setItem('link',link);
    localStorage.setItem('media_post', link);
  }

  // FOR INPUT INSIDE DRAGGABLE CONTENT FOCUSED

  $(':input').bind('click', function(){
    $(this).focus();
  });

  // PREVENT INPUT WITH ENTER (DISABLE POPUP REQUIRED)

  $(':input').on('keypress', function(e){
    return e.which !== 13;
  });

  // SEARCH TAGGED PRODUCT

  $('.col-tagged-product-no-result').hide();

  $('#search-tagged').keypress(function (e){

    if (e.which == 13){

    var query = $('#search-tagged').val();
    var shop_id = $('#shop_id').val();

      if (query!=""){

        $.ajax({
        type: "POST",
        url: '../logics/tab5/search_tagged',
        data: {
          "query" : query,
          "shop_id" : shop_id
        },
        success: function(response){

          var objResult = JSON.parse(response);
          console.log(response);

          // IF THERE IS DATA (RESPONSE STRING MORE THAN 3)

          if (response.length>3){

            $('.row-tagged').html(""); 
            $('.col-tagged-product-no-result').hide();

            Object.keys(objResult).forEach(function (item){

              var product_image = objResult[item]['THUMB_ID'].split("|");
              var i = 0;

              // IF THUMBNAIL WAS VIDEOS CHANGE IT TO NEXT ARRAY TO IMAGE

              if ((product_image[i]).slice(-3) == 'mp4'){

                while (i < product_image.length){

                  // WHEN GET PICTURES ON CENTER [ EX 1,3 VIDEO, 2 PICTURE, BREAK AT 2 ]

                  if ((product_image[i]).slice(-3) == 'mp4'){
                    var product_image_video = product_image[(i+1)];
                  }else{
                    break;
                  }
                  
                  i++;
                }

                // IF GET PICTURE TAG = IMG

                if (product_image_video){
                  var product_image_video = product_image_video;
                  ext = "img style='object-fit:cover'";
                  ext2 = "";

                // IF THERE IS NO PICTURE ON PRODUCT TAG = VIDEO
                  
                }else{
                  var product_image_video = product_image[0]+"#t=0.5";
                  ext = "video style='object-fit:none'";
                  ext2 = "</video>";
                }

              }else{
                var product_image_video = product_image[i];
                ext = "img style='object-fit:cover'";
                ext2 = "";
              }

              var array_tagged = $('.tagged_product').val();

              // IF CODE WAS ALREADY CHECKED BEFORE

              if (array_tagged.includes(objResult[item]['CODE'])){
                $checked = "checked";
              }else{
                $checked = "";
              }

              // APPEND NEW LIST FROM QUERY

              var new_tagged = 
              `<div class="col-6 col-md-6 col-lg-6 gx-0 shadow-sm col-tagged-product">
                <div class="single-tagged-product">
                  <label for="myCheck`+objResult[item]['ID']+`">
                    <div class="form-check form-check-tagged" style="padding-left: 0 !important">
                      <input class="form-check-input check-form-input check-form-input" `+$checked+` type="checkbox" data-code-tagged="`+objResult[item]['CODE']+`" value="`+objResult[item]['NAME']+`" id="myCheck`+objResult[item]['ID']+`">

                      <`+ext+` src="`+product_image_video+`" class="tagged-image">`+ext2+`
                      
                      <img src="../assets/img/tab5/Settings-(White).png" class="tagged-product-settings" id="dropdownMenuSelectLanguage" data-bs-toggle="dropdown" aria-expanded="false">
                      <ul class="dropdown-menu" style="min-width: auto !important; position: absolute" aria-labelledby="dropdownMenuLanguage">
                        
                        <li><a href="tab5-edit-listing.php?id=`+objResult[item]['CODE']+`" class="dropdown-item" data-translate="tab5newpost-5">Edit</a></li>
                        <form action="../logics/tab5/delete_listing.php?id=`+objResult[item]['CODE']+`" method="POST">  
                          <li><button type="submit" style="color:brown" class="dropdown-item" data-translate="tab5newpost-6">Delete</button></li>
                        </form>
                      
                      </ul>
                      <div class="row tagged-product-desc">
                        <span class="small-text tagged-product-title"><b>`+objResult[item]['NAME']+`</b></span>
                        <div class="row tagged-product-desc-row">
                          <div class="col-6 col-md-6 col-lg-6 gx-0 d-flex justify-content-start">
                            <span class="smallest-text text-grey">`+objResult[item]['QUANTITY']+` <span data-translate="tab5newpost-4"> in stock</span></span>
                          </div>
                          <div class="col-6 col-md-6 col-lg-6 gx-0 d-flex justify-content-end">
                            <span class="smallest-text text-grey">Rp `+objResult[item]['PRICE'].toLocaleString()+`</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </label>
                </div>
              </div>`;
            
              $('.row-tagged').append(new_tagged);

            });

          }else{
            $('.col-tagged-product').html("");
            $('.col-tagged-product-no-result').show();
          }
        }
      });
    }
  }
});

  // FUNCTION X ON SEARCH

  $("#delete-query").click(function () {

    $('#search-tagged').val('');
    $('#delete-query').addClass('d-none');

    // SELECT ALL DATA FROM DATABASE

    var shop_id = $('#shop_id').val();

    $.ajax({
    type: "POST",
    url: '../logics/tab5/all_product',
    data: {
      "shop_id" : shop_id
    },
    success: function(response){

      console.log(response);
      var objResult = JSON.parse(response);

      $('.row-tagged').html(""); 
      $('.col-tagged-product-no-result').hide();

      Object.keys(objResult).forEach(function (item) {

        var product_image = objResult[item]['THUMB_ID'].split("|");
        var i = 0;

        // IF THUMBNAIL WAS VIDEOS CHANGE IT TO NEXT ARRAY TO IMAGE

        if ((product_image[i]).slice(-3) == 'mp4'){

          while (i < product_image.length){

            // WHEN GET PICTURES ON CENTER [ EX 1,3 VIDEO, 2 PICTURE, BREAK AT 2 ]

            if ((product_image[i]).slice(-3) == 'mp4'){
              var product_image_video = product_image[(i+1)];
            }else{
              break;
            }
            
            i++;
          }

          // IF GET PICTURE TAG = IMG

          if (product_image_video){
            var product_image_video = product_image_video;
            ext = "img style='object-fit:cover'";
            ext2 = "";

          // IF THERE IS NO PICTURE ON PRODUCT TAG = VIDEO
            
          }else{
            var product_image_video = product_image[0]+"#t=0.5";
            ext = "video style='object-fit:none'";
            ext2 = "</video>";
          }

        }else{
          var product_image_video = product_image[i];
          ext = "img style='object-fit:cover'";
          ext2 = "";
        }

        var array_tagged = $('.tagged_product').val();

        if (array_tagged.includes(objResult[item]['CODE'])){
          $checked = "checked";
        }else{
          $checked = "";
        }

        var new_tagged = 
        `<div class="col-6 col-md-6 col-lg-6 gx-0 shadow-sm col-tagged-product">
          <div class="single-tagged-product">
            <label for="myCheck`+objResult[item]['ID']+`">
              <div class="form-check form-check-tagged" style="padding-left: 0 !important">
                <input class="form-check-input check-form-input check-form-input" type="checkbox" `+$checked+` data-code-tagged="`+objResult[item]['CODE']+`" value="`+objResult[item]['NAME']+`" id="myCheck`+objResult[item]['ID']+`">

                <`+ext+` src="`+product_image_video+`" class="tagged-image">`+ext2+`
                
                <img src="../assets/img/tab5/Settings-(White).png" class="tagged-product-settings" id="dropdownMenuSelectLanguage" data-bs-toggle="dropdown" aria-expanded="false">
                <ul class="dropdown-menu" style="min-width: auto !important; position: absolute" aria-labelledby="dropdownMenuLanguage">
                  
                  <li><a href="tab5-edit-listing.php?id=`+objResult[item]['CODE']+`" class="dropdown-item" data-translate="tab5newpost-5">Edit</a></li>
                  <form action="../logics/tab5/delete_listing.php?id=`+objResult[item]['CODE']+`" method="POST">  
                  <li><button type="submit" style="color:brown" class="dropdown-item" data-translate="tab5newpost-6">Delete</button></li>
                  </form>
                
                </ul>
                <div class="row tagged-product-desc">
                  <span class="small-text tagged-product-title"><b>`+objResult[item]['NAME']+`</b></span>
                  <div class="row tagged-product-desc-row">
                    <div class="col-6 col-md-6 col-lg-6 gx-0 d-flex justify-content-start">
                      <span class="smallest-text text-grey">`+objResult[item]['QUANTITY']+` <span data-translate="tab5newpost-4"> in stock</span></span>
                    </div>
                    <div class="col-6 col-md-6 col-lg-6 gx-0 d-flex justify-content-end">
                      <span class="smallest-text text-grey">Rp `+objResult[item]['PRICE'].toLocaleString()+`</span>
                    </div>
                  </div>
                </div>
              </div>
            </label>
          </div>
        </div>`;

        $('.row-tagged').append(new_tagged);

        console.log(objResult[item]['NAME']);
      });
    }
  });
})

// APPEAR X WHILE STRING ON SEARCH

$('#search-tagged').keyup(function () {

  console.log('is typing: ' + $(this).val());
  
  if ($(this).val() != '') {
    $('#delete-query').removeClass('d-none');
  } else {
    $('#delete-query').addClass('d-none');
  }

})

// SAVE DATA FOR REFRESH

$("#caption").blur(function() {
  var caption = $(this).val();
  localStorage.setItem('caption_post', caption);
})

$("#hashtag").blur(function() {
  var hashtag = $(this).val();
  localStorage.setItem('hashtag_post', hashtag);
})

$("#location").blur(function() {
  var location = $(this).val();
  localStorage.setItem('location_post', location);
})

var get_caption = localStorage.getItem('caption_post');
var get_hashtag = localStorage.getItem('hashtag_post');
var get_location = localStorage.getItem('location_post');
var get_tagged = localStorage.getItem('tagged_post');
var get_tagged_name = localStorage.getItem('tagged_post_name');
var get_media = localStorage.getItem('media_post');

if (get_caption != null){
  $('#caption').siblings('.palceholder').hide();
  $("#caption").val(get_caption);
}

if (get_hashtag != null){
  $('#hashtag').siblings('.palceholder').hide();
  $("#hashtag").val(get_hashtag);
}

if (get_location != null){
  $('#location').siblings('.palceholder').hide();
  $("#location").val(get_location);
}

if (get_tagged != null){
  $("#tagged_product").val(get_tagged);

  var split = get_tagged.split("|");

  // UNCHECK ALL AND CHECK BASED ARRAY BEFORE REFRESH 

  $('.check-form-input').prop('checked', false);

  for(var i=0; i<split.length; i++){
    $('#myCheck'+split[i]).prop('checked', true);
  }
}

if (get_tagged_name != null){
  $('#tagged-text').siblings('.palceholder').hide();
  $("#tagged-text").val(get_tagged_name);
}

if (get_media != null){
  setImage(get_media);
}

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