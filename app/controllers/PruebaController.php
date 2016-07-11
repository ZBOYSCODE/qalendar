<?php
    namespace Gabs\Controllers;

    use Gabs\Mail\EnvioMail;
  
    class PruebaController extends ControllerBase
    {

        /**
         * Default action. Set the public layout (layouts/public.volt)
         */
        public function initialize()
        {
            
        }

        public function indexAction()
        {   
            echo "<pre>";
            print_r(spl_autoload_register('Gabs\Controllers'));
        }

        public function envioMailAction()
        {
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
        }
    }