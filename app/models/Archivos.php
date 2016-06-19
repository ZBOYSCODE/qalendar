<?php
namespace Gabs\Models;
use Phalcon\Mvc\Model;

class Archivos extends Model
{

    /**
     *
     * @var integer
     */
    public $arch_id;
    /**
     *
     * @var integer
     */
    public $user_id;
    /**
     *
     * @var integer
     */
    public $actv_id;
    /**
     *
     * @var integer
     */
    public $hito_id;


    public $arch_created_at;

    public $arch_updated_at;
    /**
     *
     * @var string
     */
    public $arch_nombre;
    /**
     *
     * @var string
     */
    public $arch_tipo;
    /**
     *
     * @var string
     */
    public $arch_ruta;
    /**
     *
     * @var string
     */
    public $arch_obs;
    /**
     * Initialize method for model.
     */
    public function initialize()
    {

        $this->belongsTo('user_id', __NAMESPACE__.'\Users', 'id', array('alias' => 'Usuario'));
        $this->belongsTo('actv_id', __NAMESPACE__.'\Actividad', 'actv_id', array('alias' => 'Actividad'));


    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'archivos';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Prioridad[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Prioridad
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
