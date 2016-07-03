(function($){

	$.fn.extend({
		renderSelect: function(objet, slc = null){

			var th = this;

			$.each(objet, function(indice, valor){
				// creamos el objeto <option>
				var option = jQuery('<option />', {
				    value	: indice,
				    text	: valor
				});

				// indicamos si uno las opciones es seleccionada por defecto
				if(slc != null && slc == indice){
					option.attr('selected', true);
				}

				// añadimos la opcion al select
				option.appendTo(th)
			});
		},

		alerta: function (msg, tipo_alerta){

			$(this).children().addClass('danger').hide('fast', function(){
				$(this).remove();
			});

			$('<div/>', {
			    class 	: 'alert '+tipo_alerta,
			    role 	: 'alert',
			    html 	: '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
			    			"<strong>Atención :</strong> "+msg
			}).appendTo(this);
		},

		quitar_alerta: function()
		{
			$(this).children().addClass('danger').hide('fast', function(){
				$(this).remove();
			});
		}
	});


	jQuery.xajax = function (datos, url, async)
	{
		//valor por omisión
		async = async || 'true';
		return $.ajax({
            async	: async,
            type 	: 'POST',
            data 	: datos,
            url 	: url,
            dataType: 'json',
            success : function(data)
            {
                $.log(data.msg);
                return data;
            }
        });

	}
	

	jQuery.log = function(msg){
		console.log(msg);
	}



})(jQuery)
