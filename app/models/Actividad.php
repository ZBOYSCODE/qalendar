<?php
namespace Gabs\Models;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Query;

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


    public function getActividadesDay($data)
    {
        $fecha = date('Y-m-d',strtotime($data['fecha']));
        $query = new Query("SELECT a.actv_id, a.actv_fecha, a.actv_hora FROM Gabs\Models\Actividad a  WHERE a.actv_fecha = {$fecha}", $this->getDI());

        return $query->execute();
    }    

}
