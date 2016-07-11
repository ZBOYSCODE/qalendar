<?php
    namespace Gabs\Models;
    use Phalcon\Mvc\Model;

    class Permiso extends Model
    {
        /**
         *
         * @var integer
         */
        public $id;

        /**
         *
         * @var integer
         */
        public $rol_id;

        /**
         *
         * @var string
         */
        public $permiso;

        /**
         * Returns table name mapped in the model.
         *
         * @return string
         */
        public function getSource()
        {
            return 'permisos';
        }

        /**
         * Initialize method for model.
         */
        public function initialize()
        {
            $this->hasOne('rol_id', __NAMESPACE__ . "\Roles",       'id', array('alias' => 'rol'));# Rol
        }
    }