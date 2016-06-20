$(document).ready(function(){

	var url = $("#frmajax").attr('action');

	$(document).on('click', '#upd_estado_actividad', function()
	{
		quitar_alerta();

		var actividad 	= $("#idActividad").val();
		var tipoestado 	= $("#tipoestado").val();
		
		editar_estado(actividad, tipoestado);
 
	});

	function editar_estado(act, estado)
	{

		var datos = {
			'actividad' 	: act,
			'estado'		: estado
		}
	
		fun = ajax(datos, 'updEstado');
		fun.success(function (data)
		{
			if(data.estado)
			{
				alerta(data.msg, 'alert-success');


				$("#nombre_estado").text(data.nombre);



			}else{
				alerta(data.msg, 'alert-danger');
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
                log(data.msg);
                return data; 
            }
        });
	}

	function alerta(msg, tipo_alerta){

		quitar_alerta();

		jQuery('<div/>', {
		    class 	: 'alert '+tipo_alerta,
		    role 	: 'alert',
		    html 	: '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
		    			"<strong>Atención :</strong> "+msg
		}).appendTo('#message_error');

	}

	function quitar_alerta()
	{
		$("#message_error").children().addClass('danger').hide('fast', function(){
			$(this).remove();
		});
	}

	function log(msg){
		console.log(msg);
	}
	

});