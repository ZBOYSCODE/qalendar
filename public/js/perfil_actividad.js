$(document).ready(function(){

	$(document).on('click', '#btn_cancelar_evento', function(){

		if(confirm("¿ seguro de cancelar el evento ?")){

			var actividad = $("#actividad").val();

			if(actividad == '' || actividad == 0){
				console.log("act:"+actividad);
				return false;
			}

			cambio_estado_evento(actividad, 'delete');
		}
	});

	$(document).on('click', '#btn_activar_evento', function(){

		var actividad = $("#actividad").val();

		if(actividad == '' || actividad == 0){
			console.log("act:"+actividad);
			return false;
		}

		cambio_estado_evento(actividad, 'activar');

	});

	function cambio_estado_evento(act, estado)
	{

		var url = $("#frmajax").attr('action');
		url += "/"+estado;

		var datos = {
			'act' : act
		}

		js = $.xajax(datos, url);

		js.success(function (data)
		{
			if(data.estado)
			{
				$.bootstrapGrowl(data.msg);


				if(estado == 'activar'){
					
					$(".btn_cambio_estado").attr('id', 'btn_cancelar_evento');
					$(".btn_cambio_estado").text("cancelar");

					$(".btn_cambio_estado").removeClass("btn-success");
					$(".btn_cambio_estado").addClass("btn-danger");

					$("#span_estado").text("Activado ");

				} else if(estado == 'delete') {
					$(".btn_cambio_estado").attr('id', 'btn_activar_evento');
					$(".btn_cambio_estado").text("activar");

					$(".btn_cambio_estado").removeClass("btn-danger");
					$(".btn_cambio_estado").addClass("btn-success");

					$("#span_estado").text("Cancelado ");
					var urlAgenda = $("#frmagenda").attr('action');
					window.location.replace(urlAgenda);
				}


			}else{
				$.bootstrapGrowl(data.msg,{type:'danger'});
			}
		});
	}
});