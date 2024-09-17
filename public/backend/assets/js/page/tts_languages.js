/* get languages query params */
function tts_language_events(p) {

    // console.log(language);
    return {
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search,
    };
}

$(document).on('click', '#update_lang_btn', function (e) {
    e.preventDefault();
    console.log("update language button clicked.");
    $('#status').on('change', function () {
        this.value = this.checked ? 1 : 0;
        // alert(this.value);
    }).change();


    let formdata = new FormData(document.getElementById("edit_tts_lan"));
    formdata.append(csrfName, csrfHash);
    formdata.append('status', $('#status').val());

    $.ajax({
        type: "POST",
        url: baseUrl + "admin/tts_languages/update-tts-language",
        data: formdata,
        cache: false,
        beforeSend: function () {
        },
        processData: false,
        contentType: false,
        success: function (response) {
            $('#edit_lan_Modal').modal('hide');
            // console.log(response);
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
                $('#tts_languages').bootstrapTable('refresh')
            }, 2000)
        }

    });
})