var zarinpal_has;
jQuery(document).ready(function () {
    jQuery.each(wpe_forms, function () {
        var form = this;
        var get = getQueryParams(document.location.search);
        if (get.ipg) {
            jQuery.ajax({
                url: form.ajaxurl,
                type: 'post',
                data: {
                    action: 'i_amir_net_ep_payment_pay_check',
                    formID: get.form,
                    ipg: get.ipg,
                    postReq: epPaymentRequest.post,
                    getReq: epPaymentRequest.get,
                    ref: get.ref,
                    authority: get.Authority,
                    status: get.Status,
                },
                success: function (rep) {
                    if (rep['status'] == true) {
                        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wpe_panel .container-fluid').empty();
                        var i_amir_net_ep_payment_pay_html = '<div id="i_amir_net_ep_payment_pay">';
                        i_amir_net_ep_payment_pay_html += '<h2>پرداخت شما با موفقیت انجام شد و اطلاعات شما ثبت شد.</h2>';
                        i_amir_net_ep_payment_pay_html += '<p>نام فرم: ' + rep.form + '</p>';
                        i_amir_net_ep_payment_pay_html += '<p>شماره پیگیری: ' + rep.ref + '</p>';
                        i_amir_net_ep_payment_pay_html += '<p>شماره تراکنش: ' + rep.transId + '</p>';
                        i_amir_net_ep_payment_pay_html += '<p>درگاه پرداخت: ' + rep.payment + '</p>';
                        i_amir_net_ep_payment_pay_html += '</div>';
                        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wpe_panel .container-fluid').append(i_amir_net_ep_payment_pay_html);
                    } else {
                        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] .lfb_startBtnContainer').remove();
                        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #genPrice').remove();
                        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #mainPanel').remove();
                        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #finalText').text("پرداخت شما ناموفق بود و اطلاعات شما ثبت نشد.");
                        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #finalText').show();
                    }

                }
            });
        }

        jQuery.ajax({
            url: form.ajaxurl,
            type: 'post',
            data: {
                action: 'i_amir_net_ep_payment_action_form',
                formID: form.formID,
            },
            success: function (response) {
                if (response) {
                    var i_amir_net_ep_payment_pay_html;
                    var ipg = new Array();
                    if (response.use_paypal == "1")
                        ipg.push('paypal');
                    if (response.use_stripe == "1")
                        ipg.push('stripe');
                    if (response.use_razorpay == "1")
                        ipg.push('razorpay');
                    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + wpe_forms[0].formID + '"] #mainPanel .lfb_btnNextContainer').click(function () {
                        if (ipg.length == 0) {
                            i_amir_net_ep_payment_pay_html = '<div id="lfb_paymentMethodBtns">';
                            jQuery.each(response.payments, function () {
                                var payment = this;
                                i_amir_net_ep_payment_pay_html += '<a href="javascript:" style="margin-bottom: 5px" data-payment="' + payment + '" class="btn btn-wide btn-primary"><span class="fas fa-money-bill"></span><span>' + response['txt_btn' + payment] + '</span></a>';
                            });
                            i_amir_net_ep_payment_pay_html += '</div>';
                            if (jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #lfb_paymentMethodBtns [data-payment="' + response.payments[0] + '"]').attr('data-payment') !== response.payments[0]) {
                                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #lfb_lastStepDown').after(i_amir_net_ep_payment_pay_html);
                            }
                        } else if (ipg.length == 1) {
                            i_amir_net_ep_payment_pay_html = '<div id="lfb_paymentMethodBtns">';
                            switch (ipg[0]) {
                                case "paypal":
                                    jQuery("#wtmt_paypalForm").remove();
                                    i_amir_net_ep_payment_pay_html += '<a href="javascript:" data-payment="paypal" class="btn btn-wide btn-primary"><span class="fab fa-paypal"></span><span>' + response.txt_btnPaypal + '</span></a>';
                                    break;
                                case "stripe":
                                    jQuery(".lfb_btnNextContainerStripe").remove();
                                    i_amir_net_ep_payment_pay_html += '<a href="javascript:" data-payment="stripe" class="btn btn-wide btn-primary"><span class="fab fa-stripe-s"></span><span>' + response.txt_btnStripe + '</span></a>';
                                    break;
                                case "razorpay":
                                    jQuery("#lfb_razorPayCt").remove();
                                    i_amir_net_ep_payment_pay_html += '<a href="javascript:" data-payment="razorpay" class="btn btn-wide btn-primary"><span class="fas fa-money-check-alt"></span><span>' + response.txt_btnRazorpay + '</span></a>';
                                    break;
                            }
                            jQuery.each(response.payments, function () {
                                var payment = this;
                                i_amir_net_ep_payment_pay_html += '<a href="javascript:" style="margin-bottom: 5px" data-payment="' + payment + '" class="btn btn-wide btn-primary"><span class="fas fa-money-bill"></span><span>' + response['txt_btn' + payment] + '</span></a>';
                            });
                            i_amir_net_ep_payment_pay_html += '</div>';
                            if (jQuery.is('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #lfb_paymentMethodBtns') == false) {

                                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #lfb_lastStepDown').after(i_amir_net_ep_payment_pay_html);
                            }

                        } else {
                            if (jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #lfb_paymentMethodBtns [data-payment="' + response.payments[0] + '"]').attr('data-payment') !== response.payments[0]) {
                                var i_amir_net_ep_payment_pay_html = null;
                                jQuery.each(response.payments, function () {
                                    var payment = this;
                                    i_amir_net_ep_payment_pay_html += '<a href="javascript:" style="margin-bottom: 5px" data-payment="' + payment + '" class="btn btn-wide btn-primary"><span class="fas fa-money-bill"></span><span>' + response['txt_btn' + payment] + '</span></a>';
                                });
                                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #lfb_paymentMethodBtns').append(i_amir_net_ep_payment_pay_html);
                            }

                        }

                        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #lfb_paymentMethodBtns [data-payment="ZarinPal"]').click(function () {
                            i_amir_net_ep_payment_check_go_payment(form, "ZarinPal");
                        });


                        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #lfb_paymentMethodBtns [data-payment="Payir"]').click(function () {
                            i_amir_net_ep_payment_check_go_payment(form, "Payir");
                        });

                        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #lfb_paymentMethodBtns [data-payment="Mellat"]').click(function () {
                            i_amir_net_ep_payment_check_go_payment(form, "Mellat");
                        });

                        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #lfb_paymentMethodBtns [data-payment="Sadad"]').click(function () {
                            i_amir_net_ep_payment_check_go_payment(form, "Sadad");
                        });

                        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #lfb_paymentMethodBtns [data-payment="Saman"]').click(function () {
                            i_amir_net_ep_payment_check_go_payment(form, "Saman");
                        });

                        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #lfb_paymentMethodBtns [data-payment="paypal"]').click(function (e) {
                            e.preventDefault();
                            form.useZarinpal = false;
                            form.useRazorpay = false;
                            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #lfb_ZarinpalPayCt').slideUp();
                            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #lfb_RazorPayCt').slideUp();
                            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #btnOrderPaypal').trigger('click');
                        });

                        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #lfb_paymentMethodBtns [data-payment="razorpay"]').click(function () {

                            var isOK = lfb_checkStepItemsValid('final', form.formID);
                            if (isOK) {

                                lfb_checkCaptcha(form, function () {
                                    setTimeout(function () {
                                        if (jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"]').is('.wpe_fullscreen')) {
                                            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"]').animate({
                                                scrollTop: jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #lfb_razorPayCt').offset().top - (80 + parseInt(form.scrollTopMargin))
                                            }, form.animationsSpeed * 2);
                                        } else {
                                            jQuery('body,html').animate({
                                                scrollTop: jQuery('#estimation_popup.wpe_bootstraped[data-form="' + formID + '"] #lfb_razorPayCt').offset().top - (80 + parseInt(form.scrollTopMargin))
                                            }, form.animationsSpeed * 2);
                                        }

                                    }, 350);

                                    if (lfb_lastSteps.length > 0) {
                                        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #mainPanel #lfb_razorPayCt .linkPrevious').fadeIn();
                                    }
                                    if (jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #btnOrderRazorpay').length > 0) {
                                        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #btnOrderRazorpay').trigger('click');
                                    }
                                });

                            }
                        });

                        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #lfb_paymentMethodBtns [data-payment="stripe"]').click(function () {
                            form.useRazorpay = false;
                            form.useZarinpal = false;
                            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #lfb_ZarinpalPayCt').slideUp();
                            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #lfb_razorPayCt').slideUp();
                            var isOK = lfb_checkStepItemsValid('final', form.formID);
                            if (isOK) {
                                if (jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #lfb_captcha').length > 0) {
                                    jQuery.ajax({
                                        url: form.ajaxurl,
                                        type: 'post',
                                        data: {
                                            action: 'lfb_checkCaptcha',
                                            formID: formID,
                                            captcha: jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #lfb_captchaField').val()
                                        },
                                        success: function (rep) {
                                            if (rep == '1') {

                                                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #lfb_captchaPanel').slideUp(100);
                                                lfb_showWinStripePayment(form);
                                            } else {
                                                jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #lfb_captchaField').closest('.form-group').addClass('has-error');

                                            }
                                        }
                                    });
                                } else {
                                    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #lfb_captchaPanel').slideUp(100);
                                    lfb_showWinStripePayment(form);
                                }
                            }
                        });
                        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #wpe_btnOrder').addClass('lfb-hidden');
                        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"]  #lfb_paymentMethodBtns').addClass('d-block');
                    });
                    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + wpe_forms[0].formID + '"] #mainPanel .lfb_linkPreviousCt').click(function () {
                        if (ipg.length < 2) {
                            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #lfb_paymentMethodBtns').remove();
                        }
                    });
                }
            }
        });
    });
});

function i_amir_net_ep_payment_check_go_payment(form, payment) {
    if (!tld_selectionMode){
        var ref = wpe_order_i_amir_net_ep_payment_ir(form.formID);
        i_amir_net_ep_payment_go_payment(form, ref, payment);
    }
}


function i_amir_net_ep_payment_go_payment(form, ref, payment) {
    var isOK = lfb_checkStepItemsValid('final', form.formID);
    if (form.legalNoticeEnable == 1) {
        if (!jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #lfb_legalCheckbox').is(':checked')) {
            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #lfb_legalCheckbox').closest('.form-group').addClass('has-error');
            isOK = false;
        }
    }
    if (isOK) {
        lfb_checkCaptcha(form, function () {
            var singleTotal = parseFloat(form.price);
            var subTotal = 0;
            if (jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"]').is('[data-isSubs="true"]')) {
                singleTotal = parseFloat(form.priceSingle);
                subTotal = parseFloat(form.price);
            }

            if (form.payMode == 'percent') {
                singleTotal = parseFloat(singleTotal) * (parseFloat(form.percentToPay) / 100);
                var amountToShow = '<span>' + lfb_formatPriceWithCurrency(singleTotal, form.formID) + '</span>';

            } else if (form.payMode == 'fixed') {
                singleTotal = parseFloat(form.fixedToPay);
                var amountToShow = '<span>' + lfb_formatPriceWithCurrency(singleTotal, form.formID) + '</span>';
            }


            jQuery.ajax({
                url: form.ajaxurl,
                type: 'post',
                data: {
                    action: 'i_amir_net_ep_payment_go_pay',
                    singleTotal: singleTotal,
                    subTotal: subTotal,
                    formID: form.formID,
                    url: window.location.href,
                    ref: ref,
                    ipg: payment,
                    customerInfos: wpe_getContactInformations(form.formID, true)
                },
                success: function (rep) {
                    if (rep.status == true) {
                        if (rep.name == 'Saman') {
                            var html = "<form id='samanpeyment' action='https://sep.shaparak.ir/payment.aspx' method='post'>\n" +
                                "            <input type='hidden' name='Amount' value='" + rep.Amount + "' />\n" +
                                "            <input type='hidden' name='ResNum' value='" + rep.ResNum + "'>\n" +
                                "            <input type='hidden' name='RedirectURL' value='" + rep.RedirectURL + "'/>\n" +
                                "            <input type='hidden' name='MID' value='" + rep.MID + "'/>\n" +
                                "            </form><script>document.forms['samanpeyment'].submit()</script>";

                            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #lfb_paymentMethodBtns').append(html);
                        } else {
                            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #lfb_paymentMethodBtns').empty();
                            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #lfb_paymentMethodBtns').append("درحال  بررسی و انتقال به درگاه " + rep.title);
                            window.location.href = rep.url;
                        }
                    } else {
                        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #lfb_paymentMethodBtns').show();
                        jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #lfb_paymentMethodBtns').append("خطلا: " + JSON.stringify(rep));
                    }

                }
            });

        });
    }
}

function wpe_order_i_amir_net_ep_payment_ir(formID) {
    var form = wpe_getForm(formID);
    var isOK = true;
    var informations = '';
    var email = '';
    var fields = new Array();
    jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #finalSlide').find('.lfb_item input:not([type="checkbox"])[data-itemid],.lfb_item textarea[data-itemid],.lfb_item select[data-itemid]').each(function () {
        if (jQuery(this).closest('#wtmt_paypalForm').length == 0) {
            if (jQuery(this).is('.lfb_disabled')) {
            } else {
                if (jQuery(this).is('#lfb_couponField')) {
                } else if (jQuery(this).is('#lfb_captchaField')) {
                } else {
                    var dbpoints = ':';
                    if (jQuery(this).closest('.lfb_item').find('label').html().lastIndexOf(':') == jQuery(this).closest('.lfb_item').find('label').html().length - 1) {
                        dbpoints = '';
                    }
                    if (jQuery('body').is('.rtl')) {
                        informations += '<p><b><span class="lfb_value">' + jQuery(this).val() + '</span></b>' + dbpoints + ' ' + jQuery(this).closest('.lfb_item').find('label').html() + '</p>';
                    } else {
                        informations += '<p>' + jQuery(this).closest('.lfb_item').find('label').html() + ' ' + dbpoints + ' <b><span class="lfb_value">' + jQuery(this).val() + '</span></b></p>';
                    }

                }
            }
        }
    });
    isOK = lfb_checkStepItemsValid('final', formID);
    if (form.legalNoticeEnable == 1) {
        if (!jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #lfb_legalCheckbox').is(':checked')) {
            jQuery('#estimation_popup.wpe_bootstraped[data-form="' + form.formID + '"] #lfb_legalCheckbox').closest('.form-group').addClass('has-error');
            isOK = false;
        }
    }
    if (isOK == true && lfb_checkUserEmail(form)) {
        lfb_checkCaptcha(form, function () {
            wpe_orderSend(formID, informations, email, fields);
        });
    }
}

function getQueryParams(qs) {
    qs = qs.split("+").join(" ");
    var params = {},
        tokens,
        re = /[?&]?([^=]+)=([^&]*)/g;

    while (tokens = re.exec(qs)) {
        params[decodeURIComponent(tokens[1])]
            = decodeURIComponent(tokens[2]);
    }
    return params;
}
