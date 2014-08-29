function ChangeStringByLang()
{
	//$('#news-submit').val(eval('message.'+lang+'.insert'));
	$('#news-submit').text(eval('message.'+lang+'.insert'));
}
UE.getEditor('editor');
$(document).ready(function(){
	$('#news-submit').on('click',function(){
		$('#news-form').append('<input type="hidden" name="news_body" value="'+UE.getEditor('editor').getContent()+'" />');
		$('#news-form').submit();
	});
});