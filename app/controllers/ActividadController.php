<?php

	namespace Gabs\Controllers;

	use Gabs\Models\Actividad;
	use Gabs\Models\Acceso;
	use Gabs\Models\Prioridad;
	use Gabs\Models\Categoria;
	use Gabs\Models\Users;

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

	    	$actividad 				= Actividad::findFirst($id);
	    	$data['accesos'] 		= Acceso::find();
	    	$data['prioridades'] 	= Prioridad::find();
	    	$data['categorias'] 	= Categoria::find();
	    	$data['users']			= Users::find();

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
	    }
	}