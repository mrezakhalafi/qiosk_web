<?php 

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/qiosk_web/logics/chat_dbconn.php');
$dbconn = paliolite();

// GET USER PIN

session_start();
$store_created_by = $_SESSION['user_f_pin'];
$f_pin = $_SESSION['user_f_pin'];
$id_shop = $_SESSION['id_shop'];

// GET OPEN STORE FORM

$caption = $_POST['caption'];
$tagged_product = $_POST['tagged_product'];
$location = $_POST['location'];
$hashtag = str_replace(' ', '|', $_POST['hashtag']);

// SET IMAGE DIRECTORY

$target_dir = $_SERVER['DOCUMENT_ROOT'] . '/qiosk_web/images/';
$imageFileType = strtolower(pathinfo($_FILES["post_photo"]["name"],PATHINFO_EXTENSION));
$uploadOk = 1;
$target_file = $target_dir . $f_pin . time() . "." . $imageFileType;

// CHECK IF REAL IMAGE

if (isset($_POST["submit"])){
  $check = getimagesize($_FILES["post_photo"]["tmp_name"]);
  if ($check !== false){
    echo "File is an image - " . $check["mime"] . ".";
    $uploadOk = 1;
  } else {
    echo "File is not an image.";
    $uploadOk = 0;
  }
}

// CHECK IF IMAGE EXIST

if (file_exists($target_file)) {
  echo "Sorry, file already exists.";
  $uploadOk = 0;
}

// CHECK IMAGE SIZE

if ($_FILES["post_photo"]["size"] > 5000000) {
  echo "Your file size is too large.";
  $uploadOk = 0;
}

// CHECK IMAGE FORMAT

if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
  && $imageFileType != "gif" && $imageFileType != "webp" && $imageFileType != "mp4"){
  echo "Only JPG, JPEG, PNG & GIF photo formats and MP4 video formats are allowed. Now :";
  $uploadOk = 0;
}

// CHECK IMAGE VALIDATION

$thumb_id = $f_pin . time() . "." . $imageFileType;

if ($uploadOk == 0) {
  echo("File anda belum sesuai.");
}else{

  if (move_uploaded_file($_FILES["post_photo"]["tmp_name"], $target_file)){

    // INSERT INTO POST

    list($msec, $sec) = explode(' ', microtime());
    $time_milli = $sec.substr($msec, 2, 3); // '1491536422147'
    $notif_id = $id_shop.$time_milli;

    $post_id = $f_pin.$notif_id;

    // FOR FILE TYPE

    if ($imageFileType == "mp4"){
      $file_type = 2;
    }else{
      $file_type = 1;
    }

    $queryPost = "INSERT INTO POST (POST_ID, F_PIN, TITLE, DESCRIPTION, TYPE, CREATED_DATE, 
                  PRIVACY, FILE_TYPE, THUMB_ID, FILE_ID, LAST_UPDATE, MERCHANT) VALUES 
                  ('".$notif_id."','".$f_pin."','".$caption."','".$caption."','2','".
                  $time_milli."','3','".$file_type."','".$thumb_id."','".$thumb_id."',
                  '".$time_milli."','".$id_shop."')";

    $queryShopPost = "INSERT INTO SHOP_POST (POST_CODE, SHOP_CODE, CATEGORY, TAGGED_PRODUCT, 
                      LOCATION, HASHTAG) VALUES ('".$notif_id."','".$id_shop."','0','".
                      $tagged_product."','".$location."','".$hashtag."')";

    if (mysqli_query($dbconn, $queryPost) && mysqli_query($dbconn, $queryShopPost)){
      echo("Berhasil");
    }else{
      echo("Data failed to add. $sql. " . mysqli_error($dbconn));
    }

  }else{
    echo("The file is suitable but not uploaded successfully.".$_FILES["post_photo"]["error"]);
  }
}

?>