jQuery.ajax({
    url:"http://localhost/picostrap/wp-admin/admin-ajax.php",
    type:"post",
    data:{
        'action': 'foobar',
        'foobar_id':   123
    },
    error: function (xhr) {
        console.log(xhr);
        console.log("出錯拉");
    }, // 錯誤後執行的函數,
    function(response) {
        console.log('The server responded: ', response);
    }
});


/*
 * COOKIE
 * https://www.fooish.com/javascript/cookie.html
 */
function parseCookie() {
    let cookieObj = {};
    let cookieAry = document.cookie.split(";");
    let cookie;

    for (let i = 0, l = cookieAry.length; i < l; ++i) {
        cookie = jQuery.trim(cookieAry[i]);
        cookie = cookie.split("=");
        cookieObj[cookie[0]] = cookie[1];
    }

    return cookieObj;
}

function getCookieByName(name) {
    let value = parseCookie()[name];
    if (value) {
        value = decodeURIComponent(value);
    }

    return value;
}

//計算預期獲利
function calc_expect_earning(bet_ratio) {
    jQuery("#gg_game #bet_amount").on("keyup", function () {
        let bet_amount = Number(jQuery(this).val());
        let earn_ratio = Number(bet_ratio - 1);
        let expect_earn = bet_amount * earn_ratio;
        jQuery("#gg_game #expect_earn").text(expect_earn);
    });
}

//取得賽事資料
function get_game_obj(game_id, team_name, bet_ratio, callback) {
    jQuery.ajax({
        url: window.location.href, //未來再看要傳去哪
        type: "post",
        data: {
            game_id: game_id,
            team_name: team_name,
            bet_ratio: bet_ratio,
        },
        error: function (xhr) {
            console.log(xhr);
            console.log("出錯拉");
        }, // 錯誤後執行的函數
        success: function (response) {
            console.log(getCookieByName("game_obj[ID]"));
        },
    });

    callback;
}

//輸出賽事跟賠率
function output_team_info(team_name, bet_ratio) {
    //let team_name = getCookieByName('team_name');
    console.log("輸出" + team_name);
    let output = "" + team_name + " 賠率:" + bet_ratio;
    jQuery("#game_team").html(output);
}

//賽事被點擊時觸發
jQuery(".game_team").click(function () {
    let now = new Date();
    now.setMonth(now.getMonth() - 1);
    document.cookie = "expires=" + now.toUTCString() + ";";

    let game_id = jQuery(this).attr("game-id");
    let team_name = jQuery(this).attr("team-name");
    let bet_ratio = jQuery(this).find(".bet_ratio").text();
    console.log("game_team is " + team_name);
    get_game_obj(game_id, team_name, bet_ratio, output_team_info(team_name, bet_ratio));
    calc_expect_earning(bet_ratio);
});
/*
jQuery(document).ready(function($) {


console.log(game_data_from_php);


jQuery('.game_team').click(function(){
    let game_team = jQuery(this).removeClass('border-end border-3 border-secondary game_team').addClass('mb-3').attr('data-bs-toggle', '').prop('outerHTML');
    jQuery('#game_team').html(game_team);

    let bet_ratio = Number(jQuery('#gg_game .bet_ratio').text());
    let team_name = jQuery('#gg_game .team_name').text();

    jQuery('#gg_game #bet_amount').on('keyup', function(){
        let bet_amount = Number(jQuery(this).val());
        let earn_ratio = Number(bet_ratio - 1);
        let expect_earn = bet_amount * earn_ratio;
        jQuery('#gg_game #expect_earn').text(expect_earn);
    });

    jQuery('#gg_game #place_bet').click(function(){
console.log(team_name);
        jQuery('#gg_game #confirm_text').html('您即將下注<strong>費城76人</strong>，共 NT$ 10000' );
            jQuery('#gg_game #place_bet').attr('disabled', 'disabled');
            jQuery('#gg_game #alert').slideDown(function(){
                jQuery('#gg_game #sure_confirm').click(
                    function(){
                        jQuery('#gg_game #alert').slideUp();
                        jQuery('#gg_game #place_bet').removeAttr('disabled', 'disabled').unbind('click');
                    }
                );
            });
    });

});





*/
