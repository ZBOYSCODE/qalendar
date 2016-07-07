<?php

	namespace Gabs\Controllers;

	use Gabs\Models\Actividad;
	use Gabs\Models\Disponible;
	use Gabs\Models\Archivos;
	use Gabs\Models\Categoria;
	use Gabs\Models\Users;
	use Gabs\Models\TipoEstado;
	use Gabs\Models\Proyecto;
	use Gabs\Models\UserTecnologia;
	use Gabs\Models\ConfiguradorDisponibilidad;
	use Gabs\Models\UserActividad;
	use Gabs\Models\CategoriaActividad;


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
    		$themeArray['addJs'][] 		= "js/evento.js";

	    	$actividad 				= Actividad::findFirst($id);
	    	$data['categorias'] 	= Categoria::find();
	    	$data['users']			= Users::find("rol_id = 3");

	    	$data['actividad'] 		= $actividad;
	    	$data['catAct'] 		= $actividad->getCategorias();
	    	$data['categoriaSelected'] = $actividad->actv_categoria;
	    	$data['fechaSelected']	= $actividad->actv_fecha;
	    	$data['horaSelected']	= $actividad->actv_hora;

	    	$data['duracion']		= $this->IntToTime( $actividad->categoria->duracion );

	    	$data['usersSelected'] 	= $actividad->getUsuarios();
	    	$data['nameUserSelected'] = Users::findFirstById($data['usersSelected'])->name;

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


	    	//print_r($data['actividad']->actv_id);

	    	echo $this->view->render('theme', $themeArray);
	    }

	    public function editarEventoAction(){

	    	$id 		=	$this->request->getPost("id", 'int');

	        $this->mifaces->newFaces();

	        $actividad = Actividad::findFirst($id);

	        $rol = $this->auth->getIdentity()['roleId'];

	        # solo Admin, Gestor y el creador del evento pueden modificarlos
	        if($rol == 1 || $rol == 2 || $rol == $a_model->actv_creado_por)
	        {
	        
	        	if($this->auth->getIdentity()['id']) {
					$_POST['creado_por'] = $this->auth->getIdentity()['id'];
				}

				$estado_edicion = true;

		        $actividad->proyecto_id 				= $this->request->getPost("proyecto", 		'int');
		        $actividad->actv_descripcion_breve 		= $this->request->getPost("dscr-breve", 	'string');
		        $actividad->actv_descripcion_ampliada 	= $this->request->getPost("dscr-ampliada", 	'string');
		        $actividad->actv_location 				= $this->request->getPost("donde", 			'string');
		        $actividad->actv_fecha 					= $this->request->getPost("fecha", 			'string');
		        $actividad->actv_hora 					= $this->request->getPost("hora", 			'string');
		        $actividad->actv_categoria 				= $this->request->getPost("categoria", 		'int');
		        $actividad->actv_status 				= 2;
		        #$actividad->actv_creado_por 			= $this->auth->getIdentity()['id'];
		        #$actividad->actv_created_at 			= date('Y-m-d'); 
		        $actividad->actv_updated_at 			= date('Y-m-d');
		        $actividad->activo 						= 1;

		        $usuario = $this->request->getPost("persona", 'int');

				# obtenemos los datos de la categoria
				$categoria = Categoria::findFirst($actividad->actv_categoria);

				# obtenemos los datos de configuración
				$ConfDisp = ConfiguradorDisponibilidad::findFirst(1);
	        	$valor_bloque = $ConfDisp->cnfg_intervalo;
				

				$disponible = new Disponible();
				$disponible->dspn_fecha = $actividad->actv_fecha;
				$disponible->dspn_hora	= $actividad->actv_hora;
				$disponible->user_id 	= $usuario;
				$disponible->cnfg_id 	= $ConfDisp->cnfg_id;
				$disponible->actv_id 	= $actividad->actv_id;

				/**
				 * este metodo descarta los bloques creados ya para esta actividad
				 */
				if(!$disponible->comprobarDisponibilidadEdicion($valor_bloque, $categoria->duracion)){
					# si no están disponibles					
					$estado_edicion = false;
                    $callback['msg'][] = 'No hay bloques disponibles en la hora y fecha seleccionadas.'; 
				}

				# Guardamos el evento
                if($estado_edicion){
					if(!$actividad->save()){
						$estado_edicion = false;
	                	$callback['msg'][] = 'Error guardar la actividad.';

	                	foreach ($actividad->getMessages() as $message) {
		                    $callback['msg'][] = $message->getMessage();
		                }
					}
				}

				// editando nueva actividad
				

				if($estado_edicion){
					$userActividad      = UserActividad::findFirstByActvId($actividad->actv_id);
	                $userActividad->user_id = $usuario;

	                if(!$userActividad->save()){
	                	$estado_edicion = false;
	                	$callback['msg'][] = 'Error al editar la relacion usuario-actividad.';

	                	foreach ($userActividad->getMessages() as $message) {
		                    $callback['msg'][] = $message->getMessage();
		                }
	                }
				}

				# editando categoria
				
                

				// creando nueva categoria
                if($estado_edicion){
                	$categoriaActividad = CategoriaActividad::findFirstByActvId($actividad->actv_id);
                	$categoriaActividad->ctgr_id	= $actividad->actv_categoria;

                	if(!$categoriaActividad->save()){
                		$estado_edicion = false;
                		$callback['msg'][] = 'Error al editar la relacion categoria-actividad.';

                		foreach ($categoriaActividad->getMessages() as $message) {
		                    $callback['msg'][] = $message->getMessage();
		                }
                	}
                }

                # Guardamos la disponibilidad
                if($estado_edicion){

                	

                	/**
                	 * reseteamos los que ya existian, para luego volver a crearlos según la edición
                	 */
                	$disponible->resetDisponibilidad();

					if(!$disponible->guardarDisponibilidad($valor_bloque, $categoria->duracion)){

						$estado_edicion = false;
	                	$callback['msg'][] = 'Error guardar la disponibilidad.';

	                	foreach ($disponible->getMessages() as $message) {
		                    $callback['msg'][] = $message->getMessage();
		                }
					}
				}

				$this->mifaces->newFaces();

				if(!$estado_edicion)
				{
					foreach ($callback['msg'] as $val) {
						$this->mifaces->addPosRendEval("$.bootstrapGrowl('{$val}',{type:'danger'});");
					}

					$this->mifaces->run();
				} else {
					$msg = "Actividad editada correctamente.";
					$this->mifaces->addPosRendEval("$.bootstrapGrowl('{$msg}');");
					# no se alcanza a ver este msg por la redirección

					//$this->mifaces->addPosRendEval("window.location.replace('/qalendar');");
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
					if(
						isset($callData['hora']) &&
						isset($callData['fecha']) &&
						isset($callData['calendarUser']) &&
						isset($callData['proyecto'])
					) {
						$hora = $callData['hora'];
						$fecha = $callData['fecha'];
						$owner = $callData['calendarUser'];

						#Si esta seteado el proyecto traemos sus datos, si no lo omitimos
						if($callData['proyecto'] != '') {

							$proyecto = $callData['proyecto'];
							$proyectoObj = Proyecto::findFirst("id = ".$proyecto);
							$data['proyectoSelected'] = $proyecto;
						}

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
				
				$themeArray['addJs'][] 		= "js/evento.js";

				$usersTmp = Users::find("rol_id = 3");

				#copiamos los usuarios segun el proyecto seleccionado  (solo usuarios que tengan la tecnologia asociada al proyecto)
				if(isset($data['proyectoSelected'])) {
					foreach($usersTmp as $userIter){
						#traemos todas las tecnologias
						$userTecnologias = UserTecnologia::find("user_id = ".$userIter->id)->toArray();

						foreach ($userTecnologias as $userTecnologiaIter) {
							# la primera tecnologia que encuentre igual al proyecto se agrega la persona y bye bye!
							if($userTecnologiaIter['tecnologia_id'] == $proyectoObj->tecnologia_id) {
								$data['users'][] = $userIter;
								break;
							}
						}
					}
				}
				else {
					$data['users'] = $usersTmp;
				}


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
			# comprobamos que el metodo POST sea enviado por ajax
			if($this->request->isAjax() == true) {

				$estado_creacion = true;
				
				# obtenemos los datos
				$actividad = new Actividad();
				$actividad->proyecto_id 				= $this->request->getPost("proyecto", 		'int');
		        $actividad->actv_descripcion_breve 		= $this->request->getPost("dscr-breve", 	'string');
		        $actividad->actv_descripcion_ampliada 	= $this->request->getPost("dscr-ampliada", 	'string');
		        $actividad->actv_location 				= $this->request->getPost("donde", 			'string');
		        $actividad->actv_fecha 					= $this->request->getPost("fecha", 			'string');
		        $actividad->actv_hora 					= $this->request->getPost("hora", 			'string');
		        $actividad->actv_categoria 				= $this->request->getPost("categoria", 		'int');
		        $actividad->actv_status 				= 2;
		        $actividad->actv_creado_por 			= $this->auth->getIdentity()['id'];
		        $actividad->actv_created_at 			= date('Y-m-d'); 
		        $actividad->actv_updated_at 			= date('Y-m-d');
		        $actividad->activo 						= 1;

		        $usuario = $this->request->getPost("persona", 'int');

				# obtenemos los datos de la categoria
				$categoria = Categoria::findFirst($_POST['categoria']);

				# obtenemos los datos de configuración
				$ConfDisp = ConfiguradorDisponibilidad::findFirst(1);
	        	$valor_bloque = $ConfDisp->cnfg_intervalo;
				

				$disponible = new Disponible();
				$disponible->dspn_fecha = $actividad->actv_fecha;
				$disponible->dspn_hora	= $actividad->actv_hora;
				$disponible->user_id 	= $usuario;
				$disponible->cnfg_id 	= $ConfDisp->cnfg_id;


				if(!$disponible->comprobarDisponibilidad($valor_bloque, $categoria->duracion)){
					# si no están disponibles					
					$estado_creacion = false;
                    $callback['msg'][] = 'No hay bloques disponibles en la hora y fecha seleccionadas.'; 
				}

				# Guardamos el evento
                if($estado_creacion){
					if(!$actividad->save()){
						$estado_creacion = false;
	                	$callback['msg'][] = 'Error guardar la actividad.';

	                	foreach ($actividad->getMessages() as $message) {
		                    $callback['msg'][] = $message->getMessage();
		                }
					}
				}

				// creando nueva actividad
				if($estado_creacion){
					$userActividad          = new UserActividad();
	                $userActividad->actv_id = $actividad->actv_id;
	                $userActividad->user_id = $usuario;

	                if(!$userActividad->save()){
	                	$estado_creacion = false;
	                	$callback['msg'][] = 'Error al crear la relacion usuario-actividad.';

	                	foreach ($userActividad->getMessages() as $message) {
		                    $callback['msg'][] = $message->getMessage();
		                }
	                }
				}

				// creando nueva categoria
                if($estado_creacion){
                	$categoriaActividad             = new CategoriaActividad();
                	$categoriaActividad->actv_id    = $actividad->actv_id;
                	$categoriaActividad->ctgr_id	= $actividad->actv_categoria;

                	if(!$categoriaActividad->save()){
                		$estado_creacion = false;
                		$callback['msg'][] = 'Error al crear la relacion categoria-actividad.';

                		foreach ($categoriaActividad->getMessages() as $message) {
		                    $callback['msg'][] = $message->getMessage();
		                }
                	}
                }

                # Guardamos la disponibilidad
                if($estado_creacion){

                	$disponible->actv_id 	= $actividad->actv_id;

					if(!$disponible->guardarDisponibilidad($valor_bloque, $categoria->duracion)){

						$estado_creacion = false;
	                	$callback['msg'][] = 'Error guardar la disponibilidad.';

	                	foreach ($disponible->getMessages() as $message) {
		                    $callback['msg'][] = $message->getMessage();
		                }
					}
				}

				$this->mifaces->newFaces();

				if(!$estado_creacion)
				{
					foreach ($callback['msg'] as $val) {
						$this->mifaces->addPosRendEval("$.bootstrapGrowl('{$val}',{type:'danger'});");
					}

					$this->mifaces->run();
				} else {
					$msg = "Actividad creada correctamente.";
					$this->mifaces->addPosRendEval("$.bootstrapGrowl('{$msg}');");
					# no se alcanza a ver este msg por la redirección

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

			# Calculo de duracion actividad
			$cnfg = ConfiguradorDisponibilidad::findFirst("cnfg_id = 1");
			$intervalo =  $cnfg->cnfg_intervalo;

			foreach($data['actividades']->CategoriaActividad as $CategoriaActIter){
				$duracion_actividad  = $CategoriaActIter->Categoria->duracion;
				break;
			}

			$bloques = ceil($duracion_actividad/$intervalo);

			$data['duracion'] = $bloques*$intervalo;
			# fin Calculo de duracion actividad

			$themeArray['pcData'] = $data;

			$themeArray['pcData']['estados'] = TipoEstado::find();

			$themeArray['addJs'][] 		= "js/actividad.js";
			$themeArray['addJs'][] 		= "js/perfil_actividad.js";

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

	    public function getDuracionCatAction()
	    {
	    	try {

	    		$id = $this->request->getPost("categoria", 'int');

	    		$categoria = Categoria::findFirst($id);

	    		$data['estado']		= true;
	    		$data['duracion'] 	= $this->IntToTime($categoria->duracion);// minutos a hrs
	    		
	    	} catch (Exception $e) {
	    		$data['estado'] = false;
	    		$data['msg'] = 'Error al ejecutar la consulta';
	    	}

	    	echo json_encode($data);
	    }

	    public function cargaQaByProjectAction()
	    {

	    	try {

	    		$proyecto_id = $this->request->getPost("proyecto", 'int');

		    	$proyecto = Proyecto::findFirst($proyecto_id);

		    	$qas = $this->modelsManager->createBuilder()
									 ->from('Gabs\Models\UserTecnologia')
									 ->join('Gabs\Models\Users', 'Gabs\Models\UserTecnologia.user_id = Gabs\Models\Users.id')
									 ->join('Gabs\Models\Tecnologia', 'Gabs\Models\Tecnologia.id = Gabs\Models\UserTecnologia.tecnologia_id')
									 //->join('Gabs\Models\Proyecto', 'Gabs\Models\Proyecto.tecnologia_id = Gabs\Models\UserTecnologia.id')
									 ->where('Gabs\Models\Tecnologia.id = '.$proyecto->tecnologia_id)
									 ->andWhere('Gabs\Models\Users.rol_id = 3')
									 ->columns(	'Gabs\Models\Users.id,
									 			 Gabs\Models\Users.name')
									 ->getQuery()
									 ->execute();

				if($qas->count() > 0)
				{
				 	foreach($qas as $qa)
				 	{
				 		$arr[$qa->id] = $qa->name;
				 	}

				 	$data['estado'] = true;
	    			$data['datos'] = $arr;
 				}else{
 					$data['estado'] = false;
	    			$data['msg'] = 'No se encontraron QAs asociados a la tecnología';
 				}

				

	    	} catch (Exception $e) {
	    		$data['estado'] = false;
	    		$data['msg'] = 'Error al ejecutar la consulta';
	    	}
		    	

	    	echo json_encode($data);
	    }

	    public function deleteAction()
	    {
	    	$se_puede = true;

	    	try {

	    		$id = $this->request->getPost("act", 'int');

	    		$actividad = Actividad::findFirstByActvId($id);


	    		// aquí irán las restricciones
	    		// ejem: no se podrán cancelar a cierta hora de realizarse la actividad
	    		// $se_puede = true/false
	    		// si es false, guardar en la variable $data['msg'] la razón 
	    		
	    		# Reset de disponibilidad
		    		$disponible = new Disponible();
		    		$disponible->actv_id = $actividad->actv_id;

		    		if(!$disponible->resetDisponibilidad()){
		    			$se_puede = false;
		    		}

		    	#


	    		if($se_puede)
	    		{
	    			$actividad->activo = 0;

		    		if(!$actividad->save()){
		    			$data['estado'] = false;
		    			$data['msg'] 	= "no se ha podido cancelar el evento.";
		    		}else{
		    			$data['estado'] = true;
		    			$data['msg'] 	= "Evento cancelado correctamente.";
		    			$disponibles = Disponible::findByActvId($id);
		    			foreach ($disponibles as $disp) {
    						$disp->user_id = null;
		    				$disp->actv_id = null;
		    				$disp->edsp_id = 1;
		    				$disp->save();
		    			}
		    		}

	    		}else{
	    			$data['msg'] 	= 'se cancela el cambio de estado por restricción';
	    			$data['estado'] = false;
	    		}
	    		
	    	} catch (Exception $e) {
	    		$data['estado'] = false;
	    		$data['msg'] = "Error cancelando el evento.";
	    	}

	    	echo json_encode($data);
	    }

	    public function activarAction()
	    {
	    	try {

	    		$id = $this->request->getPost("act", 'int');

	    		# obtenemos la actividad
	    		$actividad = Actividad::findFirstByActvId($id);


	    		# COMPROBAMOS DISPONIBILIDAD
		    		# obtenemos los datos de la categoria
					$categoria = Categoria::findFirst($actividad->actv_categoria);

					# obtenemos los datos de configuración
					$ConfDisp = ConfiguradorDisponibilidad::findFirst(1);
		        	$valor_bloque = $ConfDisp->cnfg_intervalo;

		    		# comprobamos la disponibilidad
		    		$disponible = new Disponible();
					$disponible->dspn_fecha = $actividad->actv_fecha;
					$disponible->dspn_hora	= $actividad->actv_hora;
					$disponible->user_id 	= $actividad->usuario->user_id;
					$disponible->cnfg_id 	= $ConfDisp->cnfg_id;
					$disponible->actv_id 	= $actividad->actv_id;

					/**
					 * este metodo descarta los bloques creados ya para esta actividad
					 */
					if(!$disponible->comprobarDisponibilidadEdicion($valor_bloque, $categoria->duracion)){
						# si no están disponibles
						$data['estado'] = false;				
	                    $data['msg'] = 'No hay bloques disponibles en la hora y fecha seleccionadas.';

					}else{
						
						$disponible->resetDisponibilidad();

						if(!$disponible->guardarDisponibilidad($valor_bloque, $categoria->duracion)){

							$data['estado'] = false;
				    		$data['msg'] = "No se ha podido guardar la disponibilidad.";

						} else {
							# una vez que esté ok la disponibilidad activamos el evento
							$actividad->activo = 1;
				    		
				    		if(!$actividad->save()){
				    			$data['estado'] = false;
				    			$data['msg'] = "No se ha podido activar el evento.";
				    		}else{
				    			$data['estado'] = true;
				    			$data['msg'] = "Evento activado correctamente.";
				    		}

						}
						
							
					}			    		

	    	} catch (Exception $e) {
	    		$data['estado'] = false;
	    		$data['msg'] = "Error activando el evento.";
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
		        $criteria->where(join(' OR ', $conditions));
		        $criteria->bind($bind);
		    }
		    return $criteria;
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

	}