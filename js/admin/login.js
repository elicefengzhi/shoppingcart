var all="";
function ChangeStringByLang(){
	lang == 'jp' ? lang = 0 : lang = 1;
	$(".rick #page_login_main #login table .td0 p img").each(function (key){
		var num=key+1;
		$(this).prop('src','/img/login_text/Lang'+lang+'/c'+num+'.png');
	});
	$(".rick #page_login_main #login #button_submit img").prop('src','/img/login_text/Lang'+lang+'/c.png');
}
$(document).ready(function (){
	var forEach = function(array, callback, thisObject) {  
		if (array.forEach) {  
			array.forEach(callback, thisObject);  
		} else {  
			for (var i = 0, len = array.length; i < len; i++) { callback.call(thisObject, array[i], i, array); }  
		}  
	} 
	function validteCode() 
	{ 
	    var codes = new Array(4);
	    var colors = new Array("Red","Green","Gray","Blue","Maroon","Aqua","Fuchsia","Lime","Olive","Silver"); 
	    for(var i=0;i < codes.length;i++) 
	    {
	        codes[i] = Math.floor(Math.random()*10); 
	    } 
	    var spans = document.getElementById("divCode").childNodes;
	    forEach(spans, function(o, i) {  
	    	if (o.tagName == 'SPAN')  
	        o.innerHTML=codes[i]; 
	        all+=codes[i];
	        o.style.color = colors[Math.floor(Math.random()*10)];
	        o.style.fontSize='larger'; 
	    }); 
	} validteCode(); 
	$('#codeChangeA').on('click',function(){
		validteCode();
	});
	hv_color($(".rick #page_login_main #login table .td1 #valid_startor"),'#00E100','#00AE00');
	$(".rick #page_login_main #login #button_submit").on('click',function(){
		if(document.getElementById("validateCode").value == all) {
			$('#login-form').submit();
		}
	});
});