jQuery(document).ready(function () {
    jQuery('#fromDate,#toDate').datepicker({
        dateFormat: 'yy-mm-dd'
    });
});
jQuery(document).ready(function ($) {
    (function () {
        window.cachebreak = 1;
        document.body.addEventListener("click", event => {

            var nodeValue = event.target.nodeName;
            var allToInsert = 0;
            var to_url = "N/A";

            if (event.target.nodeName == "INPUT" && event.target.localName == "input") {
                var anchor = event.target.defaultValue;
                allToInsert = 1;

            }
            if (event.target.nodeName == "A") {
                var anchor = event.target.textContent;
                allToInsert = 1;
                to_url = event.target.href;
            }
            if (event.target.nodeName == "BUTTON" && event.target.localName == "button") {
                var anchor = event.target.innerText;
                if (anchor == "") {
                    anchor = event.target.innerText;
                }
                allToInsert = 1;
            }
            //console.log(event.innerText);


            if (allToInsert == 1) {
                var data = new FormData();
                data.append("post_id", kawuda_js_vars.post_id);
                data.append("from_url", window.location);
                data.append("tracking_item", anchor);
                data.append("tracking_item_type", nodeValue);
                data.append("to_url", to_url);
                data.append("utm_source", kawuda_js_vars.utm_source);
                data.append("utm_medium", kawuda_js_vars.utm_medium);
                data.append("utm_campaign", kawuda_js_vars.utm_campaign);
                data.append("utm_term", kawuda_js_vars.utm_term);
                data.append("utm_content", kawuda_js_vars.utm_content);
                data.append("google_id", kawuda_js_vars.google_id);
                data.append("fb_id", kawuda_js_vars.fb_id);
                data.append("current_user_id", kawuda_js_vars.current_user_id);
                data.append("user_platform", kawuda_js_vars.user_platform);
                data.append("user_browser", kawuda_js_vars.user_browser);
                data.append("user_mobile", kawuda_js_vars.user_mobile);
                data.append("from_site", kawuda_js_vars.from_site);
                data.append("user_ip", kawuda_js_vars.user_ip);
                var url = kawuda_js_vars.home_url + '/wp-json/kawuda/v1/hit/' + window.cachebreak++;
                navigator.sendBeacon(url, data);
            }

        });

    })();
    if (kawuda_js_vars.id == 0 && kawuda_js_vars.current_user_id > 0 && kawuda_js_vars.view == "kawuda") {
        kawuda_stat();
        kawuda_user_stat_link();
    }

    function kawuda_stat() {
        jQuery.ajax({
            type: "POST",
            dataType: "json",
            url: ajaxurl,
            data: {
                action: 'view_new_stat',


            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log("The following error occured: " + textStatus, errorThrown);
            },
            success: function (response) {

                jQuery("#new_stat_block").html(response.newStatTbl);
                setTimeout(function () {
                    kawuda_stat();
                }, 10000);
            }

        });
    }

    if (kawuda_js_vars.id > 0 && kawuda_js_vars.view == "kawuda") {
        kawuda_user_stat();
    }

    function kawuda_user_stat() {
        jQuery.ajax({
            type: "POST",
            dataType: "json",
            url: ajaxurl,
            data: {
                action: 'view_new_user_stat',
                kawuda_id: kawuda_js_vars.id,


            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log("The following error occured: " + textStatus, errorThrown);
            },
            success: function (response) {

                jQuery("#new_stat_user_block").html(response.newStatTbl);
                setTimeout(function () {
                    kawuda_user_stat();
                }, 10000);
            }

        });
    }

    function kawuda_user_stat_link() {
        jQuery.ajax({
            type: "POST",
            dataType: "json",
            url: ajaxurl,
            data: {
                action: 'view_new_user_link_stat',

            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log("The following error occured: " + textStatus, errorThrown);
            },
            success: function (response) {

                jQuery("#new_stat_user_link_block").html(response.newStatTbl);
                setTimeout(function () {
                    kawuda_user_stat_link();
                }, 10000);
            }

        });
    }

});



 