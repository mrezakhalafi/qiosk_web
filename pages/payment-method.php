<!doctype html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Project</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link href="../assets/css/checkout-style.css?v=<?= time(); ?>" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="../assets/js/xendit.min.js"></script>
    <script src="../assets/js/jquery.min.js"></script>

<body style="background-color: white;">
    <div class="container-fluid" style="max-height: 93vh !important;">
        <div class="col-12">
            <div class="row" style="background-color: #6945A5; padding: 10px 0 10px 0; position: sticky; z-index: 10; top: 0;">
                <div class="col-4">
                    <a onclick="window.location = document.referrer;">
                        <img src="../assets/img/tab5/Back-(White).png" style="width:30px">
                    </a>
                </div>
                <div class="col-4 text-center d-flex align-items-center justify-content-center text-white">
                    <span>Payment</span>
                </div>
                <div class="col-4"></div>
            </div>
            <div class="row bg-white small-text p-2 pt-3 pb-4 mb-1">
                <div class="container">
                    <div class="col-12">
                        <script>
                            const showForm = (form_name) => {
                                if (form_name == 'ovo') {
                                    if (Object.values(document.getElementById('ovo-form').classList).includes('d-block')) {
                                        document.getElementById('ovo-button').innerHTML = 'OVO <i class="fas fa-caret-right"></i>';
                                        document.getElementById('ovo-form').classList = 'd-none'
                                    } else {
                                        document.getElementById('ovo-button').innerHTML = 'OVO <i class="fas fa-caret-down"></i>';
                                        document.getElementById('ovo-form').classList = 'd-block'
                                    }
                                } else if (form_name == 'cc') {
                                    if (Object.values(document.getElementById('cc-form').classList).includes('d-block')) {
                                        document.getElementById('cc-button').innerHTML = 'Credit Card <i class="fas fa-caret-right"></i>';
                                        document.getElementById('cc-form').classList = 'd-none'
                                    } else {
                                        document.getElementById('cc-button').innerHTML = 'Credit Card <i class="fas fa-caret-down"></i>';
                                        document.getElementById('cc-form').classList = 'd-block'
                                    }
                                }
                            }

                            const selectMethod = (method) => {
                                if (method == 'ovo') {
                                    localStorage.setItem('payment-method', 'OVO &gt;');
                                    window.location = document.referrer;
                                } else if (method == 'cc') {
                                    localStorage.setItem('payment-method', 'CARD &gt;');
                                    window.location = document.referrer;
                                }
                            }
                        </script>
                        <div class="row mb-3">
                            <div class="col fw-bold font-semibold">
                                <div onclick="selectMethod('ovo');" id="ovo-button">OVO <i class="fas fa-caret-right"></i></div>
                                <form class="d-none" id="ovo-form" name="ovoForm" method="post" style="border: 1px solid lightgrey">
                                    <fieldset id="fieldset-ovo">
                                        <div class="col p-3">
                                            <div class="row">Phone Number</div>
                                            <div class="row mb-2">
                                                <input maxlength="16" size="16" type="text" required id="phone-number" placeholder="e.g: +6282111234567" name="phoneNumber">
                                            </div>
                                        </div>
                                    </fieldset>
                                </form>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div id="bank-transfer-button" class="col fw-bold font-semibold">
                                Bank Transfer <i class="fas fa-caret-right"></i>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div id="credit-card-button" class="col fw-bold font-semibold">
                                <div class="mb-2" id="cc-button" onclick="selectMethod('cc');">Credit Card <i class="fas fa-caret-down"></i></div>
                                <form class="d-block" id="cc-form" name="creditCardForm" method="post" style="border: 1px solid lightgrey">
                                    <fieldset class="col py-3 px-4" id="fieldset-card">
                                        <div class="row font-semibold mb-3" style="font-size: 10px;">
                                            PAY WITH A NEW CARD
                                        </div>
                                        <div class="row px-2 py-2">
                                            <input class="col-12 ps-0 border-0 border-bottom" type="number" name="card-number" maxlength="16" id="card-number" placeholder="Card Number">
                                        </div>
                                        <div class="row px-2">
                                            <input class="col-5 ps-0 border-0 border-bottom me-3" type="text" name="card-name" id="card-name" placeholder="Name on Card">
                                            <input class="col-3 ps-0 border-0 border-bottom me-2" type="number" name="card-expiry" id="card-expiry" placeholder="Expiry">
                                            <input class="col-3 ps-0 border-0 border-bottom" type="number" name="card-cvv" id="card-cvv" placeholder="CVV">
                                        </div>
                                    </fieldset>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>

</html>