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
        // TODO: SACAR LOS ROLES '1' y '2' de una clase tipo configurador
        $rolUser = isset($this->auth->getIdentity()['roleId'])? $this->auth->getIdentity()['roleId']:null;
        if($rolUser == 1 || $rolUser == 3) {
            $this->vistaDiariaAction();
        } elseif($rolUser == 2) {
            $this->vistaSemanalAction();
        }else {
            $this->vistaSemanalAction();
        }
    }

    /**
     * Genera el calendario semanal por usuario
     */
    public function vistaSemanalAction()
    {
        #por defenco dejamos seleccionado el día actual del server
        $DIA_ACTUAL = date('Y-m-d');

        $session = $this->auth->getIdentity();
        if($session['roleId'] == 2) {
            $USER_ACTUAL = $this->auth->getIdentity()['id'];
        }
        else{
            $USER_ACTUAL = Users::findFirst("rol_id = 2")->id;
        }

        # Creamos Calendario Semanal
        $calendar = new Calendar();
        $data = array('fecha'=>$DIA_ACTUAL,'user_id'=>$USER_ACTUAL);
        $week = $calendar->getWeek($data);
        $horas = $calendar->getHorasWeek();
        $fechas = $calendar->getFechasWeek($DIA_ACTUAL);

    	$themeArray = $this->_themeArray;
    	$themeArray['pcView'] = 'webcal/webcal_semanal_view';
        #datos para la vista
        $data['week'] = $week;
        $data['horas'] = $horas;
        $data['fechas'] = $fechas;
        $data['today'] = $DIA_ACTUAL;
        $data['calendarUser'] = $USER_ACTUAL;
        $data['users'] = Users::find("rol_id = 2");
        $data['subMenuSel'] = "semanal";

    	$themeArray['pcData'] = $data;

        echo $this->view->render('theme', $themeArray);
    }


    /**
     * Actualiza el calendar con fecha y usario
     */
    public function changeCalendarUserAction() {
        if($this->request->isAjax() == true) {
            #si vienen datos por postm los seteamos
            if (isset($_POST)) {
                $callData = $_POST;
                if (isset($_POST['dateChange'])) {
                    $today = $_POST['dateChange'];
                } else {
                    $today = date("Y-m-d");
                }
                if(isset($_POST['userCalendar'])) {
                    $userCalendar = $_POST['userCalendar'];
                }
            }
            #actualizamos calendario
            $this->updateCalendar($today, $userCalendar);
        } else {
            $response = new \Phalcon\Http\Response();
            $response->redirect("");
            $response->send();
        }
    }

    /**
     * Actualiza el calendar por fecha
     */
    public function changeCalendarDateAction() {
        if($this->request->isAjax() == true) {
            #si vienen datos por postm los seteamos
            if (isset($_POST)) {
                $callData = $_POST;
                if (isset($_POST['dateChange'])) {
                    $today = $_POST['dateChange'];
                } else {
                    $today = date("Y-m-d");
                }
                $session = $this->auth->getIdentity();
                if($session['roleId'] == 2) {
                    $userCalendar = $this->auth->getIdentity()['id'];
                }
                else{
                    $userCalendar = Users::findFirst("rol_id = 2")->id;
                }

            }
            #actualizamos calendario
            $this->updateCalendar($today, $userCalendar);
        } else {
            $response = new \Phalcon\Http\Response();
            $response->redirect("");
            $response->send();
        }
    }

    /**@param $today
     * @param $userCalendar
     * @return mifaces
     * Actualiza la vista semanal y la renderiza
     */
    private function updateCalendar($today, $userCalendar) {
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
        $data['users'] = Users::find("rol_id = 2");
        $data['subMenuSel'] = "semanal";

        $dataView['pcData'] = $data;
        $view = 'webcal/webcal_calendar_base_view';

        //mifaces

        $toRenderView = $this->view->render($view, $dataView);
        $this->mifaces->newFaces();
        $this->mifaces->addToRend('calendar', $toRenderView);
        $this->mifaces->addPosRendEval('$(\'.select-chosen\').chosen({width: "100%", disable_search_threshold: 7});');
        $this->mifaces->run();
    }

    /**
     * General el calendario diario con las actividades de todos los usuarios
     */
    public function vistaDiariaAction()
    {
        $themeArray = $this->_themeArray;
        $themeArray['pcView'] = 'webcal/webcal_diaria_view';

        $DIA_ACTUAL = date('Y-m-d');

        $calendar = new Calendar();
        $daily = $calendar->getDay($DIA_ACTUAL);

        $data['actividades'] = $daily;
        $data['today'] = $DIA_ACTUAL;
        $data['users'] = Users::find("rol_id = 2");
        $data['categorias'] = Categoria::find();
        $data['subMenuSel'] = "diaria";

        $themeArray['pcData'] = $data;
        $themeArray['jsScript'] = $this->view->render('webcal/js/vista_diaria.phtml');

        echo $this->view->render('theme', $themeArray);
    }

    /**
     * Actualiza la vista diaria con ajax
     */
    public function changeDailyDateAction() {
        if($this->request->isAjax() == true) {
            #si vienen datos por postm los seteamos
            if (isset($_POST)) {
                $callData = $_POST;
                if (isset($_POST['dateChange'])) {
                    $today = $_POST['dateChange'];
                } else {
                    $today = date("Y-m-d");
                }
            }

            $calendar = new Calendar();
            $daily = $calendar->getDay($today);

            $data['actividades'] = $daily;
            $data['today'] = $today;
            $data['users'] = Users::find("rol_id = 2");
            $data['categorias'] = Categoria::find();
            $data['subMenuSel'] = "diaria";

            $dataView['pcData'] = $data;
            $view = 'webcal/webcal_diaria_base_view';


            //mifaces
            $toRenderView = $this->view->render($view, $dataView);
            $this->mifaces->newFaces();
            $this->mifaces->addToRend('calendar', $toRenderView);
            $this->mifaces->addPosRendEval('$(\'.select-chosen\').chosen({width: "100%", disable_search_threshold: 7});');
            $this->mifaces->run();
        }
        else {
            $response = new \Phalcon\Http\Response();
            $response->redirect("");
            $response->send();
        }
    }

    /**
     * Renderiza un modal con los datos del evento
     */
    public function getEventDetailAction() {
        if($this->request->isAjax() == true) {
            $actv_id = isset($_POST['actv'])?$_POST['actv']:'';

            $view = 'event/event_detalle_modal_view';
            $data['actividad'] = Actividad::findFirst('actv_id = '.$actv_id);

            $dataView['pcData'] = $data;
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

            if($this->auth->getIdentity()['name']) {
                $_POST['creado_por'] = $this->auth->getIdentity()['name'];
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
     * Trae los bloques libres con una fecha y un usuario y muestra modal con ajax
     */
    public function encontrarBloqueAction(){
        if($this->request->isAjax() == true) {
            #Si viene la persona seleccionada procedemos
            if(isset($_POST['persona']) && $_POST['persona'] != '') {
                $d_model = new Disponible();
                $themeArray['pcData']['fecha'] = $_POST['fecha'];
                $themeArray['pcData']['disponibles'] = $d_model->getDisponiblesByFecha($_POST);

                $this->mifaces->newFaces();
                if (count($themeArray['pcData']['disponibles'])) {
                    $toRend = $this->view->render('event/event_bloquesLibres_modal_view', $themeArray);
                    $this->mifaces->addPreRendEval("$('#modal-bloqueLibre').modal()");
                    $this->mifaces->addToRend('modal-bloqueLibre', $toRend);
                } else {
                    $this->mifaces->addPosRendEval("$.bootstrapGrowl('No hay bloques disponibles para la fecha seleccionada',{type:'warning'});");
                }
                $this->mifaces->run();
            }
            else{
                #si no viene seleccionada enviamos mensaje
                $this->mifaces->newFaces();
                $this->mifaces->addPosRendEval("$.bootstrapGrowl('Debes seleccionar una persona, antes de encontrar bloques libres.',{type:'warning'});");
                $this->mifaces->run();
            }
        }
    }

    /**
     * Selecciona bloque libre y actualiza la hora en formulario con ajax
     */
    public function seleccionarBloqueAction(){
        if($this->request->isAjax() == true) {
            $this->mifaces->newFaces();
            if (isset($_POST['horaSeleccionada'])) {
                $hora = $_POST['horaSeleccionada'];
                $this->mifaces->addPreRendEval('$("#hora").val("' . $hora . '");');
                $this->mifaces->addPosRendEval('$("#modal-bloqueLibre").modal("hide");');
            } else {
                $this->mifaces->addPosRendEval("$.bootstrapGrowl('Debe seleccionar al menos un bloque.',{type:'warning'});");
            }
            $this->mifaces->run();
        }
    }
}