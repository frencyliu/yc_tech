<?php
if (ONESHOP && class_exists('WooCommerce', false)) {
require_once(__DIR__ . '/class-oneshop.php');
new YC\OneShop\OneShop_Mode;
}