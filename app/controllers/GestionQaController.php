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
            print_r("test");die();
        }
        else {
            $response = new \Phalcon\Http\Response();
            $response->redirect("gestion/dia");
            $response->send();
        }
    }
}