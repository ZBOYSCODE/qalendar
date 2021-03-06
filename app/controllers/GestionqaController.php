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
use \Gabs\Models\ConfiguradorDisponibilidad;
use \Gabs\Models\UserTecnologia;

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

        $rolUser = isset($this->auth->getIdentity()['roleId'])? $this->auth->getIdentity()['roleId']:null;
        if($rolUser == 1 || $rolUser == 4) {
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
        if($session['roleId'] == 3) {
            $USER_ACTUAL = $this->auth->getIdentity()['id'];
        }
        else{
            $USER_ACTUAL = Users::findFirst("rol_id = 3")->id;
        }


        $userTecnologiasObj = UserTecnologia::find("user_id = ". $USER_ACTUAL);

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
        $data['tecnologiasUser'] = $this->_formatObjStrUserTecnologia($userTecnologiasObj->toArray());
        $data['users'] = Users::find("rol_id = 3");
        $data['subMenuSel'] = "semanal";

    	$themeArray['pcData'] = $data;
        $themeArray['jsScript'] = $this->view->render('webcal/js/vista_diaria');
        $themeArray['addJs'][] = "js/webcal.js";

        echo $this->view->render('theme', $themeArray);
    }


    /**
     *  A partir de las tecnologias de un usuario (array) genera un string con los id separados por coma
     * @param $arrayObj
     * @return string
     */
    private function _formatObjStrUserTecnologia($arrayObj) {
        $str = "";

        $arrayKeys = array_keys($arrayObj);
        $lastArrayKey = array_pop($arrayKeys);

        foreach($arrayObj as $k => $iter) {
            if($lastArrayKey != $k)
                $str .= $iter['tecnologia_id'] .",";
            else
                $str .= $iter['tecnologia_id'];
        }

        return $str;
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

    /**@param $today
     * @param $userCalendar
     * @return mifaces
     * Actualiza la vista semanal y la renderiza
     */
    private function updateCalendar($today, $userCalendar) {
        $USER_ACTUAL = isset($userCalendar)?$userCalendar:$this->auth->getIdentity()['id'];
        $userTecnologiasObj = UserTecnologia::find("user_id = ". $USER_ACTUAL);

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
        $data['tecnologiasUser'] = $this->_formatObjStrUserTecnologia($userTecnologiasObj->toArray());
        $data['users'] = Users::find("rol_id = 3");
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
        $daily = $calendar->_getDay($DIA_ACTUAL);
        $usersCalendar = $calendar->getUsersCalendarDay();

        $data['day'] = $daily;
        $data['today'] = $DIA_ACTUAL;
        $data['users'] = Users::find("rol_id = 3");
        $data['tecnologiaDictionary'] = $this->_getTecnologiaUserDictionary($data['users']);
        $data['usersCalendar'] = $usersCalendar;
        $data['subMenuSel'] = "diaria";

        $themeArray['pcData'] = $data;
        $themeArray['jsScript'] = $this->view->render('webcal/js/vista_diaria');

        $themeArray['addJs'][] = "js/vendor/jquery.visible.js";
        $themeArray['addJs'][] = "js/webcal.js";

        echo $this->view->render('theme', $themeArray);
    }


    /**
     * Para un resulset de objetos Users genera un array tipo diccionario asociados a las tecnologias del usuario
     * la tecnologias del usario son formateadas con los id y separadas por coma ej:  array[1] = "1,2,3";
     * @param $users
     * @return array
     */
    private function _getTecnologiaUserDictionary($users) {

        $dictionary = array();

        #Para todos los usuarios
        foreach($users as $user) {

            #traemos sus tecnologias
            $userTecnologiasObj = UserTecnologia::find("user_id = ". $user->id);
            $userTecnologiasArray = $userTecnologiasObj->toArray();

            #las formateamos separadas por coma
            $tecnologiasStr = $this->_formatObjStrUserTecnologia($userTecnologiasArray);

            #construimos el diccionario
            $dictionary[$user->id] = $tecnologiasStr;

        }

        return $dictionary;
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
            $daily = $calendar->_getDay($today);
            $usersCalendar = $calendar->getUsersCalendarDay();

            $data['day'] = $daily;
            $data['today'] = $today;
            $data['users'] = Users::find("rol_id = 3");
            $data['tecnologiaDictionary'] = $this->_getTecnologiaUserDictionary($data['users']);
            $data['usersCalendar'] = $usersCalendar;
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


            # Calculo de duracion actividad
            $cnfg = ConfiguradorDisponibilidad::findFirst("cnfg_id = 1");
            $intervalo =  $cnfg->cnfg_intervalo;

            foreach($data['actividad']->CategoriaActividad as $CategoriaActIter){
                $duracion_actividad  = $CategoriaActIter->Categoria->duracion;
                break;
            }

            $bloques = ceil($duracion_actividad/$intervalo);

            $data['duracion'] = $bloques*$intervalo;
            # fin Calculo de duracion actividad


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

    public function getProyectosNuevoEvento() {

    }
}