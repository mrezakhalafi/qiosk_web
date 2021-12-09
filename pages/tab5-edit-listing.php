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

    // GET SHOP CATEGORY

    $query = $dbconn->prepare("SELECT * FROM PRODUCT_CATEGORY ORDER BY ID DESC");
	$query->execute();
	$categoryData = $query->get_result();
	$query->close();

     // GET LISTING DATA

     $product_code = $_GET['id'];

     $query = $dbconn->prepare("SELECT PRODUCT.*, PRODUCT_SHIPMENT_DETAIL.WEIGHT, 
                                PRODUCT_CATEGORY.NAME AS CTG_NAME FROM PRODUCT LEFT JOIN 
                                PRODUCT_SHIPMENT_DETAIL ON PRODUCT.CODE = 
                                PRODUCT_SHIPMENT_DETAIL.PRODUCT_CODE LEFT JOIN 
                                PRODUCT_CATEGORY ON PRODUCT.CATEGORY = PRODUCT_CATEGORY.ID 
                                WHERE CODE = '$product_code'");
     $query->execute();
     $dataProduct = $query->get_result()->fetch_assoc();
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

        /* FOR RED DOT FORM */
        
        .form-group{
            position: relative;
            margin-left:-10px;
        }

        .form-control:focus {
            background-color: #FAFAFF;
            box-shadow: none;
        }

        .palceholder{
            position: absolute;
            color: #797979;
            display: none;
        }

        .star{
            color: red;
            padding-left: -5px;
            position: absolute;
        }

        .star-2{
            color: red;
            margin-left: -2px !important;
        }

        .red-dot{
            color: red;
            position: absolute;
            margin-top: -24px;
            margin-left: 49px;
            font-size: 10px;
        }

        .red-dot-2{
            color: red;
            position: absolute;
            margin-top: -22px;
            margin-left: 48px;
            font-size: 10px;
        }

        .red-dot-3{
            color: red;
            position: absolute;
            margin-top: -28px;
            margin-left: 47px;
            font-size: 10px;
        }

    </style>
</head>

<body class="bg-white-background" style="display:none">

    <!-- NAVBAR -->

    <nav class="navbar navbar-light navbar-shop-manager">
        <div class="container">
            <a href="tab5-listing.php">
                <span class="small-text" data-translate="tab5uploadlisting-1">Cancel</span>
            </a>
            <p class="navbar-title-2" data-translate="tab5uploadlisting-13">Edit Listing</p>
            <div class="navbar-brand pt-2 navbar-brand-slot">
                <img class="navbar-img-slot">
            </div>
        </div>
    </nav>

    <!-- SECTION UPLOAD LISTING PHOTO -->

    <form action="../logics/tab5/update_listing" method="POST" enctype="multipart/form-data">
        <div class="section-upload-listing-photo">
            <div class="row container gx-0 product-photo-title">
                <p class="small-text" data-translate="tab5uploadlisting-3"><b>Product Photo</b></p>
            </div>
            <div class="row small-text gx-0" style="background-color: #FAFAFF;">
                <div class="container">
                    <ul class="nav nav-tabs horizontal-slide gx-0">

                        <!-- LOOPING 10 SLOT FOR MEDIA -->
        
                        <?php for ($i=0; $i<=10; $i++){ ?>

                            <?php $product_image = explode('|', $dataProduct['THUMB_ID']); ?>

                            <!-- IF PRODUCT EXIST SHOW PRODUCT -->
                            
                            <?php if (count($product_image) > $i ): ?>

                                <li class="nav-item">
                                    <div class="upload-listing-image-slot d-flex justify-content-center">
                                        <div class="single-upload-cover-listing">
                                            <div class="image-upload">
                                                <label for="file-input-<?= $i ?>" class="row" style="--bs-gutter-x: none">
                                                    <img src="../assets/img/tab5/delete-listing-2.png" data-product-img="<?= $product_image[$i] ?>" class="delete-listing-img" style="z-index: 9999">
                                                        
                                                    <!-- CHECK IF FILE IS VIDEO OR IMAGES -->

                                                    <?php if (substr($product_image[$i], -3) == "mp4"): ?>
                                                        <video src="<?= $product_image[$i] ?>#t=0.5" loop autoplay muted id="image-preview-<?= $i ?>" class="upload-listing-border" style="object-fit: none">                                                
                                                    <?php else: ?>
                                                        <img src="<?= $product_image[$i] ?>" id="image-preview-<?= $i ?>" class="upload-listing-border">
                                                    <?php endif; ?>

                                                </label>
                                                <input id="file-input-<?= $i ?>" type="file" name="listing_thumbnail-<?= $i ?>" onchange="loadFile(event, <?= $i ?>)" />
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            
                            <?php else: ?>

                            <li class="nav-item">
                                <div class="upload-listing-image-slot d-flex justify-content-center">
                                    <div class="single-upload-cover-listing">
                                        <div class="image-upload">
                                            <label for="file-input-<?= $i ?>" class="row" style="--bs-gutter-x: none">
                                            <img src="../assets/img/tab5/delete-listing-2.png" data-delete-slot="<?= $i ?>" class="delete-listing-img" id="delete-listing-<?= $i ?>" style="z-index: 9999">
                                                <img src="../assets/img/tab5/Dashed-Image.png" id="image-preview-<?= $i ?>" class="upload-listing-border">
                                                <img src="../assets/img/tab5/Add-(Grey).png" class="upload-listing-add" id="upload-listing-add-<?= $i ?>">
                                            </label>
                                            <input id="file-input-<?= $i ?>" type="file" name="listing_thumbnail-<?= $i ?>" onchange="loadFile(event, <?= $i ?>)" />
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <?php endif; ?>

                        <?php } ?>

                    </ul>
                </div>
            </div>
        </div>

        <!-- SECTION UPLOAD LISTING DESC -->

        <div class="section-upload-listing-desc">
            <div class="row gx-0 upload-listing">
                <div class="form-group">
                    <div class="palceholder live-stream-title-input">
                        <label for="title" data-translate="tab5uploadlisting-4">Product Title</label>
                        <span class="star">*</span>
                    </div>
                    <input type="text" id="title" autocomplete="off" name="product_title" class="form-control upload-listing-input" required value="<?= $dataProduct['NAME'] ?>">
                </div>
            </div>
            <div class="row gx-0 upload-listing">
                <div class="form-group">
                    <div class="palceholder live-stream-title-input">
                        <label for="desc" data-translate="tab5uploadlisting-5">Product Description</label>
                        <span class="star">*</span>
                    </div>
                    <textarea class="upload-listing-input form-control" name="product_description" id="desc" rows="5" required><?= $dataProduct['DESCRIPTION'] ?></textarea>
                </div>
            </div>
            <div class="upload-listing">
                <div class="dropdown" style="margin-top:-7px; margin-bottom: 5px;">
                    <button class="dropdown-toggle text-grey" style="margin-left: -6px;" type="button" id="upload-listing-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="listing-category-select" style="float:left;"><?= $dataProduct['CTG_NAME'] ?></span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuSelectCategory">

                    <!-- LOOPING FROM CATEGORY TABLE -->

                    <?php foreach ($categoryData as $category) : ?>
                        
                        <li>
                            <a class="dropdown-item" data-id="<?= $category['ID'] ?>">
                                <?= $category["NAME"] ?>
                            </a>
                        </li>
                    
                    <?php endforeach; ?>

                    <input type="hidden" class="category" name="category" value="">

                    </ul>
                </div>
                <img src="../assets/img/tab5/Down-arrow-2.png" class="down-arrow-upload-listing">
            </div>
            <div class="row gx-0 upload-listing">
                <div class="col-6 col-md-6 col-lg-6 d-flex justify-content-left">
                    <div class="form-group">
                        <div class="palceholder live-stream-title-input price-stock-input">
                            <label for="price" data-translate="tab5uploadlisting-7">Price</label>
                            <span class="star-2">*</span>
                        </div>
                        <input type="number" id="price" autocomplete="off" name="price" class="form-control upload-listing-input" required value="<?= $dataProduct['PRICE'] ?>">
                    </div>
                </div>
                <div class="col-6 col-md-6 col-lg-6">
                    <div class="form-group stock-border">
                        <div class="palceholder live-stream-title-input price-stock-input">
                            <label for="stock" data-translate="tab5uploadlisting-8">Stock</label>
                            <span class="star">*</span>
                        </div>
                        <input type="number" id="stock" autocomplete="off" name="stock" class="form-control upload-listing-input" required value="<?= $dataProduct['QUANTITY'] ?>">
                    </div>
                </div>
            </div>
            <div class="row gx-0 upload-listing">
                <div class="form-group" id="variation-section" style="padding-left: 7px">

                    <!-- SEPARATE | VARIATION FROM DATABASE -->

                    <?php $product_variation = explode('|', $dataProduct['VARIATION']); ?>

                    <?php foreach ($product_variation as $variation): ?>

                        <span class="badge tagged-badge" onclick="remove_me(this)" style="margin-right:4px; font-size: 10px"><?= $variation ?></span>

                    <?php endforeach; ?>

                    <div class="palceholder live-stream-title-input old-variation">
                        <label for="variation_array" data-translate="tab5uploadlisting-9">Variation</label>
                        <span class="star">*</span>
                    </div>
                    <input type="text" disabled style="background-color: #FAFAFF" class="form-control upload-listing-input old-variation" required>
                    <img src="../assets/img/tab5/Add-(Grey).png" class="btn-plus-upload-listing" data-bs-toggle="modal" data-bs-target="#insertVariationModal">        
                    <input type="hidden" id="variation_array" name="variation" value="<?= $dataProduct['VARIATION'] ?>">
                </div>
            </div>
            <div class="row gx-0">
                <div class="col-9 col-md-9 col-lg-9 upload-listing">
                    <div class="form-group">
                        <div class="palceholder live-stream-title-input">
                            <label for="weight" data-translate="tab5uploadlisting-10">Weight (Gram)</label>
                            <span class="star">*</span>
                        </div>
                        <input type="number" name="weight" autocomplete="off" class="form-control upload-listing-input" value="<?= $dataProduct['WEIGHT'] ?>">
                    </div>
                </div>
                <div class="col-3 col-md-3 col-lg-3 pt-2" style="border-left: 1px solid #d1d5db; border-bottom: 1px solid #d1d5db; background-color: #F5F5F5">
                    <b><p class="small-text text-center align-middle text-grey" style="padding-left: 10px; padding-top: 5px">Gram</p></b>
                </div>
            </div>
        </div>

        <input type="hidden" name="id_product" value="<?= $product_code ?>">
        <input type="hidden" name="old_thumb_id" value="<?= $dataProduct['THUMB_ID'] ?>">

        <!-- FOR WHICH SLOT DELETED -->

        <input type="hidden" name="deleted_thumb_id" id="deleted_thumb_id" value="">

        <!-- FOR WHICH SLOT PHOTO CHANGED -->

        <input type="hidden" name="array_changed_photo" id="array_changed_photo" value="">

        <div id="btn-place">
            <div class="text-center fixed-bottom">
                <button class="btn-live" type="submit" style="border: none" data-translate="tab5uploadlisting-11">Save Changes</button>
            </div>
        </div>
    </form>

    <div class="modal fade" id="insertVariationModal" tabindex="-1" aria-labelledby="insertVariationModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" >
            <div class="modal-content">
                <div class="modal-body text-center mt-2">
                    <input type="text" class="tag-product-input shadow-sm" id="variation" placeholder="Write variation...">
                <div class="btn-continue-ads" data-bs-dismiss="modal" onclick="insertVariation()" data-translate="tab5uploadlisting-12">Add Variation</div>
            </div>
        </div>
    </div>

    <!-- FOR SHOW PLUS FIT BY PHOTO NUMBER -->

    <input type="hidden" id="count_photos" value="<?= count($product_image) ?>">

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

        if (localStorage.lang == 1){
            $('#variation').attr('placeholder','Tulis variasi...');
        }

        $('body').show();
	});

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

    // SCRIPT RED DOT SELECT

    $("#upload-listing-dropdown").change(function(){
        $(".red-dot").hide();
    });
    $("#upload-listing-dropdown-2").change(function(){
        $(".red-dot-2").hide();
    });

    // SCRIPT ADD VARIATION WITH |

    console.log($('#variation_array').val());

    function insertVariation(){

        if ($("#variation").val() !== ""){
            $("#variation-section").append('<span class="badge tagged-badge" onclick="remove_me(this)" style="margin-right: 4px; font-size: 10px">'+$("#variation").val()+'</span>');  
            
            if ($("#variation_array").val() == ""){
                $("#variation_array").val($("#variation").val());
            }else{
                $("#variation_array").val($("#variation_array").val() + "|" + $("#variation").val());
            }

            $("#variation").val("");
            console.log($('#variation_array').val());
        }
    }

    // SCRIPT REMOVE BADGE ITSELF

    function remove_me(elm){

    // REMOVE STRING | FROM VARIATION AND REPLACE

    var variation_deleted = $(elm).text();

    if ($("#variation_array").val().includes(variation_deleted)){
        var new_variation = $("#variation_array").val();

        if (new_variation.includes("|")){
            var a = new_variation.replace("|"+variation_deleted,"");

            $("#variation_array").val(a);

            if(a.charAt(0) != "|" ){
                var new_variation = $("#variation_array").val();
                var a = new_variation.replace(variation_deleted+"|","");

                $("#variation_array").val(a);              
            }

        }else{
            var a = new_variation.replace(variation_deleted,"");

            $("#variation_array").val(a);   
        }

        console.log($('#variation_array').val());
    }

    // REMOVE BADGE

    $(elm).remove();
    check_div();

    }

    // CHECK IF LAST BADGE RETURN RED DOT

    function check_div(){

        if ($(".badge")[0]){
            // NO ACTION REQUIRED (STILL HAVE BADGE)
        }else{
            $(".old-variation").show();
            $("#variation-section").css("margin-top", "0px");
            $("#variation-section").css("margin-bottom", "0px");
            $(".btn-plus-upload-listing").css("margin-top", "-29px");
        }

    }

    // CHANGE DROPDOWN AS NAME AS CLICK

    $('.dropdown-item').click(function(){
        $('.listing-category-select').text($(this).text());
        $('.red-dot-3').hide();
        $('.category').val($(this).data('id'));
    });

    // DISABLE PLUS FROM 2 TO END

    var count_photos = $('#count_photos').val();

    for (var i=0; i<=25; i++){
        $('#upload-listing-add-'+i).hide();
        $('#file-input-'+i).prop("type", "text");
        $('#file-input-'+count_photos).prop("type", "file");
        $('#delete-listing-'+i).hide();
    }

    // CHANGE IMAGE AS CHOOSE

    var array_changed_photo = [];

    var loadFile = function(event, number) {
        var reader = new FileReader();
        reader.onload = function(){

        var strings = reader.result.split("/");
        console.log(strings[0])

        // CHECK IF UPLOADED MEDIA IS PHOTO OR VIDEO

        if (strings[0] == 'data:video'){
            $('#image-preview-'+number).replaceWith('<video src="'+reader.result+'#t=0.5" id="video-preview-'+number+'" style="object-fit: none" type="video/mp4" class="upload-listing-border">');
            
            // IF DIV ALREADY VIDEO REPLACE AGAIN (IMG CANNOT BE FOUND)

            $('#video-preview-'+number).replaceWith('<video src="'+reader.result+'#t=0.5" id="video-preview-'+number+'" style="object-fit: none" type="video/mp4" class="upload-listing-border">');
        
        }else{
            var output = document.getElementById('image-preview-'+number);
            output.src = reader.result;
        }

        };
        reader.readAsDataURL(event.target.files[0]);

        // SHOW PLUS AFTER BEFORE IMAGE ALREADY SELECTED

        $('#upload-listing-add-'+number).hide();
        $('#upload-listing-add-'+(number+1)).show();
        $('#file-input-'+(number+1)).prop("type", "file");
        $('#delete-listing-'+number).show();

        // WHICH SLOT CHANGED IMAGE EDITED [EX = 0,4,5]

        array_changed_photo.push(number);
        $('#array_changed_photo').val(array_changed_photo);

        console.log($('#array_changed_photo').val());
    };

    // SHOW PLUS RIGHT AFTER EDIT PHOTO FROM DATABASE END

    $('#upload-listing-add-'+count_photos).show();
    $('#file-input-'+(count_photos)).prop("type", "file");

    // PREVENT FROM KEYBOARD GOING UP

    $('input').focus(function() {
        $('#btn-place').removeClass('fixedBottom');
    });

    $('input').focusout(function() {
        $('#btn-place').addClass('fixedBottom');
    });

    $('textarea').focus(function() {
        $('#btn-place').removeClass('fixedBottom');
    });

    $('textarea').focusout(function() {
        $('#btn-place').addClass('fixedBottom');
    });

    // ONLOAD AUTO RESIZE WITH EXISTING VARIATION

    $(".old-variation").hide();
    $("#variation-section").css("margin-top", "6px");
    $("#variation-section").css("margin-bottom", "5px");
    $(".btn-plus-upload-listing").css("margin-top", "0px");

    // DELETE SINGLE LISTING FROM DATABASE

    var number_extend = 11;

    $('body').on("click", ".delete-listing-img", function() {

       $(this).parent().parent().parent().parent().parent().remove(); 

       var deleted_img = $(this).data("product-img"); 
       var array_deleted_image = $('#deleted_thumb_id').val();

        // CHECK IF DELETED ARRAY IS EXIST

       if (array_deleted_image != ""){
            array_deleted_image = array_deleted_image + "|" + deleted_img;
       }else{
            array_deleted_image = deleted_img;
       }

       $('#deleted_thumb_id').val(array_deleted_image);
       console.log($('#deleted_thumb_id').val());

        // DELETE LISTING TEMPORARY / NOT FROM DATABASE

        // IF MATCH WITH SLOT DELETE THAT EX = DELETE 2 REMOVE 2 FROM ARRAY
        
        var delete_slot = $(this).data("delete-slot");
        array_changed_photo = array_changed_photo.filter(e => e !== delete_slot);

        $('#array_changed_photo').val(array_changed_photo);
        console.log($('#array_changed_photo').val());

        // ADD MORE SLOT WHILE USER DELETED SLOT

        var add_slot =
        `<li class="nav-item">
            <div class="upload-listing-image-slot d-flex justify-content-center">
                <div class="single-upload-cover-listing">
                    <div class="image-upload">
                        <label for="file-input-`+number_extend+`" class="row" style="--bs-gutter-x: none">
                        <img src="../assets/img/tab5/delete-listing-2.png" data-delete-slot="`+number_extend+`" class="delete-listing-img" id="delete-listing-`+number_extend+`" style="z-index: 9999; display:none">
                            <img src="../assets/img/tab5/Dashed-Image.png" id="image-preview-`+number_extend+`" class="upload-listing-border">
                            <img src="../assets/img/tab5/Add-(Grey).png" class="upload-listing-add" id="upload-listing-add-`+number_extend+`" style="display:none">
                        </label>
                        <input id="file-input-`+number_extend+`" type="file" name="listing_thumbnail-`+number_extend+`" onchange="loadFile(event, `+number_extend+`)" />
                    </div>
                </div>
            </div>
        </li>`

        $('.horizontal-slide').append(add_slot);

        number_extend++;        
            
    });
    
</script>
</html>