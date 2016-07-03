<?php
    namespace Gabs\Models;
    use Phalcon\Mvc\Model;

    class Proyecto extends Model
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
            return 'proyectos';
        }

        /**
         * Initialize method for model.
         */
        public function initialize()
        {
            $this->hasOne('creador_id', __NAMESPACE__ . "\Users",       'id', array('alias' => 'creador'));# Creador
            $this->hasOne('jefep_id', __NAMESPACE__ . "\Users",       'id', array('alias' => 'jefeproyecto'));# Jefe proyecto

            $this->hasOne('area_id', __NAMESPACE__ . "\Area",        'id', array('alias' => 'area'));# Area
            $this->hasOne('tecnologia_id', __NAMESPACE__ . "\Tecnologia",  'id', array('alias' => 'tecnologia'));# Tecnología
        }
    }