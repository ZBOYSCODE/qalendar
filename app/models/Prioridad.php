<?php
namespace Gabs\Models;
use Phalcon\Mvc\Model;

class Prioridad extends Model
{

    /**
     *
     * @var integer
     */
    public $prrd_id;

    /**
     *
     * @var string
     */
    public $prrd_nombre;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('prrd_id', 'Actividad', 'prrd_id', array('alias' => 'Actividad'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'prioridad';
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
