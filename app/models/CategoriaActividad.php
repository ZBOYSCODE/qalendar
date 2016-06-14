<?php

namespace Gabs\Models;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Query;

class CategoriaActividad extends Model
{

    /**
     *
     * @var integer
     */
    public $ctgr_actv_id;

    /**
     *
     * @var integer
     */
    public $ctgr_id;

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
        $this->belongsTo('actv_id',  __NAMESPACE__.'\Actividad', 'actv_id', array('alias' => 'Actividad'));
        $this->belongsTo('ctgr_id',  __NAMESPACE__.'\Categoria', 'ctgr_id', array('alias' => 'Categoria'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'categoria_actividad';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return CategoriaActividad[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return CategoriaActividad
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
