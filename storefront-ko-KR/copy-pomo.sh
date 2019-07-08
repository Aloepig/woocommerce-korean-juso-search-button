#! /bin/bash
echo "Storefront po & mo 파일복사"
echo "실행명령: sh copy_pomo.sh"
echo "/wp-content/themes/storefront/inc/woocommerce/storefront-woocommerce-template-functions.php 텍스트 번역을 위해"
cp storefront-ko_KR.mo /usr/share/nginx/html/jangheungdawon/wp-content/themes/storefront/languages/storefront-ko_KR.mo
cp storefront-ko_KR.po /usr/share/nginx/html/jangheungdawon/wp-content/themes/storefront/languages/storefront-ko_KR.po
echo "wp-content/themes/storefront/languages 복사완료"
