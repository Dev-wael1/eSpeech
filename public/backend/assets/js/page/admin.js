// edit page
"use strict";
let userId;
if ($("#featured_text").length) {
    // use this if you are using id to check
    var featured_text = $("#featured_text");
    var x = document.getElementById("featured").checked;
    // it exists
    if (x) {
        featured_text.show();
    } else {
        featured_text.hide();
    }
}

function featured_toggle() {
    var x = document.getElementById("featured").checked;
    if (x) {
        featured_text.show();
    } else {
        featured_text.hide();
    }
}

function load_lotties(select, div, selectelement) {
    $("#lottie_customs").hide();
    $("#" + div).html("");

    var json = $(select).val();

    if (json == "") {
        $("#lottie_customs").show();
        $(select).removeAttr("name");
        $("#lottie_inputs").attr("name", "lottie");
        $("#lottie_inputs").attr("required", true);
    } else {
        $("#lottie_customs").hide();

        $("#lottie_inputs").removeAttr("name");
        $(select).attr("name", "lottie");
        $("#lottie_inputs").removeAttr("required");
    }

    lottie.loadAnimation({
        container: document.getElementById(div), // required
        path: json, // the dom element
        renderer: "svg",
        loop: true,
        autoplay: true,
    });
}

function load_lottie_inputs(select, div) {
    var json = $(select).val();
    if (json != "") {
        $("#" + div).html("");
        lottie.loadAnimation({
            container: document.getElementById(div), // required
            path: json, // the dom element
            renderer: "svg",
            loop: true,
            autoplay: true,
        });
    }
}

function get_options() {
    $("#lottie_demo").html("");
    var options = [];
    $("#lottie_selects option").each(function () {
        options.push($(this).val());
    });

    var url = $("#lottie_url").val();
    if (options.includes(url)) {
        $("#lottie_selects").val(url).change();
    } else {

        $("#lottie_selects").val("").change();
        $("#lottie_inputs").val(url).change();
    }
}
get_options();
// edit page

//plans page
function change_color(variable, element) {
    $("body").get(0).style.setProperty("--primary-color", $(element).val());
}
let save_order_btn;
var featured_text = $("#featured_text");
featured_text.hide();

function delete_plan(element) {
    if (!confirm("Are you sure you want to delete this plan?")) {
        return false;
    }
    var plan_id = $(element).data("plan-id");
    let req_body = {
        [csrfName]: csrfHash,
        plan_id: plan_id,
    };
    $.ajax({
        url: baseUrl + "/admin/plans/delete_plan",
        type: "POST",
        data: req_body,
        success: function (result) {
            csrfName = result["csrfName"];
            csrfHash = result["csrfHash"];
            if (result.error) {
                showToastMessage(result.message, "error");
                3;
                return;
            } else {
                window.location.reload();
            }
        },
        error: function (error) {
            console.log(error);
        },
    });
}
$("#featured").change(function () {
    if (this.checked) {}
});
$("#sortable").sortable({
    axis: "y",
    opacity: 0.6,
    cursor: "grab",
});

var sortableLinks = $("#plan");
$(function () {
    $(sortableLinks).sortable();
});

function arange() {
    var data = $(sortableLinks).sortable("serialize");

    save_order_btn = $("#update_btn").text();
    $("#update_btn").text("Please Wait ..");
    $("#update_btn").attr("disabled", "disabled");
    $.ajax({
        url: baseUrl + "/admin/plans/arange",
        type: "GET",
        data: data,
        success: function (result) {

            if (result.error) {
                showToastMessage(result.message, "error");
            } else {
                showToastMessage(result.message, "success");
            }

        },
        error: function (error) {
            console.log(error);
        },
    }).then(() => {
        $("#update_btn").removeAttr("disabled");
        $("#update_btn").text(save_order_btn);
    });
}

function featured_toggle() {
    var x = document.getElementById("featured").checked;
    if (x) {
        featured_text.show();
    } else {
        featured_text.hide();
    }
}

function load_lottie(select, div, selectelement) {
    $("#lottie_custom").hide();
    $("#" + div).html("");

    var json = $(select).val();

    if (json == "") {
        $("#lottie_custom").show();
        $(select).removeAttr("name");
        $("#lottie_input").attr("name", "lottie");
        $("#lottie_input").attr("required", true);
    } else {
        $("#lottie_custom").hide();

        $("#lottie_input").removeAttr("name");
        $(select).attr("name", "lottie");
        $("#lottie_input").removeAttr("required");
    }

    lottie.loadAnimation({
        container: document.getElementById(div), // required
        path: json, // the dom element
        renderer: "svg",
        loop: true,
        autoplay: true,
    });
}

function load_lottie_input(select, div) {
    var json = $(select).val();
    if (json != "") {
        $("#" + div).html("");
        lottie.loadAnimation({
            container: document.getElementById(div), // required
            path: json, // the dom element
            renderer: "svg",
            loop: true,
            autoplay: true,
        });
    }
}

load_lottie("#lottie_select", "lottie_demo", "");
load_lottie_input("#lottie_input", "lottie_demo");
//plans page

//profile page

$("[data-crop-image]").each(function (e) {
    $(this).css({
        overflow: "hidden",
        position: "relative",
        height: $(this).data("crop-image"),
    });
});
var canvas = "";
var imageUrl = "";
var context = "";
var cropper = "";
$("#customFile").on("input", function () {
    canvas = $("#canvas");
    context = canvas.get(0).getContext("2d");
    if (this.files && this.files[0]) {
        if (this.files[0].type.match(/^image\//)) {
            var reader = new FileReader();
            reader.onload = function (evt) {
                canvas.show();
                var img = new Image();
                img.onload = function () {
                    context.canvas.height = img.height;
                    context.canvas.width = img.width;
                    context.drawImage(img, 0, 0);
                    cropper = canvas.cropper({
                        aspectRatio: 1 / 1,
                        viewMode: 1,
                    });
                };
                img.src = null;
                img.src = evt.target.result;
            };
            reader.readAsDataURL(this.files[0]);
        } else {
            console.log("Invalid file type! Please select an image file.");
        }
    } else {
        console.log("No file(s) selected.");
    }
});
$("#update_user_profile").on("submit", function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    if (canvas !== "") {
        imageUrl = canvas.cropper("getCroppedCanvas").toDataURL("image/jpeg");
        formData.append("profile", imageUrl);
    }
    var check = false;
    $.ajax({
        type: "POST",
        url: $(this).attr("action"),
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        beforeSend: function () {
            $("#updt_prfl_btn").html("Please Wait...");
            $("#updt_prfl_btn").prop("disabled", true);
        },
        success: function (result) {

            csrfName = result["csrfName"];
            csrfHash = result["csrfHash"];
            if (!result["error"]) {
                var data = result["data"];
                // console.log(data);
                // return false;
                if (data['old'] != "" && data['new'] != "") {
                    check = true;
                }
                $("#header_name").html(data["first_name"]);
                $("#f_name").html(data["first_name"]);
                $("#l_name").html(data["last_name"]);
                showToastMessage(result["message"], "success");
            } else {
                showToastMessage(result["message"], "error");
            }
            setTimeout(() => {
                if (check) {
                    window.location.href = baseUrl + "/auth";
                } else {
                    location.reload();
                }
            }, 1000);
        },
    });
});

//profile page

//subscription

let start_date = "";
let end_date = "";
let subscription_status = "";
let date_filter_by = "";

function subscriptions_query(p) {
    return {
        search: p.search,
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        start_date: start_date,
        end_date: end_date,
        subscription_status: subscription_status,
        date_filter_by: date_filter_by,
    };
}
$("#subscription_type").change(function () {
    subscription_status = $(this).val();
});
$("#date_filter_by").change(function () {
    date_filter_by = $(this).val();
});
$(function () {
    $('input[name="date_range"]').daterangepicker({
            opens: "left",
        },
        function (start, end, label) {
            start_date = start.format("YYYY-MM-DD");
            end_date = end.format("YYYY-MM-DD");
        }
    );
});

//subscription

//users

function user_formater(index, row) {
    var html = [];
    $.each(row, function (key, value) {
        if (key != "image" && key != "operations") {
            html.push("<p><b>" + key + ":</b> " + value + "</p>");
        }
    });
    return html.join("");
}
//users

//transaction
let txn_start_date = "";
let txn_end_date = "";
let transaction_status = "";
let txn_provider = "";

function txn_table(p) {
    return {
        search: p.search,
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        start_date: txn_start_date,
        end_date: txn_end_date,
        txn_provider: txn_provider,
        transaction_status: transaction_status,
    };
}
$(function () {
    $("#txn_date").daterangepicker({
            opens: "left",
        },
        function (start, end, label) {
            txn_start_date = start.format("YYYY-MM-DD");
            txn_end_date = end.format("YYYY-MM-DD");
        }
    );
});

function transaction_table_formatter(index, row) {
    var html = [];
    $.each(row, function (key, value) {
        html.push("<p><b>" + key + ":</b> " + value + "</p>");
    });
    return html.join("");
}
$("#payment_method").change(function () {
    txn_provider = $(this).val();
});
$("#transaction_status").change(function () {
    transaction_status = $(this).val();
});
//transaction

// users_tts

function ttsQueryParams(p) {
    return {
        user_id: $("#user_id").val(),
        is_saved: $("#is_saved").val(),
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search,
    };
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

// users_tts

//user
function user_formater(index, row) {
    var html = [];
    $.each(row, function (key, value) {
        if (key != "image" && key != "operations") {
            html.push("<p><b>" + key + ":</b> " + value + "</p>");
        }
    });
    return html.join("");
}
//user
function refresh_table(id) {
    $("#" + id).bootstrapTable("refresh");
}
if (document.getElementById('system-update-dropzone')) {

    var systemDropzone = new Dropzone("#system-update-dropzone", {
        url: baseUrl + '/admin/upload_update_file',
        paramName: "update_file",
        autoProcessQueue: false,
        parallelUploads: 1,
        maxFiles: 1,
        timeout: 360000,
        autoDiscover: false,
        addRemoveLinks: true,
        dictRemoveFile: 'x',
        dictMaxFilesExceeded: 'Only 1 file can be uploaded at a time ',
        dictResponseError: 'Error',
        uploadMultiple: true,
        dictDefaultMessage: '<p><input type="button" value="Select Files" class="btn btn-success" /><br> or <br> Drag & Drop System Update / Installable / Plugin\'s .zip file Here</p>',
    });

    systemDropzone.on("addedfile", function (file) {
        var i = 0;
        if (this.files.length) {
            var _i, _len;
            for (_i = 0, _len = this.files.length; _i < _len - 1; _i++) {
                if (this.files[_i].name === file.name && this.files[_i].size === file.size && this.files[_i].lastModifiedDate.toString() === file.lastModifiedDate.toString()) {
                    this.removeFile(file);
                    i++;
                }
            }
        }
    });

    systemDropzone.on("error", function (file, response) {
        console.log(response);
    });

    systemDropzone.on('sending', function (file, xhr, formData) {
        formData.append(csrfName, csrfHash);
        xhr.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                var response = JSON.parse(this.response);
                csrfName = response.csrfName;
                csrfHash = response.csrfHash;
                if (response['error'] == false) {
                    showToastMessage(response['message'], "success");
                } else {
                    showToastMessage(response['message'], "error");

                }
                $(file.previewElement).find('.dz-error-message').text(response.message);
            }
        };
    });
    $('#system_update_btn').on('click', function (e) {
        e.preventDefault();
        systemDropzone.processQueue();
    });
}
$(document).ready(function () {
    setTimeout(() => {
        $('#timezone').select2();
    }, 100)
});
$('#timezone').on('change', () => {
    var gmt = $('#timezone').find(':selected').data('gmt');
    $("#system_timezone_gmt").val(gmt);
});
$("#timezone").val($("#set").val()).trigger("change");