<?php

namespace Usuario\Form;

use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Form\Element\Csrf;

class UsuarioForm extends Form {

    public function __construct($name) {

        parent::__construct($name);
        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'id',
            'required' => true,
            'filters' => array(
                array('name' => 'Int'),
            ),
        ));

        $this->add(array(
            'name' => 'real_name',
            'type' => 'text',
            'options' => array(
                'label' => 'Nome',
                'id' => 'real_name',
                'attributes' => array(
                    'placeholder' => 'nome',
                )
            )
        ));

        $this->add(array(
            'name' => 'username',
            'type' => 'text',
            'options' => array(
                'label' => 'Login',
                'id' => 'username',
                'attributes' => array(
                    'placeholder' => 'login',
                )
            )
        ));

        $this->add(array(
            'name' => 'password',
            'type' => 'password',
            'options' => array(
                'label' => 'Senha',
                'id' => 'password',
                'attributes' => array(
                    'placeholder' => '*********',
                )
            )
        ));

        $this->add(array(
            'name' => 'status',
            'required' => true,
            'filters' => array(
                array('name' => 'Bit'),
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Csrf',
            'name' => 'loginCsrf',
            'options' => array(
                'csrf_options' => array(
                    'timeout' => 3600
                )
            )
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Salvar',
            ),
        ));
    }

}
