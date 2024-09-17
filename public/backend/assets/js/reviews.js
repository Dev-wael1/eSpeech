function set_name() {
    let req_body = {
      user_id: document.getElementById("userReview").value,
      [csrfName]: csrfHash,
    };
    $.ajax({
      url: baseUrl + "/admin/reviews/get-username",
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
$(document).ready(function () {


    $('#reviewformuser').on("submit",function (e) {
        e.preventDefault();
        console.log("save btn clicked");
        $.ajax({
            url: baseUrl + "user/review/send",
            type: "post",
            data: new FormData(this),
            processData: false,
            contentType: false,
            cache: false,
            async: false,
            success: function (data) {
                console.log(data);
                csrfName = data['csrfName'];
                csrfHash = data['csrfHash'];
                if (data.error == false) {
                    iziToast.success({
                        title: "Success",
                        message: data.message,
                        position: "topRight",
                    })
                } else {
                    var messages = data.message;
                    console.log(typeof messages);
                    if (typeof messages == "object") {
                        Object.values(messages).forEach(e => {
                            showToastMessage(e, 'error');
                        })
                    } else {
                        showToastMessage(messages, 'error');
                    }
                }
                setTimeout(() => {
                    document.location.href = baseUrl + "/user";
                }, 2000)
            }
        });
    });

    $('#reviewadmin').on("submit",function (e) {
        e.preventDefault();
        console.log("save btn clicked");
        $.ajax({
            url: baseUrl + "admin/review/send",
            type: "post",
            data: new FormData(this),
            processData: false,
            contentType: false,
            cache: false,
            async: false,
            success: function (data) {
                console.log(data.message['review']);

                csrfName = data['csrfName'];
                csrfHash = data['csrfHash'];
                if (data.error == false) {
                    iziToast.success({
                        title: "Success",
                        message: data.message,
                        position: "topRight",
                    })
                } else {
                    var messages = data.message;
                    console.log(typeof messages);
                    if (typeof messages == "object") {
                        Object.values(messages).forEach(e => {
                            showToastMessage(e, 'error');
                        })
                    } else {
                        showToastMessage(messages, 'error');
                    }
                }
                setTimeout(() => {
                    document.location.href = baseUrl + "/admin/reviews";
                    $('#reviews').bootstrapTable('refresh');
                }, 2000)
            }
        });
    });

    $(document).on("click", '.hide_review', function () {
        console.log('hide btn clicked');
        var review_id = $(this).attr("data-uid");
        console.log(review_id);

        var input_body = {
            [csrfName]: csrfHash,
            'review_id': review_id
        };
        console.log(input_body);
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to Show this Review",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Proceed!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: baseUrl + "/admin/reviews/show_review",
                    data: input_body,
                    dataType: "JSON",
                    success: function (response) {
                        csrfName = response['csrfName'];
                        csrfHash = response['csrfHash'];
                        console.log(response);
                        if (response.error == false) {
                            showToastMessage(response.message, "success");
                            setTimeout(() => {
                                $('#reviews').bootstrapTable('refresh')
                            }, 2000)
                        } else {
                            showToastMessage(response.message, "error");
                            setTimeout(() => {
                                $('#reviews').bootstrapTable('refresh')
                            }, 2000)
                        }
                    }
                });
            }
        });
    })

    $(document).on("click", '.show_review', function () {
        console.log('show btn clicked');
        var review_id = $(this).attr("data-uid");
        console.log(review_id);

        var input_body = {
            [csrfName]: csrfHash,
            'review_id': review_id
        };
        console.log(input_body);
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to Hide this Review",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Proceed!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: baseUrl + "/admin/reviews/hide_review",
                    data: input_body,
                    dataType: "JSON",
                    success: function (response) {
                        csrfName = response['csrfName'];
                        csrfHash = response['csrfHash'];
                        console.log(response);
                        if (response.error == false) {
                            showToastMessage(response.message, "success");
                            setTimeout(() => {
                                $('#reviews').bootstrapTable('refresh')
                            }, 2000)
                        } else {
                            showToastMessage(response.message, "error");
                            setTimeout(() => {
                                $('#reviews').bootstrapTable('refresh')
                            }, 2000)
                        }
                    }
                });
            }
        });
    })
})