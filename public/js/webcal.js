$(document).ready(function(){

    var url = $("#newEventBox").attr('action');
    var getUrl = window.location;
    var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];

    //console.log("url:" +baseUrl);


    $(document).on('click', '#newEventBox', function(e){

        e.preventDefault();

        var fecha = $(this).attr('data-fecha');
        var hora = $(this).attr('data-hora');
        var user = $(this).attr('data-user');
        var tecnologias = $(this).attr('data-tecnologias');
        getBubble(fecha, hora, user, tecnologias);

    });

    function getBubble(fecha, hora, user, tecnologias)
    {


        datos = {
            'fecha' : fecha,
            'hora' : hora,
            'user' : user,
            'tecnologias' : tecnologias
        }

        js = ajax(datos, 'proyectosActividad');

        js.success(function (data)
        {
            if(data.estado)
            {
                $form = $("<form id='newEvent-"+hora+"-"+fecha+"-"+user+"' action='"+baseUrl+"/evento/nuevo' method='post' data-ajax='true'></form>");
                $form = $("div[id='b-"+hora+"-"+fecha+"-"+user+"']").append($form);

                ln = "<div class=\"form-group row my-chosen\"> \n";
                ln += "<span class=\"col-sm-12\">Crear Evento</span> \n";
                ln += "<label class=\"col-sm-12 control-label\" for=\"persona\">Seleccionar Proyecto *</label> \n";
                ln += "<div class=\"col-sm-12 select-daily\" style=\"text-align: left;\"> \n";
                ln +=  "<select id=\"proyecto\" name=\"proyecto\" class=\"select-chosen\" data-placeholder=\"Seleccionar...\" style=\"width: 250px;\"> \n";
                ln +=  "</select> \n";
                ln +=  "</div> \n";

                ln +=  "<input class=\"hidden\" name=\"hora\" value="+hora+" /> \n";
                ln +=  "<input class=\"hidden\" name=\"fecha\" value="+fecha+" /> \n";
                ln +=  "<input class=\"hidden\" name=\"calendarUser\" value="+user+" /> \n";
                ln +=  "<div class=\"col-xs-12 btn-group-box\"> \n";
                ln +=  "<button type=\"submit\" class=\"btn btn-sm btn-default\">Continuar</button>\n";
                ln +=  "</div>\n";
                ln +=  "</div>\n";


                $form = $("form[id='newEvent-"+hora+"-"+fecha+"-"+user+"']").html(ln);



                //Agregamos los proyectos al select
                $('#proyecto').append($('<option>'));
                $.each(data.proyectos, function (i, item) {
                    $('#proyecto').append($('<option>', {
                        value: item.id,
                        text : item.nombre
                    }));
                });

                $('#proyecto').chosen({width: "100%", disable_search_threshold: 7});


                //mostramos el bubble
                $("div[id='b-"+hora+"-"+fecha+"-"+user+"']").css({"display":"flex"});

                //comprobamos si no esta dentro de la pantalla y lo posicionamos a la derecha
                visibility = $("div[id='b-"+hora+"-"+fecha+"-"+user+"']").visible(false,false,'horizontal');
                if(visibility == false){
                    $("div[id='b-"+hora+"-"+fecha+"-"+user+"']").addClass("to-right");
                }



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
                //console.log(data.msg);
                return data;
            }
        });
    }

    $(document).mouseup(function (e) {
        var container = $("div[id^='b-']");

        if (!container.is(e.target) // if the target of the click isn't the container...
            && container.has(e.target).length === 0) // ... nor a descendant of the container
        {
            container.empty();
            container.hide();
        }
    });

    jQuery.expr.filters.offscreen = function(el) {
        return (
            (el.offsetLeft + el.offsetWidth) < 0
            || (el.offsetTop + el.offsetHeight) < 0
            || (el.offsetLeft > window.innerWidth || el.offsetTop > window.innerHeight)
        );
    };

});