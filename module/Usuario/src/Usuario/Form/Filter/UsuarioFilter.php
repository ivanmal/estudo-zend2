<?php

namespace Usuario\Form\Filter;

use Zend\InputFilter\InputFilter;
use Zend\Validator\NotEmpty;

class UsuarioFilter extends InputFilter {

    public function __construct() {

        $isEmpty = NotEmpty::IS_EMPTY;

        $this->add(array(
            'name' => 'real_name',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            $isEmpty => 'Nome é Obrigatório'
                        )
                    ),
                    'break_chain_on_failure' => true
                ),
            ),
        ));

        $this->add(array(
            'name' => 'username',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            $isEmpty => 'Login é Obrigatório'
                        )
                    ),
                    'break_chain_on_failure' => true
                ),
            ),
        ));

        $this->add(array(
            'name' => 'password',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            $isEmpty => 'Senha é Obrigatório'
                        )
                    )
                )
            )
        ));
    }

}
