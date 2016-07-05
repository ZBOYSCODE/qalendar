<?php
namespace Gabs\Models;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Query;

class Categoria extends Model
{

    /**
     *
     * @var integer
     */
    public $ctgr_id;

    /**
     *
     * @var string
     */
    public $ctgr_nombre;

    /**
     *
     * @var integer
     */
    public $duracion;


    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('ctgr_id',  __NAMESPACE__.'\CategoriaActividad', 'ctgr_id', array('alias' => 'CategoriaActividad'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'categoria';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Categoria[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Categoria
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
