"use strict";


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

function afterLoad() {
    let select = $("#planTenureDuration");
    for (let i = 1; i <= 36; i++) {
        let e = `<option value='${i}'>${i} month</option>`;
        select.append(e);
    }

}
$(document).ready(() => {

    showHidePlanType();
})
var html;

function showHidePlanType() {
    let plantype = $("#planType option:selected").val();
    $("#planServiceProviderCharacter1").hide();
    $("#planServiceProviderCharacter2").hide();
    $("#planCharacters").hide();

    if (plantype == "general") {
        $("#planCharacters").show();
    } else if (plantype === "provider") {
        $("#planServiceProviderCharacter2").show();
        $("#planServiceProviderCharacter1").show();
    }
}


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

/* Function binding */
$(document).ready(afterLoad);
$("#planType").change(showHidePlanType);

$(document).on('click', ".remove-tenure-item", function (e) {
    e.preventDefault();
    $(this).parent().parent().remove();
});

$(document).on("click", "#add_tenure", function (e) {
    e.preventDefault();
    validate_tenure();
});

function validate_tenure() {
    var tenure = $('#tenure').val();
    var price = $('#price').val();
    var months = $('#months').val();
    var discounted_price = $('#discounted_price').val();
    if (tenure == null || tenure == "") {
        iziToast.error({
            title: 'Error!',
            message: "Tenure cannot be blank",
            position: 'topRight'
        });
    } else if (price == null || price == "") {
        iziToast.error({
            title: 'Error!',
            message: "Price cannot be blank",
            position: 'topRight'
        });
    } else {
        html = '<div class="tenure-item py-1"><div class="row"><div class="col-md-3 custom-col">' +
            '<input type="text" class="form-control" class="tenure" name="tenure[]" placeholder="Ex. Weekly, Quarterly, Monthly, Yearly" value="' + tenure + '" required></div>' +
            '<div class="col-md-3 custom-col">' +
            '<select class="form-control" class="months" name="months[]" required>' +
            '<option value="1" ' + ((months == 1) ? "selected" : "") + '>1</option><option value="2" ' + ((months == 2) ? "selected" : "") + '>2</option><option value="3" ' + ((months == 3) ? "selected" : "") + '>3</option><option value="4" ' + ((months == 4) ? "selected" : "") + '>4</option>' +
            '<option value="5" ' + ((months == 5) ? "selected" : "") + '>5</option><option value="6" ' + ((months == 6) ? "selected" : "") + '>6</option><option value="7" ' + ((months == 7) ? "selected" : "") + '>7</option><option value="8" ' + ((months == 8) ? "selected" : "") + '>8</option><option value="9" ' + ((months == 9) ? "selected" : "") + '>9</option><option value="10" ' + ((months == 10) ? "selected" : "") + '>10</option>' +
            '<option value="11" ' + ((months == 11) ? "selected" : "") + '>11</option><option value="12" ' + ((months == 12) ? "selected" : "") + '>12</option><option value="13" ' + ((months == 13) ? "selected" : "") + '>13</option><option value="14" ' + ((months == 14) ? "selected" : "") + '>14</option><option value="15" ' + ((months == 15) ? "selected" : "") + '>15</option><option value="16" ' + ((months == 16) ? "selected" : "") + '>16</option>' +
            '<option value="17" ' + ((months == 17) ? "selected" : "") + '>17</option><option value="18" ' + ((months == 18) ? "selected" : "") + '>18</option><option value="19" ' + ((months == 19) ? "selected" : "") + '>19</option><option value="20" ' + ((months == 20) ? "selected" : "") + '>20</option><option value="21" ' + ((months == 21) ? "selected" : "") + '>21</option><option value="22" ' + ((months == 22) ? "selected" : "") + '>22</option>' +
            '<option value="23" ' + ((months == 23) ? "selected" : "") + '>23</option><option value="24" ' + ((months == 24) ? "selected" : "") + '>24</option><option value="25" ' + ((months == 25) ? "selected" : "") + '>25</option><option value="26" ' + ((months == 26) ? "selected" : "") + '>26</option><option value="27" ' + ((months == 27) ? "selected" : "") + '>27</option><option value="28" ' + ((months == 28) ? "selected" : "") + '>28</option>' +
            '<option value="29" ' + ((months == 29) ? "selected" : "") + '>29</option><option value="30" ' + ((months == 30) ? "selected" : "") + '>30</option><option value="31" ' + ((months == 31) ? "selected" : "") + '>31</option><option value="32" ' + ((months == 32) ? "selected" : "") + '>32</option><option value="33">33</option><option value="34" ' + ((months == 34) ? "selected" : "") + '>34</option>' +
            '<option value="35" ' + ((months == 35) ? "selected" : "") + '>35</option><option value="36" ' + ((months == 36) ? "selected" : "") + '>36</option></select></div>' +
            '<div class="col-md-2 custom-col"><input type="number" class="form-control" class="price" name="price[]" min="0" placeholder="0.00" value="' + price + '" required>' +
            '</div><div class="col-md-2 custom-col"><input type="number" class="form-control" class="discounted_price" name="discounted_price[]" min="0" value="' + discounted_price + '" placeholder="0.00"></div>' +
            ' <div class="col-md-1 custom-col"><button class="btn btn-icon btn-danger remove-tenure-item" name="remove_tenure"><i class="fas fa-trash"></i></button></div>'
        '</div></div></div>';
        $('#tenures').append(html);
        $('#tenure').val('');
        $('#price').val('');
        $('#months').val('');
        $('#months').attr('selectedIndex', 0);;

        $('#discounted_price').val('');
    }
}