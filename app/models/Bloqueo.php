<?php
	namespace Gabs\Models;

	use Phalcon\Mvc\Model;
	use Phalcon\Mvc\Model\Query;

	class Bloqueo extends Model
	{
		/**
	     *
	     * @var integer
	     */
	    public $id;

	    /**
	     *
	     * @var id
	     */
	    public $user_id;

	    /**
	     *
	     * @var datetime
	     */
	    public $fecha_inicio;

	    /**
	     *
	     * @var datetime
	     */
	    public $fecha_termino;

	    /**
	     * Initialize method for model.
	     */
	    public function initialize()
	    {
	        $this->hasOne('user_id',  __NAMESPACE__.'\Users', 'id', array('alias' => 'usuario'));
	    }


		/**
	     * Returns table name mapped in the model.
	     *
	     * @return string
	     */
	    public function getSource()
	    {
	        return 'bloqueos';
	    }
		
	}