<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Qiosk - Cart</title>

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>

    <!-- custom css -->
    <link href="../assets/css/style-cart.css?v=<?= time(); ?>" rel="stylesheet">

    <!-- font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400;1,500;1,600&display=swap" rel="stylesheet">

    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/cart.js?v=<?= time(); ?>"></script>

</head>

<body>
    <div class="container-fluid nav-bar bg-white">
        <div class=" bg-purple mb-3" id="header">
            <div class="row" style="background-color: #6945A5; padding: 10px 0 10px 0;">
                <div class="col-4">
                    <a onclick="goBack();">
                        <img src="../assets/img/tab5/Back-(White).png" style="width:30px">
                    </a>
                </div>
                <div class="col-4 text-center d-flex align-items-center justify-content-center text-white">
                    <span style="font-size: 1.0625rem;">Cart</span>
                </div>
                <div class="col-4"></div>
            </div>
        </div>

        <!-- cart/saved tab -->
        <div class="container-fluid mt-3 bg-white">
            <div class="row">
                <div onclick="changeTab('items');" id="cart-items-tab" class="col-6 p-2 text-center font-medium tab-active">
                    Your Cart
                </div>
                <div onclick="changeTab('saved');" id="cart-saved-tab" class="col-6 p-2 text-center font-medium">
                    Saved For Later
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid px-0" id="main-body">
        <!-- <div class="tab-content" id="tab-content"> -->
        <div class="tab-pane fade show active" id="your-cart" role="tabpanel" aria-labelledby="cart-tab">
            <div id="cart-body" class="d-none">
                <!-- shop items -->
                <div id="cart-items"></div>
                <div id="cart-saved" class="d-none"></div>

                <!-- voucher -->
                <div id="pricetag">
                    <div class="container-fluid px-4 py-2 voucher">
                        <div class="row">
                            <div class="col-6 font-semibold">
                                Voucher Qiosk
                            </div>
                            <div id="promo-code" class="col-6 text-end text-grey" onclick="enterPromoCode();">
                                Enter promo code >
                            </div>
                        </div>
                    </div>

                    <!-- total -->
                    <div class="container-fluid px-4 py-2">
                        <div class="row my-1">
                            <div id="total-item" class="col-6 font-medium"></div>
                            <div id="total-price" class="col-6 font-medium text-end"></div>
                        </div>
                        <div class="row my-1">
                            <div class="col-6 font-medium">
                                Delivery
                            </div>
                            <div id="delivery-cost" class="col-6 font-medium text-end"></div>
                        </div>
                        <div class="row my-1">
                            <div class="col-6 font-medium">
                                Total (Tax included)
                            </div>
                            <div id="total-price-tax-inc" class="col-6 font-medium text-end grand-total"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="cart-empty" class="row mx-5 my-5 text-center">
                <p>Your cart is empty!</p>
            </div>
        </div>

        <div class="tab-pane fade" id="saved" role="tabpanel" aria-labelledby="saved-tab">
        </div>
        <!-- </div> -->

        <div class="container-fluid py-3">
            <div class="row">
                <a>
                    <div class="col-12 text-center text-white">
                        Checkout
                    </div>
                </a>
            </div>
        </div>
    </div>
    <!-- checkout -->
    <div id="checkout-button" class="container-fluid checkout-btn py-3 d-none">
        <div class="row">
            <a href="checkout.php">
                <div class="col-12 text-center">
                    Checkout
                </div>
            </a>
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
</body>
<script>
    document.addEventListener("DOMContentLoaded", async function(event) {
        // Your code to run since DOM is loaded and ready
        await populateCart();
        if (countTotal('all') == 'Rp 0') {
            document.getElementById('checkout-button').classList.add('d-none');
        }
    });

    async function changeTab(tab) {
        if (tab == 'items') {
            document.getElementById('cart-items-tab').classList.add('tab-active');
            document.getElementById('cart-saved-tab').classList.remove('tab-active');
            document.getElementById('pricetag').classList.remove('d-none');
            await populateCart();
            if (countTotal('all') == 'Rp 0') {
                document.getElementById('checkout-button').classList.add('d-none');
            }
        } else {
            document.getElementById('cart-saved-tab').classList.add('tab-active');
            document.getElementById('cart-items-tab').classList.remove('tab-active');
            populateSaved();
        }
    }
</script>

</html>