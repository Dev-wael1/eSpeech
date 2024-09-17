"use strict";
var tenure_table = $("#tenure_table").bootstrapTable();
document.getElementById("userIdentity").selectedIndex = -1;
document.getElementById("plan").selectedIndex = -1;
$(document).ready(() => {
  //select2
  setTimeout(() => {
    $("#userIdentity").select2({
      placeholder: "Select user",
    });
    $("#plan").select2({
      placeholder: "Select Plan",
    });
    $("#planTenure").select2({
      placeholder: "Select Tenure",
    });
   
  }, 100);
});



function detailFormatter(index, row) {
  var html = [];
  $.each(row, function (key, value) {
      if(key != "profile"){

          html.push("<p><b>" + key + ":</b> " + value + "</p>");
      }
  });
  return html.join("");
}

function set_name() {
  let req_body = {
    user_id: document.getElementById("userIdentity").value,
    [csrfName]: csrfHash,
  };
  $.ajax({
    url: baseUrl + "/admin/subscriptions/get-username",
    type: "POST",
    data: req_body,
    beforeSend: function () {
  
    },
    success: function (result) {
     
      csrfName = result["csrfName"];
      csrfHash = result["csrfHash"];
      var name = result["data"];
      $("#userName").val(name);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function get_plan_data() {
  $("#planType").val("");
  var option = "";
  $("#planTenure").html(option);
  let req_body = {
    plan_id: document.getElementById("plan").value,
    [csrfName]: csrfHash,
  };
  $.ajax({
    url: baseUrl + "/admin/subscriptions/get-plan-data",
    type: "POST",
    data: req_body,
    success: function (result) {
      csrfName = result["csrfName"];
      csrfHash = result["csrfHash"];
      if (result["error"] == false) {
        result = result["data"];
        $("#planType").val(result["type"]);
        var tenures = result["tenures"];

        tenures.forEach((tenure) => {
         
          option =
            option +
            `<option value="` +
            tenure["id"] +
            `">` +
            tenure["title"] +
            `</option>`;
        });
        $("#planTenure").html(option);
      }
    },
    error: function (error) {
      console.log(error);
    },
  }).then(() => {
    get_price();
  });
}

function get_price() {
  var tenure_id = document.getElementById("planTenure").value;
  let req_body = {
    tenure_id: tenure_id,
    [csrfName]: csrfHash,
  };
  let price;
  $.ajax({
    url: baseUrl + "/admin/subscriptions/get-price",
    type: "POST",
    data: req_body,
    success: function (result) {
      csrfName = result["csrfName"];
      csrfHash = result["csrfHash"];
      if (result["error"] == false) {
        price = result["data"]["price"];
        $("#price").val(price);
        $("#months").val(result["data"]["months"]);
      }
    },
    error: function (error) {
      console.log(error);
    },
  }).then(() => {
    set_date();
  });
  return price;
}
let date;
function set_date() {
  var currentDate = moment(date);

  var futureMonth = moment(currentDate).add($("#months").val(), "M");
  var futureMonthEnd = moment(futureMonth).endOf("month");

  if (
    currentDate.date() != futureMonth.date() &&
    futureMonth.isSame(futureMonthEnd.format("YYYY-MM-DD"))
  ) {
    futureMonth = futureMonth.add(1, "d");
  }
  $("#ends_from").val(futureMonth.format("YYYY-MM-DD"));
}
function handler(e){
    date = e.target.value;
    set_date();
}
function add_subscription() {
  var user;
  var tenure;
  var plan;

  if (
    (user = $("#userIdentity").val()) &&
    (tenure = $("#planTenure").val()) &&
    (plan = $("#plan").val())
  ) {
    let req_body = {
      tenure_id: tenure,
      plan_id: plan,
      user_id: user,
      starts_from: $("#starts_from").val(),
      [csrfName]: csrfHash,
    };
    $.ajax({
      url: baseUrl + "/admin/subscriptions/add-subscription",
      type: "POST",
      data: req_body,
      success: function (result) {
        csrfName = result["csrfName"];
        csrfHash = result["csrfHash"];
        if (result["error"] == false) {
          var table = $("#subscription_table");
          showToastMessage(result.message,"success")
          table.bootstrapTable("refresh");
        }else{
            showToastMessage(result.message,"error")

        }
      },
      error: function (error) {
        console.log(error);
      },
    });
  } else {
    alert("please select all fields..");
  }
}

document.getElementById("add").addEventListener("click", () => {
  add_subscription();
});
