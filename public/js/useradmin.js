$(document).ready(function(){

	$(document).on('click', '.delete', function(){

		var id = $(this).attr('data-id');

		if(confirm("¿Seguro desea eliminar el usuario?")){
			delete_user(id);
		}

	});


	function delete_user(id)
	{
		var datos = {
			'user_id' : id
		}

		var url = $("#frm").attr('action');
		url += "/delete";


		js = $.xajax(datos, url);

		js.success(function (data)
		{
			if(data.estado)
			{
				$.bootstrapGrowl(data.msg);

				$("#tr_"+id).children().addClass('danger').hide('slow', function(){
					$(this).remove();
				});

			}else{
				$.bootstrapGrowl(data.msg,{type:'danger'});
			}
		});
	}

});