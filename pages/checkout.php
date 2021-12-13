<!doctype html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Project</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link href="../assets/css/checkout-style.css?v=<?= time(); ?>" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>

    <script src="../assets/js/xendit.min.js"></script>
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/cart.js?v=<?= time(); ?>"></script>

<body style="background-color: gainsboro;">
    <div class="container-fluid" style="max-height: 93vh !important;">
        <div class="col-12">
            <div class="row" style="background-color: #6945A5; padding: 10px 0 10px 0; position: sticky; z-index: 10; top: 0;">
                <div class="col-4">
                    <a onclick="window.location = document.referrer;">
                        <img src="../assets/img/tab5/Back-(White).png" style="width:30px">
                    </a>
                </div>
                <div class="col-4 text-center d-flex align-items-center justify-content-center text-white">
                    <span>Cart</span>
                </div>
                <div class="col-4"></div>
            </div>
            <div class="row bg-white small-text p-2 pt-3 pb-4 mb-1">
                <div class="container">
                    <div class="col-12">
                        <div class="row mb-3">
                            <div class="col fw-bold">
                                Delivery Address
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-9 fw-bold" id="receiver-name"></div>
                            <div class="col-3 text-end fw-bold orange-text" onclick="changeDeliveryAddress();">Change</div>
                        </div>
                        <div class="row gray-text">
                            <div id="delivery-address" class="col-6"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row bg-white small-text p-2 pt-3 pb-4 mb-1">
                <div class="container">
                    <div class="col-12">
                        <div class="row mb-3">
                            <div class="col fw-bold">
                                Delivery Options
                            </div>
                        </div>
                        <span id="delivery-options"></span>
                    </div>
                </div>
            </div>
            <div class="row bg-white small-text p-2">
                <div class="container">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-6 font-semibold">
                                Voucher Qiosk
                            </div>
                            <div class="col-6 text-end gray-text" onclick="enterPromoCode();">
                                Enter promo code >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row bg-white p-2" style="margin-top: 1px;">
                <div class="container">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-6 payment-method font-semibold">
                                Payment Method
                            </div>
                            <div class="col-6 text-end payment-method">
                                <div class="dropdown">
                                    <a href="payment-method.php" class="px-0 font-semibold" type="button">
                                        <script>document.writeln(localStorage.getItem('payment-method') || "QPay >")</script>
                                    </a>
                                    <!-- <a class="dropdown-toggle px-0 font-semibold" type="button" id="dropdownMenuSelectMethod" data-bs-toggle="dropdown" aria-expanded="false">
                                        QPay >
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li onclick="selectMethod(this);" class="dropdown-item">CARD</li>
                                        <li onclick="selectMethod(this);" class="dropdown-item">OVO</li>
                                        <li onclick="selectMethod(this);" class="dropdown-item">DANA</li>
                                        <li onclick="selectMethod(this);" class="dropdown-item">LINKAJA</li>
                                    </ul> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row bg-white p-2" style="margin-top: 1px;">
                <div class="container">
                    <div class="col-12">
                        <div class="row small-text pt-1 pb-1">
                            <div id="total-item" class="col-6 font-medium"></div>
                            <div id="total-price" class="col-6 text-end font-medium"></div>
                        </div>
                        <div class="row small-text pt-1 pb-1">
                            <div class="col-6 font-medium">
                                Delivery
                            </div>
                            <div id="delivery-cost" class="col-6 text-end"></div>
                        </div>
                        <div class="row pt-1 pb-1">
                            <div class="col-6 small-text font-medium">
                                Total (Tax Included)
                            </div>
                            <div id="total-price-tax-inc" class="col-6 font-medium grand-total text-end"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row text-center" style="opacity: 0;">
            <div class="small-text text-white p-3">
                Make Payment
            </div>
        </div>
        <div class="row text-center payment-button">
            <div class="small-text text-white p-3" onclick="palioPay();">
                Make Payment
            </div>
        </div>
    </div>


    <div class="modal fade" id="modal-payment" tabindex="-1" role="dialog" aria-labelledby="modal-addtocart" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body p-0" id="modal-payment-body">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-address" tabindex="-1" role="dialog" aria-labelledby="modal-addtocart" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body p-0" id="modal-address-body">
                </div>
            </div>
        </div>
    </div>


    <!-- FOOTER -->

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>

<script>
    document.addEventListener("DOMContentLoaded", function(event) {
        // Your code to run since DOM is loaded and ready
        payment();
        deliveryAddress().then((val) => {
            document.getElementById('receiver-name').innerHTML = `${JSON.parse(val).FIRST_NAME + JSON.parse(val).LAST_NAME}`;
            if (val == '') {
                window.open('/qiosk_web/pages/tab5-change-address', '_self')
            }
            document.getElementById('delivery-address').innerHTML = JSON.parse(val).ADDRESS;
        });
    });
</script>

</html>