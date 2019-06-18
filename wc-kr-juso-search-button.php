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
define( 'WC_KR_JUSO_SEARCH_BUTTON_LAST_NAME', '전화번호');

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
            // checkout 부분
            add_action('woocommerce_after_checkout_billing_form' , array('Aloepig_Juso_Search_Button_Plugin', 'billingJusoSearchButton'));            
            add_action('woocommerce_after_checkout_shipping_form' , array('Aloepig_Juso_Search_Button_Plugin', 'shippingJusoSearchButton'));
            // edit address 부분
            add_action('woocommerce_after_edit_address_form_billing' , array('Aloepig_Juso_Search_Button_Plugin', 'billingJusoSearchButton'));            
            add_action('woocommerce_after_edit_address_form_shipping' , array('Aloepig_Juso_Search_Button_Plugin', 'shippingJusoSearchButton'));
        }

        private function setAddFilterCheckout() {
            // 주소 공통
            add_filter('woocommerce_default_address_fields', function($address_fields){
                $address_fields['last_name']['label'] = WC_KR_JUSO_SEARCH_BUTTON_LAST_NAME;
                $address_fields['postcode']['custom_attributes'] = array('readonly'=>'readonly');
                $address_fields['address_1']['custom_attributes'] = array('readonly'=>'readonly'); 
                return $address_fields;
            });
            // 청구주소(기본주소) 공통
            add_filter('woocommerce_billing_fields', function($fields){
                $fields['billing_first_name']['priority'] = 1;  
                $fields['billing_last_name']['priority'] = 2;  
                $fields['billing_email']['priority'] = 3;  
                $fields['billing_company']['priority'] = 4;  
                $fields['billing_country']['priority'] = 5;  
                $fields['billing_state']['priority'] = 6;  
                $fields['billing_city']['priority'] = 7;  
                $fields['billing_phone']['priority'] = 8;  
                $fields['billing_address_1']['priority'] = 9;  
                $fields['billing_address_2']['priority'] = 10;  
                $fields['billing_postcode']['priority'] = 11;
                
                unset($fields['billing_company']);
                unset($fields['billing_country']);
                unset($fields['billing_state']);
                unset($fields['billing_city']);
                unset($fields['billing_phone']);
                return $fields; 
            }); 
            // 배송주소(다른배송지) 공통
            add_filter('woocommerce_shipping_fields', function($fields){
                unset($fields['shipping_company']);
                unset($fields['shipping_country']);
                unset($fields['shipping_state']);
                unset($fields['shipping_city']);
                return $fields; 
            });
        }

        public function billingJusoSearchButton() {
            ?>
            <input type="text" id="wc_billing_juso_search_text" placeholder="0로0길0/번지/건물명">
            <button type="button" class="wc_juso_search_button" onclick="getWCJusoSearchText('billing','1');">우편번호 검색</button>
            <div id=wc_billing_juso_list></div>
            <?php
        }

        public function shippingJusoSearchButton() {
            ?>
            <input type="text" id="wc_shipping_juso_search_text" placeholder="0로0길0/번지/건물명">
            <button type="button" class="wc_juso_search_button" onclick="getWCJusoSearchText('shipping','1');">우편번호 검색</button>
            <div id=wc_shipping_juso_list></div>
            <?php
        }
    }
 
    Aloepig_Juso_Search_Button_Plugin::init();
}

?>