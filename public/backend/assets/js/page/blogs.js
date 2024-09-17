$(document).ready(function () {

    $('#blogform').submit(function (e) {
        e.preventDefault();
        let formdata = new FormData(this);
        formdata.append(csrfName, csrfHash);
        console.log(formdata);
        $.ajax({
            url: baseUrl + "admin/blogs/add-blog",
            type: "post",
            data: formdata,
            processData: false,
            contentType: false,
            cache: false,
            async: false,
            success: function (data) {
                console.log(data.message);
                csrfName = data['csrfName'];
                csrfHash = data['csrfHash'];
                if (data.error == false) {
                    iziToast.success({
                        title: "Success",
                        message: data.message,
                        position: "topRight",
                    })
                    setTimeout(() => {
                        document.location.href = baseUrl + "admin/blogs";
                        $('#blogs').bootstrapTable('refresh')
                    }, 2000)
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
            }
        });
    });

    $('#updateblog').submit(function (e) {
        e.preventDefault();
        let formdata = new FormData(this);
        formdata.append(csrfName, csrfHash);
        console.log(formdata);
        $.ajax({
            url: baseUrl + "admin/blogs/update-blog",
            type: "post",
            data: formdata,
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
                    setTimeout(() => {
                        document.location.href = baseUrl + "admin/blogs";
                        $('#blogs').bootstrapTable('refresh')
                    }, 2000)
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
            }
        });
    });
});
