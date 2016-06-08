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
    	$themeArray['pcView'] = 'webcal/webcal_base_view';

    	echo $this->view->render('theme', $themeArray);
    }
}