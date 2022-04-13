<?php

/**
 * Order Customer Details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details-customer.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.6.0
 */

defined('ABSPATH') || exit;

$show_shipping = !wc_ship_to_billing_address_only() && $order->needs_shipping_address();

$order_data = $order->get_data();
?>
<section class="woocommerce-customer-details">

    <?php if ($show_shipping) : ?>

        <section class="woocommerce-columns woocommerce-columns--2 woocommerce-columns--addresses col2-set addresses">
            <div class="woocommerce-column woocommerce-column--1 woocommerce-column--billing-address col-1">

            <?php endif; ?>

            <h2 class="woocommerce-column__title"><?php esc_html_e('Billing address', 'woocommerce'); ?></h2>

            <?php
            foreach( $order->get_items( 'shipping' ) as $item_id => $item ){
                $shipping_method_id          = $item->get_method_id(); // The method ID
            }

                $ecPay_shipping = get_post_meta( $order_data['id'], 'ecPay_shipping', true );

                switch ($ecPay_shipping) {
                    case 'UNIMART':
                        $ecPay_shipping_name = '7-11 | 信用卡、ATM、超商代碼、超商條碼';
                        break;
                case 'UNIMART_Collection':
                        $ecPay_shipping_name = '7-11 | 取貨付款';
                        break;
                case 'FAMI':
                        $ecPay_shipping_name = '全家 | 信用卡、ATM、超商代碼、超商條碼';
                        break;
                case 'FAMI_Collection':
                        $ecPay_shipping_name = '全家 | 取貨付款';
                        break;
                }



            /*echo '<pre>';
            var_dump($ecPay_shipping);
            echo '</pre>';*/
            ?>

            <table class="woocommerce-table shop_table">
                    <tbody>
                        <tr>
                            <td>姓名</td>
                            <td><?php echo wp_kses_post($order_data['billing']['first_name']); ?></td>
                        </tr>
                        <tr>
                            <td>聯絡電話</td>
                            <td><?php echo wp_kses_post($order_data['billing']['phone']); ?></td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td><?php echo wp_kses_post($order_data['billing']['email']); ?></td>
                        </tr>
                        <?php if( $shipping_method_id == 'ecpay_shipping'): ?>
                        <tr>
                            <td>門市</td>
                            <td>
                                <?php
                            $_shipping_purchaserStore = get_post_meta( $order_data['id'], '_shipping_purchaserStore', true );
                            $_shipping_purchaserAddress = get_post_meta( $order_data['id'], '_shipping_purchaserAddress', true );
                            echo wp_kses_post($_shipping_purchaserStore); ?><br><span class="badge bg-success me-3"><?php echo $ecPay_shipping_name; ?></span><span class="badge bg-dark"><?php echo wp_kses_post($_shipping_purchaserAddress); ?></span></td>
                        </tr>
                        <?php elseif( $shipping_method_id !== 'ecpay_shipping'): ?>
                        <tr>
                            <td>地址</td>
                            <td><?php echo wp_kses_post($order_data['billing']['postcode'] . ' ' . $order_data['billing']['address_1']); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if( !empty($order_data['billing']['company']) ): ?>
                        <tr>
                            <td>公司抬頭</td>
                            <td><?php echo wp_kses_post($order_data['billing']['company']); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php
                        $_billing_company_id = get_post_meta( $order_data['id'], '_billing_company_id', true );
                        if( !empty($_billing_company_id) ): ?>
                        <tr>
                            <td>公司統編</td>
                            <td><?php echo wp_kses_post($_billing_company_id); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php
                        $_billing_einvoice = get_post_meta( $order_data['id'], '_billing_einvoice', true );
                        if( !empty($_billing_einvoice) ): ?>
                        <tr>
                            <td>載具</td>
                            <td><?php echo wp_kses_post($_billing_einvoice); ?></td>
                        </tr>
                        <?php endif; ?>

                    </tbody>
                </table>


            <?php if ($show_shipping) : ?>

            </div><!-- /.col-1 -->

            <div class="woocommerce-column woocommerce-column--2 woocommerce-column--shipping-address col-2">
                <h2 class="woocommerce-column__title"><?php esc_html_e('Shipping address', 'woocommerce'); ?></h2>
                <address>
                    <?php echo wp_kses_post($order->get_formatted_shipping_address(esc_html__('N/A', 'woocommerce'))); ?>

                    <?php if ($order->get_shipping_phone()) : ?>
                        <p class="woocommerce-customer-details--phone"><?php echo esc_html($order->get_shipping_phone()); ?></p>
                    <?php endif; ?>
                </address>
            </div><!-- /.col-2 -->

        </section><!-- /.col2-set -->

    <?php endif; ?>

    <?php do_action('woocommerce_order_details_after_customer_details', $order); ?>

</section>