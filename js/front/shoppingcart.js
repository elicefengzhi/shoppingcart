$(document).ready(function(){
	//获得文本框对象
	var t = $("#product-qty");	
	//数量增加操作
	$("#product-add").on('click',function(){		
		t.val(parseInt(t.val())+1);
		if (parseInt(t.val())!=1){
			$('#product-min').attr('disabled',false);
		}
		setTotal();
	});	
	//数量减少操作
	$("#product-min").on('click',function(){
		t.val(parseInt(t.val())-1);
		if (parseInt(t.val())==1){
			$('#product-min').attr('disabled',true);
		}
		setTotal();
	});
	//计算操作
	function setTotal(){
		var totalprice = (parseInt(t.val())*parseInt(product_price)).toFixed(2);
		$("#product-total").html(totalprice);//toFixed()是保留小数点的函数很实用哦
	}	
	//初始化
	setTotal();
	$('#product-shoppingcart').on('click',function(){
		$.ajax({
			url: shoppingcartUrl,
			type: 'get',
			dataType: 'json',
			data: {
				'count': parseInt(t.val()),
				'size': $('#product-size').val()
			},
			success: function(data){
				data.isOk == true ? alert('追加成功') : alert('追加失敗');
			},
			error: function(){
				alert('追加失敗');
			}
		});
	});
});