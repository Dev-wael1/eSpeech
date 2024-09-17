/* ------------------------------
Js for User/Dashboard/text_to_speech
---------------------------------*/


setInterval(()=>{
  var count = $("#text").val().length;

  $("#limit").text(count);
},100);
/*------------------------------------
* Charts
------------------------------------ */
if ($("#usageChart").length > 0) {
  var ChartU = new Chart(document.getElementById("usageChart"), {
    type: "bar",
    data: {
      labels: ["Google", "AWS Polly", "IBM Whatson", "Microsoft Azure"],
      datasets: [
        {
          label: "Google Cloud",
          data: [12, 25, 24, 10],
          backgroundColor: "rgb(99, 99, 92)",
          borderWidth: 0,
          borderRadius: 100,
        },
      ],
    },
  });
}
if ($("#earningChart").length > 0) {
  var ChartE = new Chart(document.getElementById("earningChart"), {
    type: "bar",
    data: {
      labels: ["Google", "AWS Polly", "IBM Whatson", "Microsoft Azure"],
      datasets: [
        {
          label: "Google Cloud",
          data: [12, 25, 24, 10],
          backgroundColor: "rgb(255, 99, 132)",
          borderColor: "rgba(75, 192, 192, 1)",
          borderWidth: 0,
          borderRadius: 100,
        },
      ],
    },
  });
}

/*------------------------------------
* Table
------------------------------------ */
if ($("#Table").length > 0) {
  $("#Table").bootstrapTable({
    pagination: true,
    search: true,
    data: getData(1000, false),
  });

  var total = 0;

  function getData(number, isAppend) {
    if (!isAppend) {
      total = 0;
    }

    var data = [];

    for (var i = total; i < total + number; i++) {
      data.push({
        sr: i,
        name: "User " + i,
        ssdate: "2021, 02, 2",
        sedate: "2022, 02, 1",
        sid: i + 5000,
        price: "$" + (i + 9999),
      });
    }
    return data;
  }
}
