var productTypeAddString = '';
var deleteString = '';
var deleteKaku = '';
function ChangeStringByLang()
{
	$('#product-submit').val(eval('message.'+lang+'.insert'));
	productTypeAddString = eval('message.'+lang+'.add');
	deleteString = eval('message.'+lang+'.delete');
	deleteKaku = eval('message.'+lang+'.deleteKaku');
}
$(document).ready(function(){
	$('#product-submit').on('click',function(){
		$('#product-form').append('<input type="hidden" name="description" value="'+UE.getEditor('editor').getContent()+'" />');
		$('#product-form').submit();
	});
	$('.product-image-add').on('click',function(){
		$('#product-image').append('<p><input type="file" name="image[]" />&nbsp;<a class="product-image-del" href="javascript:;">删除</a></p>');
	});
	$('body').on('click','.product-image-del',function(){
		$(this).parent().remove();
	});
	$('body').on('click','.product-type-add',function(){
		$('.product-type-linkage').eq(0).clone().appendTo('#product-type').wrap('<p></p>').after('&nbsp;<a class="product-type-del" href="javascript:;">删除</a>').removeAttr('id');
	});
	$('body').on('click','.product-type-del',function(){
		$(this).parent().remove();
	});
});