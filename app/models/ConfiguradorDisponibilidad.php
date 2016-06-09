<?php

class ConfiguradorDisponibilidad extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $cnfg_cdg;

    /**
     *
     * @var string
     */
    public $cnfg_hora_inicio;

    /**
     *
     * @var string
     */
    public $cnfg_hora_fin;

    /**
     *
     * @var string
     */
    public $cnfg_fecha_inicio;

    /**
     *
     * @var string
     */
    public $cnfg_fecha_fin;

    /**
     *
     * @var integer
     */
    public $cnfg_intervalo;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('cnfg_cdg', 'Disponible', 'cnfg_cdg', array('alias' => 'Disponible'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'configurador_disponibilidad';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ConfiguradorDisponibilidad[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ConfiguradorDisponibilidad
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
