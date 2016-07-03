$(document).ready(function(){


	var url = "/qalendar/actividad/buscar";

	var temPopOver ="	<form action='"+url+"' method='POST'>"+
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