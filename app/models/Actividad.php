<?php
namespace Gabs\Models;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Query;
use Gabs\Models\Disponible;
use Gabs\Models\UserActividad;

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

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('actv_id', __NAMESPACE__.'\Disponible', 'actv_id', array('alias' => 'Disponible'));
        $this->hasMany('actv_id', __NAMESPACE__.'\UserActividad', 'actv_id', array('alias' => 'UserActividad'));
        $this->hasMany('actv_id', __NAMESPACE__.'\CategoriaActividad', 'actv_id', array('alias' => 'CategoriaActividad'));
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

        $this->accs_id = $_POST['acceso'];
        $this->prrd_id = $_POST['prioridad'];
        $this->actv_descripcion_breve = $_POST['dscr-breve'];
        $this->actv_descripcion_ampliada = $_POST['dscr-ampliada'];
        $this->actv_location = $_POST['donde'];
        $this->actv_fecha = $_POST['fecha'];
        $this->actv_hora = $_POST['hora'];
        $this->actv_duracion_horas = $_POST['duracion'];
        $this->actv_duracion_minutos = $_POST['duracion'];
        $this->actv_categoria = $_POST['categoria'];
        //$this->actv_status = $_POST['status'];
        $this->actv_status = 'Pendiente Aprobación';
        $this->actv_creado_por = 'admin';
        $this->actv_created_at = date('Y-m-d'); 
        $this->actv_updated_at = date('Y-m-d'); 
        //$actividad->actv_comentarios = $_POST['comentarios'];


        // Considerando relacion 1 a 1 para actividad - persona
        $persona = $_POST['persona'];

        $disponible = Disponible::findFirst("dspn_fecha = '{$this->actv_fecha}' AND dspn_hora = '{$this->actv_hora}' AND user_id = {$persona}");

        if($disponible){
            if($disponible->edsp_id == 1){ //Disponible
                $disponible->edsp_id = 2;
            } else{
                $callback['error'] = 1;
                $callback['msg'] = 'No hay bloques disponibles en la hora y fecha seleccionadas.';  
            }
        } else{
            /*
            $callback['error'] = 1;
            $callback['msg'] = 'No hay bloques creados en la hora y fecha seleccionadas.';*/

            //Bloques no creados ahora se crean.
            $disponible = new Disponible();
            $disponible->dspn_fecha = $this->actv_fecha;
            $disponible->dspn_hora = $this->actv_hora;
            $disponible->user_id = $persona;
            $disponible->edsp_id = 2; // Ocupado
            $disponible->save();

        }

        if(isset($callback['error']))
            return $callback; 


        if ($this->save() == false) {
            /*
            foreach ($this->getMessages() as $message) {
                //echo "Message: ", $message->getMessage();
                //echo "Field: ", $message->getField();
                //echo "Type: ", $message->getType();
            }*/
            $callback['error'] = 1;
            $callback['msg'] = 'Faltan rellenar campos requeridos.';
            return $callback;
        } else{
            $disponible->actv_id = $this->actv_id;
            $disponible->update();

            $userActividad = new UserActividad();
            $userActividad->actv_id = $this->actv_id;
            $userActividad->user_id = $_POST['persona'];

            if($userActividad->save()) {
                foreach ($this->getMessages() as $message) {
                    echo "Message: ", $message->getMessage();
                    echo "Field: ", $message->getField();
                    echo "Type: ", $message->getType();
                }
            }

            $callback['msg'] = 'Actividad creada correctamente.';
            return $callback;
        }
    }

}
