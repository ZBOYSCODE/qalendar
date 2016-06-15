<?php
namespace Gabs\Controllers;
use Gabs\Models\Users;


class UsuariosController extends ControllerBase{


    public function initialize()
    {
    	null;
	}

	public function indexAction(){
		$this->profile();
	}	

	public 	function profileAction()
	{
		$auth=$this->auth->getIdentity();
		if($auth['email']==$this->dispatcher->getParam("us")){
			$idUser = $auth['id'];
			$userProfile = Users::findFirst($idUser)->toArray();
			$pcData['pcData']['userProfile'] = $userProfile;
			$pcData['pcView']	= "usuarios/profile_edit_view";
			$pcData['topMenu'] = true;
			$pcData['menuSel'] = '';
			$pcData['sideBarSel'] = 'home';
			$pcData['jsScript'] = '';
			$pcData['lmView'] = '';


			print($this->view->render('theme',$pcData));
		}else{
			$response = new \Phalcon\Http\Response();
			$response->redirect("");
			$response->send();
		}

	}

	public function editProfileAction()
	{

		$error = 0;
        $this->mifaces->newFaces();
        if($_POST['userPassword']!="")
        {
			if($_POST['userPassword'] !== $_POST['userConfirmPassword'] OR ($_POST['userPassword'] == "")){
	            $this->mifaces->addPosRendEval("$.bootstrapGrowl('Las contraseñas no coinciden, vuelva a ingresar.',{type:'warning',align:'center'});");
	            $error = 1;
	        }  else{
	            $passwordHashed = $this->getDI()
	                ->getSecurity()
	                ->hash($_POST['userPassword']);              
	        }
    	}
        if($_POST['userNombre'] == "" OR $_POST['userEmail'] == ""){
            $this->mifaces->addPosRendEval("$.bootstrapGrowl('Falta rellenar campos requeridos.',{type:'warning',align:'center'});");
        } elseif(!$error){
	        $modelUser = new Users();
	        $user = $modelUser::findFirstById($this->auth->getIdentity()['id']);
	        if(isset($passwordHashed))
	        	$user->password = $passwordHashed;
	        $user->name = $_POST['userNombre'];
	        $user->email = $_POST['userEmail'];
	        $user->update();
	        $this->auth->authUserById($this->auth->getIdentity()['id']);
            $this->mifaces->addPosRendEval("$.bootstrapGrowl('Datos actualizados correctamente.',{type:'success',align:'center'});");	        	        	
        }

        $this->mifaces->run();		
	}

}
?>