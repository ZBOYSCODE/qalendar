<?php

namespace Gabs\Controllers;
use Gabs\Models\Actividad;
use Gabs\Models\Disponible;
use \Gabs\Dto\Calendar;
use \Gabs\Models\Persona;
use \Gabs\Models\Prioridad;
use \Gabs\Models\Acceso;

class GestionQaController extends ControllerBase
{
    
    public $_themeArray;

    public function initialize() {
    	$this->_themeArray = array('topMenu'=>true, 'menuSel'=>'','pcView'=>'', 'pcData'=>'', 'jsScript'=>'');
    }

    /**
     * @param $test
     */
    public function indexAction()
    {
        /*Constantes dummies*/
        $DIA_ACTUAL = '2016-06-09';
        $USER_ACTUAL = 1;

        /* Creamos Calendario Semanal*/
        $calendar = new Calendar();
        $data = array('fecha'=>$DIA_ACTUAL,'user_id'=>$USER_ACTUAL);
        $week = $calendar->getWeek($data);
        $horas = $calendar->getHorasWeek();
        $fechas = $calendar->getFechasWeek('2016-06-09');

    	$themeArray = $this->_themeArray;
    	$themeArray['pcView'] = 'webcal/webcal_semanal_view';
        $data['week'] = $week;
        $data['horas'] = $horas;
        $data['fechas'] = $fechas;
        $data['today'] = $DIA_ACTUAL;

    	$themeArray['pcData'] = $data;

        echo $this->view->render('theme', $themeArray);
    }

    /**
     *
     */
    public function vistaDiariaAction() {
        $themeArray = $this->_themeArray;
        $themeArray['pcView'] = 'webcal/webcal_diaria_view';

        echo $this->view->render('theme', $themeArray);
    }

    /**
     *
     */
    public function getEventDetailAction() {
        if($this->request->isAjax() == true) {
            $actv_id = isset($_POST['actv'])?$_POST['actv']:'';

            $view = 'event/event_detalle_modal_view';
            $dataView = $actv_id;

            //mifaces
            $toRenderView = $this->view->render($view, $dataView);
            $this->mifaces->newFaces();
            $this->mifaces->addToRend('modal-data', $toRenderView);
            $this->mifaces->addPosRendEval("$('#modal-data').modal()");
            $this->mifaces->run();
        }
        else {
            $response = new \Phalcon\Http\Response();
            $response->redirect("gestion/dia");
            $response->send();
        }
    }

    /**
     *
     */
    public function crearEventoAction() {
        $themeArray = $this->_themeArray;
        $themeArray['pcView'] = 'event/event_nuevo_view';
        $themeArray['jsScript'] = $this->view->render('event/js/evento_nuevoJS');

        $data['users'] = Persona::find();
        $data['prioridad'] = Prioridad::find();
        $data['acceso'] = Acceso::find();

        $themeArray['pcData'] = $data;

        //$themeArray['addJs'] = array("js/evento_nuevo.js");
        echo $this->view->render('theme', $themeArray);
    }


    public function guardarEventoAction(){
        $this->mifaces->newFaces();
        $a_model = new Actividad();
        $callback = $a_model->guardarActividad($_POST);
        if(isset($callback['error'])){
            if($callback['error'] == 1)
                $this->mifaces->addPosRendEval("$.bootstrapGrowl('{$callback['msg']}',{type:'danger'});");
        } else{
            $this->mifaces->addPosRendEval("$.bootstrapGrowl('{$callback['msg']}');");
        }
        if(isset($callback['error']))
            $this->mifaces->run();            
        else{
            $this->mifaces->addPosRendEval("window.location.replace('/qalendar');");
            $this->mifaces->run();            
        }

    }

    public function encontrarBloqueAction(){
        $d_model = new Disponible();
        $themeArray['pcData']['fecha'] = $_POST['fecha'];
        $themeArray['pcData']['disponibles'] = $d_model->getDisponiblesByFecha($_POST);

        $this->mifaces->newFaces();
        if(count($themeArray['pcData']['disponibles'])){
            $toRend = $this->view->render('event/event_bloquesLibres_modal_view',$themeArray);
            $this->mifaces->addPreRendEval("$('#modal-bloqueLibre').modal()");            
            $this->mifaces->addToRend('modal-bloqueLibre',$toRend);
        }else{
            $this->mifaces->addPosRendEval("$.bootstrapGrowl('No hay bloques disponibles para la fecha seleccionada',{type:'warning'});");
        }
        $this->mifaces->run();
    }

    public function seleccionarBloqueAction(){
        $this->mifaces->newFaces();
        if(isset($_POST['horaSeleccionada'])){
            $hora = $_POST['horaSeleccionada'];
            $this->mifaces->addPreRendEval('$("#hora").val("'.$hora.'");');
            $this->mifaces->addPosRendEval('$("#modal-bloqueLibre").modal("hide");');
        } else{
            $this->mifaces->addPosRendEval("$.bootstrapGrowl('Debe seleccionar al menos un bloque.',{type:'warning'});");   
        }
        $this->mifaces->run();
    }
}