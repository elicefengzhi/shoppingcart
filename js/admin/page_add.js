function ChangeStringByLang()
{
	$('#page-submit').val(eval('message.'+lang+'.insert'));
}
UE.getEditor('editor');
$(document).ready(function(){
	$('#page-submit').on('click',function(){
		$('#page-form').append('<input type="hidden" name="page_body" value="'+UE.getEditor('editor').getContent()+'" />');
		$('#page-form').submit();
	});
});