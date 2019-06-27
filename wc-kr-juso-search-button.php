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
            wp_enqueue_script( 'wc_kr_juso_search_script', plugin_dir_url( __FILE__ ) . 'assets/js/wc-kr-juso-search-button.js', array('jquery'), WC_KR_JUSO_SEARCH_BUTTON_VERSION, false ); 
            wp_enqueue_style( 'wc_kr_juso_search_css', plugin_dir_url( __FILE__ ) . 'assets/css/wc-kr-juso-search-button.css', array(), WC_KR_JUSO_SEARCH_BUTTON_VERSION, 'all');
            wp_enqueue_script( 'jquery-ui-selectable' );

            self::setAddActionForJusoSearch();
            self::setAddFilterForJusoSerch();
        }
 
        private function setAddActionForJusoSearch() {
            // checkout 부분
            add_action('woocommerce_after_checkout_billing_form' , array('Aloepig_Juso_Search_Button_Plugin', 'billingJusoSearchButton'));            
            add_action('woocommerce_after_checkout_shipping_form' , array('Aloepig_Juso_Search_Button_Plugin', 'shippingJusoSearchButton'));
            // edit address 부분
            add_action('woocommerce_after_edit_address_form_billing' , array('Aloepig_Juso_Search_Button_Plugin', 'billingJusoSearchButton'));            
            add_action('woocommerce_after_edit_address_form_shipping' , array('Aloepig_Juso_Search_Button_Plugin', 'shippingJusoSearchButton'));
            // edit account 부분
            add_action('woocommerce_after_edit_account_form', function(){
                ?>
                <script>
                   jQuery("label[for='account_last_name']").html("전화번호<span class=required>*</span>");
                </script>
                <?php 
            });
        }

        private function setAddFilterForJusoSerch() {
            // 주소 공통
            add_filter('woocommerce_default_address_fields', function($address_fields){
                $address_fields['last_name']['label'] = WC_KR_JUSO_SEARCH_BUTTON_LAST_NAME;
                $address_fields['postcode']['custom_attributes'] = array('readonly'=>'readonly');
                $address_fields['address_1']['custom_attributes'] = array('readonly'=>'readonly'); 
                $address_fields['company']['required '] = false; 
                $address_fields['state']['required '] = false; 
                $address_fields['city']['required '] = false; 
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
                // unset하기 전에 반드시 required를 false해야 한다.(2019.06.18)  
                $fields['billing_phone']['required'] = false;  
                unset($fields['billing_company']);
                unset($fields['billing_state']);
                unset($fields['billing_city']);
                unset($fields['billing_phone']);
                return $fields; 
            }); 
            // 배송주소(다른배송지) 공통
            add_filter('woocommerce_shipping_fields', function($fields){
                unset($fields['shipping_company']);
                unset($fields['shipping_state']);
                unset($fields['shipping_city']);
                return $fields; 
            });
        }

        public function billingJusoSearchButton() {
            ?>
            <div class="alert alert-danger" id="wc_billing_juso_search_alert_massage"></div>
            <input type="text" id="wc_billing_juso_search_text" placeholder="검색어를 입력 하세요">
            <button type="button" class="wc_juso_search_button" onclick="getWCJusoSearchText('billing','1');">주소 검색</button>
            <div id=wc_billing_juso_list></div>
            <script>
            	jQuery(function(){
                    if ( jQuery('#billing_address_1').val().length === 0 || jQuery('#billing_postcode').val().length === 0){
                        AloepigHideAndShow.billingAddressHide();
                    }                
	            });
            </script>
            <?php
        }

        public function shippingJusoSearchButton() {
            ?>
            <div class="alert alert-danger" id="wc_shipping_juso_search_alert_massage"></div>
            <input type="text" id="wc_shipping_juso_search_text" placeholder="검색어를 입력 하세요">
            <button type="button" class="wc_juso_search_button" onclick="getWCJusoSearchText('shipping','1');">주소 검색</button>
            <div id=wc_shipping_juso_list></div>
            <script>
            	jQuery(function(){
                    if ( jQuery('#shipping_address_1').val().length === 0 || jQuery('#shipping_postcode').val().length === 0){
                        AloepigHideAndShow.shippingAddressHide();
                    }                
	            });
            </script>
            <?php
        }
    }
 
    Aloepig_Juso_Search_Button_Plugin::init();
}

?>