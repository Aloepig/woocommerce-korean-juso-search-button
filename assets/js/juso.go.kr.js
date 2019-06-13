// input id 형식: wc_[addressType]_juso_search_text
function getWCJusoSearchText(addressType){
	var jusoSearchTextId = "wc_" + addressType + "_juso_search_text";
	getAddr(jQuery('#' + jusoSearchTextId).val(), addressType);
}

// jusoSearchText: 검색어
// addressType: billing, shipping
function getAddr(jusoSearchText, addressType){
	var jusoListId = "wc_" + addressType + "_juso_list";
	// AJAX 주소 검색 요청
	jQuery.ajax({
		url:"http://www.juso.go.kr/addrlink/addrLinkApi.do"
		,type:"post"
        ,data: {currentPage:"1", countPerPage:"12", resultType:"json", confmKey:"U01TX0FVVEgyMDE5MDQyMzE2MzUxMTEwODY3NDI=", keyword:jusoSearchText}
		// ,data:jQuery("#form").serialize() 						// 요청 변수 설정
		,dataType:"json"											// 데이터 결과 : JSON
		,success:function(jsonStr){	
			jQuery("#wc_"+ jusoListId).html("");						// 결과 출력 영역 초기화
			var errCode = jsonStr.results.common.errorCode; 		// 응답코드
			var errDesc = jsonStr.results.common.errorMessage;		// 응답메시지
			if(errCode != "0"){ 	
				alert(errCode+":"+errDesc);
			}else{
				if(jsonStr!= null){
					makeListJson(jsonStr, addressType);							// 결과 JSON 데이터 파싱 및 출력
				}
			}
		}
		,error: function(xhr,status, error){
			console.error("에러발생");								// AJAX 호출 에러
		}
	});
}
                                     
function makeListJson(jsonStr, addressType){
	var jusoListId = "wc_" + addressType + "_juso_list";
	var jusoTrClass = "wc_" + addressType + "_juso_tr";
	var addressId = addressType + "_address_1";
	var postcodeId = addressType + "_postcode"; 
	var htmlStr = "";
	htmlStr += "<table class=wc_juso_search_results>";
	// jquery를 이용한 JSON 결과 데이터 파싱
	jQuery(jsonStr.results.juso).each(function(){
		htmlStr += "<tr class="+ jusoTrClass +">";
		htmlStr += "<td>"+this.roadAddr+"</td>";		// 도로명 주소
		htmlStr += "<td>"+this.zipNo+"</td>";			// 우편번호
		htmlStr += "</tr>";
	});

	// 결과 HTML을 FORM의 결과 출력 DIV에 삽입
	jQuery("#" + jusoListId).html(htmlStr);
	jQuery("table.wc_juso_search_results").selectable({
		filter: 'tr'
	});
	
	jQuery(function(){
		jQuery('tr.'+ jusoTrClass).click(function(){
			jQuery('#' + addressId).val(jQuery(this).children().eq(0).text());
			jQuery('#' + postcodeId).val(jQuery(this).children().eq(1).text());
		});
	});
}