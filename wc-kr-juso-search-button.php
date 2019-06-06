<?php
 /**
 * Plugin Name: WooCommerce - Korean Juso Search button
 * Plugin URI: https://github.com/Aloepig/woocommerce-korean-juso-serch-button
 * Description: Korean postal code search form using "http://www.juso.go.kr" API (주소검색버튼 추가)
 * Version: 1.0.0
 * Author: aloepig (이병직)
 * Author URI: https://byonjiwa.com
 * Text Domain: woocommerce-korean-juso-search-button
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Domain Path: /languages
 *
 * @package WooCommerce
 * @category Postcode
 * @author aloepig (aloepig@gmail.com)
 */

defined( 'ABSPATH' ) || exit;

// script include
function aloepig_wc_juso_button_scripts(){
    wp_enqueue_script( 'aloepig_wc_juso_search_script', plugin_dir_url( __FILE__ ) . 'assets/js/juso.go.kr.js', array('jquery'), '1.0.0', false ); 
    wp_enqueue_script( 'jquery-ui-selectable' );
}

// CSS include
function aloepig_wc_juso_button_style(){
    wp_enqueue_style('aloepig_wc_juso_search_css', plugins_url('assets/css/juso.go.kr.css', __FILE__));
}

// woocommerce : 주소검색 버튼
function wc_juso_search_button() {
    ?>
    <input type="text" id="wc_juso_search_text">
    <button type="button" id="wc_juso_search_button" onclick="getJHJusoSearchText();">주소 검색</button>
    <div id=wc_juso_list></div>
    <?php
}

// woocommerce checkout 필드 버튼
function wcjuso_search_button() {
    ?>
    <input type="text" id="wc_juso_search_text">
    <button type="button" id="wc_juso_search_button" onclick="getJHJusoSearchText();">주소 검색</button>
    <div id=wc_juso_list></div>
    <?php
}



add_action('wp_enqueue_scripts', 'aloepig_wc_juso_button_scripts');
add_action('wp_enqueue_style', 'aloepig_wc_juso_button_style');


add_action('woocommerce_after_checkout_billing_form' ,'wc_juso_search_button', 20);
add_action('woocommerce_after_checkout_registration_form' ,'wc_juso_search_button', 20);


//2019.5.30 테스트
// https://docs.woocommerce.com/document/tutorial-customising-checkout-fields-using-actions-and-filters/
add_filter('woocommerce_checkout_fields', 'custom_override_checkout_fields');
//add_filter('woocommerce_billing_fields', 'custom_override_checkout_fields');
//add_filter('woocommerce_shipping_fields', 'custom_override_checkout_fields');
// Our hooked in function - $fields is passed via the filter!
function custom_override_checkout_fields( $fields ) {
    // $fields['order']['order_comments']['placeholder'] = 'My new placeholder';
    // $fields['billing']['billing_postcode']['clear'] = true;
    // $fields['order']['order_comments']['label'] = 'My new label';
    // $fields['shipping']['shipping_phone'] = array(
    //     'label'     => __('Phone', 'woocommerce'),
    // 'placeholder'   => _x('Phone', 'placeholder', 'woocommerce'),
    // 'required'  => false,
    // 'class'     => array('form-row-wide'),
    // 'clear'     => true
    // );
    return $fields;
}

add_filter('woocommerce_billing_fields', 'my_woocommerce_billing_fields'); 
function my_woocommerce_billing_fields($fields) { 
    $fields['billing_postcode']['custom_attributes'] = array('readonly'=>'readonly');
 return $fields; 
}

?>