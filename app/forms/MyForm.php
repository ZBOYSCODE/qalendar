<?php
namespace Gabs\Forms;

use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Select;
use Phalcon\Validation\Validator\PresenceOf;

class MyForm extends BaseForm {
    #Debemos extender BaseForm para formatear los mensajes como array
    public function initialize()
    {
        #Definimos el field "name"
        $name =
            new Text(
                "name",
                #le agregamos parametros al input
                array(
                    'maxlength'   => 30,
                    'placeholder' => 'Ingresa tu nombre...',
                    'class' => 'form-control input-sm'
                )
            );

        #le agregamos validaciones
        $name->addValidators(array(
            new PresenceOf(array(
                'message' => 'El Nombre es requerido.'
            ))
        ));


        $phone =
            new Text(
                "telephone",
                array(
                 'maxlength'   => 30,
                 'placeholder' => 'Ingresa tu Telefono...',
                 'class' => 'form-control input-sm'
                )
            );

        $phone->addValidators(array(
            new PresenceOf(array(
                'message' => 'El telefono es requerido.'
            ))
        ));



        #Agregamos todos los fields al formulario
        $this->add($name);
        $this->add($phone);

    }

}