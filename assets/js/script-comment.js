function commentProduct($productCode) {
    // var score = parseInt($('#follow-counter-post-' + $productCode).text().slice(0,-9));
    // var isFollowed = false;
    // if (followedStore.includes($storeCode)) {
    //   followedStore = followedStore.filter(p => p !== $storeCode);
    //   $(".follow-icon-" + $storeCode).attr("src", "../assets/img/person_add.png");
    //   if (score > 0) {
    //     $('.follow-counter-store-' + $storeCode).text((score - 1)+" pengikut");
    //   }
    //   isFollowed = false;
    // } else {
    //   followedStore.push($storeCode);
    //   $(".follow-icon-" + $storeCode).attr("src", "../assets/img/ic_nuc_follow3_check.png");
    //   $('.follow-counter-store-' + $storeCode).text((score + 1)+" pengikut");
    //   isFollowed = true;
    // }

    if (document.getElementById("input").value.trim() != '') {
        $('input:text').click(
            function () {
                $(this).val('');
            });
    } else {
        showAlert(getTranslation('comment-6') || "Isi Komentar...");
        return;
    }

    //TODO send like to backend
    if (window.Android) {
        var f_pin = window.Android.getFPin();
        // var f_pin = "02b3c7f2db";
        if (f_pin) {
            var curTime = (new Date()).getTime();

            var formData = new FormData();

            var discussion_id = curTime.toString() + f_pin;

            formData.append('product_code', $productCode);
            formData.append('f_pin', f_pin);
            formData.append('last_update', curTime);
            formData.append('comment', document.getElementById("input").value.trim());
            formData.append('discussion_id', discussion_id);

            if (!document.getElementById("reply-div").classList.contains("d-none")) {
                var commentId = getCookie("commentId");
                formData.append('reply_id', commentId);
            }

            let xmlHttp = new XMLHttpRequest();
            xmlHttp.onreadystatechange = function () {
                if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                    if (xmlHttp.responseText == 'Success Comment') {
                        if (!document.getElementById("reply-div").classList.contains("d-none")) {
                            deleteAllCookies();
                            $("#reply-div").addClass('d-none');
                            document.getElementById("content-comment").style.marginBottom = "50px";
                        }
                        location.reload();
                        window.scrollTo(0, document.body.scrollHeight);
                        updateScore($productCode);
                    }
                }
            }
            xmlHttp.open("post", "/qiosk_web/logics/comment_product");
            xmlHttp.send(formData);
        }
    }
    // var formData = new FormData();
    // var curTime = (new Date()).getTime();

    // formData.append('product_code', $productCode);
    // formData.append('f_pin', "25Tefsg");
    // formData.append('last_update', curTime);
    // formData.append('comment', document.getElementById("input").value);

    // let xmlHttp = new XMLHttpRequest();
    // xmlHttp.onreadystatechange = function () {
    //     if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
    //         console.log(xmlHttp.responseText);
    //         if(xmlHttp.responseText == 'Success Comment') {
    //             location.reload();
    //         }
    //     }
    // }
    // xmlHttp.open("post", "/qiosk_web/logics/comment_product.php");
    // xmlHttp.send(formData);
}

function getCookie(c_name) {
    if (document.cookie.length > 0) {
        c_start = document.cookie.indexOf(c_name + "=");
        if (c_start != -1) {
            c_start = c_start + c_name.length + 1;
            c_end = document.cookie.indexOf(";", c_start);
            if (c_end == -1) {
                c_end = document.cookie.length;
            }
            return unescape(document.cookie.substring(c_start, c_end));
        }
    }
    return "";
}

document.querySelectorAll(".comments").forEach(item => {
    let commentId = item.querySelector(".commentId").innerText;
    let fPinContent = item.querySelector(".fPin").innerText;
    var f_pin = '';
    try{
        if(window.Android){
            f_pin = window.Android.getFPin();
        } 
    } catch (err){
    }
    // var f_pin = "02b3c7f2db";
    if (fPinContent == f_pin) {
        item.addEventListener('contextmenu', event => {
            event.preventDefault();
            showSuccessModal(commentId, console.log(""));
        }, false)
    } else {
        return;
    }
})

async function showSuccessModal(commentId, method) {
    event.preventDefault();

    $('body').css('overflow', 'hidden');
    this.myModal = new SuccessModal(commentId, method);

    try {
        const modalResponse = await myModal.question();
    } catch (err) {
        console.log(err);
    }
}

class SuccessModal {

    constructor(commentId, method) {
        this.modalTitle = getTranslation('comment-7') || "Kamu yakin ingin menghapus komentar ini?";
        this.acceptText = getTranslation('comment-8') || "Hapus";
        this.cancelText = getTranslation('comment-9') || "Batal";

        this.parent = document.body;
        this.commentId = commentId;
        this.method = method;

        this.modal = undefined;
        this.acceptButton = undefined;
        this.cancelButton = undefined;

        this._createModal();
    }

    question() {
        return new Promise((resolve, reject) => {
            if (!this.modal || !this.acceptButton) {
                reject("There was a problem creating the modal window!");
                return;
            }

            this.acceptButton.addEventListener("click", () => {
                var formData = new FormData();

                formData.append('comment_id', this.commentId);
                let xmlHttp = new XMLHttpRequest();
                xmlHttp.onreadystatechange = function () {
                    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                        if (xmlHttp.responseText == 'Success Delete Comment') {
                            location.reload();
                        }
                    }
                }
                xmlHttp.open("post", "/qiosk_web/logics/delete_comment");
                xmlHttp.send(formData);
            });

            this.cancelButton.addEventListener("click", () => {
                this._destroyModal();
                $('body').css('overflow', 'auto');
            });

        })
    }

    _createModal() {
        // Background dialog
        this.modal = document.createElement('dialog');
        this.modal.setAttribute("style", "z-index: 1031;");
        this.modal.classList.add('simple-modal-dialog');
        this.modal.show();

        // Message window
        const window = document.createElement('div');
        window.classList.add('simple-modal-window');
        this.modal.appendChild(window);

        // Title
        const title = document.createElement('div');
        title.classList.add('simple-modal-title');
        window.appendChild(title);

        // Title text
        const titleText = document.createElement('span');
        titleText.classList.add('simple-modal-title-text');
        titleText.style.marginLeft = "5px";
        titleText.style.marginRight = "5px";
        titleText.textContent = this.modalTitle;
        title.appendChild(titleText);

        // // Main text
        // const text = document.createElement('span');
        // text.setAttribute("id", "payment-form");
        // text.classList.add('simple-modal-text');
        // text.innerHTML = this.status;
        // window.appendChild(text);

        // Accept and cancel button group
        const buttonGroup = document.createElement('div');
        buttonGroup.classList.add('simple-modal-button-group');
        window.appendChild(buttonGroup);

        // Accept button
        this.acceptButton = document.createElement('button');
        this.acceptButton.type = "button";
        this.acceptButton.classList.add('simple-modal-button-green');
        this.acceptButton.textContent = this.acceptText;
        buttonGroup.appendChild(this.acceptButton);

        // Cancel button
        this.cancelButton = document.createElement('button');
        this.cancelButton.type = "button";
        this.cancelButton.classList.add('simple-modal-button-red');
        this.cancelButton.textContent = this.cancelText;
        buttonGroup.appendChild(this.cancelButton);

        // Let's rock
        this.parent.appendChild(this.modal);
    }

    _destroyModal() {
        this.parent.removeChild(this.modal);
        delete this;
    }
}

function onReply(condition, name, commentId) {
    if (condition) {
        $("#input").focus();
        finalName = document.getElementById(name).innerHTML;
        document.getElementById("content-reply").innerHTML = "Reply to " + finalName;
        document.getElementById("content-comment").style.marginBottom = "100px";
        document.cookie = "commentId=" + commentId;
        $("#reply-div").removeClass('d-none');

        function sleep(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        }
        sleep(500).then(() => {
            window.scrollTo(0, document.body.scrollHeight);
        });
    } else {
        deleteAllCookies();
        $("#reply-div").addClass('d-none');
        document.getElementById("content-comment").style.marginBottom = "50px";
    }
    window.scrollTo(0, document.body.scrollHeight);
}

function deleteAllCookies() {
    var cookies = document.cookie.split(";");

    for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i];
        var eqPos = cookie.indexOf("=");
        var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
        document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
    }
}

function goBack() {
    if (window.Android) {
        window.Android.closeView();
    } else {
        window.history.back();
    }
}

function showAlert(word) {
    window.Android.showAlert(word);
}

function hideProdDesc() {
    $(".prod-desc").each(function () {
        if ($(this).text().length > 50) {
            $(this).addClass("mb-3");
            $(this).toggleClass("truncate");
            let toggleText = document.createElement("span");
            toggleText.innerHTML = (getTranslation('comment-4') || "Selengkapnya...");
            // toggleText.href = "#";
            toggleText.style.color = "#999999";
            toggleText.classList.add("truncate-read-more");
            $(this).parent().append(toggleText);
        }
    });
}

function toggleProdDesc() {
    $(".truncate-read-more").each(function () {
        $(this).click(function () {
            console.log("read more");
            $(this).parent().find(".prod-desc").toggleClass("truncate");
            let strmore = (getTranslation('comment-4') || "Selengkapnya...");
            if ($(this).text() == strmore) {
                $(this).text(getTranslation('comment-5') || "Sembunyikan");
            } else {
                $(this).text(strmore);
            }
        });
    });
}

function onFocusInput() {
    if (window.Android) {
        try {
            window.Android.onFocusInput();
        } catch (e) {

        }
    }
}

function changeLanguage(){
    var lang = localStorage.lang;	
    change_lang(lang);
}

$(function () {
    // hideProdDesc();
    toggleProdDesc();
    // $(".prod-desc").readmore({
    //     moreLink: '<a href="#">Selengkapnya...</a>',
    //     lessLink: '<a href="#">Sembunyikan</a>',
    //     collapsedHeight: 22
    // });
    changeLanguage();
})