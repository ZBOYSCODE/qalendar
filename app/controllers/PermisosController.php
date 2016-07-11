<?php
    namespace Gabs\Controllers;

    use Gabs\Models\Roles;
    use Gabs\Models\Permiso;

    
    class PermisosController extends ControllerBase
    {
        public function initialize()
        {
            $this->_themeArray = array('topMenu'=>true, 'menuSel'=>'','pcView'=>'', 'pcData'=>'', 'jsScript'=>'');
        }
        
        /**
         * Default action, shows the search form
         */
        public function indexAction()
        {   
            $data['permisos'] = $this->getControllersAndMethod();

            $data['roles'] = Roles::find();

            $themeArray = $this->_themeArray;
            $themeArray['pcView']   = 'permisos/list';
            $themeArray['addJs'][]  = "js/permisos.js";

            $themeArray['pcData'] = $data;
            echo $this->view->render('theme', $themeArray);
        }

        public function getPermisosAction()
        {
            $rol = $this->request->getPost("rol", 'int');

            $data['estado'] = true;
            $permisos = Permiso::findByRolId($rol);


            foreach ($permisos as $permiso) {

                $data['permisos'][] = $permiso->permiso;
            }

            echo json_encode($data);
        }

        public function updatePermisosAction()
        {
            $rol        =  $this->request->getPost("rol", 'int');
            $permisos   =  $this->request->getPost("permisos");

            $data['estado'] = true;

            $this->deletePermisos($rol);

            if(count($permisos) > 0){
                foreach ($permisos as $permiso) {

                    $per = new Permiso();
                    $per->permiso   = $permiso;
                    $per->rol_id    = $rol;

                    if(!$per->save()){
                        $data['estado'] = false;
                        $data['msg']    = "Error al guardar permisos.";

                        foreach ($per->getMessages() as $message) {
                            $data['detalle'][] = $message->getMessage();
                        }
                    } 
                }

                if($data['estado']){
                    $data['msg']    = "Permisos actualizados correctamente.";
                }
            } else {
                $data['msg']    = "No hay permisos para actualizar.";
            }

                
                

            echo json_encode($data);
        }

        private function getControllersAndMethod()
        {   

            $controladores = $this->getControllers();

            foreach ($controladores as $className) {

                require_once($className.'.php');

                $a = '\Gabs\Controllers\\'.$className;

                if(class_exists($a)) {

                    $meths = get_class_methods( new $a );

                    foreach ($meths as $meth) {

                        $pos = strpos($meth, 'Action');

                        if($pos !== false) {
                            $arr[str_replace('Controller', '', $className)][] = str_replace('Action', '', $meth) ;
                        }
                    }
                }    
            }

            return $arr;
        }


        private function getControllers()
        {

            $dir = $this->config->application->controllersDir;
            $ctrls =  scandir($dir);

            foreach ($ctrls as $controlador) {
                
                $ruta_controlador = $dir.$controlador;

                if(is_file($ruta_controlador))
                {
                    // Obtenemos el nombre de nuestro controlador
                    $namectrl = str_replace(".php", "", $controlador);

                    if($namectrl != "ControllerBase" )
                    {
                        $controladores[] = $namectrl;
                    }
                    
                }
            }

            return $controladores;
        }

        private function deletePermisos($rol)
        {
            $permisos = Permiso::findByRolId($rol);

            foreach ($permisos as $permiso){
                $permiso->delete();
            }
        }
    }