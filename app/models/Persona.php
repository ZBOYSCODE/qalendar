<?php

use Phalcon\Mvc\Model\Validator\Email as Email;

class Persona extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $prsn_cdg;

    /**
     *
     * @var integer
     */
    public $user_id;

    /**
     *
     * @var string
     */
    public $nombres;

    /**
     *
     * @var string
     */
    public $apellido_paterno;

    /**
     *
     * @var string
     */
    public $apellido_materno;

    /**
     *
     * @var string
     */
    public $email;

    /**
     * Validations and business logic
     *
     * @return boolean
     */
    public function validation()
    {
        $this->validate(
            new Email(
                array(
                    'field'    => 'email',
                    'required' => true,
                )
            )
        );

        if ($this->validationHasFailed() == true) {
            return false;
        }

        return true;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('user_id', 'Users', 'id', array('alias' => 'Users'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'persona';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Persona[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Persona
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
