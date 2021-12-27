<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$f_pin = $_GET['f_pin'];
?>

<!doctype html>
<html lang="en">

<head>
  <title>Timeline</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;1,100;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
  <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"> -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
  <!-- font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400;1,500;1,600&display=swap" rel="stylesheet">
  <script src="https://kit.fontawesome.com/c6d7461088.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="../assets/css/clean-switch.css" />
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="../assets/css/tab3-style.css?v=<?php echo time(); ?>" />
  <!-- <link rel="stylesheet" href="../assets/css/roboto.css" /> -->
  <link rel="stylesheet" href="../assets/css/tab1-style.css?random=<?= time(); ?>" />
  <link rel="stylesheet" href="../assets/css/paliopay.css?random=<?= time(); ?>" />

  <script src="../assets/js/xendit.min.js"></script>
  <script src="../assets/js/jquery.min.js"></script>
  <script src="../assets/js/jQueryRotate.js"></script>
  <script src="../assets/js/jquery.validate.js"></script>
  <script src="../assets/js/isInViewport.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="../assets/js/jquery.ui.touch-punch.min.js"></script>
  
  <style>
    @media screen and (max-height:667px) {
    #container-rating-description {
      max-height: 100px !important;
      overflow: auto;
    }

    .product-img {
      height: 200px !important;
      width:auto;
    }

    .prod-addtocart .col-3 {
      width: 33.33333333%;
    }

    .prod-addtocart .col-9 {
      width: 66.66666667%;
    }
  }
  </style>
</head>

<body>
  <img id="scroll-top" class="rounded-circle" src="../assets/img/ic_collaps_arrow.png" onclick="topFunction(true)">
  <div class="container-fluid">
    <div id="header-layout" class="sticky-top">
      <div id="header" class="row justify-content-between">
        <div class="col-9 search-col">
          <?php require('filter.php'); ?>
        </div>
        <div id="gear-div" class="col-3 d-flex align-items-center justify-content-center" style="padding-right: 9px; padding-left: 9px;">
          <a class="me-2" href="cart.php?v=<?= time(); ?>">
            <div class="position-relative">
              <img class="header-icon" src="../assets/img/icons/Shopping-Cart-(White).png">
              <span id='counter-here'></span>
            </div>
          </a>
          <a id="to-notifications" class="me-3" href="notifications.php">
          <div class="position-relative">
            <img class="header-icon mx-auto" src="../assets/img/icons/Shop Manager/App-Notification-(white).png">
            <span id='counter-notifs'></span>
          </div>
          </a>
        </div>
      </div>
      <div id="category-tabs" class="ms-2 small-text">
        <ul class="nav nav-tabs horizontal-slide" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="categoryFilter-all" data-bs-toggle="tab" role="tab">All</a>
          </li>
          <?php

          $filters = include_once($_SERVER['DOCUMENT_ROOT'] . '/qiosk_web/logics/fetch_products_category.php');

          for ($i = 0; $i < count($filters); $i++) {

            $idFilter = $filters[$i]["ID"];
            $nameFilter = $filters[$i]["NAME"];
            echo '<li class="nav-item">';

            echo '<a class="nav-link" id="categoryFilter-' . $idFilter . '" data-bs-toggle="tab" role="tab">' . $nameFilter . '</a>';
            echo '</li>';
          }

          ?>
        </ul>
      </div>
      <div id="story-container">
        <?php require('timeline_story_container.php'); ?>
      </div>
    </div>
    <div class="timeline" id="pbr-timeline">
      <?php //require('timeline_products.php'); 
      ?>
    </div>
    <div id="loader_message"></div>
  </div>

  <div class="modal fade" id="modal-addtocart" tabindex="-1" role="dialog" aria-labelledby="modal-addtocart" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content animate-bottom">
        <div class="modal-body p-0" id="modal-add-body" style="position: relative;">
        </div>
      </div>
    </div>
  </div>

  <!-- add to cart success modal -->
  <div class="modal fade" id="addtocart-success" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body">
          <h6>Product added to cart!</h6>
        </div>
        <div class="modal-footer">
          <button id="addtocart-success-close" type="button" class="btn btn-addcart" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <!-- add to cart success modal -->

  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script> -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
  <script src="../assets/js/tab5-collection.js?r=<?= time(); ?>"></script>
  <script src="../assets/js/update_counter.js?r=<?= time(); ?>"></script>
  <script src="../assets/js/script-filter.js?random=<?= time(); ?>"></script>
  <script src="../assets/js/profile-shop.js?random=<?= time(); ?>"></script>
  <script src="../assets/js/update-score-shop.js?random=<?= time(); ?>"></script>
  <script src="../assets/js/update-score.js?random=<?= time(); ?>"></script>
  
  <script src="../assets/js/script-timeline.js?random=<?= time(); ?>"></script> 
  <script src="../assets/js/wishlist.js?v=<?php echo time(); ?>"></script>
  <script>
    // $('#addtocart-success').on('hidden.bs.modal', function() {
    //   location.reload();
    // });

    if (localStorage.lang == 0) {

      $('input#query').attr('placeholder', 'Search');
    } else {
      $('input#query').attr('placeholder', 'Pencarian');
    }
  </script>

  <!-- <script src="../assets/js/paliopay-dictionary.js?random=<?= time(); ?>"></script>
  <script src="../assets/js/paliopay.js?random=<?= time(); ?>"></script> -->
</body>

</html>