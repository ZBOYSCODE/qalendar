<?php

namespace Gabs\Models;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Query;
use Gabs\Models\Disponible;
use Gabs\Models\UserActividad;
use Gabs\Models\Categoria;
use Gabs\Models\CategoriaActividad;
use Gabs\Models\Archivos;
use Gabs\Models\ConfiguradorDisponibilidad;

class Actividad extends Model
{

    /**
     *
     * @var integer
     */
    public $actv_id;

    /**
     *
     * @var integer
     */
    public $accs_id;

    /**
     *
     * @var integer
     */
    public $prrd_id;

    /**
     *
     * @var integer
     */
    public $proyecto_id;

    /**
     *
     * @var string
     */
    public $actv_descripcion_breve;

    /**
     *
     * @var string
     */
    public $actv_descripcion_ampliada;

    /**
     *
     * @var string
     */
    public $actv_location;

    /**
     *
     * @var string
     */
    public $actv_fecha;

    /**
     *
     * @var string
     */
    public $actv_hora;

    /**
     *
     * @var integer
     */
    public $actv_duracion_horas;

    /**
     *
     * @var integer
     */
    public $actv_duracion_minutos;

    /**
     *
     * @var string
     */
    public $actv_categoria;

    /**
     *
     * @var string
     */
    public $actv_status;    

    /**
     *
     * @var string
     */
    public $actv_creado_por;    

    /**
     *
     * @var string
     */
    public $actv_comentarios;    


    public $activo;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('actv_id', __NAMESPACE__.'\Disponible', 'actv_id', array('alias' => 'Disponible'));
        $this->hasMany('actv_id', __NAMESPACE__.'\UserActividad', 'actv_id', array('alias' => 'UserActividad'));

        $this->belongsTo("actv_status", __NAMESPACE__ . "\TipoEstado", "id", array('alias' => 'estado'));
        $this->belongsTo("actv_creado_por", __NAMESPACE__ . "\Users", "id", array('alias' => 'creadopor'));
        

        $this->hasMany('actv_id', __NAMESPACE__.'\CategoriaActividad', 'actv_id', array('alias' => 'CategoriaActividad'));
        $this->hasMany('actv_id', __NAMESPACE__.'\Archivos', 'actv_id', array('alias' => 'Archivos'));
        $this->hasMany('actv_id', __NAMESPACE__.'\Hito', 'actv_id', array('alias' => 'Hito'));

        $this->HasOne('actv_categoria', __NAMESPACE__.'\Categoria', 'ctgr_id', array('alias' => 'categoria'));

        $this->HasOne('proyecto_id', __NAMESPACE__.'\Proyecto', 'id', array('alias' => 'Proyecto'));

        $this->belongsTo('accs_id', __NAMESPACE__.'\Acceso', 'accs_id', array('alias' => 'Acceso'));
        $this->belongsTo('prrd_id', __NAMESPACE__.'\Prioridad', 'prrd_id', array('alias' => 'Prioridad'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'actividad';
    }

    /**
     * Allows to querys a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Actividad[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Actividad
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }


    /**
     * @param $data
     * @return mixed
     */
    public function getActividadesDay($data)
    {
        $fecha = date('Y-m-d',strtotime($data['fecha']));
        $query = new Query("SELECT a.actv_id, a.actv_fecha, a.actv_hora FROM Gabs\Models\Actividad a  WHERE a.actv_fecha = {$fecha}", $this->getDI());

        return $query->execute();
    }

    public function getCategorias()
    {

        $cat = array();

        foreach ($this->CategoriaActividad as $c) {
            $cat[] = $c->ctgr_id;
        }

        return $cat;
    }

    public function getUsuarios()
    {
        $usr = array();

        foreach ($this->UserActividad as $u) {
            //$usr[] = $u->user_id;
            $usr = $u->user_id;
        }

        return $usr;
    }
}
