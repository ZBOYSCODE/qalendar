$(document).ready(function(){

	var url = $("#frm").attr('action');


	$(document).on('click', '#crear_bloque', function(){

		var user  = $("#usuario").val();	
		var fecha = $("#fecha").val();
		var horai = $("#hora_inicio").val();
		var horaf = $("#hora_fin").val();

		$.log(user);
		$.log(fecha);
		$.log(horai);
		$.log(horaf);

		if(user == ''){ $.bootstrapGrowl("Debe seleccionar un usuario.",{type:'danger'}); return false; }
		if(fecha == '' ){ $.bootstrapGrowl("Debe ingresar una fecha.",{type:'danger'}); return false; }
		if(horai == '' || horai == '00:00'){ $.bootstrapGrowl("Debe ingresar una hora de inicio.",{type:'danger'}); return false; }
		if(horaf == '' || horaf == '00:00'){ $.bootstrapGrowl("Debe ingresra una hora de termino.",{type:'danger'}); return false; }

		crea_bloque(user, fecha, horai, horaf);

	});

	$(document).on('click', '.delete-bloqueo', function(){

		var id = $(this).attr('data-id');

		if(confirm("¿Seguro desea eliminar el bloqueo de horas?")){
			elimina_bloque(id);
		}
		
	});

	function elimina_bloque(id)
	{
		var datos = {
			'bloqueo' 	: id
		}

		fun = $.xajax(datos, url+'/delete');
		fun.success(function (data)
		{
			if(data.estado)
			{
				$.log(data.datos);

				$.bootstrapGrowl(data.msg);

				$("#tr_"+id).addClass('danger').hide('slow', function(){
					$(this).remove();
				});

			}else{
				$.bootstrapGrowl(data.msg,{type:'danger'});
				$.log(data);
			}
		});
	}

	function crea_bloque(user, fecha, horai, horaf)
	{
		var datos = {
			'user' 	: user,
			'fecha'	: fecha,
			'horai'	: horai,
			'horaf'	: horaf
		}

		fun = $.xajax(datos, url+'/create');
		fun.success(function (data)
		{
			if(data.estado)
			{
				$.log(data.datos);


				//$("#alerta").alerta(data.msg, 'alert-success');
				//$.bootstrapGrowl(data.msg);

				//carga_datos();
				//$("#fecha").val('');
				//$("#hora_inicio").val('');
				//$("#hora_fin").val('');

				location.reload();
			}else{
				$.bootstrapGrowl(data.msg,{type:'danger'});
				$.log(data);
			}
		});
	}

	function carga_datos()
	{

	}

});