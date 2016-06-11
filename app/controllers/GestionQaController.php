<?php

namespace Gabs\Controllers;
use \Gabs\Dto\Calendar;

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

        //$themeArray['addJs'] = array("js/evento_nuevo.js");
        echo $this->view->render('theme', $themeArray);
    }
}