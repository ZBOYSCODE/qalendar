<?php

class EstadoDisponibilidad extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $edsp_id;

    /**
     *
     * @var string
     */
    public $edsp_nombre;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('edsp_id', 'Disponible', 'edsp_id', array('alias' => 'Disponible'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'estado_disponibilidad';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return EstadoDisponibilidad[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return EstadoDisponibilidad
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
