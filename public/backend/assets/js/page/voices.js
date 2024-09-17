"use strict";

function clear_tags() {
    var text = $("#text").val();
    text = "<span>" + text + "</span>";
    text = $(text).text();
    $("#text").val(text);
}

//images in select2 js for languages
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

    //images in select2 js for provider
    $("#provider").select2({
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
            var $opt = $('<span><img src="' + optimage + '" width="30px" /> ' + opt.text + "</span>");
            return $opt;
        }
    }
}, 80);

/* get voices query params */
function voices_params(p) {
    $("#voice").empty();
    clear_tags();

    var language = $("#language").val();
    var provider = $("#provider").val();

    // console.log(language);
    return {
        language: language,
        provider: provider,
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search,
    };
}
