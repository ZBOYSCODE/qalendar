<?php
    namespace Gabs\Models;
    use Phalcon\Mvc\Model;

    class Tecnologia extends Model
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
            return 'tecnologias';
        }

        /**
         * Initialize method for model.
         */
        public function initialize()
        {

        }
    }