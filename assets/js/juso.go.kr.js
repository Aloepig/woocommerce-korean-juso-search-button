function getJHJusoSearchText(){
	getAddr(jQuery('#wc_juso_search_text').val());
}

// jusoSearchText: 검색어
// pageType: 페이지 종류
function getAddr(jusoSearchText, pageType){
	// AJAX 주소 검색 요청
	jQuery.ajax({
		url:"http://www.juso.go.kr/addrlink/addrLinkApi.do"
		,type:"post"
        ,data: {currentPage:"1", countPerPage:"12", resultType:"json", confmKey:"U01TX0FVVEgyMDE5MDQyMzE2MzUxMTEwODY3NDI=", keyword:jusoSearchText}
		// ,data:jQuery("#form").serialize() 						// 요청 변수 설정
		,dataType:"json"											// 데이터 결과 : JSON
		,success:function(jsonStr){	
			jQuery("#jh_juso_list").html("");						// 결과 출력 영역 초기화
			var errCode = jsonStr.results.common.errorCode; 		// 응답코드
			var errDesc = jsonStr.results.common.errorMessage;		// 응답메시지
			if(errCode != "0"){ 	
				alert(errCode+"="+errDesc);
			}else{
				if(jsonStr!= null){
					makeListJson(jsonStr);							// 결과 JSON 데이터 파싱 및 출력
				}
			}
		}
		,error: function(xhr,status, error){
			console.error("에러발생");								// AJAX 호출 에러
		}
	});
}
                                     
function makeListJson(jsonStr, pageType){
	var htmlStr = "";
	htmlStr += "<table id=wc_juso_search_results>";
	// jquery를 이용한 JSON 결과 데이터 파싱
	jQuery(jsonStr.results.juso).each(function(){
		htmlStr += "<tr class=wc_juso_tr>";
		htmlStr += "<td>"+this.roadAddr+"</td>";		// 도로명 주소
		//htmlStr += "<td>"+this.siNm+"</td>";			// 시도명
		htmlStr += "<td>"+this.zipNo+"</td>";			// 우편번호
		htmlStr += "</tr>";
	});
	htmlStr += "</table>";
	// 결과 HTML을 FORM의 결과 출력 DIV에 삽입
	jQuery("#wc_juso_list").html(htmlStr);
	jQuery("#wc_juso_search_results").selectable();
	
	jQuery(function(){
		jQuery('tr').click(function(){
			jQuery('#billing_address_1').val(jQuery(this).children().eq(0).text());
			jQuery('#billing_postcode').val(jQuery(this).children().eq(1).text());
		});
	});
}