$(document).ready(function (){
	/*---top_menu_starts---*/
	$(".rick #page_list_main #navigation p").eq(0).css('border-left','1px solid #999');
	$(".rick #page_list_main #navigation p[class!=\"selected\"]").css('background-image','url(/img/p1.jpg)');
	$(".rick #page_list_main #navigation p[class!=\"selected\"]").css({'cursor':'pointer','color':'#666'});
	hv_bg($(".rick #page_list_main #navigation p[class!=\"selected\"]"),'/img/p1h.jpg','/img/p1.jpg');
	/*---menu_second_floor_starts---*/
	hv_bg($("#AdTopMenu >li > p"),'/img/admin/p1h.gif','/img/admin/p1.gif');
	hv_color($("#AdTopMenu >li >p"),'#00E100','#00AE00');
    $('#AdTopMenuB a').css('text-decoration','none').css('color','#00AE00');
	$('#navigation p').on('click',function(){
		$('#AdTopMenuB ul').each(function(){
			$(this).css('display','none');
		});
		$('.'+$(this).attr('data-ul')).css('display','inline');
		$('#navigation p').each(function(){
			$(this).removeAttr('class');
		});
		$(this).attr('class','selected');
	});
});