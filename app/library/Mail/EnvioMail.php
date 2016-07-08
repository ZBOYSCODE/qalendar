<?php
	namespace Gabs\Mail;

	use Phalcon\Mvc\User\Component;
	use Phalcon\Mvc\View;


	require('../app/library/PHPMailer/class.phpmailer.php');
	require('../app/library/PHPMailer/class.smtp.php');


	class EnvioMail extends Component
	{
	    private $FromEmail 	= 'notificaciones@qalendar.cl';
	    private $FromTitle 	= 'Notificación';


		
	    public function send($to, $subject, $name, $tipo_notificacion, $params)
	    {
	    	//$para = key($to);

	        $mailSettings = $this->config->mail;

	        $template = $this->getTemplate($tipo_notificacion, $params);


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

	    public function getTemplate($tipo_notificacion, $params)
	    {

	    	switch ($tipo_notificacion)
	    	{
	    		case 'crea_evento':
	    				$vista = $this->view->render('email/crea_evento');
	    			break;

	    		case 'cancela_evento':
	    				$vista = $this->view->render('email/cancela_evento');
	    			break;
	    		
	    	}


	    	$vista = str_replace("{{ nombre }}", $params['nombre'], $vista);
	    	$vista = str_replace("{{ lugar }}", $params['lugar'], $vista);
	    	$vista = str_replace("{{ fecha }}", $params['fecha'], $vista);
	    	$vista = str_replace("{{ hora }}", $params['hora'], $vista);

	    	return $vista;
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