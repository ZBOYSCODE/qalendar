$(document).ready(function(){

	var url = $("#frm").attr('action');
	console.log(url);


	$(document).on('click', '#delete-project', function(e){

		e.preventDefault();

		var id = $(this).attr('data-id');

		if(confirm("¿Seguro desea eliminar este proyecto?")){
			deleteProyecto(id);
		}

	});

	function deleteProyecto(id)
	{

		datos = {
			'proyecto' : id
		}

		js = ajax(datos, 'delete');

		js.success(function (data)
		{
			if(data.estado)
			{
				$("#tr_"+id).addClass('danger').hide('slow', function(){
					$(this).remove();
				});

				$.bootstrapGrowl(data.msg);

			}else{
				$.bootstrapGrowl(data.msg,{type:'danger'});
			}
		});
	}

	function ajax(datos, metodo, async)
	{
		//valor por omisión
		async = async || 'true';
		return $.ajax({
            async	: async,
            type 	: 'POST',
            data 	: datos,
            url 	: url+'/'+metodo,
            dataType: 'json',
            success : function(data)
            {
                //log(data.msg);
                return data; 
            }
        });
	}

});