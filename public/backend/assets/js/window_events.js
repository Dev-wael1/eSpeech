"use strict";

// this JS is only and only for window events over bootstrap events

window.tts_events = {
    'click .delete': function (e, value, row, index) {
        console.log(row);
        var input_body = {
            [csrfName]: csrfHash,
            'tts_id': row.id,
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
                    url: baseUrl + "/admin/text-to-speech/delete-tts",
                    data: input_body,
                    dataType: "JSON",
                    success: function (response) {
                        if (response.error == false) {
                            showToastMessage(response.message, "success");
                            setTimeout(() => {
                                $('#tts_table').bootstrapTable('refresh')
                            }, 2000)
                        } else {
                            showToastMessage(response.message, "error");
                            setTimeout(() => {
                                $('#tts_table').bootstrapTable('refresh')
                            }, 2000)
                        }
                    }
                });
            }
        });
    }
}

window.blog_events = {
    'click .delete': function (e, value, row, index) {
        // console.log(row);
        var input_body = {
            [csrfName]: csrfHash,
            'blog_id': row.id,
        };
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to delete this Blog !",
            icon: 'error',
            showCancelButton: true,
            confirmButtonText: 'Yes, Proceed!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: baseUrl + "/admin/blogs/delete-blog",
                    data: input_body,
                    dataType: "JSON",
                    success: function (response) {
                        if (response.error == false) {
                            showToastMessage(response.message, "success");
                            setTimeout(() => {
                                $('#blogs').bootstrapTable('refresh')
                            }, 2000)
                        } else {
                            showToastMessage(response.message, "error");
                            setTimeout(() => {
                                $('#blogs').bootstrapTable('refresh')
                            }, 2000)
                        }
                    }
                });
            }
        });
    }

}

window.review_events = {
    'click .delete': function (e, value, row, index) {
        // console.log(row);
        var input_body = {
            [csrfName]: csrfHash,
            'review_id': row.id,
        };
        console.log(input_body);
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to delete this Review !",
            icon: 'error',
            showCancelButton: true,
            confirmButtonText: 'Yes, Proceed!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: baseUrl + "/admin/reviews/delete-review",
                    data: input_body,
                    dataType: "JSON",
                    success: function (response) {
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
    }

}

window.bank_events = {
    'click .delete': function (e, value, row, index) {
        console.log(row);

        var input_body = {
            [csrfName]: csrfHash,
            'id': row.id,
        };
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to delete !",
            icon: 'error',
            showCancelButton: true,
            confirmButtonText: 'Yes, Proceed!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: baseUrl + "/admin/bank_transfers/delete_transaction",
                    data: input_body,
                    dataType: "JSON",
                    success: function (response) {
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
    }
}

window.voice_events = {
    'click .edit': function (e, value, row, index) {
        // console.log(row);
        $("#id").val(row.id);
        $("#tts_language").val(row.language);
        $("#tts_voice").val(row.voice);
        $("#display_name").val(row.display_name);
        // $("#icon").val(row.icon);
        // console.log(row.gender_base);
        if (row.gender_base == 'female') {
            $("#female").prop('checked', 'checked');
        } else if (row.gender_base == 'male') {
            $("#male").prop('checked', 'checked');
        } else {
            $('#male').prop('checked', false);
            $('#female').prop('checked', false);
        }
        // $("#status").val(row.status);
        if (row.status == 1) {
            $("#status").prop('checked', true);
        } else if (row.status == 0) {
            $('#status').prop('checked', false);
        }
    }
}

window.language_events = {
    'click .edit_tts_language': function (e, value, row, index) {
        console.log(row);
        $("#lan_id").val(row.id);
        $("#language_code").val(row.language_code);
        $("#language_name").val(row.language_name);
        if (row.status == 1) {
            $("#status").prop('checked', true);
        } else if (row.status == 0) {
            $('#status').prop('checked', false);
        }
    }
}

var link_id;
window.link_events = {
    'click .delete-link': function (e, value, row, index) {
        console.log(row);

        var input_body = {
            [csrfName]: csrfHash,
            'id': row.id,
        };
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to delete this Link!",
            icon: 'error',
            showCancelButton: true,
            confirmButtonText: 'Yes, Proceed!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: baseUrl + "/admin/settings/add-links/delete-link",
                    data: input_body,
                    dataType: "JSON",
                    success: function (response) {
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
    },
    'click .edit-link': function (e, value, row, index) {
        console.log(row);
        link_id = row.id;
        $(window).scrollTop(0);

        $('#site_name').val(row.site_name);
        $('#site_url').val(row.site_url);

        $('#file_ls').removeClass('col-sm');
        $('#site-img').removeClass('d-none');

        $('#site_img').attr('src', row.icon_for_edit);
        $('#file_ls').addClass('col-sm-6 mt-5');
    }
}

$(document).ready(function () {
    $('#social_links').on('submit', function (e) {
        e.preventDefault();
        let formdata = new FormData(this);
        formdata.append(csrfName, csrfHash);
        formdata.append('id', link_id);
        console.log(formdata);

        $.ajax({
            type: "POST",
            url: baseUrl + "/admin/settings/add-links/add-link",
            data: formdata,
            dataType: "json",
            cache: false,
            beforeSend: function () {
                // $("#update").attr("disabled", true);
                // $("#update").html("Activating.. .");
            },
            processData: false,
            contentType: false,
            success: function (response) {
                // console.log(response);
                csrfName = response['csrfName'];
                csrfHash = response['csrfHash'];
                if (response.error == false) {
                    console.log(response);
                    iziToast.success({
                        title: "Success",
                        message: response.message,
                        position: "topRight",
                    });
                    // window.location.reload();
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
                }
            }
        });
    });

});
$(document).on('click', '#update_voice_btn', function (e) {
    e.preventDefault();
    console.log("button clicked.");
    $('#status').on('change', function () {
        this.value = this.checked ? 1 : 0;
        // alert(this.value);
    }).change();


    let formdata = new FormData(document.getElementById("edit_voice"));
    formdata.append(csrfName, csrfHash);
    formdata.append('status', $('#status').val());
    formdata.append('gender', $('input[name=gender]:checked', '#edit_voice').val());
    formdata.append('icon', $('#icon').val());

    $.ajax({
        type: "POST",
        url: baseUrl + "admin/voices/update-voices",
        data: formdata,
        cache: false,
        beforeSend: function () {
        },
        processData: false,
        contentType: false,
        success: function (response) {
            $('#editModal').modal('hide');
            csrfName = response['csrfName'];
            csrfHash = response['csrfHash'];
            if (response.error == false) {
                iziToast.success({
                    title: "Success",
                    message: response.message,
                    position: "topRight",
                })
            } else {
                var messages = response.message;
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
                $('#tts_voices').bootstrapTable('refresh')
            }, 2000)
        }

    });
})
