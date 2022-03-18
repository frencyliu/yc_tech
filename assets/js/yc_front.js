// Notice how this gets configured before we load Font Awesome
window.FontAwesomeConfig = { autoReplaceSvg: false };

jQuery(document).ready(($) => {
    /**
     * Login popup
     */
    jQuery(".unlogin .need_login").each(function () {
        let link = jQuery(this).attr("href");
        jQuery(this).attr("data-link", link).attr("onclick", "need_login();").removeAttr("href");
    });

    jQuery(".yc-login-popup").on("click touchstart", function (e) {
        jQuery(".yc-login-popup").slideUp();
        e.stopPropagation();
    });
    jQuery(".yc-login-popup .yc_login_form_wrap").click(function (e) {
        // Do something
        e.stopPropagation();
    });

    //檢查是否為超商取貨

    jQuery(document.body).on("updated_checkout", function (e, data) {
        if (data !== undefined) {
            //jQuery("#billing_postcode_field").find('input[name^="billing_"]').val("");
            //jQuery("#billing_address_1_field").find('input[name^="billing_"]').val("");

            let is_ec_shipping = jQuery("#shipping_method_0_ecpay_shipping").prop("checked");
            if (is_ec_shipping === true) {
                jQuery("#billing_postcode_field").slideUp().find('input[name^="billing_"]').val("100");
                jQuery("#billing_address_1_field").slideUp().find('input[name^="billing_"]').val("N/A");
            } else {
                jQuery("#billing_postcode_field").slideDown();
                jQuery("#billing_address_1_field").slideDown();
            }
        }
    });

    //CHATBUTTON
    let chatbutton_width = jQuery(".chatbutton_content").width();
    let left_shift = chatbutton_width - 75;
    let left_shift_unit = left_shift + "px";
    let count_btn = jQuery(".chatbutton_content_inner_scroll a").length;
    let count_width = 75 * count_btn;
    jQuery(".chatbutton_content_inner_scroll").css({ "min-width": count_width + "px" });
    jQuery(".chatbutton_content .fa-reply-all").click(function () {
        jQuery(".chatbutton_content").addClass("expand").animate({ left: "0px" }, 300);
    });
    jQuery(".chatbutton_content .fa-share-all").click(function () {
        jQuery(".chatbutton_content").removeClass("expand").animate({ left: left_shift_unit }, 300);
    });

    jQuery(".chatbutton_content .fa-arrow-from-right.expand").click(function () {
        jQuery(".chatbutton_content .fa-arrow-from-right.expand").removeClass("expand");
        jQuery(".chatbutton_content").animate({ left: left_shift_unit }, 300);
    });

    //Copy URL
    var jQuerytemp = jQuery('<input id="for_select">');
    var jQueryurl = jQuery(location).attr("href");
    jQuery("#copy_url").click(function () {
        jQuery("body").append(jQuerytemp);
        jQuerytemp.val(jQueryurl).select();
        document.execCommand("copy");
        jQuerytemp.remove();
        alert("URL Copied!");
    });

    /**
     * Form Validation
     */
    const html = `
                <style>
                    input:invalid {
                    background-color: #ffdddd;
                    }

                    input:required:invalid {
                    border: 2px dashed  var(--mdb-red, #ff0000) !important;
                    outline: unset !important;
                    }
                </style>
                `;
    jQuery(".form_submit").click(function () {
        jQuery("head").append(html);
    });
});

function need_login() {
    jQuery(".yc-login-popup").slideDown({
        start: function () {
            jQuery(this).css({
                display: "flex",
            });
        },
    });
}
