<?php

namespace Gabs\Controllers;
use Gabs\Models\Actividad;
use Gabs\Models\Disponible;
use \Gabs\Dto\Calendar;
use \Gabs\Models\Persona;
use \Gabs\Models\Prioridad;
use \Gabs\Models\Acceso;
use \Gabs\Models\Categoria;
use \Gabs\Models\Users;

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
        //TODO: Modificar user_actual por el usuario seleccionado en cuestión
        $DIA_ACTUAL = date('Y-m-d');
        $USER_ACTUAL = isset($userCalendar)?$userCalendar:$this->auth->getIdentity()['id'];

        /* Creamos Calendario Semanal*/
        $calendar = new Calendar();
        $data = array('fecha'=>$DIA_ACTUAL,'user_id'=>$USER_ACTUAL);
        $week = $calendar->getWeek($data);
        $horas = $calendar->getHorasWeek();
        $fechas = $calendar->getFechasWeek($DIA_ACTUAL);

    	$themeArray = $this->_themeArray;
    	$themeArray['pcView'] = 'webcal/webcal_semanal_view';
        $data['week'] = $week;
        $data['horas'] = $horas;
        $data['fechas'] = $fechas;
        $data['today'] = $DIA_ACTUAL;
        $data['calendarUser'] = $USER_ACTUAL;

    	$themeArray['pcData'] = $data;

        echo $this->view->render('theme', $themeArray);
    }

    /**
     *
     */
    public function changeCalendarDateAction() {
        if($this->request->isAjax() == true) {
            if(isset($_POST)) {
                $callData = $_POST;
                if(isset($_POST['dateChange'])) {
                    $today = $_POST['dateChange'];
                }
                else {
                    $today = date("Y-m-d");
                }
            }
            //TODO: Modificar user_actual por el usuario seleccionado en cuestión
            $USER_ACTUAL = isset($userCalendar)?$userCalendar:$this->auth->getIdentity()['id'];

            /* Creamos Calendario Semanal*/
            $calendar = new Calendar();
            $data = array('fecha'=>$today,'user_id'=>$USER_ACTUAL);
            $week = $calendar->getWeek($data);
            $horas = $calendar->getHorasWeek();
            $fechas = $calendar->getFechasWeek($today);

            $data['week'] = $week;
            $data['horas'] = $horas;
            $data['fechas'] = $fechas;
            $data['today'] = $today;
            $data['calendarUser'] = $USER_ACTUAL;

            $dataView['pcData'] = $data;
            $view = 'webcal/webcal_calendar_base_view';

            //mifaces
            $toRenderView = $this->view->render($view, $dataView);
            $this->mifaces->newFaces();
            $this->mifaces->addToRend('calendar', $toRenderView);
            $this->mifaces->run();

        }
        else {
            $response = new \Phalcon\Http\Response();
            $response->redirect("/");
            $response->send();
        }
    }

    /**
     *
     */
    public function vistaDiariaAction()
    {
        $themeArray = $this->_themeArray;
        $themeArray['pcView'] = 'webcal/webcal_diaria_view';

        # 
        $fecha      =   $this->request->getPost("fecha");

        $conditions = "actv_fecha = :fecha:";
        $params = array("fecha" => $fecha);

        # obtenemos todad las actividades por día especifico
        $actividades = Actividad::find(array(
                $conditions,
                "bind" => $params
        ));

        $themeArray['pcData']['actividades'] = $this->ordenar($actividades);

        echo $this->view->render('theme', $themeArray);
    }

    private function ordenar($actividades)
    {
        $data = array();

        # Iteramos las actividades para ordenarlas
        foreach ($actividades as $value) {
            # Obtenemos la hora de inicio de la actividad
            $hora = explode(':', $value->actv_hora);
            $hora = (int)$hora[0];
            # Guardamos el objeto actividad en un array con la hora de inicio como indice
            $data[$hora][] = $value;
        }

        # ordenamos por hora de menor a mayor
        ksort($data);
        return $data;
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

            $themeArray['pcData'] = $data;

            //$themeArray['addJs'] = array("js/evento_nuevo.js");
            echo $this->view->render('theme', $themeArray);
        }
        else {
            $response = new \Phalcon\Http\Response();
            $response->redirect("/");
            $response->send();
        }
    }


    public function guardarEventoAction(){
        $this->mifaces->newFaces();
        $a_model = new Actividad();
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