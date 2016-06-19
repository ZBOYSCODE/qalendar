<?php
namespace Gabs\Models;
use Phalcon\Mvc\Model;

class Hito extends Model
{

    public $hito_id;
    public $actv_id;

    public $hito_nombre;
    public $hito_descripcion;
    public $hito_tipo;

    public $created_at;
    public $updated_at;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
       $this->hasMany('actv_id', 'Actividad', 'actv_id', array('alias' => 'actividad'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'hitos';
    }

}
