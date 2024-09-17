"use strict";
let btn_html;
let synt_btn = $("#get_tts");
let play_btn = $("#tts-play");
let download_btn = $("#download_tts");
let save_btn = $("#save_tts");
let predefined_btn = $("#save_predefined");
let test_button = $("#listen_sample_voice");
userId = $("#user_id").val();
var voice_effects = document.getElementById("voice_effects");
var say_as = document.getElementById("say_as");
var emphasis = document.getElementById("emphasis");
var volume = document.getElementById("volume");
async function toggle() {
    var provider;
    $(voice_effects).show();
    $(say_as).show();
    $(emphasis).show();
    $(volume).show();
    try {
        provider = JSON.parse($("#voice").val())["provider"];
        if (provider != "aws") {
            $(voice_effects).hide();
        }
        if (provider == "ibm") {
            $(say_as).hide();
            $(emphasis).hide();
            $(volume).hide();
        }
    } catch (e) {
        return false;
    }
    return true;
}

function clear_tags() {
    var text = $("#text").val();
    text = "<span>" + text + "</span>";
    text = $(text).text();
    $("#text").val(text);
}

function insert_tags(id, start_tag, end_tag) {
    var textArea = document.getElementById(id);
    var text = textArea.value;
    var indexStart = textArea.selectionStart;
    var indexEnd = textArea.selectionEnd;
    text =
        text.slice(0, indexStart) +
        start_tag +
        text.substring(indexStart, indexEnd) +
        end_tag +
        text.slice(indexEnd, text.length);
    textArea.value = "";
    textArea.value = text;
}

function delete_tts(tts_id) {
    if (!confirm("are you sure you want to delete this text to speech ?")) {
        return 0;
    }
    let req_body = {
        tts_id: tts_id,
        [csrfName]: csrfHash,
    };
    $.ajax({
        url: baseUrl + "/admin/text-to-speech/delete-tts",
        type: "POST",
        data: req_body,
        success: function (result) {
            csrfName = result["csrfName"];
            csrfHash = result["csrfHash"];
            if (result["error"] == false) {
                //success
                var message = result["message"];

                iziToast.success({
                    message: result["message"],
                    position: "topRight",
                    class: "toast",
                });

                $("#tts_table").bootstrapTable("refresh");
            } else {
                //error
                var message = result["message"];
                iziToast.error({
                    message: result["message"],
                    position: "topRight",
                    class: "toast",
                });
                console.log(message);
            }
        },
        error: function (error) {
            console.log(error);
        },
    });
}

function insert_ssml(element) {
    var start_tag = $(element).find("option:selected").data("start-tag");
    var end_tag = $(element).find("option:selected").data("end-tag");
    insert_tags("text", start_tag, end_tag);
    element.selectedIndex = 0;
}

function detailFormatter(index, row) {
    var html = [];
    $.each(row, function (key, value) {
        if (key != "base_64" && key != "is_ssml" && key != "operate") {
            html.push("<p><b>" + key + ":</b> " + value + "</p>");
        }
    });
    return html.join("");
}

$(document).ready(() => {
    toggle();

    function toggle() {
        var provider;
        $(voice_effects).hide();
        try {
            provider = JSON.parse($("#voice").val())["provider"];
            if (provider == "aws") {
                $(voice_effects).show();
            }
        } catch (e) {
            return false;
        }
        return true;
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

    play_btn.attr("disabled", true);
    download_btn.attr("disabled", true);
    save_btn.attr("disabled", true);
    predefined_btn.attr("disabled", true);
    test_button.attr("disabled", true);

    if ($("#text")) {
        $("#text").keyup(function () {
            $("#limit").text($("#text").val().length);
        });
    }

    // synthesizer text
    var box = document.getElementById('changer');

    document.getElementById('add_file').addEventListener('change', function () {
        var fr = new FileReader();
        let limit = document.getElementById('limit');
        // let text = document.getElementById('text');
        fr.onload = function () {
            if (box.checked == true) {
                document.getElementById('text').value += fr.result.replace(/\r?\n|\r/g, " ");
                limit.textContent = document.getElementById('text').value.length;
            } else {
                document.getElementById('text').value = fr.result.replace(/\r?\n|\r/g, " ");
            }
        }
        fr.readAsText(this.files[0]);
    });

    $(document).ready(function () {
        $('input[type=checkbox][name=changer]').change(function () {
            if ($(this).is(':checked')) {
                $("#para").text("Append text at the end");
            } else {
                $("#para").text("Replace the old text");
            }
        });
    });
    // ends here

    function save_tts() {
        var base64 = localStorage.getItem("base64");
        var tts_id = localStorage.getItem("tts_id");

        let req_body = {
            tts_id: tts_id,
            base64: base64,
            title: $("#title").val(),
            [csrfName]: csrfHash,
        };
        $.ajax({
            url: baseUrl + "/admin/text-to-speech/save-tts",
            type: "POST",
            data: req_body,
            beforeSend: function () {
                $("#save_tts").attr("disabled", true);
                btn_html = $("#save_tts").html();
                $("#save_tts").html("Saving now.. .");
            },
            success: function (result) {
                csrfName = result["csrfName"];
                csrfHash = result["csrfHash"];
                if (result["error"] == false) {
                    //success
                    iziToast.success({
                        message: result["message"],
                        position: "topRight",
                        class: "toast",
                    });
                    var message = result["message"];

                    $("#tts_table").bootstrapTable("refresh");
                } else {
                    //error
                    var message = result["message"];
                    iziToast.error({
                        message: result["message"],
                        position: "topRight",
                        class: "toast",
                    });
                    console.log(message);
                }
                var $table = $("#tts_table");

                $("#save_tts").attr("disabled", false);
                $("#save_tts").html("Save TTS");
            },
            error: function (error) {
                console.log(error);
            },
        });
    }

    function convert_active() {
        let req_body = {
            [csrfName]: csrfHash,
            user_id: users_id,
        };
        $.ajax({
            url: baseUrl + "/admin/text-to-speech/convert_active",
            type: "POST",
            data: req_body,
            beforeSend: function () { },
            success: function (result) {
                csrfName = result["csrfName"];
                csrfHash = result["csrfHash"];
                if (result["error"] == false) {
                    Swal.fire("", result["message"], "success");
                } else {
                    iziToast.error({
                        message: result["message"],
                        position: "topRight",
                        class: "toast",
                    });
                }
            },
            error: function (error) {
                console.log(error);
            },
        });
    }

    function get_tts() {
        let language = $("#language").val();
        if (language == "") {
            showToastMessage("Please select language", "error");
            return false;
        }
        let text = document.getElementById("text").value;
        if (text == "") {
            showToastMessage("Text cannot be blank.", "error");
            return false;
        }
        let voice = JSON.parse($("#voice").val());
        // console.log(voice);
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
        var saveData = $.ajax({
            type: "post",
            data: req_body,
            url: baseUrl + "/admin/text-to-speech/synthesize",
            dataType: "json",
            beforeSend: function () {
                synt_btn.attr("disabled", true);
                btn_html = synt_btn.html();
                synt_btn.html(
                    '<i class="fa-solid fa-circle-notch fa-spin"></i> &nbsp; Processing '
                );
                play_btn.attr("disabled", true);
                download_btn.attr("disabled", true);
                save_btn.attr("disabled", true);
                predefined_btn.attr("disabled", true);
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

                    if (
                        result["data"].hasOwnProperty("upcoming") &&
                        result["data"]["upcoming"] == true
                    ) {
                        sweetConfirm(result["message"], convert_active);
                    } else {
                        console.log(result);
                        iziToast.error({
                            message: result["message"],
                            position: "topRight",
                            class: "toast",
                        });
                    }
                } else {
                    //success

                    //
                    $("#audio_controll").attr(
                        "src",
                        `data:audio/mpeg;base64, ${Data["data"]}`
                    );
                    $("#download_tts").attr({
                        href: `data:audio/mp3;base64, ${Data["data"]}`,
                        download: "output.mp3",
                    });
                    iziToast.success({
                        message: result["message"],
                        position: "topRight",
                        class: "toast",
                    });
                    localStorage.setItem("base64", Data["data"]);
                    localStorage.setItem("tts_id", Data["id"]);
                    play_btn.attr("disabled", false);
                    save_btn.attr("disabled", false);
                    predefined_btn.attr("disabled", false);

                    synt_btn.attr("disabled", false);
                    synt_btn.html(btn_html);
                }
            },
        });
    }
    $("#voice_effects").hide();

    document.getElementById("get_tts").addEventListener("click", () => {
        get_tts();
    });
    document.getElementById("save_tts").addEventListener("click", () => {
        save_tts();
    });
});

function set_predefined() {
    test_button.attr("disabled", true);
    clear_tags();
    $("#predefined_audio").attr("src", "");
    let voice = JSON.parse($("#voice").val());
    toggle();

    let req_body = {
        voice: voice["voice"],
        language: voice["language"],
        [csrfName]: csrfHash,
    };
    $.ajax({
        url: baseUrl + "/admin/text-to-speech/set-predefined",
        type: "POST",
        data: req_body,
        beforeSend: function () { },
        success: function (result) {
            csrfName = result["csrfName"];
            csrfHash = result["csrfHash"];
            if (result["error"] == false) {
                iziToast.success({
                    message: result["message"],
                    position: "topRight",
                    class: "toast",
                });

                if (result["data"] != "") {
                    $("#predefined_audio").attr(
                        "src",
                        `data:audio/mpeg;base64, ${result["data"]}`
                    );
                    test_button.attr("disabled", false);
                } else {
                    iziToast.error({
                        message: result["message"],
                        position: "topRight",
                        class: "toast",
                    });
                }
            } else {
                iziToast.error({
                    message: result["message"],
                    position: "topRight",
                    class: "toast",
                });
            }
        },
        error: function (error) {
            console.log(error);
        },
    });
}

// test voice toggle
document.getElementById("pause_test").style.display = "none";
var activeSong = document.getElementById("predefined_audio");
document.getElementById("listen_sample_voice").addEventListener("click", () => {
    if (activeSong.paused) {
        activeSong.play();
        document.getElementById("pause_test").style.display = "block";
        document.getElementById("play_test").style.display = "none";
    } else {
        activeSong.pause();
        document.getElementById("pause_test").style.display = "none";
        document.getElementById("play_test").style.display = "block";
    }
});
activeSong.addEventListener("ended", () => {
    document.getElementById("play_test").style.display = "block";
    document.getElementById("pause_test").style.display = "none";
});

document.getElementById("pause_synthesize").style.display = "none";
var active = document.getElementById("audio_controll");
document.getElementById("tts-play").addEventListener("click", () => {
    if (active.paused) {
        active.play();
        document.getElementById("play_synthesize").style.display = "none";
        document.getElementById("pause_synthesize").style.display = "inline-block";
        $("#play_text").html("Pause audio");
    } else {
        active.pause();
        document.getElementById("pause_synthesize").style.display = "none";
        document.getElementById("play_synthesize").style.display = "inline-block";
        $("#play_text").html("Play audio");
    }
});
active.addEventListener("ended", () => {
    document.getElementById("pause_synthesize").style.display = "none";
    document.getElementById("play_synthesize").style.display = "inline-block";
    $("#play_text").html("Play audio");
});

function set_voices() {
    $("#voice").empty();
    clear_tags();
    var lang = $("#language").val();
    let req_body = {
        language: lang,
        [csrfName]: csrfHash,
    };
    $.ajax({
        url: baseUrl + "/admin/text-to-speech/set-voices",
        type: "POST",
        data: req_body,
        success: function (result) {
            var resultData = result;
            csrfName = resultData["csrfName"];
            csrfHash = resultData["csrfHash"];
            if (resultData["error"] == false) {
                let voice = resultData["data"];
                for (var i = 0; i < voice.length; i++) {
                    var val = JSON.stringify(voice[i]);
                    console.log(val);
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
                            " - " +
                            voice[i]["provider"] +
                            " </option>";
                        $("#voice").empty;
                        $("#voice").append(e);
                    } else {
                        let e =
                            "<option data-image = " +
                            voice[i]["image"] +
                            " value=" +
                            val +
                            ">" +
                            voice[i]["display_name"] +
                            " - " +
                            voice[i]["provider"] +
                            " </option>";
                        $("#voice").empty;
                        $("#voice").append(e);
                    }
                    // $("#voice").empty;
                    // $("#voice").append(e);
                }
                set_predefined();
            }
        },
        error: function (error) {
            console.log(error);
        },
    });
}

function ttsQueryParams(p) {
    return {
        user_id: $("#user_id").val(),
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search,
    };
}

function save_predefined() {
    var base64 = localStorage.getItem("base64");
    var tts_id = localStorage.getItem("tts_id");

    let req_body = {
        tts_id: tts_id,
        base64: base64,
        [csrfName]: csrfHash,
    };
    $.ajax({
        url: baseUrl + "/admin/text-to-speech/save-predefined",
        type: "POST",
        data: req_body,
        beforeSend: function () {
            $("#save_predefined").attr("disabled", true);
            btn_html = $("#save_tts").html();
            $("#save_predefined").html("Saving now.. .");
        },
        success: function (result) {
            csrfName = result["csrfName"];
            csrfHash = result["csrfHash"];
            if (result["error"] == false) {
                //success
                var message = result["message"];

                iziToast.success({
                    message: result["message"],
                    position: "topRight",
                    class: "toast",
                });
            } else {
                //error
                var message = result["message"];
                console.log(message);
                iziToast.error({
                    message: result["message"],
                    position: "topRight",
                    class: "toast",
                });
            }
            set_predefined();
            $("#save_predefined").attr("disabled", false);
            $("#save_predefined").html("Save as Predefined");
        },
        error: function (error) {
            console.log(error);
        },
    });
}
const act = [];

function playAudio(id) {
    act[id] = document.getElementById("play" + id);

    act[id].onended = function () {
        saved_toggle_play(id);
    };
    if (act[id].paused) {
        var playPromise = act[id].play();

        if (playPromise !== undefined) {
            playPromise
                .then((_) => {
                    // Automatic playback started!
                    // Show playing UI.
                })
                .catch((error) => {
                    act[id].pause();
                });
        }

        saved_toggle_pause(id);
    } else {
        act[id].pause();

        saved_toggle_play(id);
    }
}

function saved_toggle_play(id) {
    document.getElementById("pause_saved" + id).style.display = "none";
    document.getElementById("play_saved" + id).style.display = "inline-block";
}

function saved_toggle_pause(id) {
    document.getElementById("play_saved" + id).style.display = "none";
    document.getElementById("pause_saved" + id).style.display = "inline-block";
}