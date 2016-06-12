<?php
namespace Gabs\Models;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Query;

class Disponible extends Model
{

    /**
     *
     * @var integer
     */
    public $dspn_id;

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
    public $cnfg_id;

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
        $this->belongsTo('actv_id', __NAMESPACE__.'\Actividad', 'actv_id', array('alias' => 'Actividad'));
        $this->belongsTo('cnfg_id',  __NAMESPACE__.'\ConfiguradorDisponibilidad', 'cnfg_id', array('alias' => 'ConfiguradorDisponibilidad'));
        $this->belongsTo('edsp_id',  __NAMESPACE__.'\EstadoDisponibilidad', 'edsp_id', array('alias' => 'EstadoDisponibilidad'));
        $this->belongsTo('user_id',  __NAMESPACE__.'\Users', 'id', array('alias' => 'Users'));
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
        $fecha = date('Y-m-d',strtotime($data['fecha']));
        $query = new Query("SELECT d.dspn_id,dspn_fecha,dspn_hora FROM Gabs\Models\Disponible d WHERE YEARWEEK('{$fecha}',7) = YEARWEEK(d.dspn_fecha,7) AND d.user_id = {$data['user_id']}", $this->getDI());

        return $query->execute();
    }

    public function getDisponiblesByFecha($data)
    {

        $dataQuery = array("dspn_fecha = '".$data['fecha']."' AND edsp_id = 1");        
        return Disponible::find($dataQuery);
    }


}
