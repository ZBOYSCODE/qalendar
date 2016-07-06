<?php

namespace Gabs\Models;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Query;
use Gabs\Models\Disponible;
use Gabs\Models\UserActividad;
use Gabs\Models\Categoria;
use Gabs\Models\CategoriaActividad;
use Gabs\Models\Archivos;
use Gabs\Models\ConfiguradorDisponibilidad;

class Actividad extends Model
{

    /**
     *
     * @var integer
     */
    public $actv_id;

    /**
     *
     * @var integer
     */
    public $accs_id;

    /**
     *
     * @var integer
     */
    public $prrd_id;

    /**
     *
     * @var integer
     */
    public $proyecto_id;

    /**
     *
     * @var string
     */
    public $actv_descripcion_breve;

    /**
     *
     * @var string
     */
    public $actv_descripcion_ampliada;

    /**
     *
     * @var string
     */
    public $actv_location;

    /**
     *
     * @var string
     */
    public $actv_fecha;

    /**
     *
     * @var string
     */
    public $actv_hora;

    /**
     *
     * @var integer
     */
    public $actv_duracion_horas;

    /**
     *
     * @var integer
     */
    public $actv_duracion_minutos;

    /**
     *
     * @var string
     */
    public $actv_categoria;

    /**
     *
     * @var string
     */
    public $actv_status;    

    /**
     *
     * @var string
     */
    public $actv_creado_por;    

    /**
     *
     * @var string
     */
    public $actv_comentarios;    


    public $activo;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('actv_id', __NAMESPACE__.'\Disponible', 'actv_id', array('alias' => 'Disponible'));
        $this->hasMany('actv_id', __NAMESPACE__.'\UserActividad', 'actv_id', array('alias' => 'UserActividad'));

        $this->belongsTo("actv_status", __NAMESPACE__ . "\TipoEstado", "id", array('alias' => 'estado'));
        $this->belongsTo("actv_creado_por", __NAMESPACE__ . "\Users", "id", array('alias' => 'creadopor'));
        

        $this->hasMany('actv_id', __NAMESPACE__.'\CategoriaActividad', 'actv_id', array('alias' => 'CategoriaActividad'));
        $this->hasMany('actv_id', __NAMESPACE__.'\Archivos', 'actv_id', array('alias' => 'Archivos'));
        $this->hasMany('actv_id', __NAMESPACE__.'\Hito', 'actv_id', array('alias' => 'Hito'));

        $this->HasOne('actv_categoria', __NAMESPACE__.'\Categoria', 'ctgr_id', array('alias' => 'categoria'));

        $this->belongsTo('accs_id', __NAMESPACE__.'\Acceso', 'accs_id', array('alias' => 'Acceso'));
        $this->belongsTo('prrd_id', __NAMESPACE__.'\Prioridad', 'prrd_id', array('alias' => 'Prioridad'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'actividad';
    }

    /**
     * Allows to querys a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Actividad[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Actividad
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }


    /**
     * @param $data
     * @return mixed
     */
    public function getActividadesDay($data)
    {
        $fecha = date('Y-m-d',strtotime($data['fecha']));
        $query = new Query("SELECT a.actv_id, a.actv_fecha, a.actv_hora FROM Gabs\Models\Actividad a  WHERE a.actv_fecha = {$fecha}", $this->getDI());

        return $query->execute();
    }

    /**
     * @param $data
     * @return mixed
     */
    public function guardarActividad($data){

        if( empty($_POST['persona']) OR 
            empty($_POST['fecha']) OR 
            empty($_POST['hora'])

            ){
            $callback['error'] = 1;
            $callback['msg'][] = 'Faltan rellenar campos requeridos.'; 
            //$callback['msg'][] = 'Persona, fecha u hora.';            
            return $callback;
        }


        $this->proyecto_id = $_POST['proyecto'];
        $this->actv_descripcion_breve = $_POST['dscr-breve'];
        $this->actv_descripcion_ampliada = $_POST['dscr-ampliada'];
        $this->actv_location = $_POST['donde'];
        $this->actv_fecha = $_POST['fecha'];
        $this->actv_hora = $_POST['hora'];
        $this->actv_categoria = $_POST['categoria'];
        $this->actv_status = 2;
        $this->actv_creado_por = isset($_POST['creado_por'])?$_POST['creado_por']:'Admin';
        $this->actv_created_at = date('Y-m-d'); 
        $this->actv_updated_at = date('Y-m-d');
        $this->activo = 1;
        // Considerando relacion 1 a 1 para actividad - persona
        $persona = $_POST['persona'];

        // creamos variable update que indica si se esta creando o actualizando la actividad
        $update = true;
        if(empty($this->actv_id)) $update = false; 
        
            
        

        if(isset($callback['error']))
            return $callback; 


        if ($this->save() == false) {


            foreach ($this->getMessages() as $message) {
                $callback['msg'][] = $message->getMessage();
            }

            $callback['error'] = 1;
            $callback['msg'][] = 'Faltan rellenar campos requeridos.';
        } else{

            if(!$update)// se está creando recién
            {

                $disponible = Disponible::findFirst("dspn_fecha = '{$this->actv_fecha}' AND dspn_hora = '{$this->actv_hora}' AND user_id = {$persona}");

                if($disponible){
                    if($disponible->edsp_id == 1){ //Disponible
                        $disponible->edsp_id = 2;
                    } else{
                        $callback['error'] = 1;
                        $callback['msg'][] = 'No hay bloques disponibles en la hora y fecha seleccionadas.';  
                    }
                } else{
                    /*
                    $callback['error'] = 1;
                    $callback['msg'] = 'No hay bloques creados en la hora y fecha seleccionadas.';*/


                    $categoria  = Categoria::findFirst($_POST['categoria']);

                    $nblocks    = $categoria->duracion/15;

                    $bloqueHora = $this->actv_hora;
                    for ($i=0; $i < $nblocks; $i++) { 
                        
                        $disp = Disponible::findFirst("dspn_fecha = '{$this->actv_fecha}' AND dspn_hora = '{$bloqueHora}' AND user_id = {$persona}");
                        if(is_object($disp)){
                            if($disp->edsp_id != 1){
                                $callback['error'] = 1;
                                $callback['msg'][] = 'La duración de los bloques excede la disponibilidad actual.';                             
                                return $callback;
                            }
                        }

                        $date = new \DateTime($bloqueHora);
                        $valor_bloque = $this->getValorBloque();
                        $date->add(new \DateInterval('PT'.$valor_bloque.'M'));
                        $bloqueHora = $date->format('H:i:s');                         

                    }

                    for ($i=0; $i < $nblocks; $i++) {

                        //Bloques no creados ahora se crean.
                        $disponible = new Disponible();
                        $disponible->dspn_fecha = $this->actv_fecha;
                        $disponible->dspn_hora = $this->actv_hora;
                        $disponible->user_id = $persona;
                        $disponible->edsp_id = 2; // Ocupado
                        $disponible->actv_id = $this->actv_id;
                        $disponible->save();

                        // se le suman el valor de un bloque
                        $date = new \DateTime($this->actv_hora);

                        $valor_bloque = $this->getValorBloque();

                        $date->add(new \DateInterval('PT'.$valor_bloque.'M'));
                        $this->actv_hora = $date->format('H:i:s');
                    }
                }

                

                // creando nueva actividad
                $userActividad          = new UserActividad();
                $userActividad->actv_id = $this->actv_id;
                
                // creando nueva categoria
                $categoriaActividad             = new CategoriaActividad();
                $categoriaActividad->actv_id    = $this->actv_id;

            }else{

                // editando

                $userActividad      = UserActividad::findFirstByActvId($this->actv_id);
                $disponible = Disponible::findFirst("dspn_fecha = '{$this->actv_fecha}' AND dspn_hora = '{$this->actv_hora}' AND user_id = {$userActividad->user_id}");

                $disponible->user_id = (int)$_POST['persona'];

                $disponible->save();

                $categoriaActividad = CategoriaActividad::findFirstByActvId($this->actv_id);
            }           
            //print_r($categoriaActividad);die();
            
            $userActividad->user_id         = (int)$_POST['persona'];
            $categoriaActividad->ctgr_id    = (int)$_POST['categoria'];

            $categoriaActividad->save();
            

            if($userActividad->save() == false) {
                foreach ($userActividad->getMessages() as $message) {
                    $callback['msg'][] = $message->getMessage();
                }
                $callback['error'] = 1;
                $callback['msg'][] = 'Error creando al usuario.';                
            }

            if(!isset($callback)){
                return array();
            }

            
        }
        return $callback;
    }

    public function getValorBloque(){
        // obtenemos los minutos que vale el bloque
        $valor = ConfiguradorDisponibilidad::findFirst(1);
        return $valor->cnfg_intervalo;
    }

    public function getCategorias()
    {

        $cat = array();

        foreach ($this->CategoriaActividad as $c) {
            $cat[] = $c->ctgr_id;
        }

        return $cat;
    }

    public function getUsuarios()
    {
        $usr = array();

        foreach ($this->UserActividad as $u) {
            //$usr[] = $u->user_id;
            $usr = $u->user_id;
        }

        return $usr;
    }
}
