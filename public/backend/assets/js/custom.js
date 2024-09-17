/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 *
 */

"use strict";

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
if ($(".summernotes").length) {
    tinymce.init({
        selector: ".summernotes",
        height: 350,
        menubar: false,
        plugins: [
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table paste",
        ],
        toolbar: "undo redo | styleselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link code",
    });
}

function set_locale(language_code) {
    $.ajax({
        url: baseUrl + "/lang/" + language_code,
        type: "GET",
        success: function (result) {
            location.reload();
        }
    });
}

/* remove language link */
$(".delete-language-btn").on("click", function (e) {
    e.preventDefault();
    if (confirm("Are you sure want to delete language?")) {
        window.location.href = $(this).attr('href');
    }
});


/* bank_transfers query params */
function bank_transfer_params(p) {
    var subscription_id = ($("#subscription_id").val()) ? $("#subscription_id").val() : "";
    // console.log(subscription_id);
    return {
        user_id: $("#user_id").val(),
        subscription_id: subscription_id,
        is_saved: $("#is_saved").val(),
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search,
    };
}

$(document).ready(function () {
    $(document).on("click", '.view-reciepts', function () {
        var subscription_id = $(this).attr("data-id");
        $('#subscription_id').val(subscription_id);
        $('#bank_transfer').bootstrapTable("refresh");
    });
});

$(document).ready(function () {
    $(document).on("click", '.active_subscription', function () {
        console.log('btn clicked');
        var user_id = $(this).attr("data-uid");
        var subscription_id = $(this).attr("data-sid");
        // console.log(user_id);
        // console.log(subscription_id);
        var input_body = {
            [csrfName]: csrfHash,
            'subscription_id': subscription_id,
            'user_id': user_id
        };
        console.log(input_body);
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to active subscription",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Proceed!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: baseUrl + "/admin/bank_transfers/activate_subscription",
                    data: input_body,
                    // dataType: "JSON",
                    success: function (response) {
                        console.log(response);
                        if (response.error == false) {
                            showToastMessage(response.message, "success");
                            setTimeout(() => {
                                $('#bank_transfer').bootstrapTable('refresh')
                            }, 2000)
                        } else {
                            showToastMessage(response.message, "error");
                            setTimeout(() => {
                                $('#bank_transfer').bootstrapTable('refresh')
                            }, 2000)
                        }
                    }
                });
            }
        });
    })
})

function receipt_check(element) {
    $('#bank_transfer_id').val($(element).data('id'));
    $('#user_id').val($(element).data('uid'));
}


// ajax 
// for checking reciept form admin-side

$(document).ready(function () {
    let status = $('input[type=radio][name=pending]');
    $('#reciept_check_form').on('submit', function (e) {
        e.preventDefault();

        if ($('#pending').is(':checked')) {
            swal.fire({
                title: "Status Change",
                text: "you must change status to either accepted or rejected",
                icon: "warning",
            });
            return false;
        }

        var formData = new FormData(this);
        formData.append(csrfName, csrfHash);
        $.ajax({
            type: $(this).attr("method"),
            url: $(this).attr("action"),
            data: formData,
            dataType: "json",
            beforeSend: function () {
                $("#update_receipt_btn").attr("disabled", true);
                $("#update_receipt_btn").html("Updating.. .");
            },
            processData: false,
            contentType: false,
            success: function (result) {
                console.log(result);
                /* setting new CSRF for the next request */
                csrfName = result.csrfName;
                csrfHash = result.csrfHash;

                $("#update_receipt_btn").html("Uploading receipt");
                $("#update_receipt_btn").attr("disabled", false);

                if (result.error == false) {
                    iziToast.success({
                        title: "Success",
                        message: result.message,
                        position: "topRight",
                    })
                    $('.close').click();
                    window.location.reload();
                } else {
                    iziToast.error({
                        title: "Error",
                        message: result.message,
                        position: "topRight",
                    })
                    window.location.reload();
                }
            },
        });
    });
});

// for uploading reciept form custome-side

$(document).ready(function () {
    $('#upload_form').on("submit", function (e) {
        e.preventDefault();

        let formdata = new FormData(this);
        formdata.append(csrfName, csrfHash);
        console.log(formdata);
        $.ajax({
            type: $(this).attr('method'),
            url: $(this).attr('action'),
            data: formdata,
            dataType: "json",
            cache: false,
            beforeSend: function () {
                $("#update_receipt_btn").attr("disabled", true);
                $("#update_receipt_btn").html("Updating.. .");
            },
            processData: false,
            contentType: false,
            success: function (result) {
                csrfName = result.csrfName;
                csrfHash = result.csrfHash;

                $("#update_receipt_btn").html("Uploading receipt");
                $("#update_receipt_btn").attr("disabled", false);

                if (result.error == false) {
                    iziToast.success({
                        title: "Success",
                        message: result.message,
                        position: "topRight",
                    })
                    $('.close').click();
                    window.location.reload();
                } else {
                    iziToast.error({
                        title: "Error",
                        message: result.message,
                        position: "topRight",
                    })
                    window.location.reload();
                }
            }
        });
    });
});

// for active subscription form adminside-side

$(document).ready(function () {
    $('#active_subscriptions_form').on("submit", function (e) {
        e.preventDefault();
        let formdata = new FormData($(this)[0]);
        formdata.append(csrfName, csrfHash);
        console.log(formdata);
        $.ajax({
            type: $(this).attr('method'),
            url: $(this).attr('action'),
            data: formdata,
            dataType: "json",
            beforeSend: function () {
                $("#active_btn").attr("disabled", true);
                $("#active_btn").html("Activating...");
            },
            processData: false,
            contentType: false,
            success: function (result) {
                csrfName = result.csrfName;
                csrfHash = result.csrfHash;
                console.log(result);
                $("#active_btn").html("Uploading receipt");
                $("#active_btn").attr("disabled", false);

                if (result.error == false) {
                    iziToast.success({
                        title: "Success",
                        message: result.message,
                        position: "topRight",
                    })
                    $('.close').click();
                    window.location.reload();
                } else {
                    iziToast.error({
                        title: "Error",
                        message: result.message,
                        position: "topRight",
                    })
                    window.location.reload();
                }
            }
        });
    });
});

// user activation and deactivation

function activate_user(element) {
    $('#user_id_active').val($(element).data('uid'));
}

function deactivate_user(element) {
    $('#user_id').val($(element).data('uid'));
}

$(document).ready(function () {
    $('#deactivate_user_form').on('submit', function (e) {
        e.preventDefault();
        let formdata = new FormData(this);
        formdata.append(csrfName, csrfHash);
        console.log(formdata);
        $.ajax({
            type: $(this).attr('method'),
            url: $(this).attr('action'),
            data: formdata,
            dataType: "json",
            cache: false,
            beforeSend: function () {
                $("#deactive_btn").attr("disabled", true);
                $("#deactive_btn").html("Deactivating.. .");
            },
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.error == false) {
                    iziToast.success({
                        title: "Success",
                        message: response.message,
                        position: "topRight",
                    })
                    $("#deactive_btn").attr("disabled", false);
                    $("#deactive_btn").html("Deactivate User");
                    $('.close').click();
                    window.location.reload();
                } else {
                    iziToast.error({
                        title: "Error",
                        message: response.message,
                        position: "topRight",
                    })
                    $('.close').click();
                    window.location.reload();
                }
            }
        });
    });
});
$(document).ready(function () {
    $('#activate_user_form').on('submit', function (e) {
        e.preventDefault();
        let formdata = new FormData(this);
        formdata.append(csrfName, csrfHash);
        console.log(formdata);
        $.ajax({
            type: $(this).attr('method'),
            url: $(this).attr('action'),
            data: formdata,
            dataType: "json",
            cache: false,
            beforeSend: function () {
                $("#activate_btn").attr("disabled", true);
                $("#activate_btn").html("Activating.. .");
            },
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.error == false) {
                    iziToast.success({
                        title: "Success",
                        message: response.message,
                        position: "topRight",
                    })
                    $("#activate_btn").attr("disabled", false);
                    $("#activate_btn").html("Activated...");
                    $('.close').click();
                    window.location.reload();
                } else {
                    iziToast.error({
                        title: "Error",
                        message: response.message,
                        position: "topRight",
                    })
                    $('.close').click();
                    window.location.reload();
                }
            }
        });
    });
});

// this for change in payment gateway link of the paypal
// not sure if it will work or not

$(document).ready(function () {
    $('#paypal_mode').on('change', function (e) {
        // e.preventDefault();
        // ale0rt('mode changed');
        if ($(this).val() == 'test') {
            $('#end_point_url').val('https://api-m.sandbox.paypal.com/');
        } else {
            $('#end_point_url').val('https://api-m.paypal.com/');
        }
    });

    // console.log();

    $('input[type=checkbox][name=activate_registration]').change(function (e) {
        e.preventDefault();
        console.log('is');
        if ($('input[type=checkbox][name=activate_registration]').is(':checked')) {
            $('#activate_text').text('Active')
        } else {
            $('#activate_text').text('Inactive')
        }
    });

    $('input[type=checkbox][name=template_status]').change(function (e) {
        e.preventDefault();
        console.log('is');
        if ($('input[type=checkbox][name=template_status]').is(':checked')) {
            $('#template_status_text').text('Active')
        } else {
            $('#template_status_text').text('Inactive')
        }
    });

    $('input[type=checkbox][name=allow_view_keys]').change(function (e) {
        e.preventDefault();
        console.log('is');
        if ($('input[type=checkbox][name=allow_view_keys]').is(':checked')) {
            $('#key_text').text('Active')
        } else {
            $('#key_text').text('Inactive')
        }
    });

});

var template_id = '';

window.template_events = {
    'click .delete-template': function (e, val, row, index) {
        console.log(row);

        var input_body = {
            [csrfName]: csrfHash,
            'id': row.id,
        };
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to delete this TTS !",
            icon: 'error',
            showCancelButton: true,
            confirmButtonText: 'Yes, Proceed!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: baseUrl + "/admin/mail-templates/delete-mail-template",
                    data: input_body,
                    dataType: "JSON",
                    success: function (response) {
                        if (response.error) {
                            showToastMessage(response.message, "success");
                            setTimeout(() => {
                                $('#mail_list').bootstrapTable('refresh')
                            }, 2000)
                        } else {
                            showToastMessage(response.message, "error");
                            setTimeout(() => {
                                $('#mail_list').bootstrapTable('refresh')
                            }, 2000)
                        }
                    }
                });
            }
        });

    }
}

$(document).ready(function () {
    $('#add_template_btn').click(function (e) {
        e.preventDefault();
        if ($('#template_body').hasClass('d-none')) {
            $("#template_body").fadeIn('slow');
            $("#template_body").removeClass('d-none');
            $('#add_template_btn').html('<i class="fas fa-times h5"> </i>');
            $("#add_template_btn").removeClass('btn-block btn-primary');
            $('#add_template_btn').addClass('btn-danger');
        } else {
            var promise = new Promise(function (resolve, reject) {
                $("#template_body").fadeOut('slow');
                if ($('#template_body').css('display', 'none')) {
                    resolve();
                } else {
                    reject();
                }
            });
            promise.
                then(function () {
                    $("#template_body").addClass('d-none');
                    $('#add_template_btn').addClass('btn-primary');
                    $('#add_template_btn').removeClass('p-4');
                    $('#add_template_btn').html('<i class="fas fa-pen"> </i>');
                    $('#mail_template').trigger("reset");
                }).
                catch(function () { });
        }
    });

    $('#mail_template').on('submit', function (e) {
        e.preventDefault();
        console.log(template_id);
        // return;
        console.log('submitted');
        let formdata = new FormData(this);
        formdata.append(csrfName, csrfHash);
        formdata.append('id', template_id);

        $.ajax({
            type: "POST",
            url: baseUrl + "/mail-templates/add-mail-template",
            data: formdata,
            dataType: "json",
            cache: false,
            beforeSend: function () {
                $("#activate_btn").attr("disabled", true);
                $("#activate_btn").html("Activating.. .");
            },
            processData: false,
            contentType: false,
            success: function (response) {
                csrfName = response['csrfName'];
                csrfHash = response['csrfHash'];
                if (response.error) {
                    console.log(response);
                    iziToast.success({
                        title: "Success",
                        message: response.message,
                        position: "topRight",
                    });
                    window.location.reload();
                } else {
                    if (typeof (response.message) == 'object') {

                        var msgs = Object.values(response.message)
                        msgs.forEach(element => {
                            iziToast.error({
                                title: "error",
                                message: element,
                                position: "topRight",
                            })
                        });
                    } else {
                        iziToast.error({
                            title: "error",
                            message: response.message,
                            position: "topRight",
                        })
                    }
                    window.location.reload();
                }
            }
        });

    });
});

// for links


$(document).ready(function () {
    $('#mail_tye').select2();

    $('.mail_type').change(function (e) {
        e.preventDefault();
        tinyMCE.get('mail_text').setContent('');
        $('#subject_of_mail').val('');
        $('#tags').empty();
        // console.log($('.mail_type').val());
        var type = $('.mail_type').val();
        var input_body = {
            [csrfName]: csrfHash,
            'mail_type': type,
        };
        console.log(input_body);
        $.ajax({
            type: "POST",
            url: baseUrl + "/mail-templates/fetch-mail-type-data",
            data: input_body,
            dataType: "json",
            success: function (response) {
                csrfName = response['csrfName'];
                csrfHash = response['csrfHash'];
                if (response.error == true) {
                    iziToast.error({
                        title: "error",
                        message: response.message,
                        position: "topRight",
                    })
                } else {
                    console.log(response);

                    var data = JSON.parse(response.data);
                    if (data.total == 0) {
                        iziToast.error({
                            title: "error",
                            message: "Currently there is no Template available on this type please add",
                            position: "topRight",
                        });
                    } else {
                        var text = Object.values(data.rows);
                        console.log(data);
                        console.log(type);
                        var text_of_mail = text[0].email_text_for_edit;

                        $('#subject_of_mail').val(text[0].email_subject);
                        tinyMCE.get('mail_text').setContent(text_of_mail);
                    }
                    if (type == 'forgot_password') {
                        $('#tags').append("{user_name} {link} {company_title}");
                    } else if (type == 'subscription') {
                        $('#tags').append("{user_name} {company_title}");
                    } else if (type == 'deactivate_user') {
                        $('#tags').append("{user_name} {support_email} {company_title}");
                    } else if (type == 'activate_user') {
                        $('#tags').append("{user_name} {company_title}");
                    } else if (type == 'activate_user') {
                        $('#tags').append("{user_name} {company_title}");
                    } else if (type == 'activate_new_user') {
                        $('#tags').append("{user_name} {first_name} {link} {company_title}");
                    } else if (type == 'contact_us') {
                        $('#tags').append("{user_name} {first_name} {link} {company_title}");
                    } else if (type == 'receipt_rejected' || type == 'receipt_accepted') {
                        $('#tags').append("{user_name} {first_name} {amount} {transaction_id} {message} {company_title}");
                    } else if (type == 'activate_subscription') {
                        $('#tags').append("{first_name} {last_name} {amount} {transaction_id}  {start_date} {expiry_date} {month} {support_email}  {company_title}");
                    } else {
                        $('#tags').empty();
                    }
                }
            }
        });
    });
});


// for updating all the providers
function updateVoice() {

    var input_body = {
        [csrfName]: csrfHash,
    };
    var old_btn_html = $("#updateVoices").html();
    $.ajax({
        type: "POST",
        url: baseUrl + "/admin/update-all-voices",
        data: input_body,
        dataType: "JSON",
        beforeSend: function () {
            $("#updateVoices").html("Updating.. please wait.").attr("disabled", true);

        },
        success: function (response) {
            console.log(response);
            csrfName = response['csrfName'];
            csrfHash = response['csrfHash'];
            $("#updateVoices").html(old_btn_html).attr("disabled", false);
            if (response.error) {
                showToastMessage(response.message, "success");
                setTimeout(() => {
                    $('#mail_list').bootstrapTable('refresh')
                }, 2000)
            } else {
                showToastMessage(response.message, "error");
                setTimeout(() => {
                    $('#mail_list').bootstrapTable('refresh')
                }, 2000)
            }
        }
    });
}

