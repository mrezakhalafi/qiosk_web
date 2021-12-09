<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/db_conn.php'); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/customize_template.php'); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/mail_template.php'); ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/session_function.php'); ?>

<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/palio_browser/logics/chat_dbconn.php');?>

<?php

// $price_chosen = 33.50;
$services = "ls,vc,ac,ss,um,wb,cb";
$storage = 100;
$bandwidth = 100;
$type = 'monthly';

$checkPwd = 'T3sB4Y4rN0X3nd1t';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['sign_up']) && $_POST['sign_up'] == "0") {
    // echo 'begin sign up';

    // if(!isset($_SESSION['flag'])){
    //     $_SESSION['flag'] = 1;
    // } else {
    //     redirect(base_url());
    // }

    if (isset($_POST['country_code'])) {
        if ($_POST['country_code'] == 'ID') {
            $currency = 'IDR';
            $price_chosen = 450000.00;
        } else {
            $currency = 'USD';
            $price_chosen = 33.50;
        }
    }

    $link = $_POST['shop_link'];
    $sent_time = $_POST['sent_time'];
    $hex = $_POST['hex'];
    $created_by = $_POST['created_by'];
    $description = $_POST['description'];

    $category = $_POST['category'];

    //check session token
    // $query = $dbconn->prepare("SELECT * FROM SESSION WHERE SESSION_TOKEN = ?");
    // $query->bind_param("s", $_SESSION['session_token']);
    // $query->execute();
    // $token = $query->get_result()->fetch_assoc();
    // $token_exist = $token['USER_ID'];
    // $query->close();

    // if ($token_exist == null) {

    $email = $_POST['email'];
    $query = $dbconn->prepare("SELECT COUNT(*) AS CNT FROM USER_ACCOUNT WHERE EMAIL_ACCOUNT = ?");
    $query->bind_param("s", $email);
    $query->execute();
    $items = $query->get_result()->fetch_assoc();
    $countEmail = $items['CNT'];
    // $cnt = $items['cnt'];
    $query->close();

    if ($countEmail > 0) {
        // echo "<script>";
        // echo "alert('Your email has already been registered, please use a different email address');";
        // echo "</script>";
        echo 'Your email has already been registered, please use a different email address';
        return;
    }

    $cmpny = $_POST['companyname'];
    if (strlen($cmpny) > 0 && substr($cmpny, 0, 1) === " " || substr($cmpny, strlen($cmpny) - 1, strlen($cmpny)) === " ") {
        // echo ("<script>alert('Your company name cannot start or end with a blank space');</script>");
        // echo ("<script>window.location = window.location.href;</script>");
        echo "Your store name cannot start or end with a blank space";
        return;
    }

    $usrnm = $_POST['username'];
    if (strlen($usrnm) > 0 && substr($usrnm, 0, 1) === " " || substr($usrnm, strlen($usrnm) - 1, strlen($usrnm)) === " ") {
        // echo ("<script>alert('Your username cannot start or end with a blank space');</script>");
        // echo ("<script>window.location = window.location.href;</script>");
        echo 'Your username cannot start or end with a blank space';
        return;
    }

    $pwds = $_POST['pwd'];
    $pwdscheck = $_POST['pwdcheck'];
    // setSession('password_show', $pwds);
    // setSession('password', md5($pwds));
    // setSession('email', $_POST['email']);
    // setSession('price', $price_chosen);
    // setSession('username', $_POST['username']);

    if (strlen($pwds) > 0 && substr($pwds, 0, 1) === " " || substr($pwds, strlen($pwds) - 1, strlen($pwds)) === " ") {
        // echo ("<script>alert('Your password cannot start or end with a blank space');</script>");
        // echo ("<script>window.location = window.location.href;</script>");
        echo 'Your password cannot start or end with a blank space';
        return;
    }

    if (strlen($pwds) < 6 || strlen($pwdscheck) < 6) {
        // echo ("<script>alert('Your password is less than 6 characters!');</script>");
        // echo ("<script>window.location = window.location.href;</script>");
        echo 'Your password is less than 6 characters!';
        return;
    }

    if ($pwds != $pwdscheck) {
        // echo ("<script>alert('Password does not match!');</script>");
        // echo ("<script>window.location = window.location.href;</script>");
        echo 'Password does not match!';
        return;
    }

    if ($pwds == null || $pwdscheck == null || $cmpny == null || $usrnm == null || $email == null) {
        // echo ("<script>alert('Input cannot be blank!');</script>");
        // echo ("<script>window.location = window.location.href;</script>");
        echo 'Input cannot be blank!';
        return;
    }

    if ($pwds == '') {
        // echo ("<script>alert('Your password cannot be blank');</script>");
        // echo ("<script>window.location = window.location.href;</script>");
        echo 'Your password cannot be blank';
        return;
    }

    if (!preg_match('/^[A-Z0-9._-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i', $email)) {
        // echo ("<script>alert('Email is not valid!');</script>");
        // echo ("<script>window.location = window.location.href;</script>");
        echo 'Email is not valid!';
        return;
    }

    $dbconn = getDBConn();
    $password = $_POST['pwd'];
    $username = $_POST['username'];
    $company_name = $_POST['companyname'];
    $hash = md5(rand(0, 1000));
    $product_id = 0;

    // cek email
    $query = $dbconn->prepare("SELECT * FROM USER_ACCOUNT WHERE EMAIL_ACCOUNT = ?");
    $query->bind_param("s", $email);
    $query->execute();
    // $itemUser = $query->get_result()->fetch_assoc();
    $counteremail = $query->get_result()->fetch_assoc();
    if ($counteremail != null) {
        echo ("<script>alert('Email has already registered!');</script>");
        echo ("<script>window.location = '" . base_url() . "newpricing.php#pay';</script>");
        return;
    }
    // $cnt = $itemUser['cnt'];
    $msg = "";
    $query->close();
    // end cek email

    // // captcha
    // // Secret Key ini kita ambil dari halaman Google reCaptcha
    // // Sesuai pada catatan saya di STEP 1 nomor 6
    // $secret_key = "6LfwcuAUAAAAAPlhqsGI1bRUXzJeFLt8l8OzAPMR";
    // // Disini kita akan melakukan komunkasi dengan google recpatcha
    // // dengan mengirimkan scret key dan hasil dari response recaptcha nya
    // $verify = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $_POST['g-recaptcha-response']);
    // $response = json_decode($verify);
    // // end captcha

    // redirect ke page name
    // if (!$itemUser) exit('No rows');

    if ($countEmail > 0) {
        $msg = 'Email has already registered!';
    } else {
        // captcha oke
        // if ($response->success) {

        // select an apikey
        $query = $dbconn->prepare("SELECT APIKEY FROM APIKEY ORDER BY ID DESC LIMIT 1");
        $query->execute();
        $apiarray = $query->get_result()->fetch_assoc();
        $apikey = $apiarray['APIKEY'];
        $query->close();

        // insert company
        $query = $dbconn->prepare("INSERT INTO COMPANY (API_KEY, DOMAIN, STATUS) VALUES (?, 'easysoft', 0)");
        $query->bind_param("s", $apikey);
        $query->execute();
        $company_id = $query->insert_id;
        $query->close();

        $_SESSION['id_company'] = $company_id;

        // delete used apikey
        $query = $dbconn->prepare("DELETE FROM APIKEY WHERE APIKEY = ?");
        $query->bind_param("s", $apikey);
        $query->execute();
        $query->close();

        //if untuk tombol trial
        if ($_POST['sign_up'] == "0") {
            //insert id product to subscribe table
            $query = $dbconn->prepare("INSERT INTO SUBSCRIBE (COMPANY, PRODUCT, START_DATE, END_DATE, STATUS) VALUES (?, ?, NOW(), DATE_ADD(NOW(), INTERVAL 1 DAY), 3)");
            $query->bind_param("ii", $company_id, $product_id);
            $query->execute();
            $subscribe_id = $query->insert_id;
            $query->close();

            // insert to user account
            $query = $dbconn->prepare("INSERT INTO USER_ACCOUNT (COMPANY, USERNAME, EMAIL_ACCOUNT, PASSWORD, STATUS, HASH, ACTIVE) VALUES (?, ?, ?, MD5(?), 3, ?, 0);");
            $query->bind_param("issss", $company_id, $username, $email, $password, $hash);
            $query->execute();
            if (!$dbconn->commit()) {
                // echo "Commit insert user account gagal ";
            } else {
                // echo "Commit insert user account sukses ";
            }
            $user_id = $query->insert_id;
            $query->close();

            // update session
            $query = $dbconn->prepare("UPDATE SESSION SET USER_ID = ? WHERE SESSION_TOKEN = ?");
            $query->bind_param("is", $user_id, $_SESSION['session_token']);
            $query->execute();
            $query->close();
        } else {
            //insert id product to subscribe table
            $query = $dbconn->prepare("INSERT INTO SUBSCRIBE (COMPANY, PRODUCT, START_DATE, END_DATE, STATUS) VALUES (?, ?, NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY), 0)");
            $query->bind_param("ii", $company_id, $product_id);
            $query->execute();
            $subscribe_id = $query->insert_id;
            $query->close();

            // insert to user account
            $query = $dbconn->prepare("INSERT INTO USER_ACCOUNT (COMPANY, USERNAME, EMAIL_ACCOUNT, PASSWORD, STATUS, HASH, ACTIVE) VALUES (?, ?, ?, MD5(?), 0, ?, 0);");
            $query->bind_param("issss", $company_id, $username, $email, $password, $hash);
            $query->execute();
            if (!$dbconn->commit()) {
                echo "Commit insert user account gagal ";
            } else {
                echo "Commit insert user account sukses ";
            }
            $user_id = $query->insert_id;
            $query->close();

            // update session
            $query = $dbconn->prepare("UPDATE SESSION SET USER_ID = ? WHERE SESSION_TOKEN = ?");
            $query->bind_param("is", $user_id, $_SESSION['session_token']);
            $query->execute();
            $query->close();

            //check order number availability
            do {
                $bytes = random_bytes(8);
                $hexbytes = strtoupper(bin2hex($bytes));
                $order_number = substr($hexbytes, 0, 15);

                $query = $dbconn->prepare("SELECT COUNT(*) as counter_bill FROM BILLING WHERE ORDER_NUMBER = ?");
                $query->bind_param("s", $order_number);
                $query->execute();
                $counter = $query->get_result()->fetch_assoc();
                $counter_bill = $counter['counter_bill'];
                $query->close();
            } while ($counter_bill > 0);

            $query = $dbconn->prepare("INSERT INTO BILLING (ORDER_NUMBER, BILL_DATE, DUE_DATE, COMPANY, SUBSCRIBE, CURRENCY, CHARGE, CUT_OFF_DATE, IS_PAID) VALUES (?, NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY), ?, ?, ?, ?, DATE_ADD(NOW(), INTERVAL 37 DAY), 0)");
            $query->bind_param("siisd", $order_number, $company_id, $subscribe_id, $currency, $price_chosen);
            $query->execute();
            $query->close();

            setSession('order_number', $order_number);
        }

        $logoName = null;
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/qiosk_web/images/";
        // upload gambar ke palio_browser/images + copy ke folder logo dashboard
        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadDir . $_FILES['file']['name'])) {
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/qiosk_web/images/";
            $logoName = $_FILES['file']['name'];
            $uploadedFile = $uploadDir . $logoName;
            $uploadedFile = preg_replace('/\s/i', '%20', $uploadedFile);
            copy($uploadedFile, $_SERVER['DOCUMENT_ROOT'] . "/dashboardv2/uploads/logo/" . $logoName);
        }

        // insert company info
        $query = $dbconn->prepare("INSERT INTO COMPANY_INFO (COMPANY, COMPANY_NAME, CREATED_DATE, PRODUCT_INTEREST, COMPANY_LOGO) VALUES (?, ?, NOW(), ?, ?);");
        $query->bind_param("isss", $company_id, $company_name, $services, $logoName);
        $query->execute();
        $query->close();

        // insert company info
        $query = $dbconn->prepare("REPLACE INTO CREDIT (USER_ID, COMPANY_ID, CURRENCY) VALUES (?, ?, ?);");
        $query->bind_param("iis", $user_id, $company_id, $currency);
        $query->execute();
        $query->close();

        $feature_count = 7;

        $secret = sha1($hash);
        $_SESSION['secret'] = $secret;

        // insert temporary table
        $query = $dbconn->prepare("INSERT INTO HASH (EMAIL, HASH) VALUES (?, ?);");
        $query->bind_param("ss", $email, $secret);
        $query->execute();
        $query->close();

        $dbconn2 = paliolite();
        // insert into merchant table

        $store_palio_id = (string) $company_id;
        
        if (isset($_POST['is_artist']) && $_POST['is_artist'] == "1") {
            $query = $dbconn2->prepare(
                "INSERT INTO SHOP(`CODE`, `NAME`, `CREATED_DATE`, `DESCRIPTION`, `FILE_TYPE`, `THUMB_ID`, `LINK`, `CATEGORY`, `USE_ADBLOCK`, `PALIO_ID`, `CREATED_BY`) VALUES (?, ?, ?, '".$description."', 1, ?, ?, '4', 0, ?, ?)");
            $query->bind_param("sssssss", $hex, $company_name, $sent_time, $logoName, $link, $store_palio_id, $created_by);
            $status = $query->execute();
            $query->close();
        } else {
            $query = $dbconn2->prepare(
                "INSERT INTO SHOP(`CODE`, `NAME`, `CREATED_DATE`, `DESCRIPTION`, `FILE_TYPE`, `THUMB_ID`, `LINK`, `CATEGORY`, `USE_ADBLOCK`, `PALIO_ID`, `CREATED_BY`) VALUES (?, ?, ?, '".$description."', 1, ?, ?, '".$category."', 0, ?, ?)");
            $query->bind_param("sssssss", $hex, $company_name, $sent_time, $logoName, $link, $store_palio_id, $created_by);
            $status = $query->execute();
            $shop_id = $query->insert_id;
            $query->close();

            // QUERY ADD ADDRESS LOCATION

            $location = $_POST['location'];
            $phone_number = $_POST['phone_number'];
    
            // $queryAddress = "INSERT INTO palio_lite.SHOP_SHIPPING_ADDRESS (STORE_CODE, ADDRESS, VILLAGE, DISTRICT,
            //                 CITY, PROVINCE, ZIP_CODE, PHONE_NUMBER, COURIER_NOTE) VALUES ('".$hex."','".$location."','1','1',
            //                 '1','1','1','".$phone_number."','1')"; 

            $queryAddress = "INSERT INTO palio_lite.SHOP_SHIPPING_ADDRESS (STORE_CODE, ADDRESS,
                                PHONE_NUMBER) VALUES ('".$hex."','".$location."','".$phone_number."');";
    
            if (mysqli_query($dbconn, $queryAddress)){
                header("Location: ../../pages/tab5-success-open-shop.php");
            }else{
                echo "Data gagal ditambahkan ke database. $sql. " . mysqli_error($dbconn);
            }
        }

        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ // EMAIL ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        $url = base_url();
        $email = strtolower($_POST['email']);
        $activation_link = "https://qmera.palio.io/verify.php?h=" . $secret;
        // $header = file_get_contents('Palio_Confirmation_Header.html');
        // $body = file_get_contents('Palio_Confirmation_Body.html');
        // $footer = file_get_contents('Palio_Confirmation_Footer.html');
        // $content = $header . $username . $body . $activation_link . $footer;
        $content = customizeTemplateRemoteEmailConfirmation($username, $activation_link);
        // sendMail($content, $email);
        // $content = file_get_contents('template/PalioEmailConfirmation.htm');
        sendMailEmailConfirmation($content, $email);

        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ // END EMAIL ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        // redirect(base_url() . "verifyemail.php");
        echo "sign up successful";
        exit();
    }
    // else {
    //     $msg = 'Please Validate that you are human!';
    //     // echo("<script>$('#submit_sign_up').prop('disabled',true);</script>");
    // }
} else {
    echo 'sign up fail';
}
// end redirect
// }
// } 
// else {
//     echo ("<script>alert('You already signed up another account!');
//     window.location.href = 'index.php'</script>");
//     exit();
// }
// }

// function sendMail($body, $destination)
// {
//     require $_SERVER['DOCUMENT_ROOT'] . '/PHPMailer/src/Exception.php';
//     require $_SERVER['DOCUMENT_ROOT'] . '/PHPMailer/src/PHPMailer.php';
//     require $_SERVER['DOCUMENT_ROOT'] . '/PHPMailer/src/SMTP.php';

//     //$email=$_POST['email'];
//     //$msg=$_POST['message'];

//     $succMsg = "";
//     $errMsg = "";

//     if ($destination != "") {

//         $mail = new PHPMailer();
//         //$mail->SMTPDebug = 2;
//         $mail->isSMTP();
//         $mail->Host       = 'smtp.gmail.com';
//         $mail->SMTPAuth   = true;
//         $mail->Username   = 'support@palio.io';
//         $mail->Password   = '12345easySoft67890';
//         $mail->SMTPSecure = 'tls';
//         $mail->Port       = 587;

//         //Recipients
//         $mail->setFrom('support@palio.io', 'Palio');
//         $mail->addAddress($destination);
//         $mail->addReplyTo('support@palio.io');

//         $mail->isHTML(true);
//         $mail->Subject = 'Palio Email Confirmation';
//         $mail->Body = $body;

//         if (!$mail->send()) {
//             $mail->ClearAllRecipients();
//             $succMsg = $mail->ErrorInfo;
//         } else {
//             $mail->ClearAllRecipients();
//             $succMsg = "Email has been sent successfully.";
//         }
//     } else {
//         $errMsg = "Please fill all the form!";
//     }
// }

?>