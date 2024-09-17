"use strict";
$(function () {
    setNavigation();
});

function setNavigation() {
    var path = window.location.pathname;
    path = path.replace(/\/$/, "");
    path = decodeURIComponent(path);
    path = document.location.href;

    $(".navbar li a").each(function () {
        var href = $(this).attr("href");

        if (href === path) {
            $(this).closest("a").addClass("active");
        }
    });
}

(function () {


    /**
     * Easy selector helper function
     */
    const select = (el, all = false) => {
        el = el.trim();
        if (all) {
            return [...document.querySelectorAll(el)];
        } else {

        }
    };

    /**
     * Easy event listener function
     */
    const on = (type, el, listener, all = false) => {
        if (all) {
            select(el, all).forEach((e) => e.addEventListener(type, listener));
        } else {

        }
    };

    /**
     * Easy on scroll event listener
     */
    const onscroll = (el, listener) => {
        el.addEventListener("scroll", listener);
    };

    /**
     * Navbar links active state on scroll
     */
    let navbarlinks = select("#navbar .scrollto", true);
    const navbarlinksActive = () => {
        let position = window.scrollY + 200;
        navbarlinks.forEach((navbarlink) => {
            if (!navbarlink.hash) return;
            let section = select(navbarlink.hash);
            if (!section) return;
            if (
                position >= section.offsetTop &&
                position <= section.offsetTop + section.offsetHeight
            ) {
                navbarlink.classList.add("active");
            } else {
                navbarlink.classList.remove("active");
            }
        });
    };
    window.addEventListener("load", navbarlinksActive);
    onscroll(document, navbarlinksActive);

    /**
     * Scrolls to an element with header offset
     */
    const scrollto = (el) => {
        let header = select("#header");
        let offset = header.offsetHeight;

        if (!header.classList.contains("header-scrolled")) {
            offset -= 10;
        }

        let elementPos = select(el).offsetTop;
        window.scrollTo({
            top: elementPos - offset,
            behavior: "smooth",
        });
    };

    /**
     * Toggle .header-scrolled class to #header when page is scrolled
     */
    let selectHeader = select("#header");
    if (selectHeader) {
        const headerScrolled = () => {
            if (window.scrollY > 100) {
                selectHeader.classList.add("header-scrolled");
            } else {
                selectHeader.classList.remove("header-scrolled");
            }
        };
        window.addEventListener("load", headerScrolled);
        onscroll(document, headerScrolled);
    }

    /**
     * Back to top button
     */
    let backtotop = select(".back-to-top");
    if (backtotop) {
        const toggleBacktotop = () => {
            if (window.scrollY > 100) {
                backtotop.classList.add("active");
            } else {
                backtotop.classList.remove("active");
            }
        };
        window.addEventListener("load", toggleBacktotop);
        onscroll(document, toggleBacktotop);
    }

    /**
     * Mobile nav toggle
     */
    on("click", ".mobile-nav-toggle", function (e) {
        select("#navbar").classList.toggle("navbar-mobile");
        this.classList.toggle("bi-list");
        this.classList.toggle("bi-x");
    });

    /**
     * Mobile nav dropdowns activate
     */
    on(
        "click",
        ".navbar .dropdown > a",
        function (e) {
            if (select("#navbar").classList.contains("navbar-mobile")) {
                e.preventDefault();
                this.nextElementSibling.classList.toggle("dropdown-active");
            }
        },
        true
    );

    /**
     * Scrool with ofset on links with a class name .scrollto
     */
    on(
        "click",
        ".scrollto",
        function (e) {
            if (select(this.hash)) {
                e.preventDefault();

                let navbar = select("#navbar");
                if (navbar.classList.contains("navbar-mobile")) {
                    navbar.classList.remove("navbar-mobile");
                    let navbarToggle = select(".mobile-nav-toggle");
                    navbarToggle.classList.toggle("bi-list");
                    navbarToggle.classList.toggle("bi-x");
                }
                scrollto(this.hash);
            }
        },
        true
    );

    /**
     * Scroll with ofset on page load with hash links in the url
     */
    window.addEventListener("load", () => {
        if (window.location.hash) {
            if (select(window.location.hash)) {
                scrollto(window.location.hash);
            }
        }
    });

    /**
     * Clients Slider
     */
    new Swiper(".clients-slider", {
        speed: 400,
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        slidesPerView: "auto",
        pagination: {
            el: ".swiper-pagination",
            type: "bullets",
            clickable: true,
        },
        breakpoints: {
            320: {
                slidesPerView: 2,
                spaceBetween: 40,
            },
            480: {
                slidesPerView: 3,
                spaceBetween: 60,
            },
            640: {
                slidesPerView: 4,
                spaceBetween: 80,
            },
            992: {
                slidesPerView: 6,
                spaceBetween: 120,
            },
        },
    });

    /**
     * Animation on scroll
     */
    function aos_init() {
        AOS.init({
            duration: 1000,
            easing: "ease-in-out",
            once: true,
            mirror: false,
        });
    }
    window.addEventListener("load", () => {
        aos_init();
    });
})();

function display_discounted_price(index) {
    $("#price" + index).empty;
    $("#discounted_div").empty();
    // $("#price" + index).
    var currency = ($("#currency") && $("#currency").val() != "") ? $("#currency").val() : "$";
    var id = $("#plan" + index)
        .find(":selected")
        .attr("value");
    var value = $("#plan" + index)
        .find(":selected")
        .attr("data-price");
    var discounted = $("#plan" + index)
        .find(":selected")
        .attr("data-discount");
    var true_value = value.replace(',', '') - discounted;
    console.log(typeof (discounted));
    if (discounted != 'undefined' && parseInt(discounted) > 0) {
        console.log($("#price" + index));
        $("#price" + index).html(numberWithCommas(discounted) +
            `<div id="discounted_div"><h6><strike>${currency} ${value}</strike></h6></div>`);
    } else {
        $("#price" + index).html(numberWithCommas(value));
    }
}
function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
(function () {
    "use strict";

    /**
     * Easy selector helper function
     */
    const select = (el, all = false) => {
        el = el.trim();
        if (all) {
            return [...document.querySelectorAll(el)];
        } else {
            return document.querySelector(el);
        }
    };

    /**
     * Easy event listener function
     */
    const on = (type, el, listener, all = false) => {
        if (all) {
            select(el, all).forEach((e) => e.addEventListener(type, listener));
        } else {
            select(el, all).addEventListener(type, listener);
        }
    };

    /**
     * Easy on scroll event listener
     */
    const onscroll = (el, listener) => {
        el.addEventListener("scroll", listener);
    };

    /**
     * Navbar links active state on scroll
     */
    let navbarlinks = select("#navbar .scrollto", true);
    const navbarlinksActive = () => {
        let position = window.scrollY + 200;
        navbarlinks.forEach((navbarlink) => {
            if (!navbarlink.hash) return;
            let section = select(navbarlink.hash);
            if (!section) return;
            if (
                position >= section.offsetTop &&
                position <= section.offsetTop + section.offsetHeight
            ) {
                navbarlink.classList.add("active");
            } else {
                navbarlink.classList.remove("active");
            }
        });
    };
    window.addEventListener("load", navbarlinksActive);
    onscroll(document, navbarlinksActive);

    /**
     * Scrolls to an element with header offset
     */
    const scrollto = (el) => {
        let header = select("#header");
        let offset = header.offsetHeight;

        if (!header.classList.contains("header-scrolled")) {
            offset -= 10;
        }

        let elementPos = select(el).offsetTop;
        window.scrollTo({
            top: elementPos - offset,
            behavior: "smooth",
        });
    };

    /**
     * Toggle .header-scrolled class to #header when page is scrolled
     */
    let selectHeader = select("#header");
    if (selectHeader) {
        const headerScrolled = () => {
            if (window.scrollY > 100) {
                selectHeader.classList.add("header-scrolled");
            } else {
                selectHeader.classList.remove("header-scrolled");
            }
        };
        window.addEventListener("load", headerScrolled);
        onscroll(document, headerScrolled);
    }

    /**
     * Back to top button
     */
    let backtotop = select(".back-to-top");
    if (backtotop) {
        const toggleBacktotop = () => {
            if (window.scrollY > 100) {
                backtotop.classList.add("active");
            } else {
                backtotop.classList.remove("active");
            }
        };
        window.addEventListener("load", toggleBacktotop);
        onscroll(document, toggleBacktotop);
    }

    /**
     * Mobile nav toggle
     */
    on("click", ".mobile-nav-toggle", function (e) {
        select("#navbar").classList.toggle("navbar-mobile");
        this.classList.toggle("bi-list");
        this.classList.toggle("bi-x");
    });

    /**
     * Mobile nav dropdowns activate
     */
    on(
        "click",
        ".navbar .dropdown > a",
        function (e) {
            if (select("#navbar").classList.contains("navbar-mobile")) {
                e.preventDefault();
                this.nextElementSibling.classList.toggle("dropdown-active");
            }
        },
        true
    );

    /**
     * Scrool with ofset on links with a class name .scrollto
     */
    on(
        "click",
        ".scrollto",
        function (e) {
            if (select(this.hash)) {
                e.preventDefault();

                let navbar = select("#navbar");
                if (navbar.classList.contains("navbar-mobile")) {
                    navbar.classList.remove("navbar-mobile");
                    let navbarToggle = select(".mobile-nav-toggle");
                    navbarToggle.classList.toggle("bi-list");
                    navbarToggle.classList.toggle("bi-x");
                }
                scrollto(this.hash);
            }
        },
        true
    );

    /**
     * Scroll with ofset on page load with hash links in the url
     */
    window.addEventListener("load", () => {
        if (window.location.hash) {
            if (select(window.location.hash)) {
                scrollto(window.location.hash);
            }
        }
    });

    /**
     * Clients Slider
     */
    new Swiper(".clients-slider", {
        speed: 400,
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        slidesPerView: "auto",
        pagination: {
            el: ".swiper-pagination",
            type: "bullets",
            clickable: true,
        },
        breakpoints: {
            320: {
                slidesPerView: 2,
                spaceBetween: 40,
            },
            480: {
                slidesPerView: 3,
                spaceBetween: 60,
            },
            640: {
                slidesPerView: 4,
                spaceBetween: 80,
            },
            992: {
                slidesPerView: 6,
                spaceBetween: 120,
            },
        },
    });

    /**
     * Animation on scroll
     */
    function aos_init() {
        AOS.init({
            duration: 1000,
            easing: "ease-in-out",
            once: true,
            mirror: false,
        });
    }
    window.addEventListener("load", () => {
        aos_init();
    });
})();
let btn_play = '<i class="fas fa-play-circle"></i> Play';
let btn_pause = '<i class="fas fa-pause-circle"></i> Pause ';
$(document).ready(() => {
    setTimeout(() => {
        $("#language").select2({
            templateResult: formatState,
            templateSelection: formatState,
        });

        function formatState(opt) {
            if (!opt.id) {
                return opt.text;
            }

            var optimage = $(opt.element).attr("data-image");
            if (!optimage) {
                return opt.text;
            } else {
                var $opt = $(
                    '<span><img src="' +
                    optimage +
                    '" width="28px" /> ' +
                    opt.text +
                    "</span>"
                );
                return $opt;
            }
        }

        //images in select2 js for voices
        $("#voice").select2({
            templateResult: formatState1,
            templateSelection: formatState1,
        });

        function formatState1(opt) {
            if (!opt.id) {
                return opt.text;
            }

            var optimage = $(opt.element).attr("data-image");
            if (!optimage) {
                return opt.text;
            } else {
                var $opt = $('<span><img src="' + baseUrl + "/" + optimage + '" width="30px" /> ' + opt.text + "</span>");
                return $opt;
            }
        }
    }, 80);
});

function set_voices() {
    $("#voice").empty();
    var lang = $("#language").val();
    let length;
    let req_body = {
        language: lang,
    };
    $.ajax({
        url: baseUrl + "/home/set-voices",
        type: "get",
        data: req_body,
        beforeSend: () => {
            $("#voice").html('');
            $("#voice").append('<option>Please Wait</option>');
        },
        success: function (result) {
            var resultData = result;


            if (resultData["error"] == false) {
                $("#voice").html('');
                let voice = resultData["data"];
                length = voice.length;
                for (var i = 0; i < voice.length; i++) {
                    var val = JSON.stringify(voice[i]);
                    if (voice[i]["type"] != '') {
                        let e =
                            "<option data-image = " +
                            voice[i]["image"] +
                            " value=" +
                            val +
                            ">" +
                            voice[i]["display_name"] +
                            " (" +
                            voice[i]["type"] +
                            ")" +
                            " </option>";
                        $("#voice").append(e);
                    } else {
                        let e =
                            "<option data-image = " +
                            voice[i]["image"] +
                            " value=" +
                            val +
                            ">" +
                            voice[i]["display_name"] +
                            " </option>";
                        $("#voice").append(e);
                    }
                    // let e =
                    //     "<option data-image = " +
                    //     voice[i]["image"] +
                    //     " value=" +
                    //     val +
                    //     ">" +
                    //     voice[i]["display_name"] +
                    //     " (" +
                    //     voice[i]["type"] +
                    //     ")" +
                    //     " </option>";
                    // $("#voice").append(e);
                }
            }
        },
        error: function (error) {
            console.log(error);
        },
    }).then(() => {
        if (length == 0) {
            $("#voice").empty();
            $("#voice").append('<option>No Voices found.</option>');
        }
    });
}


let audio;
let src;
let btn_html;

function get_tts() {
    $("#play-btn").attr("disabled", true);
    let language = $("#language").val();
    let textarea = $("#text").val();
    if (language == "") {
        showToastMessage("Please select language", "error");
        return false;
    }
    if (textarea.trim() == "") {
        showToastMessage("Please insert text", "error");
        return false;
    }

    let text = document.getElementById("text").value;
    let voice = JSON.parse($("#voice").val());
    let title = $("#title").val();
    let req_body = {
        language: voice.language,
        [csrfName]: csrfHash,
        voice: voice.voice,
        engine: (voice.type != null) ? voice.type.toLowerCase() : null,
        text: text,
        title: title,
        provider: voice.provider,
    };

    let synt_btn = $("#get_tts");

    var saveData = $.ajax({
        type: "post",
        data: req_body,
        url: baseUrl + "/home/synthesize",
        beforeSend: function () {

            synt_btn.attr("disabled", true);
            btn_html = synt_btn.html();
            synt_btn.html(
                '<i class="fa-solid fa-circle-notch fa-spin"></i> &nbsp; Processing '
            );
        },
        success: function (result) {
            var Data = result;
            csrfName = result["csrfName"];
            csrfHash = result["csrfHash"];
            if (Data["error"] == true) {
                //error

                //
                var save_language = "";
                var save_text = "";
                var save_voice = "";
                var base64 = "";
                synt_btn.attr("disabled", false);
                synt_btn.html(btn_html);
                showToastMessage(result["message"], "error");
            } else {
                //success
                audio = new Audio("data:audio/mpeg;base64, " + Data["data"]);
                $("#play-btn").attr("disabled", false);

                showToastMessage(result["message"], "success");


                synt_btn.attr("disabled", false);
                synt_btn.html(btn_html);
            }
        },
    });
}
if (document.getElementById("get_tts")) {
    document.getElementById("get_tts").addEventListener("click", () => {
        get_tts();
    });
}

function showToastMessage(message, type) {
    switch (type) {
        case "error":
            $().ready(
                iziToast.error({
                    title: "Error",
                    message: message,
                    position: "topRight",
                })
            );
            break;

        case "success":
            $().ready(
                iziToast.success({
                    title: "Success",
                    message: message,
                    position: "topRight",
                })
            );
            break;
    }
}

function play_pause() {
    audio.onended = function () {
        $("#play-btn").html(btn_play);
        $("#play-btn").blur();
    };
    if (audio.paused) {
        var playPromise = audio.play();

        if (playPromise !== undefined) {
            playPromise
                .then((_) => {
                    // Automatic playback started!
                    // Show playing UI.
                })
                .catch((error) => {
                    audio.pause();
                });
        }

        $("#play-btn").html(btn_pause);
    } else {
        audio.pause();
        $("#play-btn").blur();

        $("#play-btn").html(btn_play);
    }
}

function set_admin() {
    $("#identity").val("admin@espeech.in");
    $("#password").val("password");
}

function set_user() {
    $("#identity").val("user@espeech.in");
    $("#password").val("password");
}
let contact_submit = $("#contact_submit");
let contact_btn_html = contact_submit.html();
$("#contact_form").on("submit", function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    formData.append(csrfName, csrfHash);
    $.ajax({
        type: "POST",
        url: $(this).attr("action"),
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function () {
            contact_btn_html = contact_submit.html();
            contact_submit.attr("disabled", true);
            contact_submit.html("Please Wait..");
        },
        success: function (result) {
            csrfName = result.csrfName;
            csrfHash = result.csrfHash;
            if (!result.error) {
                showToastMessage(result.message, "success");
                document.getElementById("contact_form").reset();
            } else {
                showToastMessage(result.message, "error");
            }
        },
    }).then(() => {
        contact_submit.html(contact_btn_html);
        contact_submit.removeAttr("disabled");
    });
});

"use strict";

var box = document.getElementById('changer');
$(document).ready(function () {
    // console.log($('#add_file').length);

    if ($('#add_file').length > 0) {
        document.getElementById('add_file').addEventListener('change', function () {
            var fr = new FileReader();
            fr.onload = function () {
                if (box.checked == true) {
                    document.getElementById('text').value += fr.result.replace(/\r?\n|\r/g, " ");
                } else {
                    document.getElementById('text').value = fr.result.replace(/\r?\n|\r/g, " ");
                }
            }
            fr.readAsText(this.files[0]);
        });
    }
});



$(document).ready(function () {
    $('input[type=checkbox][name=changer]').change(function () {
        if ($(this).is(':checked')) {
            $("#para").text("Append");
        } else {
            $("#para").text("New");
        }
    });


    $('#carousel div:first').addClass('active');
    $('.carousel-item').addClass('w-50');

    if ($('.carousel-item').hasClass('active')) {
        $("#carousel div").css("left", "25%");
        $(".rating-stars").css("left", "0%");
    };
});
