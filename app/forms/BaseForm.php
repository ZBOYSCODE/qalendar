<?php
/**
 * Created by PhpStorm.
 * User: jasilva
 * Date: 03/07/2016
 * Time: 11:32
 */

namespace Gabs\Forms;
use Phalcon\Forms\Form;


class BaseForm extends Form
{
    private $errorMessages = array();

    public function formatMessages(){

        #Obtenemos los mensajes del formulario instanciado
        $messages = $this->getMessages();
        $_messagesTmp = array();

        #transformamos de la forma array[field] = messsage
        foreach($messages as $message){
            $_messagesTmp[$message->getField()] = $message->getMessage();
        }

        $this->errorMessages = $_messagesTmp;

    }

    /**
     * @return array
     */
    public function getErrorMessages()
    {
        return $this->errorMessages;
    }

    /**
     * @param array $errorMessages
     */
    public function setErrorMessages($errorMessages)
    {
        $this->errorMessages = $errorMessages;
    }



}