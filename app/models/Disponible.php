<?php
namespace Gabs\Models;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Query;
use \Gabs\Dto\Calendar;

use Gabs\Models\Bloqueo;

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
        $query = new Query("SELECT d.dspn_id,dspn_fecha,dspn_hora FROM Gabs\Models\Disponible d WHERE YEARWEEK('{$fecha}',7) = YEARWEEK(d.dspn_fecha,7) AND d.user_id = {$data['user_id']} AND edsp_id = 2 AND actv_id is not null", $this->getDI());

        return $query->execute();
    }

    public function getDisponiblesDay($data)
    {
        $fecha = date('Y-m-d',strtotime($data['fecha']));
        $query = new Query("SELECT d.dspn_id,dspn_fecha,dspn_hora FROM Gabs\Models\Disponible d WHERE d.dspn_fecha = '{$fecha}' AND edsp_id = 2 AND actv_id is not null", $this->getDI());

        return $query->execute();
    }

    public function getDisponiblesByFecha($data)
    {
        $calendar = new Calendar();
        $horasTotales = $calendar->getHorasWeek();
        $dataQuery = array("dspn_fecha = '".$data['fecha']."' AND edsp_id != 1 AND user_id = ".$data['persona']);        
        $noDisponibles = Disponible::find($dataQuery);
        if(count($noDisponibles)>0){
            foreach ($noDisponibles as $val) {
                $horasNoDisponibles[] = date('H:i',strtotime($val->dspn_hora));
            }
        } else
            $horasNoDisponibles = array();

        
        $horasDisponibles = array_diff($horasTotales, $horasNoDisponibles);
        sort($horasNoDisponibles);

        return $horasDisponibles;
    }
        

    /**
     * comprbaremos la disponibilidad de la actividad
     * 
     */
    public function comprobarDisponibilidad($bloque, $duracion)
    {

        $nblocks    = $duracion/$bloque;

        $bloqueHora = $this->dspn_hora;
        
        for ($i=0; $i < $nblocks; $i++) { 
            
            $disp = Disponible::findFirst("     dspn_fecha  = '{$this->dspn_fecha}' 
                                            AND dspn_hora   = '{$bloqueHora}' 
                                            AND user_id     = {$this->user_id}"
                                        );
            
            if(is_object($disp))
            {
                if($disp->edsp_id != 1){
                    return false;
                }
            }

            $fecha      = $this->dspn_fecha." ".$bloqueHora;

            $bloqueado  = Bloqueo::findfirst("      '{$fecha}' >=   fecha_inicio 
                                                AND '{$fecha}' <   fecha_termino 
                                                AND user_id = {$this->user_id} ");

            if(is_object($bloqueado))
            {
                return false;
            }



            $date = new \DateTime($bloqueHora);
            $date->add(new \DateInterval('PT'.$bloque.'M'));
            $bloqueHora = $date->format('H:i:s');                         

        }

        return true;
    }

    public function comprobarDisponibilidadEdicion($bloque, $duracion)
    {
        $nblocks    = $duracion/$bloque;

        $bloqueHora = $this->dspn_hora;
        
        for ($i=0; $i < $nblocks; $i++) { 
            
            $disp = Disponible::findFirst("     dspn_fecha  = '{$this->dspn_fecha}' 
                                            AND dspn_hora   = '{$bloqueHora}' 
                                            AND user_id     = {$this->user_id}
                                            AND NOT actv_id = {$this->actv_id} "
                                        );
            
            if(is_object($disp))
            {
                if($disp->edsp_id != 1){
                    return false;
                }
            }

            $date = new \DateTime($bloqueHora);
            $date->add(new \DateInterval('PT'.$bloque.'M'));
            $bloqueHora = $date->format('H:i:s');                         

        }

        return true;
    }

    /**
     * una vez comprobada la actividad
     * 
     */
    public function guardarDisponibilidad($bloque, $duracion)
    {
        $nblocks    = $duracion/$bloque;
        $bloqueHora = $this->dspn_hora;

        $fecha      = $this->dspn_fecha;
        $actv_id    = $this->actv_id;
        $user       = $this->user_id;
        $cnfg_id    = $this->cnfg_id;

        for ($i=0; $i < $nblocks; $i++)
        {
            /**
             * existe la posibilidad de que ya exista la disponibilidad creada
             * en este caso, se buscara y se modificará, en caso contrario se creará una nueva
             */

            $disponible = Disponible::findFirst("     dspn_fecha  = '{$fecha}' 
                                            AND dspn_hora   = '{$bloqueHora}' 
                                            AND user_id     = {$user}"
                                        );

            // si no existe el registro, creamos uno nuevo
            if(!is_object($disponible))
            {
                $disponible = new Disponible();
            }


            //Bloques no creados ahora se crean.
            
            $disponible->dspn_fecha     = $fecha;
            $disponible->dspn_hora      = $bloqueHora;
            $disponible->edsp_id        = 2; // Ocupado
            $disponible->actv_id        = $actv_id;
            $disponible->user_id        = $user;
            $disponible->cnfg_id        = $cnfg_id;

            if(!$disponible->save())
            {
                # guardamos los mensajes de error
                foreach ($disponible->getMessages() as $message) {
                    $callback['msg'][] = $message->getMessage();
                }

                echo "<pre>"; print_r($callback);

                return false;
            }

            // se le suman el valor de un bloque
            $date = new \DateTime($bloqueHora);
            $date->add(new \DateInterval('PT'.$bloque.'M'));
            $bloqueHora = $date->format('H:i:s');
        }

        return true;
    }


    public function resetDisponibilidad()
    {
        if( isset($this->actv_id) && !empty($this->actv_id) ) {

            $disponible = Disponible::findByActvId($this->actv_id);

            foreach ($disponible as $disp){
                $disp->edsp_id = 1;
                $disp->actv_id = null;
                $disp->save();
            }
        }

        return true;
    }


}
