<?php

	namespace Gabs\Controllers;

	use Gabs\Models\Proyecto;
	use Gabs\Models\Area;
	use Gabs\Models\Tecnologia;
	use Gabs\Models\Users;
	use Gabs\Models\Actividad;
	use Gabs\Models\Categoria;
	use Gabs\Models\CategoriaActividad;

	use Phalcon\Mvc\Model\Criteria;
	use Phalcon\Paginator\Adapter\Model as PaginatorModel;

	class ProyectoController extends ControllerBase
	{
		public $_themeArray;
		public $rol_jefep = 4;

	    /**
	     * Se genera un array para la vista por defecto
	     */
	    public function initialize() {
	    	$this->_themeArray = array('topMenu'=>true, 'menuSel'=>'','pcView'=>'', 'pcData'=>'', 'jsScript'=>'');
	    }

	    /**
	     *
	     */
	    public function indexAction()
	    {
	    	# Paginación
	    	$nombre 		= $this->request->get("nombre", 'string');
	    	$codigo 		= $this->request->get("codigo", 'string');
			$user 			= $this->request->get("user", 'int');
			$currentPage	= $this->request->get("page", 'int');

			if(empty($currentPage)){
				$currentPage = 1;
			}

			# Filtro
			$buscar = array();
			if(!empty($nombre)) $buscar['nombre']	= $nombre;
			if(!empty($codigo)) $buscar['codigo']	= $codigo;

			if($this->auth->getIdentity()['roleId'] == 4 ){
				$buscar['jefep_id']	= $this->auth->getIdentity()['id'];
			}

			$model = new Proyecto();

			$query = self::fromInput($this->di, $model, $buscar);

			$this->persistent->searchParams = $query->getParams();
			
			$proyectos = Proyecto::find($this->persistent->searchParams);

			$paginator   = new PaginatorModel(
			    array(
			        "data"  => $proyectos,
			        "limit" => 10,
			        "page"  => $currentPage
			    )
			);

			$data['page'] = $paginator->getPaginate();

	    	# Form creación
	    	$data['proyectos'] = Proyecto::find();

	    	$themeArray = $this->_themeArray;
    		$themeArray['pcView'] = 'proyectos/list';
	        $themeArray['pcData'] = $data;

	        $themeArray['addJs'][] = "js/proyecto.js";

        	echo $this->view->render('theme', $themeArray);
	    }

	    public function perfilAction($id){

	    	$id = (int)$id;

		 	if($id>0)
		 	{
		 		# Form edit
		    	//$data['users'] = Users::find();
		    	//$data['areas'] = Area::find();
		    	//$data['tecnologias'] = Tecnologia::find();

		    	$data['proyecto'] = Proyecto::findFirst($id);
		    	$data['proyecto']->total_horas = $this->getTotalHoras($id);

		    	$arr = array(
		    			"proyecto_id = $id ",
		    			"order" => "actv_created_at DESC"
		    		);

		    	$data['actividades'] = actividad::find($arr);

		    	

				$themeArray = $this->_themeArray;

				$themeArray['addJs'][] = 'js/perfil_proyecto.js';
	    		$themeArray['pcView'] = 'proyectos/perfil';
		        $themeArray['pcData'] = $data;

		 	
		 	}else{
				$response = new \Phalcon\Http\Response();
				$response->redirect("acceso/denegado");
				$response->send();
		 	}
		 		

		    echo $this->view->render('theme', $themeArray);
	    }

	    public function createAction(){

	    	# Form creación
	    	$data['users'] = Users::findByRolId($this->rol_jefep);
	    	$data['areas'] = Area::find();
	    	$data['tecnologias'] = Tecnologia::find();


	    	$themeArray = $this->_themeArray;
    		$themeArray['pcView'] = 'proyectos/crear';
	        $themeArray['pcData'] = $data;

        	echo $this->view->render('theme', $themeArray);
	    }

	    public function storeAction()
	    {
	    	if ($this->request->isPost())
	    	{
		    	$proyecto = new Proyecto();

		    	$proyecto->nombre 			= $this->request->getPost("nombre", 'string');
		    	$proyecto->codigo			= $this->request->getPost("codigo", 'string');
		    	$proyecto->descripcion 		= $this->request->getPost("descripcion", 'string');
		    	$proyecto->creador_id  		= $this->auth->getIdentity()['id'];
		    	$proyecto->jefep_id 		= $this->request->getPost("jefep", 'int');
		    	$proyecto->area_id 			= $this->request->getPost("area", 'int');
		    	$proyecto->tecnologia_id 	= $this->request->getPost("tecnologia", 'int');
		    	$proyecto->created_at 		= date('Y-m-d H:i:s');
		    	$proyecto->updated_at 		= date('Y-m-d H:i:s');

				$this->mifaces->newFaces();

				if($proyecto->save() == false)
				{
					foreach ($proyecto->getMessages() as $message) {
						$val = $message->getMessage();
	                    $this->mifaces->addPosRendEval("$.bootstrapGrowl('{$val}',{type:'danger'});");
	                }

	                $this->mifaces->run();
				}else{
					$val = "Proyecto creado correctamente";
					$this->mifaces->addPosRendEval("$.bootstrapGrowl('{$val}');");
					$this->mifaces->addPosRendEval("window.location.replace('/qalendar/proyecto');");
		            $this->mifaces->run(); 
				}
			}
	    }

	    public function editAction($id){

	    	$id = (int)$id;

		 	if($id>0)
		 	{
		 		# Form edit
		    	$data['users'] = Users::find();
		    	$data['areas'] = Area::find();
		    	$data['tecnologias'] = Tecnologia::find();

		    	$data['proyecto'] = Proyecto::findFirst($id);

				$themeArray = $this->_themeArray;
	    		$themeArray['pcView'] = 'proyectos/edit';
		        $themeArray['pcData'] = $data;

		 	
		 	}else{
				$response = new \Phalcon\Http\Response();
				$response->redirect("acceso/denegado");
				$response->send();
		 	}
		 		

		    echo $this->view->render('theme', $themeArray);
	    }

	    public function updateAction()
	    {
	    	if ($this->request->isPost())
	    	{
	    		$id 	= $this->request->getPost("proyecto_id", 'int');

	    		if($id > 0)
	    		{
	    			$proyecto = Proyecto::findFirst($id);

			    	$proyecto->nombre 			= $this->request->getPost("nombre", 'string');
			    	$proyecto->codigo			= $this->request->getPost("codigo", 'string');
			    	$proyecto->descripcion 		= $this->request->getPost("descripcion", 'string');
			    	$proyecto->jefep_id 		= $this->request->getPost("jefep", 'int');
			    	$proyecto->area_id 			= $this->request->getPost("area", 'int');
			    	$proyecto->tecnologia_id 	= $this->request->getPost("tecnologia", 'int');
			    	$proyecto->updated_at 		= date('Y-m-d H:i:s');

			    	$this->mifaces->newFaces();

					if($proyecto->save() == false)
					{
						foreach ($proyecto->getMessages() as $message) {
							$val = $message->getMessage();
		                    $this->mifaces->addPosRendEval("$.bootstrapGrowl('{$val}',{type:'danger'});");
		                }

		                $this->mifaces->run();
					}else{
						$val = "Proyecto editado correctamente";
						$this->mifaces->addPosRendEval("$.bootstrapGrowl('{$val}');");
						//$this->mifaces->addPosRendEval("window.location.replace('/qalendar/proyecto');");
			            $this->mifaces->run(); 
					}
	    		}
		    }
	    }


	    public function deleteAction()
	    {

	    	try {

	    		$id = $this->request->getPost("proyecto", 'int');

	    		$proyecto = Proyecto::findFirst($id);

	    		// aquí irán las restricciones
	    		// ejem: no se podrán cancelar a cierta hora de realizarse la actividad
	    		// $se_puede = true/false
	    		// si es false, guardar en la variable $data['msg'] la razón 
	    		$se_puede = true;


	    		if($se_puede)
	    		{
	    			$proyecto->estado = 0;

		    		if(!$proyecto->save()){
		    			$data['estado'] = false;
		    			$data['msg'] 	= "No se ha podido eliminar el proyecto.";
		    		}else{
		    			$data['estado'] = true;
		    			$data['msg'] 	= "Proyecto eliminado correctamente.";
		    		}

	    		}else{
	    			$data['msg'] 	= 'Se cancela la eliminación del proyecto por restricción';
	    			$data['estado'] = false;
	    		}
	    		
	    	} catch (Exception $e) {
	    		$data['estado'] = false;
	    		$data['msg'] = "Error al tratar de eliminar el proyecto.";
	    	}

	    	echo json_encode($data);
	    }

	    public function activarAction()
	    {
	    	try {

	    		$id = $this->request->getPost("proyecto", 'int');

	    		$proyecto = Proyecto::findFirst($id);
	    		$proyecto->estado = 1;

	    		if(!$proyecto->save()){
	    			$data['estado'] = false;
	    			$data['msg'] = "no se ha podido activar el evento.";
	    		}else{
	    			$data['estado'] = true;
	    			$data['msg'] = "Evento activado correctamente.";
	    		}

	    	} catch (Exception $e) {
	    		$data['estado'] = false;
	    		$data['msg'] = "Error activando el evento.";
	    	}

	    	echo json_encode($data);
	    }

	    private function getTotalHoras($proyecto)
	    {

	    	$where = "Gabs\Models\Actividad.proyecto_id = :proyecto:";
	    	$bind['proyecto'] = (int)$proyecto;

	    	$cat = $this->modelsManager->createBuilder()
                        ->from('Gabs\Models\Categoria')
                        ->join('Gabs\Models\CategoriaActividad', 'Gabs\Models\CategoriaActividad.ctgr_id = Gabs\Models\Categoria.ctgr_id')
                        ->join('Gabs\Models\Actividad', 'Gabs\Models\CategoriaActividad.actv_id = Gabs\Models\Actividad.actv_id')
                        ->where($where, $bind)
                        ->columns('sum ( Gabs\Models\Categoria.duracion ) as total_horas')
                        ->getQuery()
                        ->getSingleResult();


	    	return $this->IntToTime($cat->total_horas);
	    }

	    private function IntToTime($int)
        {
            $min = $int % 60;//min
            $hrs = floor($int / 60);//hrs

            if($min<10){
                $min = "0".$min;
            }

            if($hrs<10){
                $hrs = "0".$hrs;
            }

            return $hrs.":".$min;
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
		        $criteria->where(join(' AND ', $conditions));
		        $criteria->bind($bind);
		    }
		    return $criteria;
		}
	}