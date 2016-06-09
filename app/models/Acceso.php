<?php
namespace Gabs\Models;
use Phalcon\Mvc\Model;

class Acceso extends Model
{

    /**
     *
     * @var integer
     */
    public $accs_id;

    /**
     *
     * @var string
     */
    public $accs_nombre;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('accs_id', 'Actividad', 'accs_id', array('alias' => 'Actividad'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'acceso';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Acceso[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Acceso
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
