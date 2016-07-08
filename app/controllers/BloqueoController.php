<?php

	namespace Gabs\Controllers;

	use Gabs\Models\Bloqueo;
	use Gabs\Models\Users;

	use Phalcon\Mvc\Model\Criteria;
	use Phalcon\Paginator\Adapter\Model as PaginatorModel;

	class BloqueoController extends ControllerBase
	{
	    public function initialize()
	    {
	    	$this->_themeArray = array('topMenu'=>true, 'menuSel'=>'','pcView'=>'', 'pcData'=>'', 'jsScript'=>'');
		}

		public function indexAction()
		{
			$themeArray = $this->_themeArray;
    		$themeArray['pcView'] = 'bloqueo/list';

    		$data['users'] = Users::find();

    		# Paginación
    		$usuario	= $this->request->get("usuario", 'int');
    		$currentPage	= $this->request->get("page", 'int');

			if(empty($currentPage)){
				$currentPage = 1;
			}

			# Filtro
			$buscar = array();
			if(!empty($usuario)) $buscar['user_id']	= $usuario;

			$model = new Bloqueo();

			$query = self::fromInput($this->di, $model, $buscar);

			$this->persistent->searchParams = $query->getParams();

			
			//$proyectos = Bloqueo::find($this->persistent->searchParams);
			$proyectos = Bloqueo::find($this->persistent->searchParams);

			$paginator   = new PaginatorModel(
			    array(
			        "data"  => $proyectos,
			        "limit" => 10,
			        "page"  => $currentPage
			    )
			);

			$data['page'] = $paginator->getPaginate();

    		//$data['bloqueos'] = Bloqueo::find(array('order' => 'id desc'));

	    	$themeArray['pcData'] = $data;
	    	$themeArray['addJs'][] = 'js/bloqueo.js';

	    	echo $this->view->render('theme', $themeArray);
		}

		public function createAction()
		{
			$user 	=	$this->request->getPost("user", 'int');
			$fecha 	=	$this->request->getPost("fecha", 'string');
			$horai 	=	$this->request->getPost("horai", 'string');
			$horaf 	=	$this->request->getPost("horaf", 'string');

			$bloqueo = new Bloqueo();
			$bloqueo->user_id 		= $user;
			$bloqueo->fecha_inicio 	= $fecha." ".$horai;
			$bloqueo->fecha_termino 	= $fecha." ".$horaf;

			if(!$bloqueo->save())
			{	
				$data['estado']		= false;
				$data['msg'] 		= "Error al crear el bloqueo de horas.";

				foreach ($bloqueo->getMessages() as $message)
				{
                    $data['detalle'][] 	= $message->getMessage();
                }
			}else{

				$data['estado']		= true;
				$data['msg']		= "Se ha creado el bloqueo de horas con exito.";
			}

			echo json_encode($data);

		}

		public function deleteAction()
		{
			$id = $this->request->getPost("bloqueo", 'int');

    		$bloqueo = Bloqueo::findFirst($id);

    		if(!$bloqueo->delete()){
    			$data['estado'] = false;
    			$data['msg'] 	= "No se ha podido eliminar el bloqueo.";
    		}else{
    			$data['estado'] = true;
    			$data['msg'] 	= "bloqueo eliminado correctamente.";
    		}


    		echo json_encode($data);
		}


		public static function fromInput($dependencyInjector, $model, $data)
		{
		    $conditions = array();

		    if (count($data)) 
		    {
		        $metaData = $dependencyInjector->getShared('modelsMetadata');

		        $dataTypes = $metaData->getDataTypes($model);

		        $bind = array();

		        foreach ($data as $fieldName => $value) 
		        {
	                if (!is_null($value)) 
	                {
	                    if ($value != '') 
	                    {  
                        	if ($dataTypes[$fieldName] == 2 || $dataTypes[$fieldName] == 6 || $dataTypes[$fieldName] == 1) 
	                        {                              
	                            $condition = $fieldName . " LIKE :" . $fieldName . ":";                             
	                            $bind[$fieldName] = '%' . $value . '%';
	                        } 
	                        //en otro caso buscamos la búsqueda exacta
	                        else 
	                        {                                
	                            $condition = $fieldName . ' = :' . $fieldName . ':';
	                            $bind[$fieldName] = $value;
	                        }
	                        
	                     	$conditions[] = $condition;
	                    }
	                }
		        }
		    }
		 	


		    $criteria = new Criteria();
		    if (count($conditions)) 
		    {
		    	# como será una busqueda ocuparemos OR
		    	# en caso de ser un filtro se ocuparía AND
		    	$where = join(' AND ', $conditions);

		        $criteria->where($where);
		        $criteria->bind($bind);
		    }
		    return $criteria;
		}
	}