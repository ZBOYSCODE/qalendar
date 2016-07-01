<?php

	namespace Gabs\Controllers;

	use Gabs\Models\Actividad;
	use Gabs\Models\Archivos;
	use Gabs\Models\Acceso;
	use Gabs\Models\Prioridad;
	use Gabs\Models\Categoria;
	use Gabs\Models\Users;
	use Gabs\Models\TipoEstado;
	use Gabs\Models\Proyecto;
	
	use Phalcon\Mvc\Model\Criteria;
	use Phalcon\Paginator\Adapter\Model as PaginatorModel;

	class actividadController extends ControllerBase
	{

		public function initialize() {
	    	$this->_themeArray = array('topMenu'=>true, 'menuSel'=>'','pcView'=>'', 'pcData'=>'', 'jsScript'=>'');
	    }

	    public function editarAction($id)
	    {
	    	$themeArray = $this->_themeArray;
    		$themeArray['pcView'] = 'actividad/edit';
    		$themeArray['jsScript'] = $this->view->render('event/js/evento_nuevoJS');

    		$themeArray['addJs'][] 		= "js/hitos.js";

	    	$actividad 				= Actividad::findFirst($id);
	    	$data['accesos'] 		= Acceso::find();
	    	$data['prioridades'] 	= Prioridad::find();
	    	$data['categorias'] 	= Categoria::find();
	    	$data['users']			= Users::find("rol_id = 2");

	    	$data['actividad'] 		= $actividad;
	    	$data['catAct'] 		= $actividad->getCategorias();
	    	$data['fechaSelected']	= $actividad->actv_fecha;
	    	$data['horaSelected']	= $actividad->actv_hora;

	    	$data['usersSelected'] 	= $actividad->getUsuarios();

	    	$themeArray['pcData'] = $data;


	    	//print_r($data['actividad']->actv_id);

	    	echo $this->view->render('theme', $themeArray);
	    }

	    public function editarEventoAction(){

	    	$id 		=	$this->request->getPost("id", 'int');

	        $this->mifaces->newFaces();

	        $a_model = Actividad::findFirst($id);

	        $rol = $this->auth->getIdentity()['roleId'];

	        # solo Admin, Gestor y el creador del evento pueden modificarlos
	        if($rol == 1 || $rol == 2 || $rol == $a_model->actv_creado_por)
	        {
	        
	        	if($this->auth->getIdentity()['name']) {
					$_POST['creado_por'] = $this->auth->getIdentity()['name'];
				}

		        $callback = $a_model->guardarActividad($_POST);
		        
		        if(isset($callback['error'])){
		            if($callback['error'] == 1){
		                foreach ($callback['msg'] as $val) {
		                    $this->mifaces->addPosRendEval("$.bootstrapGrowl('{$val}',{type:'danger'});");
		                }
		            }
		        } else{
		            $this->mifaces->addPosRendEval("$.bootstrapGrowl('{$callback['msg'][0]}');");
		        }
		        if(isset($callback['error']))
		            $this->mifaces->run();            
		        else{
		            $this->mifaces->addPosRendEval("window.location.replace('/qalendar');");
		            $this->mifaces->run();            
		        }
		        

	        }else{
	        	$msg = "Usted no está autorizado para modificar este evento";
	        	$this->mifaces->addPosRendEval("$.bootstrapGrowl('{$msg}');");
	        	$this->mifaces->run();
	        }




				
	    }

		/**
		 * Muestra la vista para crear un evento
		 */
		public function crearEventoAction() {
			$data = array();
			if(!$this->request->isAjax() == true) {
				if(isset($_POST)) {
					//caso: crear un evento con datos precargados
					$callData = $_POST;
					if(isset($callData['hora']) && isset($callData['fecha']) && isset($callData['calendarUser'])) {
						$hora = $callData['hora'];
						$fecha = $callData['fecha'];
						$owner = $callData['calendarUser'];
						$userOwner = Users::findFirst($owner);

						/*... Podemos seguir seteando datos, dependiendo del caso*/

						$data['fechaSelected'] = date("Y-m-d", strtotime($fecha));
						$data['horaSelected'] = $hora;
						$data['userSelected'] = $userOwner;
					}
				}

				$themeArray = $this->_themeArray;
				$themeArray['pcView'] = 'event/event_nuevo_view';
				$themeArray['jsScript'] = $this->view->render('event/js/evento_nuevoJS');

				$data['users'] = Users::find("rol_id = 2");
				$data['prioridad'] = Prioridad::find();
				$data['acceso'] = Acceso::find();
				$data['categoria'] = Categoria::find();

				# si el usuario es un jefe de proyectos, solo podrá ver los proyectos asociados a el
				$rol = $this->auth->getIdentity()['roleId'];
				$JefeP = 4;# id del rol de Jefe proyecto

				if($rol == $JefeP){
					$cond = "jefep_id = ".$this->auth->getIdentity()['id'];
				}else{
					$cond='';
				}

				$data['proyectos'] = Proyecto::find($cond);


				$themeArray['pcData'] = $data;
				//$themeArray['addJs'] = array("js/evento_nuevoJS.phtml");
				echo $this->view->render('theme', $themeArray);
			}
			else {
				$response = new \Phalcon\Http\Response();
				$response->redirect("");
				$response->send();
			}
		}

		/**
		 * Guarda el evento y redirige al home
		 */
		public function guardarEventoAction(){
			if($this->request->isAjax() == true) {
				$this->mifaces->newFaces();
				$a_model = new Actividad();

				if($this->auth->getIdentity()['id']) {
					$_POST['creado_por'] = $this->auth->getIdentity()['id'];
				}

				$callback = $a_model->guardarActividad($_POST);
				if (isset($callback['error'])) {
					if ($callback['error'] == 1) {
						foreach ($callback['msg'] as $val) {
							$this->mifaces->addPosRendEval("$.bootstrapGrowl('{$val}',{type:'danger'});");
						}
					}
				} else {
					$this->mifaces->addPosRendEval("$.bootstrapGrowl('{$callback['msg'][0]}');");
				}
				if (isset($callback['error']))
					$this->mifaces->run();
				else {
					$this->mifaces->addPosRendEval("window.location.replace('/qalendar');");
					$this->mifaces->run();
				}
			}
		}

		/**
		 * Muestra el perfil de un evento
         */
		public function verPerfilEventoAction($id) {

			$themeArray = $this->_themeArray;
			
			$themeArray['pcView'] = 'actividad/actividad_perfil_view';
			
			$themeArray['jsScript'] = $this->view->render('actividad/js/actividad_perfil_js');
			
			$data['actividades'] = Actividad::findFirst("actv_id = ".$id);

			$themeArray['pcData'] = $data;

			$themeArray['pcData']['estados'] = TipoEstado::find();

			$themeArray['addJs'][] 		= "js/actividad.js";

			echo $this->view->render('theme', $themeArray);
		}

		public function uploadArchivoAction(){
			
			$uploads = $_FILES;
			if(count($_FILES)>0){
				$isUploaded = false;
				
				foreach($_FILES as $k=>$upload){

					$modelArchivos = new Archivos();

					$modelArchivos->actv_id = $_POST["actividad"];
					$modelArchivos->user_id = $this->auth->getIdentity()['id'];
					$arch=explode('.',$_FILES[$k]["name"]);
					$arch= array_reverse($arch);
					$modelArchivos->arch_nombre = $arch[1];
					$modelArchivos->arch_tipo = $arch[0];
					$modelArchivos->arch_ruta = $this->directorioDinamico().$modelArchivos->user_id.'-'.$modelArchivos->actv_id.'-'.md5(uniqid(rand(), true)).'-'.strtolower($_FILES[$k]["name"]);
					$modelArchivos->arch_created_at 	= date('Y-m-d H:i:s');
					$modelArchivos->arch_updated_at 	= date('Y-m-d H:i:s');
					if (move_uploaded_file($_FILES[$k]["tmp_name"],$modelArchivos->arch_ruta)) {
						if ($modelArchivos->save() == false) {
						    foreach ($modelArchivos->getMessages() as $message) {
						        print('$.bootstrapGrowl("'.$message.'", { type: "danger" });');
						    }
						} else {
							print('$.bootstrapGrowl("Archivo OK.'.$modelArchivos->arch_id.'", { type: "success" });
									var dataIn	= new FormData();
						        	dataIn.append("actividad", "'.$modelArchivos->actv_id.'");
						        	callAjax(dataIn,"'.$this->url->get("actividad/listaArchivos").'",this);');
						}
												
					}else{
						print('$.bootstrapGrowl("Error en el Archivo.", { type: "danger" });');
					}
					print('this.removeFile(file);$("#modal-cargar").modal("hide");');
				}
			}
		}

		public function listaArchivosAction() {
			
			$data['actividades'] = Actividad::findFirst("actv_id = ".$_POST['actividad']);

	        $this->mifaces->newFaces();
	        $this->mifaces->addToRend('archivos_tr', $this->view->render('actividad/archivos_tr', array('pcData'=>$data)));
	        $this->mifaces->run();

		}

	    private function directorioDinamico(){
	        $rand = rand(1,300);
	        if(!is_dir($this->config->application->filesDir))
	        {
	            mkdir($this->config->application->filesDir, 0777);
	        }

	        if(!is_dir($this->config->application->filesDir.$rand))
	            if(mkdir($this->config->application->filesDir.$rand, 0777))
	                return $this->config->application->filesDir.$rand.'/';
	        return $this->config->application->filesDir.$rand.'/';
	    }

		public function downloadFileAction($id) {

			$file=Archivos::findFirst("arch_id = ".$id);


	        if(file_exists($file->arch_ruta)) {
	            header('Content-Description: File Transfer');
	            header('Content-Type: application/octet-stream');
	            header('Content-Disposition: attachment; filename='.basename($file->arch_nombre.'.'.$file->arch_tipo));
	            header('Content-Transfer-Encoding: binary');
	            header('Expires: 0');
	            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	            header('Pragma: public');
	            header('Content-Length: ' . filesize($file->arch_ruta));
	            ob_clean();
	            flush();
	            readfile($file->arch_ruta);
	            exit;
	        }

	    }

		public function eliminarArchivoAction(){

			$file=Archivos::findFirst("arch_id = ".$_POST['archivo']);

		    if ($file->delete() == false) {
		    	null;
		    } else {
				$this->mifaces->addPosRendEval('$.bootstrapGrowl("Archivo Eliminado.", { type: "success" });
						var dataIn	= new FormData();
			        	dataIn.append("actividad", "'.$_POST['actividad'].'");
			        	callAjax(dataIn,"'.$this->url->get("actividad/listaArchivos").'",this);');
		    }

			$this->mifaces->run();

		}

		public function updEstadoAction()
		{
			$act = Actividad::findFirstByActvId($this->request->getPost("actividad", 'int'));
			$act->actv_status = $this->request->getPost("estado", 'string');

			if(!$act->save()){

                foreach ($act->getMessages() as $message) {
                    $data['detalle'][] = $message->getMessage();
                }

                $data['estado'] = false;
                $data['msg'] = 'Error al cambiar estado.';                 
            }else{
            	$data['estado'] = true;
            	$data['nombre']	= $act->estado->nombre;
                $data['msg'] = 'Estado actualizado con exito.'; 
            }



			echo json_encode($data, JSON_PRETTY_PRINT);
		}


		public function buscarAction()
	    {
	    	$themeArray = $this->_themeArray;
    		$themeArray['pcView'] = 'actividad/buscar';

    		$data = array();
			$model = new Actividad();

			

			//if($this->request->isPost()){

				$search 		= $this->request->get("search", 'string');
				$user 			= $this->request->get("user", 'int');
				$currentPage	= $this->request->get("page", 'int');

				if(empty($currentPage)){
					$currentPage = 1;
				}

				$buscar = array();

				if(!empty($search))
				{
					$buscar['actv_descripcion_ampliada']	= $search;
					$buscar['actv_descripcion_breve'] 		= $search;
					$buscar['actv_location']				= $search;
					$buscar['actv_comentarios']				= $search;
				}

				if(!empty($user))
				{
					$buscar['actv_creado_por']				= $user;
				}
					

				$query = self::fromInput($this->di, $model, $buscar);

				$this->persistent->searchParams = $query->getParams();
				
				$actividades = Actividad::find($this->persistent->searchParams);

				$paginator   = new PaginatorModel(
				    array(
				        "data"  => $actividades,
				        "limit" => 2,
				        "page"  => $currentPage
				    )
				);

				$data['page'] = $paginator->getPaginate();



				//$data['actividades'] 	= $actividades;
		    	$data['search'] 		= $search;
				
			//}
			
			$data['users']			= Users::find();
	    	
	    	$themeArray['pcData'] = $data;

	    	echo $this->view->render('theme', $themeArray);
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
		        $criteria->where(join(' OR ', $conditions));
		        $criteria->bind($bind);
		    }
		    return $criteria;
		}

	}