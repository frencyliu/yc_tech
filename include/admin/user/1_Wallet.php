<?php

/**
 * Video post type
 */

namespace YC\Admin;

use YC_TECH;

if (!WP_DEBUG) return;

defined('ABSPATH') || exit;

class _Wallet extends YC_TECH
{

    public function __construct()
    {
        add_action('show_user_profile', [$this, 'yc_add_wallet_info'], 100);
        add_action('edit_user_profile', [$this, 'yc_add_wallet_info'], 100);
    }
    public function yc_add_wallet_info()
    {
?>

<style>
    .yc_deposit_log{
        margin-bottom: 20px;
    }
    .yc_deposit_log tr.withdrow{
        background-color: #ffe6e6;
    }
     .yc_deposit_log tr.deposit{
        background-color: #e6f3e6;
}
</style>
        <h2>錢包資訊</h2>
        <table class="form-table" id="fieldset-yc_wallet">
            <tbody>
                <tr>
                    <th>
                        <label for="yc_balance">餘額</label>
                    </th>
                    <td>
                        <input type="number" name="yc_balance" disabled="disabled" id="yc_balance" value="0" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="yc_deposit">管理員充值</label>
                    </th>
                    <td>
                        <input type="number" name="yc_deposit" id="yc_deposit" value="" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="yc_deposit_log">充值紀錄</label>
                    </th>
                    <td>
                        <table class="form-table yc_deposit_log">
                            <tr>
                                <td>時間</td>
                                <td>儲值金額</td>
                                <td>提領金額</td>
                                <td>餘額</td>
                                <td>備註</td>
                            </tr>

                            <tr class="withdrow">
                                <td>2021/12/02 18:49:37</td>
                                <td></td>
                                <td>100</td>
                                <td>200</td>
                                <td></td>
                            </tr>
                            <tr class="deposit">
                                <td>2021/12/02 18:47:37</td>
                                <td>300</td>
                                <td></td>
                                <td>300</td>
                                <td>管理員充值</td>
                            </tr>
                            <tr>
                                <td>2021/12/02 18:45:37</td>
                                <td></td>
                                <td></td>
                                <td>0</td>
                                <td>起始金額</td>
                            </tr>
                        </table>
                        <p><a id="yc_deposit_log_download_btn" class="button button-primary">下載儲值紀錄</a></p>
                    </td>
                </tr>

            </tbody>
        </table>
<?php


    }
}
new _Wallet();
