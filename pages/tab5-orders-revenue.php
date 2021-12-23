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

	// SELECT ORDER FROM PURCHASE

	$query = $dbconn->prepare("SELECT * FROM PURCHASE WHERE MERCHANT_ID = '$id_shop' AND PRODUCT_ID IS NOT NULL ORDER BY CREATED_AT DESC");
	$query->execute();
	$orders = $query->get_result();
	$query->close();

  // COUNT REVENUE ORDER FROM PURCHASE

  $totalRevenue = 0;
  while ($ordersRevenue = $orders->fetch_assoc()) {
    $totalRevenue += $ordersRevenue["PRICE"] * $ordersRevenue["AMOUNT"];
  };

  require '../logics/tab5/orders-revenue.php';

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

<body class="bg-white-background" style="display:none">

  <!-- NAVBAR -->

  <nav class="navbar navbar-light navbar-shop-manager">
    <div class="container">
      <a href="tab5-shop-manager.php">
        <img src="../assets/img/tab5/Back-(Black).png" class="navbar-back-black">
      </a>
      <p class="navbar-title-2" data-translate="tab5ordersrevenue-1">Orders & Revenue</p>
      <div class="navbar-brand pt-2 navbar-brand-slot">
        <img class="navbar-img-slot">
      </div>
    </div>
  </nav>

  <!-- SECTION ORDER REVENUE DATE -->

  <div class="row gx-0">
    <div class="col-5 col-md-5 col-lg-5 col-xl-7"></div>
    <div class="col-7 col-md-7 col-lg-4 col-xl-4 d-flex justify-content-end">
      <input type="text" readonly="readonly" class="datepicker tbl-calendar-date" style="text-align: right; margin-right: 25px;" 
        value="Today : <?= date('d F Y') ?>">
      <img src="../assets/img/tab5/Down-(Black).png" class="small-arrow-calendar" 
        style="margin-left: -10px" onclick="show_datepicker()">
    </div>
  </div>

  <!-- SECTION ORDER REVENUE GRAPH -->

  <div class="section-order-revenue-graph">
    <div class="container-fluid">
      <div class="graph-data shadow-sm">
        <div class="container">
          <div class="row">
            <span class="graph-data-title">
              <b>Rp <?= number_format($totalRevenue,0,",",",") ?></b>
            </span>
          </div>
        </div>
        <div class="container">  
          <div class="graph-data-desc">
            <span class="text-green">
              <b>Rp <?= number_format($day_income1,0,",",",") ?></b>
            </span>
            <span class="text-grey" data-translate="tab5ordersrevenue-3">Since yesterday</span>
          </div>
        </div>
        <div class="container">
          <div class="row d-flex justify-content-center">

          <!-- LOOPING GRAPH FOR WEEK -->

            <?php $i = 1; ?>

            <?php for ($x = -6; $x <= 0; $x++) { ?>

              <div class="col-1 col-md-1 col-lg-1 graph-one-bar">
                <div class="progress-2">

                  <!-- GRAPH COLOUR CHECK (TODAY = PURPLE) -->

                  <?php if ($x==0): ?>

                    <div class="progress-bar-purple" role="progressbar" id="progress-1" aria-valuenow="10"
                      aria-valuemin="0" aria-valuemax="100" style="width:<?= ($day_income1/1000000)*100 ?>%">
                    </div>

                  <?php else: ?>

                    <?php if ($x == -1): ?>

                      <div class="progress-bar-2" role="progressbar" id="progress-2" aria-valuenow="10"
                        aria-valuemin="0" aria-valuemax="100" style="width:<?= ($day_income2/1000000)*100 ?>%">
                      </div>

                    <?php elseif($x == -2): ?>

                      <div class="progress-bar-2" role="progressbar" id="progress-3" aria-valuenow="10"
                        aria-valuemin="0" aria-valuemax="100" style="width:<?= ($day_income3/1000000)*100 ?>%">
                      </div>

                    <?php elseif($x == -3): ?>

                      <div class="progress-bar-2" role="progressbar" id="progress-4" aria-valuenow="10"
                        aria-valuemin="0" aria-valuemax="100" style="width:<?= ($day_income4/1000000)*100 ?>%">
                      </div>

                    <?php elseif($x == -4): ?>

                      <div class="progress-bar-2" role="progressbar" id="progress-5" aria-valuenow="10"
                        aria-valuemin="0" aria-valuemax="100" style="width:<?= ($day_income5/1000000)*100 ?>%">
                      </div>

                    <?php elseif($x == -5): ?>

                      <div class="progress-bar-2" role="progressbar" id="progress-6" aria-valuenow="10"
                        aria-valuemin="0" aria-valuemax="100" style="width:<?= ($day_income6/1000000)*100 ?>%">
                      </div>

                    <?php elseif($x == -6): ?>

                      <div class="progress-bar-2" role="progressbar" id="progress-7" aria-valuenow="10"
                        aria-valuemin="0" aria-valuemax="100" style="width:<?= ($day_income7/1000000)*100 ?>%">
                      </div>

                    <?php endif; ?>

                  <?php endif; ?>

                </div>
                <span class="text-grey smallest-text graph-bottom-desc" id="date-text-<?= $i++ ?>">
                  <?= date('d/m', strtotime("$x day")); ?>
                </span>
              </div>

              <?php } ?>

              <div class="col-3 col-md-3 col-lg-3 d-flex justify-content-end graph-right">
                <div class="graph-right-desc">
                  <p class="smallest-text text-grey">1 JT</p>
                  <p class="smallest-text text-grey">500 K</p>
                  <p class="smallest-text text-grey">0 K</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- SECTION ORDER REVENUE RECENT -->

  <div class="section-order-revenue-recent">
    <div class="container">
      <p class="small-text text-purple order-revenue-recent-title" data-translate="tab5ordersrevenue-4">Recent Orders</p>

      <!-- CHECK IF SHOP HAVE ORDER -->
      
      <?php if (mysqli_num_rows($orders)!=0): ?>

      <table class="tbl-order-revenue">
        <tr class="tb-order-revenue-head">
          <th class="tbl-order-revenue-left" data-translate="tab5ordersrevenue-5">Order No.</th>
          <th class="tbl-order-revenue-center" data-translate="tab5ordersrevenue-6">Date</th>
          <th class="tbl-order-revenue-right" data-translate="tab5ordersrevenue-7">Revenue</th>
        </tr>

        <!-- FOR LOOP SORTING DIFFERENT PURCHASE BUT SAME F_PIN -->

        <?php

          $arrayResult = array();
          foreach ($orders as $singleOrder){

            if (!isset($arrayResult[$singleOrder['TRANSACTION_ID']])){
              $current = new stdClass();
              $current->TRANSACTION_ID = $singleOrder['TRANSACTION_ID'];
              $current->PRICE = $singleOrder['PRICE'] * $singleOrder['AMOUNT'];
              $current->AMOUNT = $singleOrder['AMOUNT'];
              $current->CREATED_AT = $singleOrder['CREATED_AT'];
              $arrayResult[$singleOrder['TRANSACTION_ID']] = $current;

            }else{
              $current = $arrayResult[$singleOrder['TRANSACTION_ID']];
              $current->PRICE += $singleOrder['PRICE'] * $singleOrder['AMOUNT'];
              $current->AMOUNT += $singleOrder['AMOUNT'];
              $arrayResult[$singleOrder['TRANSACTION_ID']] = $current;
            }
          }

          $arrayResult = array_values($arrayResult);

        ?>

        <!-- FOR LOOP PURCHASE SORTERED BY F_PIN -->

        <?php foreach ($arrayResult as $singleOrder) :

          $recentOrders = json_decode(json_encode($singleOrder), true);

          $total_price = $recentOrders["PRICE"];
          $order_date = date_create($recentOrders["CREATED_AT"]); ?>
        
          <tr>
            <td class="tbl-order-revenue-left">
              <?= $recentOrders["TRANSACTION_ID"] ?>
            </td>
            <td class="tbl-order-revenue-center">
              <?= date_format($order_date,"d/m"); ?>
            </td>
            <td class="tbl-order-revenue-right">
              Rp <?= number_format($total_price,0,",",",") ?>
            </td>
          </tr>

        <?php endforeach; ?>
        
      </table>

      <?php else: ?>
        
        <p class="text-center small-text mt-5" data-translate="tab5ordersrevenue-8">Anda belum memiliki pesanan masuk.</p>

      <?php endif; ?>

    </div>
  </div>

  <input type="hidden" id="id_shop" value="<?= $id_shop ?>">
</body>

<!-- FOOTER -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.js"></script>
<script type="module" src="../assets/js/translate.js"></script>

<script>

	//  SCRIPT CHANGE LANGUAGE

	$(document).ready(function() {
		function changeLanguage(){

		var lang = localStorage.lang;	
		change_lang(lang);
		
		}

    if (localStorage.lang == 1){
      $(".tbl-calendar-date").each(function() {
        var text = $(this).val();
        text = text.replace("Today : ", "Hari ini : ");
        $(this).val(text);
      });
    }

		changeLanguage();
    $('body').show();
	});

  // SCRIPT DATEPICKER

  $('.datepicker').datepicker({
    format: "dd MM yyyy",
    autoclose: true,
  });

  function show_datepicker(){
    $(".datepicker").datepicker('show');
  }

  // ON CHANGE DATEPICKER CHANGE SUB DATE

  $('.datepicker').change(function() { 
    // $('.tbl-calendar-date').css('padding-left', '39px');

    for (var i=1; i<8; i++){
      var date = $(this).datepicker('getDate');
      date.setDate(date.getDate()+(-7+i)); 

      $('#date-text-'+i).text($.datepicker.formatDate("dd/mm", date));
    }

    // REFRESH GRAPH FROM XMLHTTPREQUEST

    var formData = new FormData();

    var id_shop = $('#id_shop').val();
    var new_date = $.datepicker.formatDate("yy-mm-dd", date);

    formData.append('id_shop', id_shop);
    formData.append('date', new_date);

    console.log(new_date)
    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function (){

      if (xmlHttp.readyState == 4 && xmlHttp.status == 200){

        var array_result = xmlHttp.responseText.split(",");
        console.log(array_result);

        $('#progress-1').css('width',(array_result[0]/1000000)*100+"%");
        $('#progress-2').css('width',(array_result[1]/1000000)*100+"%");
        $('#progress-3').css('width',(array_result[2]/1000000)*100+"%");
        $('#progress-4').css('width',(array_result[3]/1000000)*100+"%");
        $('#progress-5').css('width',(array_result[4]/1000000)*100+"%");
        $('#progress-6').css('width',(array_result[5]/1000000)*100+"%");
        $('#progress-7').css('width',(array_result[6]/1000000)*100+"%");
      }
    }
    xmlHttp.open("post", "../logics/tab5/update_order_revenue");
    xmlHttp.send(formData);

  });

</script>
</html>