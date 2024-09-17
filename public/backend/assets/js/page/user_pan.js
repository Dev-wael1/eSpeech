"use strict";

function display_discounted_price(index) {
    $("#price" + index).empty;
    $("#discounted_div").empty();
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
    if (discounted != 'undefined' && parseInt(discounted) > 0) {
        $("#price" + index).html(numberWithCommas(discounted) + `
        <div id="discounted_div">
            <h6> 
                <strike>${currency} ${value}</strike> 
            </h6>
        </div>`);
    } else {
        $("#price" + index).html(numberWithCommas(value));
    }
}
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
                if (data['old'] != "" && data['new'] != "") {
                    check = true;
                }
                $("#header_name").html(data["first_name"]);
                $("#f_name").html(data["first_name"]);
                $("#l_name").html(data["last_name"]);
                showToastMessage(result["message"], "success");
            } else {
                showToastMessage(result["message"], "error");
                return;
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

$("#pop").on("click", function () {
    $("#imagepreview").attr("src", $("#profile_picture").attr("src")); // here asign the image to the modal when the user click the enlarge link
    $("#imagemodal").modal("show"); // imagemodal is the id attribute assigned to the bootstrap modal, then i use the show function
});

let userId = users_id;
let start_date = "";
let end_date = "";
let subscription_status = "";
let date_filter_by = "";

function subscription_table_params(p) {
    return {
        user_id: userId,
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

function detailFormatter(index, row) {
    var html = [];
    $.each(row, function (key, value) {
        if (key != "id") {
            html.push("<p><b>" + key + ":</b> " + value + "</p>");
        }
    });
    return html.join("");
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
let txn_start_date = "";
let txn_end_date = "";
let transaction_status = "";
let txn_provider = "";

function transaction_params(p) {
    return {
        user_id: userId,
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

function detailFormatter(index, row) {
    var html = [];
    $.each(row, function (key, value) {
        if (key != "id" && key != "subscription_id") {
            html.push("<p><b>" + key + ":</b> " + value + "</p>");
        }
    });
    return html.join("");
}
$(function () {
    $("#txn_date").daterangepicker({
            opens: "left",
            showDropdowns: true,
            alwaysShowCalendars: true,
            ranges: {
                Today: [moment(), moment()],
                Yesterday: [moment().subtract(1, "days"), moment().subtract(1, "days")],
                "Last 7 Days": [moment().subtract(6, "days"), moment()],
                "Last 30 Days": [moment().subtract(29, "days"), moment()],
                "This Month": [moment().startOf("month"), moment().endOf("month")],
                "Last Month": [
                    moment().subtract(1, "month").startOf("month"),
                    moment().subtract(1, "month").endOf("month"),
                ],
            },
            startDate: moment().subtract(29, "days"),
            endDate: moment(),
            locale: {
                format: "DD/MM/YYYY",
                separator: " - ",
            },
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

function refresh_table(id) {
    $('#' + id).bootstrapTable('refresh');
}