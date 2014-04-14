function ChangeStringByLang()
{
	$('#delete-button').val(eval('message.'+lang+'.delete'));
	deleteKaku = eval('message.'+lang+'.deleteKaku');
}
$(document).ready(function (){
	$(".rick #page_contents_main #table_for_opration tr:gt(0) td").css({'color':'#666'});
	check_box_bind($(":checkbox[id=\"cba\"]"),$(":checkbox[id!=\"cba\"]"));

	$('#delete-button').on('click',function(){
		if(confirm(deleteKaku)){
			$('.table-checkbox').each(function(){
				if(this.checked == true) {
					$('#delete-form').append('<input name="delete[]" type="hidden" value="'+$(this).val()+'" />');
				}
			});
			$('#delete-form').submit();
		}
	});
});