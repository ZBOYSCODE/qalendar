$(document).ready(function(){

	var url = $("#frm").attr('action');

	cargar_qa();
	carga_tiempo();

	$(document).on('change', '#proyecto', function(){
		cargar_qa();
	});

	$(document).on('change', '#categoria', function(){
		carga_tiempo();
	});

	function carga_tiempo()
	{
		var cat = $("#categoria").val();

		if(cat == 0) return false;

		var datos = {
			'categoria' : cat
		}

		$("#duracion").val("duracion en horas");

		fun = $.xajax(datos, url+'/getDuracionCat');
		fun.success(function (data)
		{
			if(data.estado)
			{
				$.log(data.datos);

				$("#duracion").val(data.duracion);

			}else{
				$.bootstrapGrowl(data.msg,{type:'danger'});
				$.log(data);
			}
		});
	}

	function cargar_qa()
	{
		var proyecto = $("#proyecto").val();
		var qa = $("#qa_selected").val();

		if(qa == ''){ qa = null; }

		if(proyecto == 0) return false;

		var datos = {
			'proyecto' : proyecto
		}

	
		fun = $.xajax(datos, url+'/cargaQaByProject');
		fun.success(function (data)
		{
			if(data.estado)
			{
				$.log(data.datos);
				$("#persona").html("<option value='0'>Seleccionar...</option>");
				$("#persona").renderSelect(data.datos, qa);
				$("#persona").trigger("chosen:updated");
			}else{
				$.bootstrapGrowl(data.msg,{type:'danger'});
				$.log(data);
			}
		});
	}


	

});