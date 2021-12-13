if (localStorage.getItem("cart") != null) {
    var cart = JSON.parse(localStorage.getItem("cart"));
} else {
    var cart = [];
}

window.addEventListener("storage", async function () {
    if (localStorage.getItem("cart") != null) {
        cart = JSON.parse(localStorage.getItem("cart"));
    } else {
        cart = [];
    }

    document.getElementById('cart-items').innerHTML = '';
    
    await populateCart();
    if (countTotal('all') == 'Rp 0') {
        document.getElementById('checkout-button').classList.add('d-none');
    }
}, false);

function getFpin() {
    let fpin;
    try {
        //android
        fpin = window.Android.getFPin();
    }
    catch (err) {
        //ios
        fpin = localStorage.getItem('f_pin');
    }

    return fpin;
}

function clearCart() {
    let item_count = 0;
    let item_to_remove_count = 0;
    cart.forEach(merchant => {
        item_count += merchant.items.length;
        item_to_remove_count += merchant.items.filter(item => item.selected == 'checked').length;
    })

    if (item_count == item_to_remove_count){
        localStorage.removeItem('cart');
    } else {
        cart.forEach(merchant => {
            merchant.items = merchant.items.filter(item => item.selected != 'checked');
        })

        localStorage.setItem("cart", JSON.stringify(cart));
    }
}

function numberWithDots(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function countTotal(merchant, tab_name){

    let totalPrice = 0;

    if(merchant == 'all'){
        cart.forEach(cartitem => {
            cartitem.items.forEach(item => {
                if (item.selected == 'checked') {
                    totalPrice += item.itemQuantity * item.itemPrice;
                }
            })
        })
    } else {
        cart.forEach(cartitem => {
            if (cartitem.merchant_name == merchant) {
                cartitem.items.forEach(item => {
                    if(tab_name == 'cart' && item.selected == 'checked'){
                        totalPrice += item.itemQuantity * item.itemPrice;
                    } else if(tab_name == 'saved' && item.selected != 'checked') {
                        totalPrice += item.itemQuantity * item.itemPrice;
                    }
                })
            }
        })
    }

    return "Rp " + numberWithDots(totalPrice);
};

function changeValue(id, mod, merchant, tab_name){
    if(mod == "add"){
        document.getElementById(id).value = parseInt(document.getElementById(id).value) + 1;
    } else {
        if (document.getElementById(id).value > 1){
            document.getElementById(id).value = parseInt(document.getElementById(id).value) - 1;
        }
    }

    cart.forEach(item => {
        item.items.forEach(item => {
            if (item.itemName == id.split('-')[0]) {
                item.itemQuantity = parseInt(document.getElementById(id).value);
                localStorage.setItem("cart", JSON.stringify(cart));
                countTotal(merchant, tab_name);
                tab_name == 'cart' ? populateCart() : populateSaved();
            }
        })

        if (item.merchant_name == merchant) {
            let total_merchant = document.getElementById("total-"+merchant.split(/\s+/).join('-')+tab_name == 'cart' ? 'cart' : 'saved');
            total_merchant.innerText = countTotal(merchant, tab_name);
        }
    })
};

function checkItem(item_name, tab_name){
    cart.forEach((merchant) => {
        merchant.items.find((element) => {
            // if item on saved moved to cart
            if (element.itemName == item_name && element.selected == undefined) {
                element.selected = 'checked' // move to cart
                document.getElementById('checkout-button').classList.remove('d-none');
                localStorage.setItem('cart', JSON.stringify(cart));
            
            // of item on item cart move to saved
            } else if (element.itemName == item_name && element.selected != undefined) {
                element.selected = undefined; // move to saved
                localStorage.setItem('cart', JSON.stringify(cart));

                // delete checkout button if item in cart 0
                if (countTotal('all') == 'Rp 0') {
                    document.getElementById('checkout-button').classList.add('d-none');
                }
            }
        })
    })

    populateCart();
    populateSaved();
    changeTab(tab_name);
}

function populateSaved(){
    let saved = JSON.parse(localStorage.getItem("cart"));
    saved.forEach(merchant => merchant.items = merchant.items.filter(item => item.selected == undefined));
    saved = saved.filter(merchant => merchant.items.length > 0);

    document.getElementById('cart-items').classList.add('d-none');
    document.getElementById('pricetag').classList.add('d-none');
    document.getElementById('checkout-button').classList.add('d-none');

    document.getElementById('cart-saved').classList.remove('d-none');

    let cartItems = document.getElementById('cart-saved');
    cartItems.innerHTML = '';

    if (saved.length > 0) {
        document.getElementById('cart-empty').classList.add('d-none');
        document.getElementById('cart-body').classList.remove('d-none');
        
        saved.slice().reverse().forEach(item => {
            let merchant_name = item.merchant_name;

            let html_shop = 
            `<div class="container-fluid p-4 shop">` +

                '<!-- shop name -->'+
                '<div class="row">'+
                    '<div class="col-6">'+
                        '<div class="row font-semibold store-name">'+
                            `<div class="col-2"><img class="verified" src="../assets/img/cart/Verified.png"></div><div class="col-10 px-1">${merchant_name}</div>`+
                        '</div>'+
                    '</div>'+
                    '<div class="col-6 align-items-center text-end small-text">'+
                        `<a href="tab3-profile?store_id=${item.items[0].store_id}&f_pin=${getFpin()}" target = "_self">`+
                            '<img class="view-store" src="../assets/img/cart/store_purple.png"> View store'+
                        '</a>'+
                    '</div>'+
                '</div>'+

                '<!-- item 1 -->'+
                `<div class="row mt-3" id="${merchant_name}-items-saved"></div>`+

            '</div>'+

            '<div class="container-fluid px-4 py-2">'+
                '<div class="row">'+
                    '<div class="col-6 font-semibold">'+
                        'Total'+
                    '</div>'+
                    `<div id="total-${merchant_name.split(/\s+/).join('-')}-saved" class="col-6 font-semibold text-end">`+
                        `${countTotal(merchant_name, 'saved')}`+
                    '</div>'+
                '</div>'+
            '</div>'+

            '<hr class="shop-border">'; 

            cartItems.innerHTML += html_shop;
            let shopItems = document.getElementById(`${merchant_name}-items-saved`);

            item.items.forEach(item => {

                function ext(url) {
                    return (url = url.substr(1 + url.lastIndexOf("/")).split('?')[0]).split('#')[0].substr(url.lastIndexOf(".") + 1);
                }

                let thumbnail_url;
                if(ext(item.thumbnail) != 'mp4'){
                    thumbnail_url = `<img class="product-img" src="${item.thumbnail}">`;
                } else {
                    thumbnail_url = `<video class="product-img" autoplay muted>
                        <source src="${item.thumbnail}" type="video/mp4" />
                    </video>`;
                }

                let html_item = 
                `<div class="row mt-3">`+
                    '<!-- img -->'+
                    '<div class="col-3">'+
                        `${thumbnail_url}`+
                    '</div>'+
                    '<!-- details -->'+
                    '<div class="col-8 col-details font-medium">'+
                        '<div class="ps-3">'+
                            `<span class="item-name">${item.itemName}</span>`+
                            `<div class="item-price">Rp ${numberWithDots(item.itemPrice)}</div>`+
                            `<div class="row">`+
                                `<div class="col-5">`+
                                    '<div class="input-group counter mt-2" style="width: 75px;">'+
                                        `<button class="btn btn-outline-secondary btn-decrease" type="button" onclick="changeValue('${item.itemName}-quantity', 'sub', '${merchant_name}', 'saved');">-</button>`+
                                                `<input id="${item.itemName}-quantity" type="number" maxlength="3" class="form-control text-center" min="1" value="${item.itemQuantity}">`+
                                        `<button class="btn btn-outline-secondary btn-increase" type="button" onclick="changeValue('${item.itemName}-quantity', 'add', '${merchant_name}', 'saved');">+</button>`+
                                    '</div>'+
                                `</div>`+
                                '<div class="col-6 d-flex align-items-end justify-content-center">'+
                                    `<div class="text-grey" onclick="checkItem('${item.itemName}', 'cart')">Move to Cart</div>`+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                    '<!-- delete item -->'+
                    '<div class="col-1 col-delete">'+
                        '<div class="delete-btn">'+
                            `<img onclick="deleteItem('${merchant_name}', '${item.itemName}', 'saved');" class="delete-icon" src="../assets/img/cart/Delete.png">`+
                        '</div>'+
                    '</div>'+
                '</div>';

                shopItems.innerHTML += html_item;
            })
        })
    } else {
        document.getElementById('cart-empty').classList.remove('d-none');
    }
}

function populateCart(mode){
    // get all item in the cart (your cart tab)
    let unsaved = JSON.parse(localStorage.getItem("cart"));
    unsaved.forEach(merchant => merchant.items = merchant.items.filter(item => item.selected == 'checked'));
    unsaved = unsaved.filter(merchant => merchant.items.length > 0);

    document.getElementById('cart-items').classList.remove('d-none');
    document.getElementById('cart-saved').classList.add('d-none');

    document.getElementById('cart-items').innerHTML = '';

    // if there are item(s) on your cart tab
    if (unsaved.length > 0) {

        document.getElementById('cart-empty').classList.add('d-none');
        document.getElementById('cart-body').classList.remove('d-none');
        document.getElementById('checkout-button').classList.remove('d-none');

        unsaved.slice().reverse().forEach(item => {
            let merchant_name = item.merchant_name;

            let cartItems = document.getElementById('cart-items');

            let html_shop = 
            `<div class="container-fluid p-4 shop">` +

                '<!-- shop name -->'+
                '<div class="row">'+
                    '<div class="col-6">'+
                        '<div class="row font-semibold store-name">'+
                            `<div class="col-2"><img class="verified" src="../assets/img/cart/Verified.png"></div><div class="col-10 px-1">${merchant_name}</div>`+
                        '</div>'+
                    '</div>'+
                    '<div class="col-6 align-items-center text-end small-text">'+
                        `<a href="tab3-profile?store_id=${item.items[0].store_id}&f_pin=${getFpin()}" target = "_self">`+
                            '<img class="view-store" src="../assets/img/cart/store_purple.png"> View store'+
                        '</a>'+
                    '</div>'+
                '</div>'+

                '<!-- item 1 -->'+
                `<div class="row mt-3" id="${merchant_name}-items-cart"></div>`+

            '</div>'+

            '<div class="container-fluid px-4 py-2">'+
                '<div class="row">'+
                    '<div class="col-6 font-semibold">'+
                        'Total'+
                    '</div>'+
                    `<div id="total-${merchant_name.split(/\s+/).join('-')}-cart" class="col-6 font-semibold text-end">`+
                        `${countTotal(merchant_name, 'cart')}`+
                    '</div>'+
                '</div>'+
            '</div>'+

            '<hr class="shop-border">'; 

            cartItems.innerHTML += html_shop;
            let shopItems = document.getElementById(`${merchant_name}-items-cart`);

            item.items.forEach(item => {

                function ext(url) {
                    return (url = url.substr(1 + url.lastIndexOf("/")).split('?')[0]).split('#')[0].substr(url.lastIndexOf(".") + 1);
                }

                let thumbnail_url;
                if(ext(item.thumbnail) != 'mp4'){
                    thumbnail_url = `<img class="product-img" src="${item.thumbnail}">`;
                } else {
                    thumbnail_url = `<video class="product-img" autoplay muted>
                        <source src="${item.thumbnail}" type="video/mp4" />
                    </video>`;
                }

                let html_item = 
                `<div class="row mt-3">`+
                    '<!-- img -->'+
                    '<div class="col-3">'+
                        `${thumbnail_url}`+
                    '</div>'+
                    '<!-- details -->'+
                    '<div class="col-8 col-details font-medium">'+
                        '<div class="ps-3">'+
                            `<span class="item-name">${item.itemName}</span>`+
                            `<div class="item-price">Rp ${numberWithDots(item.itemPrice)}</div>`+
                            `<div class="row">`+
                                `<div class="col-5">`+
                                    '<div class="input-group counter mt-2" style="width: 75px;">'+
                                        `<button class="btn btn-outline-secondary btn-decrease" type="button" onclick="changeValue('${item.itemName}-quantity', 'sub', '${merchant_name}', 'cart');">-</button>`+
                                                `<input id="${item.itemName}-quantity" type="number" maxlength="3" class="form-control text-center" min="1" value="${item.itemQuantity}">`+
                                        `<button class="btn btn-outline-secondary btn-increase" type="button" onclick="changeValue('${item.itemName}-quantity', 'add', '${merchant_name}', 'cart');">+</button>`+
                                    '</div>'+
                                `</div>`+
                                '<div class="col-6 d-flex align-items-end justify-content-center">'+
                                    `<div class="text-grey" onclick="checkItem('${item.itemName}', 'saved')">Save for later</div>`+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                    '<!-- delete item -->'+
                    '<div class="col-1 col-delete">'+
                        '<div class="delete-btn">'+
                            `<img onclick="deleteItem('${merchant_name}', '${item.itemName}', 'cart');" class="delete-icon" src="../assets/img/cart/Delete.png">`+
                        '</div>'+
                    '</div>'+
                '</div>';

                shopItems.innerHTML += html_item;
                payment();
            })
        })

    } else {
        document.getElementById('cart-empty').classList.remove('d-none');
        document.getElementById('pricetag').classList.add('d-none');
    }
};

async function payment() {

    let unsaved = JSON.parse(localStorage.getItem("cart"));
    unsaved.forEach(merchant => merchant.items = merchant.items.filter(item => item.selected == 'checked'));
    unsaved = unsaved.filter(merchant => merchant.items.length > 0);

    if (unsaved.length == 0){

        let cartBody = document.getElementById('cart-body');
        cartBody.classList.add('d-none');

        let checkoutButton = document.getElementById('checkout-button');
        checkoutButton.classList.add('d-none');

        let cartEmpty = document.getElementById('cart-empty');
        cartEmpty.classList.remove('d-none');

    } else {

        let rate = await shippingRate();
        let tax = 0;
        let delivery = rate.data.fixed_price;
        let totalPrice = 0;
        let totalItem = 0;

        // delivery options
        let time_detail = rate.data.time_detail;
        time_detail.forEach(td => {

            let html_delivery =
                '<div class="row mb-2" style="border: 1px solid lightgray">' +
                    '<div class="col-1 d-flex align-items-center justify-content-center p-0 delivery-options" style="margin-right: 5px;" onclick="checkThis(this);"><span style="height: 15px;width: 15px;background-color: transparent;border: 1px solid lightgray;border-radius: 50%;display: inline-block;"></span></div>' +
                    '<div class="col-9">' +
                        `<div class="row fw-bold">${td.service} ${delivery}</div>` +
                        `<div class="row gray-text">Delivered on or before ${td.time_delivery_end} ${td.service == 'same_day' ? 'today' : 'next day'}</div>` +
                    '</div>' +
                '</div>';


            document.getElementById("delivery-options") != null ? document.getElementById("delivery-options").innerHTML += html_delivery : {};
        });
        // checkThis(document.querySelector('.delivery-options'));

        unsaved.forEach(merchant => {
            merchant.items.forEach(item => {
                if(item.selected != undefined){
                    totalPrice += item.itemQuantity * item.itemPrice;
                    totalItem += parseInt(item.itemQuantity);
                }
            })
        })

        let totalPriceTaxInc = totalPrice + tax / 100 * totalPrice + delivery;
        localStorage.setItem('grand-total', totalPriceTaxInc);

        document.getElementById("total-item").innerHTML = `Sub-total ( ${totalItem} items )`;
        document.getElementById("total-price").innerHTML = `Rp ${numberWithDots(totalPrice)}`;
        document.getElementById("delivery-cost").innerHTML = `Rp ${numberWithDots(delivery)}`;
        document.getElementById("total-price-tax-inc").innerHTML = `Rp ${numberWithDots(totalPriceTaxInc)}`;

    }

}

async function deleteItem(merchant_name, product_name, tab_name) {

    let confirmationModal = new ConfirmModal(merchant_name, product_name);
    let response = await confirmationModal.question();

    if (response == true){

        // get items from selected merchant
        let items = cart.find(merchant => merchant.merchant_name == merchant_name).items;

        // get the index of deleted item
        let indexItem = items.indexOf(items.find(item => item.itemName == product_name));
        let indexMerchant = cart.indexOf(cart.find(merchant => merchant.merchant_name == merchant_name));

        // remove selected item
        cart.find(merchant => merchant.merchant_name == merchant_name).items.splice(indexItem, 1);

        // delete merchant from cart if no item
        if (cart.find(merchant => merchant.merchant_name == merchant_name).items.length == 0) {
            cart.splice(indexMerchant, 1);
        }

        // update localstorage
        localStorage.setItem('cart', JSON.stringify(cart));
        cart = JSON.parse(localStorage.getItem('cart'));
        countTotal(merchant_name, tab_name);
    }

    if (tab_name == 'cart'){
        populateCart();
    } else {
        populateSaved();
    }

}

function goBack() {
    if (window.Android) {
        window.Android.closeView();
    } else if(window.webkit){
        window.webkit.messageHandlers.messageHandler.postMessage({
            "message": "goBack"
        });
    } else {
        window.history.back();
    }
}

function selectMethod(e) {

    document.getElementById('dropdownMenuSelectMethod').innerHTML = `${e.innerHTML} >`;

}

function getMerchantAddress(merchant_name) {

    let dummy = JSON.parse('{"ID":1,"STORE_CODE":"1","ADDRESS":"Jl. Sultan Iskandar Muda No.6C","VILLAGE":"Kebayoran Lama","DISTRICT":"Kebayoran Lama","CITY":"Jakarta Selatan","PROVINCE":"DKI Jakarta","ZIP_CODE":"12240","PHONE_NUMBER":"081987654321","NOTE":"Lantai 6 divisi IT"}')
    return dummy;
}

function getItemDetail(item_name) {

    let dummy = JSON.parse('{"ID":1,"PRODUCT_CODE":"1","LENGTH":12,"WIDTH":13,"HEIGHT":14,"IS_FRAGILE":0,"WEIGHT":1,"CATEGORY":"Snack"}');
    return dummy;
}

function selectedItems() {
    let new_cart = [];
    cart.forEach(merchant => {
        let new_object = {}; //objek merchant
        merchant.items.forEach(item => {
            new_object.merchant_name = merchant.merchant_name;
            if (new_object.items == undefined) { new_object.items = [] }
            new_object.items.push(item);
        })
        if (Object.entries(new_object).length !== 0) { new_cart.push(new_object) }
    })

    return new_cart;
}

function shippingRate() {

    let formData = new FormData();
    let rate = 0;

    let merchantCount = selectedItems().length;
    for (let i = 0; i < merchantCount; i++) {

        // get merchant address
        let origin = getMerchantAddress(selectedItems("checked")[i].merchant_name);
        let items = selectedItems("checked")[0].items;

        for (let item of items) {

            let itemDetail = getItemDetail(item.itemName);

            // origin
            formData.append("address_origin", origin.ADDRESS);
            formData.append("province_origin", origin.PROVINCE);
            formData.append("city_origin", origin.CITY);
            formData.append("district_origin", origin.DISTRICT);
            formData.append("zip_code_origin", origin.ZIP_CODE);

            // destination
            formData.append("address_destination", "jl sultan iskandar muda no 6c");
            formData.append("province_destination", "dki jakarta");
            formData.append("city_destination", "jakarta selatan");
            formData.append("district_destination", "kebayoran lama");
            formData.append("zip_code_destination", "12240");

            // items
            formData.append("weight_items", itemDetail.WEIGHT);
            formData.append("length_items", itemDetail.LENGTH);
            formData.append("width_items", itemDetail.WIDTH);
            formData.append("height_items", itemDetail.HEIGHT);

            return new Promise(function (resolve, reject) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "/qiosk_web/logics/shipment_api/paxel_shipments_rate");

                xhr.onload = function () {
                    if (this.status >= 200 && this.status < 300) {
                        resolve(JSON.parse(xhr.response));
                    } else {
                        reject({
                            status: this.status,
                            statusText: xhr.statusText
                        });
                    }
                };
                xhr.onerror = function () {
                    reject({
                        status: this.status,
                        statusText: xhr.statusText
                    });
                };

                xhr.send(formData);
            });

        };

    }

}

function checkThis(e) {

    let verif = '<img src="../assets/img/tab5/Verified.png" alt="verified-icon" class="small-icon">';
    let deliv = document.querySelectorAll('.delivery-options');
    deliv.forEach(d => {
        d.innerHTML = `<span style="height: 15px;width: 15px;background-color: transparent;border: 1px solid lightgray;border-radius: 50%;display: inline-block;"></span>`;
    });

    e.innerHTML = verif;
}


// card payment template
var cardModalHtml =
    '<div id="three-ds-container" style="display: none;">' +
    '   <iframe id="sample-inline-frame" name="sample-inline-frame" width="100%" height="400"> </iframe>' +
    '</div>' +
    '<form id="credit-card-form" name="creditCardForm" method="post">' +
    '<fieldset id="fieldset-card">'+
    '<div class="col p-3">'+
    '  <div class="row">' +
    '    Credit Card Number' +
    '  </div>' +
    '  <div class="row mb-2">' +
    '    <input maxlength="16" size="16" type="text" required class="form-control" id="credit-card-number" placeholder="e.g: 4000000000000002" name="creditCardNumber">' +
    '  </div>' +
    '  <div class="row mb-4">' +
    '    <div class="col-3">' +
        '  <div class="row">' +
        '    Month' +
        '  </div>' +
    '      <div class="row">' +
    '        <select required class="form-control form-control fs-16 fontRobReg" id="credit-card-exp-month" placeholder="MM" style="border-color: #608CA5" name="creditCardExpMonth">' +
    '          <option>01</option>' +
    '          <option>02</option>' +
    '          <option>03</option>' +
    '          <option>04</option>' +
    '          <option>05</option>' +
    '          <option>06</option>' +
    '          <option>07</option>' +
    '          <option>08</option>' +
    '          <option>09</option>' +
    '          <option>10</option>' +
    '          <option>11</option>' +
    '          <option>12</option>' +
    '        </select>' +
    '      </div>' +
    '    </div>' +
    '    <div class="col-5 mx-1">' +
        '  <div class="row">' +
        '    Year' +
        '  </div>' +
    '      <div class="row">' +
    '        <input maxlength="4" size="4" type="text" required class="form-control form-control fs-16 fontRobReg" id="credit-card-exp-year" placeholder="YYYY" style="border-color: #608CA5" name="creditCardExpYear">' +
    '      </div>' +
    '    </div>' +
    '    <div class="col-3">' +
    '  <div class="row">' +
    '    CVV' +
    '  </div>' +
    '      <div class="row">' +
    '        <input maxlength="3" size="3" type="text" required class="form-control form-control fs-16 fontRobReg" id="credit-card-cvv" placeholder="123" style="border-color: #608CA5" name="creditCardCvv">' +
    '      </div>' +
    '    </div>' +
    '  </div>' +
    '<div class="row">' +
    '  <input class="pay-button" onclick="return toSubmit();" type="submit" id="pay-with-credit-card" value="Pay" name="payWithCreditCard">' +
    '</div>'+
    '</div>'+
    '</fieldset>'+
    '</form>';

// ovo payment template
var ovoModalHtml =
    '<form id="ovo-form" name="ovoForm" method="post">' +
    '<fieldset id="fieldset-ovo">' +
        '<div class="col p-3">'+
        '  <div class="row">Phone Number</div>' +
        '  <div class="row mb-2">' +
        '    <input maxlength="16" size="16" type="text" required id="phone-number" placeholder="e.g: +6282111234567" name="phoneNumber">' +
        '  </div>' +
        '  <div class="row">' +
        '       <input class="pay-button" onclick="return toSubmitOVO();" type="submit" id="pay-with-ovo" value="Pay" name="payWithOVO">' +
        '  </div>'+
        '</div>'+
    '</fieldset>'+
    '</form>';

// dana payment template
var danaModalHtml =
    '<form id="dana-form" name="danaForm" method="post">' +
    '<fieldset id="fieldset-dana">' +
    '   <div class="col p-3">'+
    '       <div class="row">'+
    '           <input class="pay-button" onclick="return toSubmitDANA();" type="submit" id="pay-with-dana" class="col-md-12 simple-modal-button-green py-1 px-3 m-0 my-4 fs-16" value="Pay" name="payWithDANA">' +
    '       </div>'+
    '   </div>'+
    '</fieldset>'+
    '</form>';

// linkaja payment template
var linkajaModalHtml =
    '<form id="linkaja-form" name="linkajaForm" method="post">' +
    '<fieldset id="fieldset-linkaja">' +
    '   <div class="col p-3">' +
    '       <div class="row">' +
    '           <input class="pay-button" onclick="return toSubmitLINKAJA();" type="submit" id="pay-with-linkaja" class="col-md-12 simple-modal-button-green py-1 px-3 m-0 my-4 fs-16" value="Pay" name="payWithLINKAJA">' +
    '       </div>' +
    '   </div>' +
    '</fieldset>' +
    '</form>';

// payment with ovo
function toSubmitOVO() {
    event.preventDefault();

    var js = {
        phone_number: $('#phone-number').val(),
        amount: localStorage.getItem('grand-total'),
    };

    // var callbackURL = this.callbackURL;
    // var amount = this.price;

    $.post("../logics/paliobutton/php/paliopay_ovo",
        js,
        function (data, status) {
            try {
                if (data == "SUCCEEDED") {
                    clearCart();
                    var items = JSON.stringify(cart);
                    var base64items = btoa(items);
                    var fpin = getFpin();

                    let purchased_cart = [];
                    cart.forEach(merchant => {
                        merchant.items.forEach(product => {
                            if (product.selected == 'checked') {
                                let p = {};
                                p.p_code = product.itemCode;
                                p.price = product.itemPrice;
                                p.amount = product.itemQuantity;
                                purchased_cart.push(p);
                            }
                        })
                    })

                    postForm("../logics/paliobutton/php/store_payment", { fpin: fpin, method: "OVO", status: "success", items: base64items, cart: btoa(JSON.stringify(purchased_cart)) });
                } else {
                    alert("Credit card transaction failed");
                    // showSuccessModal(dictionary.checkout.notice.failed[defaultLang], "OVO");
                }
            } catch (err) {
                // console.log(err);
                alert("Error occured");
                // showSuccessModal(dictionary.checkout.notice.error[defaultLang], "OVO");
            }
        }
    );

    // alert("Please finish your payment.");
}

// payment with dana
function toSubmitDANA() {
    event.preventDefault();

    var js = {
        // callback: this.callbackURL,
        callback: "http://202.158.33.26/paliobutton/php/close",
        amount: localStorage.getItem('grand-total'),
    };

    $.post("../logics/paliobutton/php/paliopay_dana",
        // $.post("/test/paliopay_dana",
        js,
        function (data, status) {
            try {
                var response = JSON.parse(data);

                checkEwallet(response.id);

                window.open(response.actions.desktop_web_checkout_url, "_blank");
                // console.log(response.actions.desktop_web_checkout_url);
            } catch (err) {
                // console.log(err);
                alert("Error occured");
                // showSuccessModal(dictionary.checkout.notice.error[defaultLang], "DANA");
            }
        }
    );
}

// payment with linkaja
function toSubmitLINKAJA() {
    event.preventDefault();

    var js = {
        // callback: this.callbackURL,
        callback: "http://202.158.33.26/paliobutton/php/close",
        amount: localStorage.getItem('grand-total'),
    };

    $.post("../logics/paliobutton/php/paliopay_linkaja",
        js,
        function (data, status) {
            try {
                var response = JSON.parse(data);

                checkEwallet(response.id);

                window.open(response.actions.desktop_web_checkout_url, "_blank");
                // console.log(response.actions.desktop_web_checkout_url);
            } catch (err) {
                // console.log(err);
                alert("Error occured");
                // showSuccessModal(dictionary.checkout.notice.error[defaultLang], "LINKAJA");
            }
        }
    );
}

// check ewallet payment status
function checkEwallet(id) {
    // 1. Create a new XMLHttpRequest object
    let xhr = new XMLHttpRequest();

    // 2. Configure it: GET-request for the URL /article/.../load
    xhr.open('GET', '../logics/ewallet_check?id=' + id);
    // xhr.open('GET', '/test/ewallet_check?id=' + id);

    xhr.responseType = 'json';

    // 3. Send the request over the network
    xhr.send();

    // 4. This will be called after the response is received
    xhr.onload = async function () {
        if (xhr.status != 200) { // analyze HTTP status of the response
            alert(`Error ${xhr.status}: ${xhr.statusText}`); // e.g. 404: Not Found
            // showSuccessModal(dictionary.checkout.notice.error[defaultLang], "");

        } else { // show the result
            let responseObj = xhr.response;
            // console.log(responseObj);

            if (responseObj.status == "SUCCEEDED") {
                // alert(`Payment received!`); // response is the server response
                if (responseObj.channel_code == "ID_DANA") {
                    var method = "DANA";
                } else if (responseObj.channel_code == "ID_LINKAJA") {
                    var method = "LINKAJA";
                }
                clearCart();
                var items = JSON.stringify(cart);
                var base64items = btoa(items);
                var fpin = getFpin();

                let purchased_cart = [];
                cart.forEach(merchant => {
                    merchant.items.forEach(product => {
                        if (product.selected == 'checked') {
                            let p = {};
                            p.p_code = product.itemCode;
                            p.price = product.itemPrice;
                            p.amount = product.itemQuantity;
                            purchased_cart.push(p);
                        }
                    })
                })

                postForm("../logics/paliobutton/php/store_payment", { fpin: fpin, method: method, status: "success", items: base64items, cart: btoa(JSON.stringify(purchased_cart)) });

            } else {
                checkEwallet(id);
            }
            // alert(`Done, got ${xhr.response.length} bytes`); // response is the server response
        }
    };

    xhr.onerror = function () {
        alert("Request failed");
        // showSuccessModal(dictionary.checkout.notice.error[defaultLang], "OVO");
    };
}

// xendit cc functions
function toSubmit() {
    event.preventDefault();
    let fieldset = document.getElementById('fieldset-card');
    fieldset.setAttribute('disabled', 'disabled');

    // document.getElementById("credit-card-form").classList.add('d-none');

    //dev
    Xendit.setPublishableKey('xnd_public_development_QToOEG2Dx1gvrMjuOjwbWKcOttQTwjhPtjI3JYUMzv7mzAzRTmT9iHQonH12');
    //prod
    // Xendit.setPublishableKey('xnd_public_production_qoec6uRBSVSb4n0WwIijVZgDJevwSZ5xKuxaTRh4YBix0nMSsKgxi226yxtTd7');

    var tokenData = getTokenData();

    Xendit.card.createToken(tokenData, xenditResponseHandler);
}

function postForm(path, params, method) {
    method = method || 'post';

    var form = document.createElement('form');
    form.setAttribute('method', method);
    form.setAttribute('action', path);

    for (var key in params) {
        if (params.hasOwnProperty(key)) {
            var hiddenField = document.createElement('input');
            hiddenField.setAttribute('type', 'hidden');
            hiddenField.setAttribute('name', key);
            hiddenField.setAttribute('value', params[key]);

            form.appendChild(hiddenField);
        }
    }

    document.body.appendChild(form);
    form.submit();
}

function xenditResponseHandler(err, creditCardCharge) {
    if (err) {
        console.log(err);
        return displayError(err);
        // console.log(err);
    }

    if (creditCardCharge.status === 'APPROVED' || creditCardCharge.status === 'VERIFIED') {
        console.log("success");
        displaySuccess(creditCardCharge);
    } else if (creditCardCharge.status === 'IN_REVIEW') {
        window.open(creditCardCharge.payer_authentication_url, 'sample-inline-frame');
        $('.overlay').show();
        $('#three-ds-container').show();
    } else if (creditCardCharge.status === 'FRAUD') {
        displayError(creditCardCharge);
    } else if (creditCardCharge.status === 'FAILED') {
        displayError(creditCardCharge);
    }
}

function displayError(err) {
    alert('Request Credit Card Charge Failed');
    $('#three-ds-container').hide();
    $('.overlay').hide();
    let fieldset = document.getElementById('fieldset-card');
    fieldset.removeAttribute('disabled');
    // showSuccessModal(dictionary.checkout.notice.error[defaultLang], "");
};

function displaySuccess(creditCardCharge) {
    var $form = $('#credit-card-form');
    $('#three-ds-container').hide();
    $('.overlay').hide();

    var js = {
        token_id: creditCardCharge.id,
        amount: localStorage.getItem('grand-total'),
        cvv: $form.find('#credit-card-cvv').val()
    };
    var items = JSON.stringify(cart);
    var base64items = btoa(items);
    var fpin = getFpin();

    let purchased_cart = [];
    cart.forEach(merchant => {
        merchant.items.forEach(product => {
            if(product.selected == 'checked'){
                let p = {};
                p.p_code = product.itemCode;
                p.price = product.itemPrice;
                p.amount = product.itemQuantity;
                purchased_cart.push(p);
            }
        })
    })

    // if (userAgent) {
    //     var fpin = getFpin();
    // } else {
    //     var fpin = "test";
    // }

    $.post("../logics/paliobutton/php/paliopay",
        js,
        function (data, status) {
            try {
                if (data.status == "CAPTURED") {
                    clearCart();
                    postForm("../logics/paliobutton/php/store_payment", { fpin: fpin, method: "card", status: "success", items: base64items, cart: btoa(JSON.stringify(purchased_cart)) });
                } else {
                    alert("Credit card transaction failed");
                    let fieldset = document.getElementById('fieldset-card');
                    fieldset.removeAttribute('disabled');
                }
            } catch (err) {
                console.log(err);
                alert("Error occured");
                let fieldset = document.getElementById('fieldset-card');
                fieldset.removeAttribute('disabled');
            }
        }, 'json'
    );
}

function getTokenData() {
    var $form = $('#credit-card-form');
    return {
        // amount: $form.find('#credit-card-amount').val(),
        amount: localStorage.getItem('grand-total'),
        card_number: $form.find('#credit-card-number').val(),
        card_exp_month: $form.find('#credit-card-exp-month').val(),
        card_exp_year: $form.find('#credit-card-exp-year').val(),
        card_cvn: $form.find('#credit-card-cvv').val(),
        is_multiple_use: false,
        should_authenticate: true
    };
}

// summmon payment modal
async function palioPay() {
    this.myModal = new SimpleModal();

    try {
        const modalResponse = await myModal.question();
    } catch (err) {
        console.log(err);
    }
}

"use strict";

// payment modal
class SimpleModal {

    constructor(modalTitle) {
        this.modalTitle = "title";
        this.parent = document.body;

        this.modal = document.getElementById('modal-payment-body');
        this.modal.innerHTML = "";

        this._createModal();
    }

    question() {
        return new Promise((resolve, reject) => {
            this.closeButton.addEventListener("click", () => {
                resolve(null);
                this._destroyModal();
            })
        })
    }

    _createModal() {

        // Message window
        const window = document.createElement('div');
        window.classList.add('container');
        this.modal.appendChild(window);

        // Main text
        const text = document.createElement('span');
        text.setAttribute("id", "payment-form");

        // let payment_method = document.getElementById('dropdownMenuSelectMethod').innerHTML;
        let payment_method = localStorage.getItem('payment-method');
        if (payment_method == "CARD &gt;"){
            text.innerHTML = cardModalHtml;
        } else if (payment_method == "OVO &gt;") {
            text.innerHTML = ovoModalHtml;
        } else if (payment_method == "DANA &gt;") {
            text.innerHTML = danaModalHtml;
        } else if (payment_method == "LINKAJA &gt;") {
            text.innerHTML = linkajaModalHtml;
        } else {
            text.innerHTML = cardModalHtml;
        }
        
        window.appendChild(text);

        // Let's rock
        $('#modal-payment').modal('show');
    }

    _destroyModal() {
        this.parent.removeChild(this.modal);
        delete this;
    }
}

// delete modal
class ConfirmModal {

    constructor(merchant_name, product_name) {

        this.html = 
            '<form method="post">' +
            '<fieldset>' +
            '   <div class="col p-3">' +
            '       <div class="row">' +
            `           Delete this item from your cart?` +
            '       </div>' +
            '       <div class="row">' +
            `           <button id="confirm-delete" class="col-md-12 py-1 px-3 m-0 my-1 fs-16">Delete</button>` +
            '       </div>' +
            '   </div>' +
            '</fieldset>' +
            '</form>';

        this.parent = document.body;
        this.modal = document.getElementById('modal-payment-body');
        this.modal.innerHTML = " ";

        this._createModal();
    }

    question() {
        this.delete_button = document.getElementById('confirm-delete');
        
        return new Promise((resolve, reject) => {
            this.delete_button.addEventListener("click", () => {
                event.preventDefault();
                resolve(true);
                this._destroyModal();
            })
        })
    }

    _createModal() {

        // Message window
        const window = document.createElement('div');
        window.classList.add('container');
        this.modal.appendChild(window);

        // Main text
        const text = document.createElement('span');
        text.setAttribute("id", "payment-form");
        text.innerHTML = this.html;
        window.appendChild(text);

        // Let's rock
        $('#modal-payment').modal('show');
    }

    _destroyModal() {
        $('#modal-payment').modal('hide');
    }
}

// input promo code modal
class PromoModal {

    constructor() {

        this.html =
            '<form method="post">' +
            '<fieldset>' +
            '   <div class="col p-3">' +
            '       <div class="row font-semibold">' +
            `           Insert Promo Code` +
            '       </div>' +
            '       <div class="row d-flex align-items-center justify-content-end">' +
            `           <input type="text" id="input-promo" class="position-relative py-3 px-3 m-0 my-1 fs-16" style="border: 1px solid lightgrey;">` +
            `           <span id="confirm-promo" class="position-absolute font-semibold  py-1 px-3 m-0 my-1 fs-16" style="width: auto; background-color: transparent; color: black;">Apply</span>` +
            '       </div>' +
            '   </div>' +
            '</fieldset>' +
            '</form>';

        this.parent = document.body;
        this.modal = document.getElementById('modal-payment-body');
        this.modal.innerHTML = " ";

        this._createModal();
    }

    question() {
        this.delete_button = document.getElementById('confirm-promo');

        return new Promise((resolve, reject) => {
            this.delete_button.addEventListener("click", () => {
                event.preventDefault();
                resolve(document.getElementById('input-promo').value);
                this._destroyModal();
            })
        })
    }

    _createModal() {

        // Message window
        const window = document.createElement('div');
        window.classList.add('container');
        this.modal.appendChild(window);

        // Main text
        const text = document.createElement('span');
        text.setAttribute("id", "payment-form");
        text.innerHTML = this.html;
        window.appendChild(text);

        // Let's rock
        $('#modal-payment').modal('show');
    }

    _destroyModal() {
        $('#modal-payment').modal('hide');
    }
}


async function enterPromoCode() {
    let confirmationModal = new PromoModal();
    let response = await confirmationModal.question();

    document.getElementById('promo-code').innerHTML = `<b>${response}</b>`;

}

function pullRefresh() {
    if (window.Android && $('#your-cart').scrollTop() == 0) {
      window.scrollTo(0, document.body.scrollHeight - (document.body.scrollHeight - 3));
    }
  }

$(function() {
    $('#your-cart').scroll(function() {
        console.log($(this).scrollTop());
    })

    pullRefresh();
})

// change/get address modal
function deliveryAddress(address='') {
    let formData = new FormData();
    formData.append("fpin", getFpin());
    formData.append("address", address);

    return new Promise(function (resolve, reject) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "/qiosk_web/logics/get_delivery_address");

        xhr.onload = function () {
            if (this.status >= 200 && this.status < 300) {
                resolve(xhr.response);
            } else {
                reject({
                    status: this.status,
                    statusText: xhr.statusText
                });
            }
        };
        xhr.onerror = function () {
            reject({
                status: this.status,
                statusText: xhr.statusText
            });
        };

        xhr.send(formData);
    });
}

class AddressModal {

    constructor(address) {
        this.delivery_address = address;

        this.html =
            '<form method="post">' +
            '<fieldset>' +
            '   <div class="col p-3">' +
            '       <div class="row">' +
            `           Insert delivery address` +
            '       </div>' +
            '       <div class="row">' +
            `           <input type="text" id="input-address" class="col-md-12 simple-modal-button-green py-1 px-3 m-0 my-1 fs-16" value='${this.delivery_address}'>` +
            `           <button id="confirm-address" class="col-md-12 simple-modal-button-green py-1 px-3 m-0 my-1 fs-16">OK</button>` +
            '       </div>' +
            '   </div>' +
            '</fieldset>' +
            '</form>';

        this.parent = document.body;
        this.modal = document.getElementById('modal-address-body');
        this.modal.innerHTML = " ";

        this._createModal();
    }

    static async build() {
        let address = await deliveryAddress();
        return new AddressModal(address);
    }

    _createModal() {

        // Message window
        const window = document.createElement('div');
        window.classList.add('container');
        this.modal.appendChild(window);

        // Main text
        const text = document.createElement('span');
        text.setAttribute("id", "address-form");
        text.innerHTML = this.html;
        window.appendChild(text);

        this.delete_button = document.getElementById('confirm-address');
        this.delete_button.addEventListener("click", () => {
            let new_delivery_address = document.getElementById('input-address').value;
            event.preventDefault();
            deliveryAddress(new_delivery_address);
            document.getElementById('delivery-address').innerHTML = new_delivery_address;
            this._destroyModal();
        });

        // Let's rock
        $('#modal-address').modal('show');
    }

    _destroyModal() {
        $('#modal-address').modal('hide');
    }
}

async function changeDeliveryAddress() {
    // let response = await AddressModal.build();
    window.open('/qiosk_web/pages/tab5-change-address' , '_self')
}