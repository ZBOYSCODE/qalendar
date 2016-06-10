<?php

namespace Gabs\Controllers;
use \Gabs\Services\Services as Services;

class GestionQaController extends ControllerBase
{
    
    public $_themeArray;

    public function initialize() {
    	$this->_themeArray = array('topMenu'=>true, 'menuSel'=>'','pcView'=>'', 'pcData'=>'', 'jsScript'=>'');
    }

    public function indexAction()
    {
    	$themeArray = $this->_themeArray;
    	$themeArray['pcView'] = 'webcal/webcal_semanal_view';

        echo $this->view->render('theme', $themeArray);
    }

    public function vistaDiariaAction() {
        $themeArray = $this->_themeArray;
        $themeArray['pcView'] = 'webcal/webcal_diaria_view';

        echo $this->view->render('theme', $themeArray);
    }

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

    public function crearEventoAction() {
        $themeArray = $this->_themeArray;
        $themeArray['pcView'] = 'event/event_nuevo_view';

        //$themeArray['addJs'] = array("js/evento_nuevo.js");
        echo $this->view->render('theme', $themeArray);
    }
}