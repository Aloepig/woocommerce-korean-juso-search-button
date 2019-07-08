#! /bin/bash
echo "우커머스 po & mo 파일복사"
echo "실행명령: sh copy_pomo.sh"
cp woocommerce-ko_KR.mo /usr/share/nginx/html/jangheungdawon/wp-content/languages/plugins/woocommerce-ko_KR.mo
cp woocommerce-ko_KR.po /usr/share/nginx/html/jangheungdawon/wp-content/languages/plugins/woocommerce-ko_KR.po
echo "wp-content/languages/plugins 복사완료"
