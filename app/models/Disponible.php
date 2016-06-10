<?php

use Phalcon\Mvc\Model\Query;

class Disponible extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $dspn_cdg;

    /**
     *
     * @var integer
     */
    public $actv_id;

    /**
     *
     * @var integer
     */
    public $user_id;

    /**
     *
     * @var integer
     */
    public $edsp_id;

    /**
     *
     * @var integer
     */
    public $cnfg_cdg;

    /**
     *
     * @var string
     */
    public $dspn_fecha;

    /**
     *
     * @var string
     */
    public $dspn_hora;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('actv_id', 'Actividad', 'actv_id', array('alias' => 'Actividad'));
        $this->belongsTo('cnfg_cdg', 'ConfiguradorDisponibilidad', 'cnfg_cdg', array('alias' => 'ConfiguradorDisponibilidad'));
        $this->belongsTo('edsp_id', 'EstadoDisponibilidad', 'edsp_id', array('alias' => 'EstadoDisponibilidad'));
        $this->belongsTo('user_id', 'Users', 'id', array('alias' => 'Users'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'disponible';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Disponible[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Disponible
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    public function getDisponiblesWeek($data)
    {
        $query = new Query("SELECT * FROM  Gabs\Models\Users where user_id = ".$id, $this->getDI() );
        return $query->execute();
    }

}
