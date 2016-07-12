<?php
	namespace Gabs\Mail;

	use Phalcon\Mvc\User\Component;
	use Phalcon\Mvc\View;


	use Gabs\Models\Actividad;
	use Gabs\Models\Proyecto;


	require('../app/library/PHPMailer/class.phpmailer.php');
	require('../app/library/PHPMailer/class.smtp.php');


	class EnvioMail extends Component
	{
	    private $FromEmail 	= 'notificaciones@qalendar.cl';
	    private $FromTitle 	= 'Qalendar';

	    public $evento;
	    public $proyecto;

	    public function aviso_creacion()
	    {
	    	$datos = $this->getDatosEvento();
	    	$users = $this->getUsersEvento();

	    	foreach ($users as $usuario) {

	    		$this->send($usuario['email'],
                        'Se ha creado un nuevo evento',
                        $usuario['nombre'],
                        'crea_evento',
                        $datos);

	    	}
	    }

	    public function aviso_edicion()
	    {
	    	$datos = $this->getDatosEvento();
	    	$users = $this->getUsersEvento();

	    	foreach ($users as $usuario) {

	    		$this->send($usuario['email'],
                        'Se ha editado un evento',
                        $usuario['nombre'],
                        'edita_evento',
                        $datos);

	    	}
	    }

	    public function aviso_cancelacion()
	    {
	    	$datos = $this->getDatosEvento();
	    	$users = $this->getUsersEvento();

	    	foreach ($users as $usuario) {

	    		$this->send($usuario['email'],
                        'Se ha cancelado un evento',
                        $usuario['nombre'],
                        'cancela_evento',
                        $datos);

	    	}
	    }

	    public function aviso_activacion()
	    {
	    	$datos = $this->getDatosEvento();
	    	$users = $this->getUsersEvento();

	    	foreach ($users as $usuario) {

	    		$this->send($usuario['email'],
                        'Se ha cancelado un evento',
                        $usuario['nombre'],
                        'activa_evento',
                        $datos);

	    	}
	    }


	    public function creacion_proyecto()
	    {
	    	$datos = $this->getDatosProyecto();
	    	$users = $this->getUsersProyecto();

	    	foreach ($users as $usuario) {

	    		$this->send($usuario['email'],
                        'Se ha creado un nuevo proyecto',
                        $usuario['nombre'],
                        'crea_proyecto',
                        $datos);

	    	}
	    }

		
	    private function send($to, $subject, $name, $tipo_notificacion, $params)
	    {
	    	//$para = key($to);

	        $mailSettings 	= $this->config->mail;
	        $template 		= $this->getTemplate($tipo_notificacion, $params);

			$mail = new \PHPMailer();
        	$mail->IsSMTP();

	        $mail->From = $this->FromEmail;
	        $mail->Sender = $this->FromEmail;
	        $mail->FromName = $this->FromTitle;
	        

	        $mail->Host = 'smtp.sendgrid.net';
	        $mail->SMTPAuth = true;
	        $mail->SMTPSecure = "tls";
	        $mail->Port = 587;

	        $mail->Username = 'bicorpcl';
	        $mail->Password = 'bicorp.2016;';

	        $mail->WordWrap = 50;
	        $mail->IsHTML(true); //
	        $mail->CharSet = 'UTF-8';
	        
	        $mail->Subject 	= $subject;
	        $mail->Body 	= $template;
	        $mail->AltBody 	= $this->FromTitle;
	        $mail->AddAddress($to, $name);

	        if ($mail->Send()) {
	            return true;
	        }
	        else {
	            return false;
	        }
	        $mail->ClearAddresses();
	        $mail->ClearAttachments();
			

			//mail($para, $subject, $template, $cabeceras);
	    }

	    private function getTemplate($tipo_notificacion, $params)
	    {

	    	switch ($tipo_notificacion)
	    	{
	    		case 'crea_evento':
	    				$mensaje = "Se ha creado un nuevo evento en el cual ha sido invitado a participar";
	    				$render = 'email/evento';
	    				$titulo = "Creación de evento";
	    			break;

	    		case 'cancela_evento':
	    				$mensaje = "Se ha cancelado un evento en el cual participa";
	    				$render = 'email/evento';
	    				$titulo = "Cancelación de evento";
	    			break;

	    		case 'edita_evento':
	    				$mensaje = "Se ha editado un evento en el cual participa";
	    				$render = 'email/evento';
	    				$titulo = "Edición de evento";
	    			break;

	    		case 'activa_evento':
	    				$mensaje = "Se ha activado un evento en el cual participa";
	    				$render = 'email/evento';
	    				$titulo = "Activación de evento";
	    			break;

	    		case 'crea_proyecto':
	    				$mensaje = "Se ha creado un proyecto en el cual participa";
	    				$render = 'email/proyecto';
	    				$titulo = "Creación de proyecto";
	    			break;
	    		
	    	}

	    	$vista = $this->view->render($render);

	    	$vista = str_replace("{{ mensaje }}", $mensaje, $vista);

	    	$vista = str_replace("{{ nombre }}", $params['nombre'], $vista);
	    	$vista = str_replace("{{ lugar }}", $params['lugar'], $vista);
	    	$vista = str_replace("{{ fecha }}", $params['fecha'], $vista);
	    	$vista = str_replace("{{ hora }}", $params['hora'], $vista);

	    	$vista = str_replace("{{ cod_proyecto }}", $params['cod_proyecto'], $vista);
	    	$vista = str_replace("{{ jefeproyecto }}", $params['jefeproyecto'], $vista);
	    	$vista = str_replace("{{ tecnologia }}", $params['tecnologia'], $vista);
	    	$vista = str_replace("{{ area }}", $params['area'], $vista);


	    	return $vista;
	    }

	    private function getDatosEvento()
	    {
	    	$evento = Actividad::findByActvId($this->evento)->getFirst();

	    	$arr = array(
	    			'nombre' 	=> $evento->actv_descripcion_breve,
	    			'lugar'		=> $evento->actv_location,
	    			'fecha'		=> $evento->actv_fecha,
	    			'hora'		=> $evento->actv_hora,
	    			'proyecto' 	=> $evento->proyecto->nombre,
	    			'cod_proyecto' => $evento->proyecto->codigo,
	    			'jefeproyecto' => $evento->proyecto->jefeproyecto->name
	    		);

	    	return $arr;
	    }


	    private function getDatosProyecto()
	    {
	    	$proyecto = Proyecto::find($this->proyecto)->getFirst();

	    	$arr = array(
	    			'nombre' 		=> $proyecto->nombre,
	    			'lugar'			=> '',
	    			'fecha'			=> $proyecto->created_at,
	    			'hora'			=> '',
	    			'cod_proyecto' 	=> $proyecto->codigo,
	    			'jefeproyecto' 	=> $proyecto->jefeproyecto->name,
	    			'tecnologia'	=> $proyecto->tecnologia->nombre,
	    			'area'			=> $proyecto->area->nombre
	    		);

	    	return $arr;
	    }

	    private function getUsersEvento()
	    {
	    	$evento = Actividad::findByActvId($this->evento)->getFirst();
	    	//Creador
	    	$users[0]['id'] 		= $evento->creadopor->id;
	    	$users[0]['nombre'] 	= $evento->creadopor->name;
	    	$users[0]['email'] 		= $evento->creadopor->email;

	    	//QA
	    	$users[1]['id'] 		= $evento->usuario->usuario->id;
	    	$users[1]['nombre'] 	= $evento->usuario->usuario->name;
	    	$users[1]['email'] 		= $evento->usuario->usuario->email;

	    	//Jefe Proyecto
	    	//$users[2]->id 		= $evento->proyecto->jefeproyecto->id;
	    	//$users[2]->nombre 	= $evento->proyecto->jefeproyecto->name;

	    	return $users;
	    }

	    private function getUsersProyecto()
	    {
	    	$proyecto = Proyecto::find($this->proyecto)->getFirst();
	    	//Creador
	    	$users[0]['id'] 		= $proyecto->creador->id;
	    	$users[0]['nombre'] 	= $proyecto->creador->name;
	    	$users[0]['email'] 		= $proyecto->creador->email;

	    	//Jefe Proyecto
	    	$users[2]['id'] 		= $proyecto->jefeproyecto->id;
	    	$users[2]['nombre'] 	= $proyecto->jefeproyecto->name;
	    	$users[2]['email'] 		= $proyecto->jefeproyecto->email;

	    	return $users;
	    }
	}


	# Ejemplo de uso

	/*

		$mail = new EnvioMail();

	    $datos = array( 'nombre'    =>  'Evento 123',
	                    'lugar'     =>  'por ahí',
	                    'fecha'     =>  '2016-07-08',
	                    'hora'      =>  '13:00'
	                    );

	    $mail->send('sebastian.silva@zentagroup.com',
	                'asunto',
	                'Seba',
	                'cancela_evento',
	                $datos);

	*/