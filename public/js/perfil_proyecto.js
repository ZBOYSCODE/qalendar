$(document).ready(function(){

	$(document).on('click', '#btn_eliminar_proyecto', function(){

		if(confirm("¿ seguro de eliminar el proyecto ?")){

			var proyecto = $("#proyecto_id").val();

			if(proyecto == '' || proyecto == 0){

				return false;
			}

			cambio_estado_proyecto(proyecto, 'delete');
		}
	});

	$(document).on('click', '#btn_activar_proyecto', function(){

		var proyecto = $("#proyecto").val();

		if(proyecto == '' || proyecto == 0){

			return false;
		}

		cambio_estado_proyecto(proyecto, 'activar');

	});

	function cambio_estado_proyecto(proyecto, estado)
	{

		var url = $("#frm").val();
		url += "/"+estado;

		var datos = {
			'proyecto' : proyecto
		}

		js = $.xajax(datos, url);

		js.success(function (data)
		{
			if(data.estado)
			{
				$.bootstrapGrowl(data.msg);


				if(estado == 'activar'){
					
					$(".btn_cambio_estado").attr('id', 'btn_eliminar_proyecto');
					$(".btn_cambio_estado").text("cancelar");

					$(".btn_cambio_estado").removeClass("btn-success");
					$(".btn_cambio_estado").addClass("btn-danger");

					$("#span_estado").text("Activo ");

				} else if(estado == 'delete') {
					$(".btn_cambio_estado").attr('id', 'btn_activar_proyecto');
					$(".btn_cambio_estado").text("activar");

					$(".btn_cambio_estado").removeClass("btn-danger");
					$(".btn_cambio_estado").addClass("btn-success");

					$("#span_estado").text("Eliminado ");
				}


			}else{
				$.bootstrapGrowl(data.msg,{type:'danger'});
			}
		});
	}
});