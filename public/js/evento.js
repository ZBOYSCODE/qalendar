$(document).ready(function(){

	var url = $("#frm").attr('action');

	cargar_qa();

	$(document).on('change', '#proyecto', function(){
		cargar_qa();
	});

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
				alerta(data.msg, 'alert-danger');
				$.log(data);
			}
		});
	}


	

});