var Ntext=
	[
	 [/*0_Jp*/ 
	  '津村ホームページ制作'
	  ],
	 [/*1_Ch*/
	  '津村日语网页制作'
	  ]
	 ];
/*function*/
function hv_color(obj,Fcolorh,Fcolor){
	obj.hover(
			function(){$(this).css({'color':Fcolorh});},
			function(){$(this).css({'color':Fcolor});}
			);
}/*function*/
function hv_bg(obj,Fbgh,Fbg){
	obj.hover(
			function(){$(this).css({'background-image':'url('+Fbgh+')'});},
			function(){$(this).css({'background-image':'url('+Fbg+')'});}
			);
}/*function*/
function check_box_bind(fc,sc){
	$(fc).click(function(){
		if($(this).prop("checked")==true){
			$(sc).prop("checked",true);
		}else{
			$(sc).prop("checked",false);
		}
	});
}/*function*/
$(document).ready(function (){
	var lang=$("#ad_lang").val();
	/*-------------header_script_start-----------*/
	$(".rick #log a img").prop('src','/img/login_text/Lang'+lang+'/TJW.png');
	/*------------foot_script_start--------*/
	$(".rick #foot #foot_company_name").html(Ntext[lang][0]);
	hv_color($(".rick #foot a"),'#00E100','#9F0000');
	/*------------#header #opration_buttons_start--------*/
	$(".rick #header #opration_box_header .line0 #logout").click(function (){location.href='index.html'});
	$(".rick #header #opration_box_header .line0 #top_back").click(function (){location.href='page_list.html'});
	hv_color($(".rick #header #opration_box_header .line0 span"),'#00E100','#000');
	hv_color($(".rick #header #opration_box_header .line1 span"),'#00E100','#00AE00');
});