"use strict";
let pre_payment_error = false;
let stripe1;
let stripe_flag = false;
$("#stripe_div").slideUp();
$("#bank_modal").slideUp();
$('#paypal_btn').slideUp();

function paystack_setup(
    key = $("#paystack_key_id").val(),
    user_email = "test@test.com",
    order_amount = $("#price").val(),
    currency = $("#paystack_currency").val()
) {
    var handler = PaystackPop.setup({
        key: key,
        email: user_email,
        amount: order_amount * 100,
        currency: currency,
        callback: function (response) {
            $("#paystack_reference").val(response.reference);
            if (response.status == "success") {
                $(".modal").modal("show");
                post_payment("paystack").done(function (result) {
                    if (result.error == false) {
                        setTimeout(function () {
                            $(".modal").modal("hide");
                            location.href = baseUrl + "/payment-success";
                        }, 500);
                    }
                });
            } else {
                $(".modal").modal("hide");

                location.href = baseUrl + "/payment-failed";
            }
        },
        onClose: function () {
            $("#place_order_btn").attr("disabled", false).html("Place Order");
        },
    });
    return handler;
}

function razorpay_script(
    razorpay_key,
    amount,
    company,
    razorpay_order_id,
    username,
    email,
    logo,
    phone,
    description = "Product Purchase",
    currency = $("#razorpay_currency").val()
) {


    var load_script = function (path) {
        var result = $.Deferred(),
            script = document.createElement("script");

        script.async = "async";
        script.type = "text/javascript";
        script.src = path;
        script.onload = script.onreadystatechange = function (_, isAbort) {
            if (!script.readyState || /loaded|complete/.test(script.readyState)) {
                if (isAbort) result.reject();
                else result.resolve();
            }
        };
        script.onerror = function () {
            result.reject();
        };
        $("head")[0].appendChild(script);
        return result.promise();
    };
    load_script("https://checkout.razorpay.com/v1/checkout.js").then(function () {
        var options = {
            key: razorpay_key, // Enter the Key ID generated from the Dashboard
            amount: amount * 100, // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
            currency: currency,
            name: company,
            description: description,
            image: logo,
            order_id: razorpay_order_id, //This is a sample Order ID. Pass the `id` obtained in the response of Step 1
            handler: function (response) {

                $("#razorpay_payment_id").val(response.razorpay_payment_id);
                $("#razorpay_signature").val(response.razorpay_signature);
                $(".modal").modal("show");
                post_payment("razorpay").done(function (result) {
                    if (result.error == false) {
                        setTimeout(function () {
                            $(".modal").modal("hide");
                            location.href = baseUrl + "/payment-success";
                        }, 500);
                    } else {
                        $(".modal").modal("show");
                        setTimeout(function () {
                            $(".modal").modal("hide");
                            location.href = baseUrl + "/payment-failed";
                        }, 500);
                    }
                });
            },
            prefill: {
                name: username,
                email: email,
                contact: phone,
            },
            notes: {
                address: username + "Purchase",
            },
            theme: {
                color: "#3399cc",
            },
            escape: false,
            modal: {
                ondismiss: function () {
                    $("#subscribe").attr("disabled", false).html("Buy Now");
                },
            },
        };
        window.rzpay = new Razorpay(options);
        rzpay.open();
    });
}

//  Paytm from prePaytm to post Paymrnt

function paytm_setup(txnToken, orderId, amount, app_name) {
    var config = {
        "root": "",
        "flow": "DEFAULT",
        "merchant": {
            "name": app_name,
            redirect: false
        },
        "style": {
            "headerBackgroundColor": "#8dd8ff",
            "headerColor": "#3f3f40"
        },
        "data": {
            "orderId": orderId,
            "token": txnToken,
            "tokenType": "TXN_TOKEN",
            "amount": amount,
        },
        "handler": {
            "notifyMerchant": function (eventName, data) {
                if (eventName == 'SESSION_EXPIRED') {
                    alert("Your session has expired!!");
                    location.reload();
                }
                if (eventName == 'APP_CLOSED') {
                    $('#subscribe').attr('disabled', false).html('Buy Now');
                }

            },
            transactionStatus: function (data) {
                window.Paytm.CheckoutJS.close();
                if (data.STATUS == 'TXN_SUCCESS' || data.STATUS == 'PENDING') {
                    post_payment("paytm").done(function (result) {
                        if (result.error == false) {
                            setTimeout(function () {
                                $(".modal").modal("hide");
                                location.href = baseUrl + "/payment-success";
                            }, 500);
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Transaction faild!'
                    });
                }

            }


        }
    };

    // console.log(config);
    if (window.Paytm && window.Paytm.CheckoutJS) {
        // initialze configuration using init method
        window.Paytm.CheckoutJS.init(config).then(function onSuccess() {

            // after successfully update configuration invoke checkoutjs
            window.Paytm.CheckoutJS.invoke();
        }).catch(function onError(error) {
            console.log("Error => ", error);
        });
    }
}

$(document).ready(function () {
    $("#subscribe").on("click", function (e) {
        e.preventDefault();
        $("#stripe_div").slideUp();

        if ($('#razorpay').length > 0 && document.getElementById("razorpay").checked == true) {
            $("#subscribe").attr("disabled", true).html("Please wait");
            $.post(
                baseUrl + "payments/pre_payment_setup", {
                    [csrfName]: csrfHash,
                    payment_method: "Razorpay",
                    amount: $("#price").val(),
                    user_id: users_id,

                },
                function (data) {
                    csrfName = data.csrfName;
                    csrfHash = data.csrfHash;
                    if (data.error == false) {
                        $("#razorpay_order_id").val(data.order_id);
                    } else {
                        pre_payment_error = true;
                        return showToastMessage(data["message"], "error");
                    }
                },
                "json"
            ).then(() => {
                if (!pre_payment_error) {
                    var razorpay_key = $("#razorpay_key_id").val();
                    var amount = $("#price").val();
                    var company = $("#app_name").val();
                    var razorpay_order_id = $("#razorpay_order_id").val();
                    var username = "espeech";
                    var email = "test@test.com";
                    var logo = $("#logo").val();
                    var phone = "9876543210";
                    razorpay_script(
                        razorpay_key,
                        amount,
                        company,
                        razorpay_order_id,
                        username,
                        email,
                        logo,
                        phone
                    );
                } else {
                    $("#subscribe").attr("disabled", false).html("Buy Now");
                }
            });
        } else if ($('#paystack').length > 0 && document.getElementById("paystack").checked == true) {
            $.post(
                baseUrl + "payments/pre_payment_setup", {
                    [csrfName]: csrfHash,
                    user_id: users_id,
                    amount: amount,
                    payment_method: "paystack",
                },
                function (data) {
                    csrfName = data.csrfName;
                    csrfHash = data.csrfHash;
                    if (data.error == false) {

                    } else {
                        pre_payment_error = true;
                        return showToastMessage(data["message"], "error");
                    }
                },
                "json"
            ).then(() => {
                if (!pre_payment_error) {
                    paystack_setup().openIframe();
                } else {
                    $("#subscribe").attr("disabled", false).html("Buy Now");
                }
            });
        } else if ($('#bank').length > 0 && document.getElementById("bank").checked == true) {

            Swal.fire({
                title: 'Are you sure?',
                text: "want to buy this plan via Bank Transfer method?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Proceed!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(
                        baseUrl + "payments/pre_payment_setup", {
                            [csrfName]: csrfHash,
                            user_id: users_id,
                            amount: $("#price").val(),
                            payment_method: "bank",
                            plan_id: $("#plan_id").val(),
                            tenure_id: $("#tenure_id").val(),
                        },
                        function (data) {
                            csrfName = data.csrfName;
                            csrfHash = data.csrfHash;

                            console.log(data);
                            if (data.error == false) {

                                showToastMessage(data.message, "success");
                                setTimeout(() => {
                                    window.location.href = baseUrl + "/auth"
                                }, 2000)
                                return;
                            } else {
                                if (!pre_payment_error) {
                                    return showToastMessage(data.message, "error");
                                }
                            }
                        },
                        "json"
                    ).then(() => {
                        if (!pre_payment_error) {
                            return showToastMessage(data.message, "success");
                        } else {
                            pre_payment_error = true;
                            return showToastMessage("something went wrong again", "error")
                        }
                    });
                }
            });
        } else if ($('#paytm').length > 0 && document.getElementById('paytm').checked == true) {
            $("#subscribe").attr("disabled", true).html("Please wait");
            $.post(
                baseUrl + "payments/pre_payment_setup", {
                    [csrfName]: csrfHash,
                    payment_method: "paytm",
                    amount: $("#price").val(),
                    order_id: $('#paytm_order_id').val(),
                    support_name: $('#support_name').val(),
                    app_name: $('#app_name').val(),
                    user_id: users_id,
                },
                function (data) {
                    csrfName = data.csrfName;
                    csrfHash = data.csrfHash;

                    if (data.error == true) {
                        return showToastMessage(data.message, "error");
                    }
                    console.log(data);
                    var txn_token = $('#paytm_transaction_token').val(data.data.body.txnToken);
                    var txn_token = $('#paytm_transaction_token').val();
                    var app_name = $('#app_name').val();
                    console.log(data.data.body.txnToken);
                    if (typeof (data.data.body.txnToken) != "undefined" && data.data.body.txnToken !== null) {
                        var txn_token = $('#paytm_transaction_token').val(data.data.body.txnToken)
                        var order_id = $('#paytm_order_id').val(data.data.order_id)
                        var txn_token = $('#paytm_transaction_token').val();
                        var order_id = $('#paytm_order_id').val();

                        var amount = $('#price').val();

                        paytm_setup(txn_token, order_id, amount, app_name);

                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Something went wrong please try again later.'
                        });
                    }
                }, "json").then(() => {
                $("#subscribe").attr("disabled", false).html("Buy Now");
            });
        } else if ($('#paypal').length > 0 && document.getElementById('paypal').checked == true) {
            var amount = $("#price").val();
            var plan_id = $("#plan_id").val();
            var tenure_id = $("#tenure_id").val();
            $.post(
                baseUrl + "payments/pre_payment_setup", {
                    [csrfName]: csrfHash,
                    user_id: users_id,
                    amount: amount,
                    payment_method: "paypal",
                },
                function (data) {
                    csrfName = data.csrfName;
                    csrfHash = data.csrfHash;
                    if (data.error == false) {
                        paypal_config(amount, users_id, plan_id, tenure_id);
                        $('#paypal_btn').slideDown();
                        $("#subscribe").attr("disabled", true).html("Buy Now");
                    } else {
                        pre_payment_error = true;
                        return showToastMessage(data["message"], "error");
                    }
                });

        }
        if (document.getElementById("stripe") != undefined  && document.getElementById("stripe").checked == true) {
            $("#subscribe").attr("disabled", true).html("Please wait");
            var amount = $("#price").val();
            $.post(
                baseUrl + "payments/pre_payment_setup", {
                    [csrfName]: csrfHash,
                    payment_method: "stripe",
                    amount: amount,
                    user_id: users_id,
                    plan_id: $("#plan_id").val(),
                    tenure_id: $("#tenure_id").val(),
                },
                function (data) {
                    csrfName = data.csrfName;
                    csrfHash = data.csrfHash;
                    if (data.error) {
                        pre_payment_error = true;
                        showToastMessage(data.message, "error");
                    } else {
                        console.log(data);
                        $("#stripe_client_secret").val(data.client_secret);
                        $("#stripe_payment_id").val(data.id);
                        var stripe_client_secret = data.client_secret;
                        console.log(stripe_client_secret)
                        stripe_payment(stripe1.stripe, stripe1.card, stripe_client_secret);
                    }
                },
                "json"
            ).then(() => {
                $("#subscribe").attr("disabled", false).html("Buy Now");
            });
        }
    });
});
$("input[name='payment_type']").on("change", function (e) {
    e.preventDefault();
    var payment_method = $("input[name=payment_type]:checked").val();
    if (payment_method == "stripe") {

        $("#stripe_div").slideDown();
        stripe1 = stripe_setup($("#stripe_key").val());
    } else {
        $("#stripe_div").slideUp();
    }
    if (payment_method == "bank") {
        $("#bank_modal").slideDown();
    } else {
        $("#bank_modal").slideUp();
    }
});



function stripe_payment(stripe, card, clientSecret) {
    // Calls stripe.confirmCardPayment
    // If the card requires authentication Stripe shows a pop-up modal to
    // prompt the user to enter authentication details without leaving your page.
    stripe
        .confirmCardPayment(clientSecret, {
            payment_method: {
                card: card,
            },
        })
        .then(function (result) {
            $("#subscribe").attr("disabled", false).html("Buy Now");
            if (result.error) {
                // Show error to your customer
                var errorMsg = document.querySelector("#card-error");
                errorMsg.textContent = result.error.message;
                setTimeout(function () {
                    errorMsg.textContent = "";
                }, 4000);

                console.log(error);

            } else {
                // The payment succeeded!
                setTimeout(function () {
                    location.href = baseUrl + "/payment-success";
                }, 1000);
            }
        });
}

function stripe_setup(key) {
    // A reference to Stripe.js initialized with a fake API key.
    // Sign in to see examples pre-filled with your key.
    var stripe = Stripe(key);
    // Disable the button until we have Stripe set up on the page
    var elements = stripe.elements();
    var style = {
        base: {
            color: "#32325d",
            fontFamily: "Arial, sans-serif",
            fontSmoothing: "antialiased",
            fontSize: "16px",
            "::placeholder": {
                color: "#32325d",
            },
        },
        invalid: {
            fontFamily: "Arial, sans-serif",
            color: "#fa755a",
            iconColor: "#fa755a",
        },
    };

    var card = elements.create("card", {
        style: style,
    });
    card.mount("#stripe-card");

    card.on("change", function (event) {
        // Disable the Pay button if there are no card details in the Element
        document.querySelector("button").disabled = event.empty;
        document.querySelector("#card-error").textContent = event.error ?
            event.error.message :
            "";
    });
    return {
        stripe: stripe,
        card: card,
    };
}
// here are data for paypal to display buttons
var cap_id = '';

function paypal_config(price, user_id, plan_id, tenure_id) {
    paypal_sdk.Buttons({
        // Sets up the transaction when a payment button is clicked
        // this is to configure the button it can change as per user's need or per devs need
        createOrder: (data, actions) => {
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        // here we're passing amounts
                        "reference_id": plan_id,
                        "custom_id": tenure_id,
                        "currency_code": "USD",
                        "value": price,
                        "breakdown": {
                            "item_total": {
                                /* Required when including the `items` array */
                                "currency_code": "USD",
                                "value": price
                            }
                        }
                    },
                }],
            });
        },
        // Finalize the transaction after payer approval
        onApprove: (data, actions) => {
            return actions.order.capture().then(function (orderData) {
                // Successful capture! For dev/demo purposes:
                /**
                 * use this for know about payment that has been approved 
                 * console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
                 * 
                 * 
                 * this is for  getting alert about approved and captured payments 
                 *  
                 * const transaction = orderData.purchase_units[0].payments.captures[0];
                 * 
                 * alert(`Transaction ${transaction.status}: ${transaction.id}\n\nSee console for all available details`);
                 */

                //    trying to reach post payment from here
                var capture_id = orderData.purchase_units[0].payments.captures[0].id;
                var intent = orderData.intent;
                var status = orderData.status;
                cap_id = capture_id;
                var od = JSON.stringify(orderData, null, 2);
                console.log(od);
                // console.log();

                post_payment('paypal');
            });
        },
        onCancel: function (data) {
            window.location.reload();
            $("#subscribe").attr("disabled", false).html("Buy Now");
        }
    }).render('#paypal_btn');
}
// function b() {
//     console.log(cap_id + 'captured id');
// }
function post_payment(provider) {
    let req_body;
    if (provider == "razorpay") {
        req_body = {
            [csrfName]: csrfHash,
            plan_id: $("#plan_id").val(),
            tenure_id: $("#tenure_id").val(),
            txn_id: $("#razorpay_payment_id").val(),
            provider: provider,
        };
    } else if (provider == "paystack") {
        req_body = {
            [csrfName]: csrfHash,
            plan_id: $("#plan_id").val(),
            tenure_id: $("#tenure_id").val(),
            txn_id: $("#paystack_reference").val(),
            provider: provider,
        };
    } else if (provider == "bank") {
        req_body = {
            [csrfName]: csrfHash,
            plan_id: $("#plan_id").val(),
            tenure_id: $("#tenure_id").val(),
            // static added
            txn_id: "abcd13465456",
            provider: provider,
        };
    } else if (provider == "paytm") {
        req_body = {
            [csrfName]: csrfHash,
            plan_id: $("#plan_id").val(),
            tenure_id: $("#tenure_id").val(),
            txn_id: $("#paytm_order_id").val(),
            provider: provider,
        };
    } else if (provider == "paypal") {
        req_body = {
            [csrfName]: csrfHash,
            plan_id: $("#plan_id").val(),
            tenure_id: $("#tenure_id").val(),
            txn_id: cap_id,
            capture_id: cap_id,
            provider: provider,
        };
        console.log(req_body);
        // return false;
    }
    return $.ajax({
        type: "POST",
        data: req_body,
        url: baseUrl + "/payments/post_payment",
        success: function (result) {
            csrfName = result["csrfName"];
            csrfHash = result["csrfHash"];
            var data = result["data"];
            if (data.error == true) {
                showToastMessage(result["message"], "error");
                $(".modal").modal("hide");
            } else {
                showToastMessage(result["message"], "success");
                $(".modal").modal("hide");
                setTimeout(function () {
                    location.href = baseUrl + "/payment-success";
                }, 1000);
            }
        },
    });
}