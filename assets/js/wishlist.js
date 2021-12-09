function numberWithDots(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// payment with ovo
function getWishlist() {

    let formData = new FormData();
    formData.append("fpin", window.Android.getFPin());
    // formData.append("fpin", "024ef50e8c");

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "/qiosk_web/logics/get_wishlist");

    xhr.onload = function () {
        if (xhr.status != 200) { // analyze HTTP status of the response
            alert(`Error ${xhr.status}: ${xhr.statusText}`); // e.g. 404: Not Found

        } else { // show the result
            let responseObj = xhr.response;
            // console.table(JSON.parse(responseObj));
            JSON.parse(responseObj).forEach(element => {

                let wishlist_html = 
                '<div class="col-6 col-md-6 col-lg-4 col-xl-3 single-wishlist mb-1">'+
                `<img src="${element.THUMB_ID.split("|")[0]}" class="wishlist-images">`+
                '<img src="../assets/img/tab5/Add-to-Cart.png" class="add-to-cart">'+
                '<div class="row wishlist-desc gx-0">'+
                    `<b class="small-text">${element.NAME}</b>`+
                    `<p class="wishlist-price">Rp. ${numberWithDots(element.PRICE)}</p>`+
                '</div>'+
                '</div>';

                let wishlist_products = document.getElementById('wishlist-products');
                wishlist_products.innerHTML += wishlist_html;
            });
        }
    };

    xhr.send(formData);
}

// add product to wishlist
function addWishlist(product_code, element) {

    let star = element.querySelector('img').src;
    let operation;
    if (star == `${document.location.origin}/qiosk_web/assets/img/icons/Wishlist-fill.png`) {
        element.querySelector('img').src = '../assets/img/icons/Wishlist.png';
        operation = 'delete';
    } else {
        element.querySelector('img').src = '../assets/img/icons/Wishlist-fill.png'
        operation = 'add';
    }

    let formData = new FormData();
    formData.append("fpin", window.Android.getFPin());
    formData.append("code", product_code);
    formData.append('operation', operation);

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "/qiosk_web/logics/add_to_wishlist");

    xhr.onload = function () {
        if (xhr.status != 200) { // analyze HTTP status of the response
            alert(`Error ${xhr.status}: ${xhr.statusText}`); // e.g. 404: Not Found

        } else { // show the result
            let responseObj = xhr.response;
            console.log(responseObj);
        }
    };

    xhr.send(formData);
}

// SEARCH WISHLIST

function getWishlistQuery(query) {

    let formData = new FormData();
    formData.append("fpin", window.Android.getFPin());
    formData.append("query", query);
    // formData.append("fpin", "024ef50e8c");

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "/qiosk_web/logics/get_wishlist_query");

    xhr.onload = function () {
        if (xhr.status != 200) { // analyze HTTP status of the response
            alert(`Error ${xhr.status}: ${xhr.statusText}`); // e.g. 404: Not Found

        } else { // show the result
            let responseObj = xhr.response;
            // console.table(JSON.parse(responseObj));
            JSON.parse(responseObj).forEach(element => {

                let wishlist_html = 
                '<div class="col-6 col-md-6 col-lg-4 col-xl-3 single-wishlist mb-1">'+
                `<img src="${element.THUMB_ID.split("|")[0]}" class="wishlist-images">`+
                '<img src="../assets/img/tab5/Add-to-Cart.png" class="add-to-cart">'+
                '<div class="row wishlist-desc gx-0">'+
                    `<b class="small-text">${element.NAME}</b>`+
                    `<p class="wishlist-price">Rp. ${numberWithDots(element.PRICE)}</p>`+
                '</div>'+
                '</div>';

                let wishlist_products = document.getElementById('wishlist-products');
                wishlist_products.innerHTML += wishlist_html;
            });
        }
    };

    xhr.send(formData);
}