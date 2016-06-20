<?php
namespace Gabs\Models;
use Phalcon\Mvc\Model;

class TipoEstado extends Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $nombre;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'tipo_estados';
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {

    }
}