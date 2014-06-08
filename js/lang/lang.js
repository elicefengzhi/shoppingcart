var lang = getCookie('LANG_COOKIE');
function getCookie(name)
{
	 var arr = document.cookie.split("; ");
	 for(var i = 0;i < arr.length;i++)
		 if (arr[i].split("=")[0] == name)
			return unescape(arr[i].split("=")[1]);
	 return null;
}
function setCookie(name,value) {
   var today = new Date();
   var expires = new Date();
   expires.setTime(today.getTime() + 1000*60*60*24*2000);
   document.cookie = name + "=" + escape(value) + "; expires=" + expires.toGMTString();
}
function clickChange(lang){
	setCookie('LANG_COOKIE',lang);
	var nowLang;
	lang == 'jp' ? nowLang = 0 : nowLang = 1;
	$(".rick #log a img").prop('src','/img/login_text/Lang'+nowLang+'/TJW.png');
}
$(document).ready(function(){
	var langKey;
	function change()
	{
		if(lang == null) {
			lang = 'jp';
		}
		$("[lang]").each(function(){
			langKey = $(this).attr('lang');
			if(langKey != '') {
				$(this).text(eval('message.'+lang+'.'+langKey+''));
			}
		});
		clickChange(lang);
		typeof(ChangeStringByLang) == 'function' && ChangeStringByLang();
	}
	$('#ad_lang').on('change',function(){
		var val = $(this).val();
		lang = 'zh';
		if(val == 0) {
			lang = 'jp';
		}
		change();
	});
	change();
	lang == 'zh' ? lang = 1 : lang = 0;
	$('#ad_lang').val(lang);
});