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
define( 'WC_KR_JUSO_SEARCH_BUTTON_VERSION', '1.0' );

if ( !class_exists( 'Aloepig_Juso_Search_Button_Plugin' ) ) {
    class Aloepig_Juso_Search_Button_Plugin
    {
        public static function init() {
            wp_enqueue_script( 'wc_kr_juso_search_script', plugin_dir_url( __FILE__ ) . 'assets/js/juso.go.kr.js', array('jquery'), WC_KR_JUSO_SEARCH_BUTTON_VERSION, false ); 
            wp_enqueue_style( 'wc_kr_juso_search_css', plugin_dir_url( __FILE__ ) . 'assets/css/juso.go.kr.css', array(), WC_KR_JUSO_SEARCH_BUTTON_VERSION, 'all');
            wp_enqueue_script( 'jquery-ui-selectable' );

            self::setAddActionCheckout();
            self::setAddFilterCheckout();
        }
 
        private function setAddActionCheckout() {
            add_action('woocommerce_after_checkout_billing_form' , array('Aloepig_Juso_Search_Button_Plugin', 'billingJusoSearchButton'));            
            add_action('woocommerce_after_checkout_shipping_form' , array('Aloepig_Juso_Search_Button_Plugin', 'shippingJusoSearchButton'));
        }

        private function setAddFilterCheckout() {
            add_filter('woocommerce_billing_fields', function($fields){
                $fields['billing_postcode']['custom_attributes'] = array('readonly'=>'readonly');
                return $fields; 
            }); 
            add_filter('woocommerce_shipping_fields', function($fields){
                $fields['shipping_postcode']['custom_attributes'] = array('readonly'=>'readonly');
                return $fields; 
            });
        }

        public function billingJusoSearchButton() {
            ?>
            <input type="text" id="wc_billing_juso_search_text" placeholder="0로0길0 또는 번지">
            <button type="button" class="wc_juso_search_button" onclick="getWCJusoSearchText('billing');">우편번호 검색</button>
            <div id=wc_billing_juso_list></div>
            <?php
        }

        public function shippingJusoSearchButton() {
            ?>
            <input type="text" id="wc_shipping_juso_search_text" placeholder="0로0길0 또는 번지">
            <button type="button" class="wc_juso_search_button" onclick="getWCJusoSearchText('shipping');">우편번호 검색</button>
            <div id=wc_shipping_juso_list></div>
            <?php
        }
    }
 
    Aloepig_Juso_Search_Button_Plugin::init();
}

?>