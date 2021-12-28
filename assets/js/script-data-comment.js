function getDisplayName(fPin, index) {
    let name = fPin;
    try {
        if (window.Android) {
            name = window.Android.getDisplayName(fPin);
        }
    } catch (err) {
    }

    if (name == fPin) {
        var xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function () {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                var personData = JSON.parse(xmlHttp.responseText);
                if (personData.length > 0) {
                    var person = personData[0];

                    var first_name = person.FIRST_NAME;
                    var last_name = person.LAST_NAME;
                    var full_name = "";
                    if (last_name) {
                        full_name = first_name + " " + last_name;
                    } else {
                        full_name = first_name;
                    }

                    document.getElementById('user-name-' + index).innerHTML = full_name;
                }
            }
        }
        xmlHttp.open("get", "/qiosk_web/logics/fetch_person?f_pin=" + fPin);
        xmlHttp.send();
    } else {
        document.getElementById('user-name-' + index).innerHTML = name;
    }
}

function getDisplayNameReff(fPin, sub, index) {
    let name = fPin;
    try {
        if (window.Android) {
            name = window.Android.getDisplayName(fPin);
        }
    } catch (err) {
    }

    if (name == fPin) {
        var xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function () {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                var personData = JSON.parse(xmlHttp.responseText);
                if (personData.length > 0) {
                    var person = personData[0];

                    var first_name = person.FIRST_NAME;
                    var last_name = person.LAST_NAME;
                    var full_name = "";
                    if (last_name) {
                        full_name = first_name + " " + last_name;
                    } else {
                        full_name = first_name;
                    }

                    document.getElementById('user-name-reff-' + sub + index).innerHTML = full_name;
                }
            }
        }
        xmlHttp.open("get", "/qiosk_web/logics/fetch_person?f_pin=" + fPin);
        xmlHttp.send();
    } else {
        document.getElementById('user-name-reff-' + sub + index).innerHTML = name;
    }
}

function getThumbId(fPin, index) {
    let thumb = '';
    try {
        if (window.Android) {
            thumb = window.Android.getImagePerson(fPin);
        }
    } catch (err) {
    }
    if (thumb == '') {
        thumb = '../assets/img/ic_person_boy.png';
        document.getElementById('user-thumb-' + index).src = thumb;

        var xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function () {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                var personData = JSON.parse(xmlHttp.responseText);
                if (personData.length > 0) {
                    var person = personData[0];
                    if (person.IMAGE) {
                        thumb = 'https://qmera.io/filepalio/image/' + person.IMAGE;
                    }
                    document.getElementById('user-thumb-' + index).src = thumb;
                }
            }
        }
        xmlHttp.open("get", "/qiosk_web/logics/fetch_person?f_pin=" + fPin);
        xmlHttp.send();
    } else {
        thumb = 'https://qmera.io/filepalio/image/' + thumb;
        document.getElementById('user-thumb-' + index).src = thumb;
    }
}

function getThumbIdReff(fPin, sub, index) {
    let thumb = '';
    try {
        if (window.Android) {
            thumb = window.Android.getImagePerson(fPin);
        }
    } catch (err) {
    }
    if (thumb == '') {
        thumb = '../assets/img/ic_person_boy.png';
        document.getElementById('user-thumb-reff-' + sub + index).src = thumb;

        var xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function () {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                var personData = JSON.parse(xmlHttp.responseText);
                if (personData.length > 0) {
                    var person = personData[0];
                    if (person.IMAGE) {
                        thumb = 'https://qmera.io/filepalio/image/' + person.IMAGE;
                    }
                    document.getElementById('user-thumb-reff-' + sub + index).src = thumb;
                }
            }
        }
        xmlHttp.open("get", "/qiosk_web/logics/fetch_person?f_pin=" + fPin);
        xmlHttp.send();
    } else {
        thumb = 'https://qmera.io/filepalio/image/' + thumb;
        document.getElementById('user-thumb-reff-' + sub + index).src = thumb;
    }
}

function showProfile(fPin) {
    window.Android.showProfile(fPin);
}