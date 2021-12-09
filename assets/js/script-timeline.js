function postData(actionUrl, method, data) {
  var mapForm = $('<form id="mapform" action="' + actionUrl + '" method="' + method.toLowerCase() + '"></form>');
  for (var key in data) {
    if (data.hasOwnProperty(key)) {
      mapForm.append('<input type="hidden" name="' + key + '" id="' + key + '" value="' + data[key] + '" />');
    }
  }
  $('body').append(mapForm);
  mapForm.submit();
}

var ua = window.navigator.userAgent;
var iOS = !!ua.match(/iPad/i) || !!ua.match(/iPhone/i);
var webkit = !!ua.match(/WebKit/i);
var iOSSafari = iOS && webkit && !ua.match(/CriOS/i);
var palioBrowser = !!ua.match(/PalioBrowser/i);
var isChrome = !!ua.match(/Chrome/i);

// $('.carousel').carousel({
//   pause: true,
//   interval: false
// });

var didScroll;
var isSearchHidden = true;
var lastScrollTop = 0;
var delta = 3;
var navbarHeight = $('#header-layout').outerHeight();
var topPosition = 0;
var STORE_ID = "";
var FILTERS = "";

function hasScrolled() {
  var st = $(this).scrollTop();

  // Make sure they scroll more than delta
  if (Math.abs(lastScrollTop - st) <= delta)
    return;

  // If they scrolled down and are past the navbar, add class .nav-up.
  // This is necessary so you never see what is "behind" the navbar.
  if (st > lastScrollTop) {
    if (topPosition - (st - lastScrollTop) < -navbarHeight) {
      topPosition = -navbarHeight;
    } else {
      topPosition = topPosition - (st - lastScrollTop);
    }

    const tp = '' + topPosition + "px";

    // Scroll Down
    $('#header-layout').css('top', tp);
  } else {
    if (topPosition - (st - lastScrollTop) > 0) {
      topPosition = 0;
    } else {
      topPosition = topPosition - (st - lastScrollTop);
    }

    const tp = '' + topPosition + "px";
    // Scroll Up
    if (st + $(window).height() < $(document).height()) {
      $('#header-layout').css('top', tp);
    }
  }

  lastScrollTop = st;
}

setInterval(function () {
  if (didScroll) {
    hasScrolled();
    didScroll = false;
  }
}, 10);

function headerOut() {
  $('#searchFilter').addClass('d-none');
  navbarHeight = $('#header-layout').outerHeight();
  $('#header-layout').css('top', '0px');
  isSearchHidden = true;
};

function headerOutAndReset() {
  $("#mic").attr("src", "../assets/img/action_mic.png");
  $('#query').val('');
  $('#switchAll').prop('checked', checked);
  setFilterCheckedAll(true);
  $('#searchFilter').addClass('d-none');
  navbarHeight = $('#header-layout').outerHeight();
  $('#header-layout').css('top', '0px');
  isSearchHidden = true;
};

// $('#header').click(function () {
//   $(document).scrollTop(0);
//   if ($('#searchFilter').hasClass('d-none')) {
//     $('#searchFilter').removeClass('d-none');
//     isSearchHidden = false;
//   } else {
//     $('#searchFilter').addClass('d-none');
//     isSearchHidden = true;
//     const query = $('#query').val();

//     if (!isFilterCheckedAny()) {
//       resetFilter();
//     } else
//     if (isFilterCheckedAny() || query != "") {
//       searchFilter();
//     } else if (query == "") {
//       var url_string = window.location.href;
//       var url = new URL(url_string);
//       var paramValue = url.searchParams.get("query");
//       if (paramValue != null) {
//         searchFilter();
//       }
//     }
//   }
//   navbarHeight = $('#header-layout').outerHeight();
//   $('#header-layout').css('top', '0px');
//   $('#gear').rotate({
//     angle: 0,
//     animateTo: 180
//   });
// });


function checkVideoViewport() {
  var pattern = /(?:^|\s)simple-modal-button-green(?:\s|$)/
  if (document.activeElement.className.match(pattern)) {
    return;
  }

  if (palioBrowser && isChrome) {
    $('.timeline-main .carousel-item video, .timeline-image video').each(function () {
      if ($(this).is(":in-viewport") && $('#modal-addtocart').not('.show')) {
        // pause carousel when video is playing
        $(this).off("play");
        $(this).on("play", function (e) {
          $(this).closest(".carousel").carousel("pause");
        })
        $(this).get(0).play();
        let $videoPlayButton = $(this).parent().find(".video-play");
        $videoPlayButton.addClass("d-none");
      } else {
        // start carousel when video is not playing
        $(this).off("stop pause ended");
        $(this).on("stop pause ended", function (e) {
          $(this).closest(".carousel").carousel();
        });
        $(this).get(0).pause();
      }
    })
    videoReplayOnEnd();
    playVid();
  }
}

document.addEventListener('visibilitychange', function () {
  // document.title = document.visibilityState;

  if (document.visibilityState == "hidden") {
    $('.carousel-item video, .timeline-image video').each(function () {
      $(this).get(0).pause();
      $(this).parent().find(".video-play").removeClass("d-none");
    })
  } else {
    $('.carousel-item video, .timeline-image video').each(function () {
      $(this).get(0).play();
      $(this).parent().find(".video-play").addClass("d-none");
    })
  }

});

document.addEventListener('focusin', function () {
  var pattern = /(?:^|\s)simple-modal-button-green(?:\s|$)/
  if (document.activeElement.className.match(pattern)) {
    $('.carousel-item video, .timeline-image video').each(function () {
      $(this).get(0).pause();
    })
  }
}, true);

function checkVideoCarousel() {
  // play video when active in carousel
  if (palioBrowser && isChrome) {
    $(".timeline-main .carousel").on("slid.bs.carousel", function (e) {
      if ($(this).find("video").length) {
        if ($(this).find(".carousel-item").hasClass("active")) {
          $(this).find("video").get(0).play();
          let $videoPlayButton = $(this).find(".video-play");
          $videoPlayButton.addClass("d-none");
        } else {
          $(this).find("video").get(0).pause();
        }
      }
    });
    videoReplayOnEnd();
    playVid();
  }
}

function onVideoStop(vid) {
  $(vid).parent().find(".video-play").removeClass("d-none");
}

function onVideoPlay(vid) {
  $(vid).parent().find(".video-play").addClass("d-none");
}

document.querySelectorAll("video.myvid").forEach(vid => {
  vid.addEventListener("stop", function () {
    onVideoStop(vid);
  }, false);
  vid.addEventListener("ended", function () {
    onVideoStop(vid);
  }, false);
  vid.addEventListener("pause", function () {
    onVideoStop(vid);
  }, false);
  vid.addEventListener("play", function () {
    onVideoPlay(vid);
  }, false);
})

var visibleCarousel = new Set();

function checkCarousel() {
  $('.timeline-main .carousel').each(function () {
    if ($(this).is(":in-viewport")) {
      if (!visibleCarousel.has($(this).attr('id'))) {
        visibleCarousel.add($(this).attr('id'));
        $(this).carousel('cycle');
      }
    } else {
      if (visibleCarousel.has($(this).attr('id'))) {
        visibleCarousel.delete($(this).attr('id'));
        $(this).carousel('pause');
      }
    }
  });
}

$(function () {
  $(window).scroll(function () {
    $('#scroll-top').hide().fadeIn(100);
    didScroll = true;

    // play video when is in view
    checkVideoViewport();
    checkVideoCarousel();
    checkCarousel();
  });
});

function topFunction() {
  window.scrollTo({
    top: 0,
    behavior: 'smooth'
  });
  setTimeout(function() {
    $("#scroll-top").css('display', 'none');
  }, 1000);
}

var productData = [];

var storeMap = new Map();
var storeIdMap = new Map();

function fetchStores() {
  // var formData = new FormData();
  // formData.append('f_pin', localStorage.F_PIN);

  var params = location.search
    .substr(1)
    .split("&");
  var fpin = "";
  for (var i = 0; i < params.length; i++) {
    if (params[i].includes('f_pin=')) {
      tmp = params[i].split("=")[1];
      fpin = tmp;
    }
  }

  if (!fpin && window.Android) {
    try {
      fpin = window.Android.getFPin();
    } catch (error) {}
  }

  var xmlHttp = new XMLHttpRequest();
  xmlHttp.onreadystatechange = function () {
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
      let data = JSON.parse(xmlHttp.responseText);
      data.forEach(storeEntry => {
        storeMap.set(storeEntry.CODE, JSON.stringify(storeEntry));
        storeIdMap.set("" + storeEntry.ID, storeEntry.CODE);
      });
    }
  }
  if (fpin != "") {
    xmlHttp.open("get", "/qiosk_web/logics/fetch_stores?f_pin=" + fpin);
  } else {
    xmlHttp.open("get", "/qiosk_web/logics/fetch_stores");
  }
  xmlHttp.send();
}

function openStore($store_code, $store_link) {
  if (window.Android) {
    if (storeMap.has($store_code)) {
      var storeOpen = storeMap.get($store_code);
      window.Android.openStore(storeOpen);
    }
  } else {
    if ($store_link != "") {
      window.location.href = $store_link;
    } else {
      window.location.href = "/qiosk_web/pages/tab3-profile.php?store_id=" + $store_code + "&f_pin=02b3c7f2db";
    }
  }
}

var likedPost = [];

function getLikedProducts() {
  if (window.Android) {
    var f_pin = window.Android.getFPin();
    if (f_pin) {
      var xmlHttp = new XMLHttpRequest();
      xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
          let likeData = JSON.parse(xmlHttp.responseText);
          likeData.forEach(product => {
            var productCode = product.PRODUCT_CODE;
            likedPost.push(productCode);
            $("#like-" + productCode).attr("src", "../assets/img/icons/Heart-fill.png");
          });
        }
      }
      xmlHttp.open("get", "/qiosk_web/logics/fetch_products_liked?f_pin=" + f_pin);
      xmlHttp.send();
    }
  }
}

function updateCartCounter() {
  let counter_badge = 0;
  if (localStorage.getItem("cart") != null) {
    var cart = JSON.parse(localStorage.getItem("cart"));
  } else {
    var cart = [];
  }
  cart.forEach(item => {
    item.items.forEach(item => {
      counter_badge += parseInt(item.itemQuantity);
    })
  })
  if (counter_badge != 0) {
    $('#cart-counter').removeClass('d-none');
    $('#cart-counter').html(counter_badge);
  } else {
    $('#cart-counter').addClass('d-none');
  }
} 

function likeProduct($productCode) {
  var score = parseInt($('#like-counter-' + $productCode).text());
  var isLiked = false;
  if (likedPost.includes($productCode)) {
    likedPost = likedPost.filter(p => p !== $productCode);
    $("#like-" + $productCode).attr("src", "../assets/img/icons/Heart.png");
    if (score > 0) {
      $('#like-counter-' + $productCode).text(score - 1);
    }
    isLiked = false;
  } else {
    likedPost.push($productCode);
    $("#like-" + $productCode).attr("src", "../assets/img/icons/Heart-fill.png");
    $('#like-counter-' + $productCode).text(score + 1);
    isLiked = true;
  }

  //TODO send like to backend
  if (window.Android) {
    var f_pin = window.Android.getFPin();
    var curTime = (new Date()).getTime();

    var formData = new FormData();

    formData.append('product_code', $productCode);
    formData.append('f_pin', f_pin);
    formData.append('last_update', curTime);
    formData.append('flag_like', (isLiked ? 1 : 0));

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
      if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
        // // console.log(xmlHttp.responseText);
        updateScore($productCode);
      }
    }
    xmlHttp.open("post", "/qiosk_web/logics/like_product");
    xmlHttp.send(formData);
  }
}

var followedStore = [];

function getFollowedStores() {
  if (window.Android) {
    var f_pin = window.Android.getFPin();
  } else {
    var f_pin = new URLSearchParams(window.location.href).get('f_pin');
  }
  // if (f_pin) {
  var xmlHttp = new XMLHttpRequest();
  xmlHttp.onreadystatechange = function () {
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
      let followData = JSON.parse(xmlHttp.responseText);
      followData.forEach(store => {
        var storeCode = store.STORE_CODE;
        followedStore.push(storeCode);
        $(".follow-icon-" + storeCode).attr("src", "../assets/img/icons/Wishlist-fill.png");
      });
    }
  }
  xmlHttp.open("get", "/qiosk_web/logics/fetch_stores_followed?f_pin=" + f_pin);
  xmlHttp.send();
  // }
}
// }

function followStore($productCode, $storeCode) {
  var score = parseInt($('#follow-counter-post-' + $productCode).text().slice(0, -9));
  var isFollowed = false;
  if (followedStore.includes($storeCode)) {
    followedStore = followedStore.filter(p => p !== $storeCode);
    $(".follow-icon-" + $storeCode).attr("src", "../assets/img/icons/Wishlist.png");
    if (score > 0) {
      $('.follow-counter-store-' + $storeCode).text((score - 1) + " pengikut");
    }
    isFollowed = false;
  } else {
    followedStore.push($storeCode);
    $(".follow-icon-" + $storeCode).attr("src", "../assets/img/icons/Wishlist-fill.png");
    $('.follow-counter-store-' + $storeCode).text((score + 1) + " pengikut");
    isFollowed = true;
  }

  //TODO send like to backend
  if (window.Android) {
    var f_pin = window.Android.getFPin();
  } else {
    var f_pin = new URLSearchParams(window.location.href).get('f_pin');
  }
  var curTime = (new Date()).getTime();

  var formData = new FormData();

  formData.append('store_code', $storeCode);
  formData.append('f_pin', f_pin);
  formData.append('last_update', curTime);
  formData.append('flag_follow', (isFollowed ? 1 : 0));

  let xmlHttp = new XMLHttpRequest();
  xmlHttp.onreadystatechange = function () {
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
      // // console.log(xmlHttp.responseText);
      updateScoreShop($storeCode);
    }
  }
  xmlHttp.open("post", "/qiosk_web/logics/follow_store");
  xmlHttp.send(formData);
}

var commentedProducts = [];

function getCommentedProducts() {
  if (window.Android) {
    var f_pin = window.Android.getFPin();
    if (f_pin) {
      //   // console.log("GETCOMMENTED");
      var xmlHttp = new XMLHttpRequest();
      xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
          let likeData = JSON.parse(xmlHttp.responseText);
          likeData.forEach(product => {
            var productCode = product.PRODUCT_CODE;
            commentedProducts.push(productCode);
            $(".comment-icon-" + productCode).attr("src", "../assets/img/icons/Comment.png");
          });
        }
      }
      xmlHttp.open("get", "/qiosk_web/logics/fetch_products_commented?f_pin=" + f_pin);
      xmlHttp.send();
    }
  }
}

$('#switchAll').click(function () {
  setFilterCheckedAll($('#switchAll').is(':checked'));
});

function checkSwitch(checked) {
  $('#switchAll').prop('checked', checked);
}

$('.checkbox-filter-cat').click(function () {
  if (!$(this).is(':checked')) {
    checkSwitch(false);
  } else if (isFilterCheckedAll()) {
    checkSwitch(true);
  }
});

function fillFilter() {
  var url_string = window.location.href;
  var url = new URL(url_string);
  var searchValue = url.searchParams.get("query");
  if (searchValue != null) {
    $('#query').val(searchValue);
  }
  var filterValue = url.searchParams.get("filter");
  if (filterValue != null) {
    filterArr = filterValue.split("-");
    filterArr.forEach(filterId => {
      $('#checkboxFilter-' + filterId).prop('checked', true);
    });
  }
  // var filterGear = document.getElementById("gear");
  if (filterValue || searchValue) {
    // filterGear.classList.add("filter-yellow");
  } else {
    // filterGear.classList.remove("filter-yellow");
  }
}

function resetFilter() {
  $('#query').val('');
  $('#switchAll').prop('checked', true);
  setFilterCheckedAll(true);
  if (!isSearchHidden) {
    headerOut();
  }
  searchFilter();
}

function onClickHasStory() {
  $(".has-story").click(function (e) {
    e.preventDefault();
    busy = true;
    if (this.id == "all-store") {
      STORE_ID = "";
      searchFilter();
    } else {
      let prev_STORE_ID = STORE_ID;
      STORE_ID = this.id.split("-")[1];
      fetchProductCount(STORE_ID, prev_STORE_ID);
    }
    // searchFilter();
  });
}

function fetchProductCount(store_id, prev_STORE_ID) {
  var xmlHttp = new XMLHttpRequest();
  xmlHttp.onreadystatechange = function () {
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
      let data = JSON.parse(xmlHttp.responseText);
      if (data != null && data > 0) {
        searchFilter();
      } else {
        var f_pin = "";
        try {
          f_pin = window.Android.getFPin();
        } catch (error) {}

        if (storeIdMap.has(store_id)) {
          var $store_code = storeIdMap.get(store_id);
          if (data != null && data == -1) {
            openStore($store_code, "");
          } else {
            if (f_pin != "") {
              window.location.href = "tab3-profile.php?f_pin=" + f_pin + "&store_id=" + $store_code;
            } else {
              window.location.href = "tab3-profile.php?store_id=" + $store_code;
            }
          }
        } else {
          if (data != null && data == -1) {
            openStore(store_id, "");
          } else {
            if (f_pin != "") {
              window.location.href = "tab3-profile.php?f_pin=" + f_pin + "&store_id=" + store_id;
            } else {
              window.location.href = "tab3-profile.php?store_id=" + store_id;
            }
          }
        }
        STORE_ID = prev_STORE_ID;
      }
      // // console.log(data);
    }
  }
  xmlHttp.open("get", "/qiosk_web/logics/fetch_store_product_count?store_id=" + store_id);
  xmlHttp.send();
}

function highlightStore() {
  if (STORE_ID != "") {
    selected_id = "#store-" + STORE_ID;
    // todo: kalo store ga ada
  } else {
    selected_id = '#all-store';
  }
  $(selected_id).toggleClass("selected");
}

let activeFilter = '';

function selectCategoryFilter() {
  $('#category-tabs .nav .nav-item .nav-link').each(function () {
    $(this).click(function () {
      busy = true;
      STORE_ID = "";
      activeFilter = $(this).attr('id').split('-')[1];
      if (activeFilter == "all") {
        activeFilter = "";
      }
      $(this).addClass('active');
      $('#category-tabs .nav-link:not(#categoryFilter-' + activeFilter + ')').removeClass('active');
      searchFilter();
    })
  });
}

async function searchFilter() {
  var selected_id = "";
  $('.has-story').removeClass("selected");
  var dest = window.location.href;
  var product_dest = "timeline_products.php";
  var filter_dest = "timeline_story_container.php";
  var params = "";
  const query = $('#query').val();
  var filter = activeFilter;
  // console.log('active filter: ' + filter);
  if (dest.includes('#')) {
    dest = dest.split('#')[0]
  }
  if (dest.includes('?')) {
    dest = dest.split('?')[0];
  }
  if (STORE_ID != "") {
    params = params + "?store_id=" + STORE_ID;
  }
  if (query != "" || filter != "") {
    if (!params.includes("?")) {
      params = params + "?";
    } else {
      params = params + "&";
    }
  }
  if (query != "") {
    let urlEncodedQuery = encodeURIComponent(query);
    params = params + "query=" + urlEncodedQuery;
    if (filter != "") {
      params = params + "&";
    }
  }
  if (filter != "") {
    let urlEncodedFilter = encodeURIComponent(filter);
    params = params + "filter=" + urlEncodedFilter;
  }
  if (window.Android) {
    var f_pin = window.Android.getFPin();
    if (f_pin) {
      if (!params.includes("?")) {
        params = params + "?f_pin=" + f_pin;
      } else {
        params = params + "&f_pin=" + f_pin;
      }
    }
  }
  // // console.log("params " + params);
  dest = dest + params;
  product_dest = product_dest + params;
  filter_dest = filter_dest + params;
  // window.location.href = dest;
  // // console.log("filter " + filter + " x " + FILTERS);
  if (filter != FILTERS) {
    $.get(filter_dest, function (data) {
      $('#story-container').html(data);
      highlightStore();
      onClickHasStory();
    });
  } else {
    highlightStore();
  }
  offset = 0;
  $('#pbr-timeline').html('');
  // console.log('busy: ' + busy);
  isCalled = false;
  await displayRecords(params, 10, offset);
  redrawLikeFollowComment();
  window.history.replaceState(null, "", dest);
  reinitCarousel();
  hideProdDesc();
  toggleProdDesc();
  setCurrentStore(STORE_ID);
  checkVideoViewport();
  checkCarousel();
  toggleVideoMute();
  fetchProductMap(params);
  addToCartModal();
}



function voiceSearch() {
  if (window.Android) {
    $isVoice = window.Android.toggleVoiceSearch();
    toggleVoiceButton($isVoice);
  }
}

function submitVoiceSearch($searchQuery) {
  // // console.log("submitVoiceSearch " + $searchQuery);
  $('#query').val($searchQuery);
  $('#searchFilterForm-a').submit();
}

function toggleVoiceButton($isActive) {
  // if ($isActive) {
  //   $("#mic").attr("src", "../assets/img/action_mic_blue.png");
  // } else {
  //   $("#mic").attr("src", "../assets/img/action_mic.png");
  // }
}

$('#searchFilterForm').validate({
  rules: {
    'category[]': {
      required: true
    }
  },
  messages: {
    'category[]': {
      required: '<div class="alert alert-danger" role="alert">Pilih minimal salah satu filter di atas</div>',
    },
  },
  submitHandler: function (form) {
    searchFilter();
  },
  errorClass: 'help-block',
  errorPlacement: function (error, element) {
    if (element.attr('name') == 'category[]') {

      error.insertAfter('#checkboxGroup');
    }
  }

});

function hasStoreId() {
  var tmp = "";
  var params = location.search
    .substr(1)
    .split("&");
  var id = "#all-store";
  var filter = "";
  for (var i = 0; i < params.length; i++) {
    if (params[i].includes('store_id=')) {
      tmp = params[i].split("=")[1];
      STORE_ID = tmp;
    } else if (params[i].includes('filter=')) {
      tmp = params[i].split("=")[1];
      FILTERS = tmp;
    }
  }
  highlightStore();
  const scrollLeft = $(id).position()['left'];
  $("#story-container ul").scrollLeft(scrollLeft);
  if (location.href.includes('#product')) {
    var product_id = '#' + location.href.split('#')[1]
    $(product_id)[0].scrollIntoView();
  }
}


onClickHasStory();

if (performance.navigation.type == 2) {
  location.reload(true);
}

function redrawLikeFollowComment() {
  likedPost.forEach(productCode => {
    $("#like-" + productCode).attr("src", "../assets/img/icons/Heart-fill.png");
  });
  followedStore.forEach(storeCode => {
    $(".follow-icon-" + storeCode).attr("src", "../assets/img/icons/Wishlist-fill.png");
  });
  commentedProducts.forEach(productCode => {
    $(".comment-icon-" + productCode).attr("src", "../assets/img/icons/Comment.png");
  });
}

function reinitCarousel() {
  $('.carousel').each(function () {
    $(this).carousel();
  });
}

function horizontalScrollPos() {
  let selectedPos = document.querySelector('.has-story.selected').offsetLeft;
  document.querySelector('#story-container ul').scrollBy({
    left: selectedPos,
    behavior: 'smooth'
  });
}

function setCurrentStore($store_id) {
  if (storeIdMap.has($store_id)) {
    var $store_code = storeIdMap.get($store_id);
    if (storeMap.has($store_code) && window.Android) {
      var storeOpen = JSON.parse(storeMap.get($store_code));
      if (storeOpen.IS_VERIFIED == 1 && !storeOpen.LINK) {
        window.Android.setCurrentStore($store_code, storeOpen.BE_ID);
      } else {
        window.Android.setCurrentStore('', '');
      }
    }
  }
}

function hideProdDesc() {
  $(".prod-desc").each(function () {
    if ($(this).text().length > 100 && $(this).siblings('.truncate-read-more').length == 0) {
      $(this).toggleClass("truncate");
      let toggleText = document.createElement("span");
      toggleText.innerHTML = "Selengkapnya...";
      toggleText.classList.add("truncate-read-more");
      $(this).parent().append(toggleText);
    }
  });
}

function toggleProdDesc() {
  $(".truncate-read-more").each(function () {
    $(this).click(function () {
      // console.log("read more");
      $(this).parent().find(".prod-desc").toggleClass("truncate");
      if ($(this).text() == "Selengkapnya...") {
        $(this).text(" Sembunyikan");
      } else {
        $(this).text("Selengkapnya...");
      }
    });
  });
}

function toggleVideoMute() {
  $(".video-sound").each(function () {
    $(this).click(function (e) {
      e.stopPropagation();
      let $videoElement = $(this).parent().find("video.myvid");
      if ($videoElement.prop("muted")) {
        $videoElement.prop("muted", false);
        $(this).find("img").attr("src", "../assets/img/video_unmute.png");
      } else {
        $videoElement.prop("muted", true);
        $(this).find("img").attr("src", "../assets/img/video_mute.png");
      }
    });
  });
}

function videoMuteAll() {
  $(".video-sound").each(function () {
    let $videoElement = $(this).parent().find("video.myvid");
    $videoElement.prop("muted", true);
    $(this).find("img").attr("src", "../assets/img/video_mute.png");
  });
}

var productMap = new Map();

function fetchProductMap(str) {
  // var formData = new FormData();
  // formData.append('f_pin', localStorage.F_PIN);

  var params = "";
  if (str == "") {
    params = location.search;
  } else {
    params = str;
  }

  var xmlHttp = new XMLHttpRequest();
  xmlHttp.onreadystatechange = function () {
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
      let data = JSON.parse(xmlHttp.responseText);
      data.forEach(productEntry => {
        productMap.set(productEntry.CODE, JSON.stringify(productEntry));
      });
    }
  }
  xmlHttp.open("get", "/qiosk_web/logics/fetch_products_json" + params);
  xmlHttp.send();
}

function openProductMenu($productCode) {
  if (window.Android) {
    if (productMap.has($productCode)) {
      var productOpen = productMap.get($productCode);
      window.Android.openProductMenu(productOpen);
    }
  }
}

function videoReplayOnEnd() {
  $(".myvid").each(function (i, obj) {
    $(this).on('ended', function () {
      // // console.log("video ended");
      let $videoPlayButton = $(this).parent().find(".video-play");
      $videoPlayButton.removeClass("d-none");
    })
  })
}

function playVid() {
  $("div.video-play").each(function () {
    $(this).click(function (e) {
      e.stopPropagation();
      $(this).parent().find("video.myvid").get(0).play();
      $(this).addClass("d-none");
    })
  })
}

function pauseAll() {
  $('.carousel-item video, .timeline-image video').each(function () {
    $(this).get(0).pause();
  })
  visibleCarousel.clear();
  $('.carousel').each(function () {
    $(this).carousel('pause');
  })
}

function resumeAll() {
  console.log('resume');
  checkVideoViewport();
  checkVideoCarousel();
  checkCarousel();
  updateCounter();
  fetchNotifCount();
}

function visitStore($store_code, $f_pin, $is_entering) {
  var formData = new FormData();

  formData.append('store_code', $store_code);
  formData.append('f_pin', $f_pin);
  formData.append('flag_visit', ($is_entering ? 1 : 0));

  if ($store_code && $f_pin) {
    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
      if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
        // // console.log(xmlHttp.responseText);
      }
    }
    xmlHttp.open("post", "/qiosk_web/logics/visit_store");
    xmlHttp.send(formData);
  }
}

function activeCategoryTab() {
  let urlSearchParams = new URLSearchParams(window.location.search);
  let activeParam = urlSearchParams.get('filter');

  if (activeParam == null) {
    activeParam = "all";
  }

  $('#categoryFilter-' + activeParam).addClass('active');
  $('#category-tabs .nav-link:not(#categoryFilter-' + activeParam + ')').removeClass('active');
}

function pullRefresh() {
  if (window.Android && $(window).scrollTop() == 0) {
    window.scrollTo(0, document.body.scrollHeight - (document.body.scrollHeight - 3));
  }
}

function ext(url) {
  return (url = url.substr(1 + url.lastIndexOf("/")).split('?')[0]).split('#')[0].substr(url.lastIndexOf("."))
}

function goBack() {
  if (window.Android) {
    window.Android.closeView();
  } else {
    window.history.back();
  }
}

function numberWithCommas(x) {
  // return x.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
  return x.toLocaleString();
}

function openDetailProduct(pr) {
  let getPr = JSON.parse(productMap.get(pr));

  $('#modal-addtocart .addcart-img-container').html('');
  $('#modal-addtocart .product-name').html('');
  $('#modal-addtocart .product-price').html('');
  $('#modal-addtocart .prod-details .col-11').html('');

  let product_imgs = getPr.THUMB_ID.split('|');
  let product_name = getPr.NAME;
  let product_price = numberWithCommas(getPr.PRICE);
  // let product_price = getPr.PRICE;
  let product_desc = getPr.DESCRIPTION;

  let product_showcase = "";

  // if (product_imgs.length == 1) {
  let extension = ext(product_imgs[0]);
  if (extension == ".jpg" || extension == ".png" || extension == ".webp") {
    product_showcase = `<img class="product-img" src="${product_imgs[0]}">`;
  } else if (extension == ".mp4" || extension == ".webm") {
    let poster = product_imgs[0].replace(extension, ".webp");
    product_showcase = `
      <div class="video-wrap"><video playsinline muted="" class="myvid" preload="metadata"
              poster="${poster}">
              <source src="${product_imgs[0]}" type="video/mp4"></video>
      </div>
      `;
  }

  let followSrc = "../assets/img/icons/Wishlist-(White).png";
  if (followedStore.includes(getPr.SHOP_CODE)) {
    followSrc = "../assets/img/icons/Wishlist-fill.png";
  }

  product_showcase += `
  <hr id="drag-this">
  <img id="btn-wishlist" class="addcart-wishlist follow-icon-${getPr.SHOP_CODE}" onclick="followStore('${getPr.CODE}','${getPr.SHOP_CODE}')" src="${followSrc}">`;

  $('#modal-addtocart .addcart-img-container').html(product_showcase);
  $('#modal-addtocart .product-name').html(product_name);
  $('#modal-addtocart .product-price').html('Rp ' + product_price);
  $('#modal-addtocart .prod-details .col-11').html(product_desc);
}

function hideAddToCart() {
  $('#modal-addtocart').modal('hide');
}

function pauseAllVideo() {
  $('.timeline-main .carousel-item video, .timeline-image video').each(function () {
    $(this).off("stop pause ended");
    $(this).on("stop pause ended", function (e) {
      $(this).closest(".carousel").carousel();
    });
    $(this).get(0).pause();
  });
}

function playAllVideo() {
  $('.timeline-main .carousel-item video, .timeline-image video').each(function () {
    // pause carousel when video is playing
    $(this).off("play");
    $(this).on("play", function (e) {
      $(this).closest(".carousel").carousel("pause");
    })
    $(this).get(0).play();
    let $videoPlayButton = $(this).parent().find(".video-play");
    $videoPlayButton.addClass("d-none");
  });
}

function playModalVideo() {
  $('#modal-addtocart .addcart-img-container video').each(function () {
    $(this).off("play");
    $(this).on("play", function (e) {
      $(this).closest(".carousel").carousel("pause");
    })
    $(this).get(0).play();
    let $videoPlayButton = $(this).parent().find(".video-play");
    $videoPlayButton.addClass("d-none");
  })
}

function changeItemQuantity(id, mod) {
  if (mod == "add") {
    document.getElementById(id).value = parseInt(document.getElementById(id).value) + 1;
  } else {
    if (document.getElementById(id).value > 1) {
      document.getElementById(id).value = parseInt(document.getElementById(id).value) - 1;
    }
  }
}

let product_id = "";

function checkButtonPos() {
  let elem = document.querySelector('.prod-addtocart');
  let bounding = elem.getBoundingClientRect();

  if (bounding.bottom > (window.innerHeight || document.documentElement.clientHeight)) {
    console.log('out')
    elem.style.bottom = elem.offsetHeight + 15 + 'px';
  } else {
    elem.style.bottom = '25px';
  }
}

function addToCartModal() {
  /* start handle detail product popup */
  const initPos = parseInt($('#header').offset().top + $('#header').outerHeight(true)) + "px";
  const fixedPos = JSON.parse(JSON.stringify(initPos));

  // let product_id = "";

  let init = parseInt(fixedPos.replace('px', ''));

  $('#modal-addtocart .modal-dialog').draggable({
    handle: ".modal-content",
    containment: "body",
    drag: function (event, ui) {

      // Keep the left edge of the element
      // at least 100 pixels from the container  

      if (ui.position.top < init) {
        ui.position.top = init;
      }

      let dialog = ui.position.top + window.innerHeight;
      if (dialog - window.innerHeight > 150) {
        $('#modal-addtocart').modal('hide');
      }
    }
  })

  var ua = window.navigator.userAgent;
  var iOS = !!ua.match(/iPad/i) || !!ua.match(/iPhone/i);
  var webkit = !!ua.match(/WebKit/i);
  var iOSSafari = iOS && webkit && !ua.match(/CriOS/i);

  $('#modal-addtocart').on('shown.bs.modal', function () {
    $('.modal').css('overflow', 'hidden');
    $('.modal').css('overscroll-behavior-y', 'contain');
    checkButtonPos();
    pullRefresh();
    pauseAllVideo();
    playModalVideo();

    if (window.Android) {
      window.Android.setIsProductModalOpen(true);
    }
  })

  $('.product-row .timeline-main').click(function () {
    // console.log('init: ' + init);
    $('#modal-addtocart .modal-dialog').css('top', '55px');
    $('#modal-addtocart .modal-dialog').css('height', window.innerHeight - fixedPos);
  })

  $('#modal-addtocart').on('hidden.bs.modal', function () {
    $('.modal').css('overflow', 'auto');
    $('.modal').css('overscroll-behavior-y', 'auto');
    let modalVideo = $('#modal-addtocart').find('video');
    if (modalVideo.length > 0) {
      $('#modal-addtocart .modal-body video').get(0).pause();
    }
    pullRefresh();
    checkVideoViewport();

    if (window.Android) {
      window.Android.setIsProductModalOpen(false);
    }
  })

  /* end handle detail product popup */
}

function eraseQuery() {
  $("#delete-query").click(function () {
    $('#searchFilterForm-a input#query').val('');
    $('#delete-query').addClass('d-none');
  })

  $('#searchFilterForm-a input#query').keyup(function () {

    if ($(this).val() != '') {
      $('#delete-query').removeClass('d-none');
    } else {
      $('#delete-query').addClass('d-none');
    }
  })
}

function resetSearch() {
  $('#searchFilterForm-a input#query').val('');
}

let busy = false;
let limit = 10;
let offset = 0;
let time = new Date().getTime();
let seed = JSON.parse(JSON.stringify(time));
let isCalled = false;

function getMaxProducts(param) {
  isCalled = true;
  return new Promise(function (resolve, reject) {
    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
      if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
        // // console.log(xmlHttp.responseText);
        resolve(xmlHttp.responseText);
      }
    }
    xmlHttp.open("get", "/qiosk_web/logics/get_max_products" + param);
    xmlHttp.send();
  });
}

function checkDupes() {
  var nodes = document.querySelectorAll('#pbr-timeline>*');
  var ids = {};
  var totalNodes = nodes.length;

  for (var i = 0; i < totalNodes; i++) {
    var currentId = nodes[i].id ? nodes[i].id : "undefined";
    if (isNaN(ids[currentId])) {
      ids[currentId] = 0;
    }
    ids[currentId]++;
  }
}

async function displayRecords(par, lim, off) {
  // let queryStr = window.location.search;

  let params = '';

  if (par.length > 0) {
    let searchQuery = par.substr(1).split("&");

    let limitIdx = searchQuery.findIndex(x => x.includes('limit'));
    let offsetIdx = searchQuery.findIndex(x => x.includes('offset'));
    if (limitIdx > -1) {
      searchQuery[limitIdx] = 'limit=' + lim;
    } else {
      searchQuery.push('limit=' + lim);
    }
    if (offsetIdx > -1) {
      searchQuery[offsetIdx] = 'offset=' + off;
    } else {
      searchQuery.push('offset=' + off);
    }
    params = searchQuery.join('&');
  } else {
    params = 'limit=' + lim + '&offset=' + off;
  }

  let url = 'timeline_products.php';

  params += '&seed=' + seed;

  // console.log('scroll:' + url);
  $.ajax({
    type: "GET",
    url: url,
    data: params,
    beforeSend: function () {
      $("#loader_message").html("").hide();
      $('#loader_image').show();
    },
    success: function (html) {
      $("#pbr-timeline")
        .append(html)
        .ready(function () {
          hasStoreId();
          checkVideoViewport();
          checkVideoCarousel();
          hideProdDesc();
          toggleProdDesc();
          checkCarousel();
          toggleVideoMute();
          videoReplayOnEnd();
          playVid();
          addToCartModal();
        });
      $('#loader_image').hide();
      if (html == "") {
        $("#loader_message").html('').show();
      } else {
        $("#loader_message").html('').show();
      }
      if ($('.product-row').length > 0) {
        $('#product-null').addClass('d-none');
      }
      busy = false;
      // console.log('busy: ' + busy);
    }
  });
}

function openNotifs() {
  let f_pin = '';

  if (window.Android) {
    f_pin = window.Android.getFPin();
  } else {
    f_pin = new URLSearchParams(window.location.search).get('f_pin');
  }

  window.location.href = 'notifications.php?f_pin=' + f_pin;
}



$(function () {

  displayRecords(window.location.search, limit, offset);

  getLikedProducts();
  getFollowedStores();
  getCommentedProducts();
  fetchStores();
  activeCategoryTab();
  fetchProductMap("");
  eraseQuery();
  selectCategoryFilter();
  updateCounter();

  if (STORE_ID != "") {
    setCurrentStore(STORE_ID);
  }

  $('#add-to-cart').click(function () {
    let itemQty = parseInt($('#modal-item-qty').val());
    addToCart(product_id, itemQty);
  })

  $(window).scroll(async function () {
    // make sure u give the container id of the data to be loaded in.
    if ($(window).scrollTop() + $(window).height() > $("#pbr-timeline").height() && !busy && !isCalled) {
      let maxProducts = await getMaxProducts(window.location.search);
      if (offset < maxProducts) {
        isCalled = false;
        busy = true;
        offset = limit + offset;
        $('#loader-image').removeClass('d-none');
        displayRecords(window.location.search, limit, offset);
      }
      // console.log(offset);
    }
  });

  $('#addtocart-success').on('hide.bs.modal', function() {
    updateCounter();
  })
});

window.onload = (event) => {
  horizontalScrollPos();
};

$(window).on('unload', function () {
  $(window).scrollTop(0);
});
window.onunload = function () {
  window.scrollTo(0, 0);
}
if ('scrollRestoration' in history) {
  history.scrollRestoration = 'manual';
}