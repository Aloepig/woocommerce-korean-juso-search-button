function getJHJusoSearchText(){
	getAddr(jQuery('#jh_juso_search_text').val());
}

function getAddr(jusoSearchText){
	// AJAX 주소 검색 요청
	jQuery.ajax({
		url:"http://www.juso.go.kr/addrlink/addrLinkApi.do"
		,type:"post"
        ,data: {currentPage:"1", countPerPage:"12", resultType:"json", confmKey:"U01TX0FVVEgyMDE5MDQyMzE2MzUxMTEwODY3NDI=", keyword:jusoSearchText}
		// ,data:jQuery("#form").serialize() 						// 요청 변수 설정
		,dataType:"json"											// 데이터 결과 : JSON
		,success:function(jsonStr){	
            console.log("jsonStr::");
            console.log(jsonStr);
			jQuery("#jh_juso_list").html("");							// 결과 출력 영역 초기화
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
                                     
function makeListJson(jsonStr){
	var htmlStr = "";
	htmlStr += "<table id=jh_juso_results>";
	// jquery를 이용한 JSON 결과 데이터 파싱
	jQuery(jsonStr.results.juso).each(function(){
		htmlStr += "<tr>";
		//htmlStr += "<td>"+this.siNm+"</td>";			// 시도명
		htmlStr += "<td>"+this.roadAddr+"</td>";		// 도로명 주소
		htmlStr += "<td>"+this.zipNo+"</td>";			// 우편번호
		// htmlStr += "<td>"+this.roadAddrPart1+"</td>";
		// htmlStr += "<td>"+this.roadAddrPart2+"</td>";
		// htmlStr += "<td>"+this.jibunAddr+"</td>"; 	// 지번 주소
		// htmlStr += "<td>"+this.engAddr+"</td>";		// 영문 주소
		// htmlStr += "<td>"+this.admCd+"</td>";		// 행정구역 코드
		// htmlStr += "<td>"+this.rnMgtSn+"</td>";		// 도로명 코드
		// htmlStr += "<td>"+this.bdMgtSn+"</td>";		// 건물관리번호
		// htmlStr += "<td>"+this.detBdNmList+"</td>";	// 상세 건물명
		/** API 서비스 제공항목 확대 (2017.02) **/
		// htmlStr += "<td>"+this.bdNm+"</td>";
		// htmlStr += "<td>"+this.bdKdcd+"</td>";
		// htmlStr += "<td>"+this.sggNm+"</td>";
		// htmlStr += "<td>"+this.emdNm+"</td>";
		// htmlStr += "<td>"+this.liNm+"</td>";
		// htmlStr += "<td>"+this.rn+"</td>";
		// htmlStr += "<td>"+this.udrtYn+"</td>";
		// htmlStr += "<td>"+this.buldMnnm+"</td>";
		// htmlStr += "<td>"+this.buldSlno+"</td>";
		// htmlStr += "<td>"+this.mtYn+"</td>";
		// htmlStr += "<td>"+this.lnbrMnnm+"</td>";
		// htmlStr += "<td>"+this.lnbrSlno+"</td>";
		// htmlStr += "<td>"+this.emdNo+"</td>";
		htmlStr += "</tr>";
	});
	htmlStr += "</table>";
	// 결과 HTML을 FORM의 결과 출력 DIV에 삽입
	jQuery("#jh_juso_list").html(htmlStr);
	
	jQuery("#jh_juso_results").selectable();
}