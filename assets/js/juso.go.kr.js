// input id 형식: wc_[addressType]_juso_search_text
function getWCJusoSearchText(addressType, currentPage){
	var jusoSearchTextId = "wc_" + addressType + "_juso_search_text";
	getAddr(jQuery('#' + jusoSearchTextId).val(), addressType, currentPage);
}

// jusoSearchText: 검색어
// addressType: billing, shipping
function getAddr(jusoSearchText, addressType, currentPage){
	var jusoListId = "wc_" + addressType + "_juso_list";
	// AJAX 주소 검색 요청
	jQuery.ajax({
		url:"https://www.juso.go.kr/addrlink/addrLinkApi.do"
		,type:"post"
        ,data: {currentPage:currentPage, countPerPage:"12", resultType:"json", confmKey:"U01TX0FVVEgyMDE5MDQyMzE2MzUxMTEwODY3NDI=", keyword:jusoSearchText}
		// ,data:jQuery("#form").serialize() 						// 요청 변수 설정
		,dataType:"json"											// 데이터 결과 : JSON
		,success:function(jsonStr){	
			jQuery("#wc_"+ jusoListId).html("");						// 결과 출력 영역 초기화
			var errCode = jsonStr.results.common.errorCode; 		// 응답코드
			var errDesc = jsonStr.results.common.errorMessage;		// 응답메시지
			//console.log(jsonStr.results.common);
			if(errCode != "0"){ 	
				alert(errCode+":"+errDesc);
			}else{
				if(jsonStr!= null){
					makeListJson(jsonStr, addressType, currentPage);							// 결과 JSON 데이터 파싱 및 출력
				}
			}
		}
		,error: function(xhr,status, error){
			console.error("에러발생");								// AJAX 호출 에러
		}
	});
}
                                     
function makeListJson(jsonStr, addressType, currentPage){
	var jusoListId = "wc_" + addressType + "_juso_list";
	var jusoTrClass = "wc_" + addressType + "_juso_tr";
	var addressId = addressType + "_address_1";
	var postcodeId = addressType + "_postcode"; 
	var jusoSearchResultsTableClass = "wc_juso_search_results";
	var buttonPageNumClass = "wc_juso_search_button_page_num";
	var buttonCurrentPageClass = "wc_juso_search_button_current_page";
	var totalPageNum = Number(jsonStr.results.common.totalCount)/ Number(jsonStr.results.common.countPerPage);
	// 너무 많은 검색결과는 제한함
	var MAX_PAGE_NUM = 6;
	if (totalPageNum > MAX_PAGE_NUM) {
		totalPageNum = MAX_PAGE_NUM;
	}
	var htmlStr = "";
	htmlStr += "<table class=" + jusoSearchResultsTableClass + ">";
	// jquery를 이용한 JSON 결과 데이터 파싱
	jQuery(jsonStr.results.juso).each(function(){
		htmlStr += "<tr class="+ jusoTrClass +">";
		htmlStr += "<td>"+this.roadAddr+"</td>";		// 도로명 주소
		htmlStr += "<td>"+this.zipNo+"</td>";			// 우편번호
		htmlStr += "</tr>";
	});

	htmlStr += "<div id=juso_search_result_current_page>";
	for(var a = 1; a <= totalPageNum; a++){
		if (a == Number(currentPage)){
			htmlStr += "<button type=\"button\" class=\""+buttonCurrentPageClass+"\" onclick=\"getWCJusoSearchText('"+addressType+"','"+a+"');\">"+a+"</button>";
		} else {
			htmlStr += "<button type=\"button\" class=\""+buttonPageNumClass+"\" onclick=\"getWCJusoSearchText('"+addressType+"','"+a+"');\">"+a+"</button>";
		}
	}
	htmlStr += "</div>";

	// 결과 HTML을 FORM의 결과 출력 DIV에 삽입
	jQuery("#" + jusoListId).html(htmlStr);
	jQuery("table." + jusoSearchResultsTableClass).selectable({
		filter: 'tr'
	});
	
	jQuery(function(){
		jQuery('tr.'+ jusoTrClass).click(function(){
			jQuery('#' + addressId).val(jQuery(this).children().eq(0).text());
			jQuery('#' + postcodeId).val(jQuery(this).children().eq(1).text());
			jQuery('.' + jusoSearchResultsTableClass).hide();
		});
	});
}