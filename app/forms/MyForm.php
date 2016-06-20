<?php
namespace Gabs\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Select;
use Phalcon\Validation\Validator\PresenceOf;

class MyForm extends Form {
    public function initialize()
    {
        $name =
            new Text(
                "name",
                array(
                    'maxlength'   => 30,
                    'placeholder' => 'Ingresa tu nombre...',
                    'class' => 'form-control input-sm'
                )
            );

        $name->addValidators(array(
            new PresenceOf(array(
                'message' => 'The name is required'
            ))
        ));

        $this->add($name);

        $this->add(
            new Text(
                "telephone",
                array(
                 'maxlength'   => 30,
                 'placeholder' => 'Ingresa tu Telefono...',
                 'class' => 'form-control input-sm'
                )
            )
        );

    }
}