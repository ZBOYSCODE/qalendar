<?php
namespace Gabs\Models;
use Phalcon\Mvc\Model;

class UserActividad extends Model
{

    /**
     *
     * @var integer
     */
    public $user_actv_id;

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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('actv_id', 'Actividad', 'actv_id', array('alias' => 'Actividad'));
        $this->belongsTo('user_id', 'Users', 'id', array('alias' => 'Users'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'user_actividad';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return UserActividad[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return UserActividad
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
