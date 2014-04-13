$(document).ready(function(){
	var lang=$("#ad_lang").val();
	$(".rick #page_contents_main #table_for_opration tr:gt(0) td").css({'color':'#666'});
	check_box_bind($(":checkbox[id=\"cba\"]"),$(":checkbox[id!=\"cba\"]"));
	$('#order-select').on('change',function(){
		if(confirm("操作确认") && $(this).val() != '-1') {
			$('.table-checkbox').each(function(){
				if(this.checked == true) {
					$('#order-form').append('<input name="order[]" type="hidden" value="'+$(this).val()+'" />');
				}
			});
			$('#order-form').submit();
		}
	});
});