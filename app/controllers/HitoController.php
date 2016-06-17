<?php

	namespace Gabs\Controllers;

	use Gabs\Models\Hito;

	class HitoController extends ControllerBase
	{


		public function crearHitoAction()
		{

			$data['estado'] = true;

	    	$idhito = $this->request->getPost("idhito");

	    	if(!$idhito){
	    		# para crear un nuevo hito
	    		$hito = new Hito();
	    		$hito->actv_id			= $this->request->getPost("idActividad", 'int');
	    		$hito->created_at 	= date('Y-m-d');

	    		$data['msg']	= "Hito creado con éxito";

	    	} else {
	    		# para actualizar hito
	    		$hito = Hito::findFirst($idhito);

	    		$data['msg']	= "Hito editado con éxito";
	    	}


	    	$hito->hito_nombre 		= $this->request->getPost("nombre", 		"string");
	    	if(empty($hito->hito_nombre)){
	    		$data['estado'] = false;
	    		$data['msg'] 	= "Lo sentimos, debe incluir el nombre";
	    	}

	    	$hito->hito_descripcion	= $this->request->getPost("descripcion",	"string");
	    	if(empty($hito->hito_descripcion)){
	    		$data['estado'] = false;
	    		$data['msg'] 	= "Lo sentimos, debe incluir la descripcion";
	    	}

	    	$hito->updated_at 		= date('Y-m-d');


	    	if($data['estado'])
	    	{
		        if ($hito->save())
		        {
		        	$data['estado'] = true;
		        	
		        	$data['id'] 	= $hito->hito_id;
		        } else {
		        	$data['estado'] = false;
		        	$data['msg'] 	= "Lo sentimos, los siguientes errores ocurrieron : ";

		            foreach ($hito->getMessages() as $message) {
		                $data['detalleError'][] = $message->getMessage();;
		            }
		        }
	    	}


	        echo json_encode($data, JSON_PRETTY_PRINT);
		}

		public function deleteHitoAction()
	    {
	    	$id = $this->request->getPost("hito", "int");
	    	$hito = Hito::findFirst($id);

	    	if ($hito != false)
	    	{
			    if ($hito->delete() == false)
			    {
			    	$data['estado'] = false;
			        $data['msg'] 	= "¡Lo sentimos, no hemos podido eliminar el hito!";

			        foreach ($hito->getMessages() as $message) {
			            $data['detalleError'][] = $message->getMessage();
			        }

			    } else {
			        $data['estado'] = true;
	        		$data['msg']	= "Hito eliminado!";
	        		$data['id']		= $id;
			    }
			}else{
				$data['estado'] = false;
			    $data['msg'] 	= "¡Lo sentimos, el hito no existe!";
			}

			echo json_encode($data, JSON_PRETTY_PRINT);
	    }
	}