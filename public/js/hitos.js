$(document).ready(function(){

	var url = $("#frm").attr('action');

	$(document).on('click', '.delete-act', function(){
		quitar_alerta();

		var idhito = $(this).attr("data-id");


		if(typeof idhito !== "undefined")
		{
			// si es una actividad creada en la bd la eliminamos
			eliminar_hito(idhito);
		} else {
			// si no, solo removemos el elemento
			$(this).parent().parent().parent().addClass('danger').hide('fast', function(){
				$(this).remove();
			});
		}
	});

	$(document).on('click', '.add-actividad', function(){
		
		quitar_alerta();

		// Añadimos una actividad al bloque correspondiente
		jQuery('<div/>', {
		    //id: 'act-'+idbloque,
		    class: 'hito',
		    html: 	
		    		"<div class='form-inline'>"+
                        "<div class='form-group'>"+

                            "<input id='nombre_hito' type='text' class='form-control input-nomb' placeholder='Nombre'>"+
                            "<input id='glosa' 		 type='text' class='form-control input-desc' placeholder='Descripción'>"+

                            "<a class='btn btn-default guardar-act' href='#' role='button'>"+
		    				"<i class='hi hi-floppy_disk' title='Añadir actividad'></i></a>"+
		    				"<a class='btn btn-default delete-act' href='#' role='button'>"+
		    				"<i class='fa fa-trash-o' title='Eliminar actividad'></i></a>"+
                        "</div>"+
                    "</div>"

		}).appendTo('#hitos');
	});


	$(document).on('click', '.guardar-act', function(){

		quitar_alerta();

		var $btn = $(this);

		idhito = $(this).attr("data-id");

		if(typeof idhito !== "undefined")
		{
			editar = true;
		}else{
			editar = false;
			idhito = 0;
		}

		var nombre 		= $(this).parent().children(".input-nomb").val();
		var descripcion	= $(this).parent().children(".input-desc").val();
		var idhito 		= $(this).attr("data-id");
		var idActividad = $("#idactividad").val();


		if(nombre 		== ''){alerta("¡Favor ingresar nombre!", 		'alert-warning'); return false;}
		if(descripcion 	== ''){alerta("¡Favor ingresar descripción!", 	'alert-warning'); return false;}
		if(descripcion 	== ''){alerta("¡Favor ingresar descripción!", 	'alert-warning'); return false;}			
		
		var datos = {
			'idActividad'	: idActividad,
			'descripcion' 	: descripcion,
			'nombre'		: nombre,
			'idhito'		: idhito
		}
		 

		var aj = ajax(datos, 'crearHito');

		aj.success(function (data) {
			if(data.estado){

				$btn.attr('data-id', data.id);
				$btn.parent().parent().parent().attr("id", "hito-"+data.id);
				$btn.parent().children(".delete-act").attr('data-id', data.id);

				$btn.children().removeClass("hi hi-floppy_disk");
				$btn.children().addClass("fa fa-refresh");

				alerta(data.msg, 'alert-success');
			}else{
				alerta(data.msg, 'alert-danger');
			}
		});

	});

	function eliminar_hito(hito)
	{
		var datos = {
			'hito' 	: hito
		}
	
		bloque = ajax(datos, 'deleteHito');
		bloque.success(function (data)
		{
			if(data.estado)
			{
				// una vez eliminado, quitamos el div
				$("#hito-"+data.id).addClass('danger').fadeOut('slow',function(){
	            	$(this).remove();
	            });
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