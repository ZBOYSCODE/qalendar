$(document).ready(function(){


	var url = "/actividad/buscar";

	var getUrl = window.location;
	var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];

	var temPopOver ="	<form action='"+baseUrl+url+"' method='POST'>"+
					"		<div class='form-group'>"+
					"			<input name='search' class='form-control' placeholder='Buscar...'>"+
					"			<button id='search' type='submit' class='btn btn-default form-control'>Buscar</submit>"+
					"		</div>";

	$("#searchop").popover({
		container 	: 	'body',
		toggle		:	'popover',
		placement	: 	'bottom',
		html 		:	'true',
		content		:	temPopOver
	});
    
});