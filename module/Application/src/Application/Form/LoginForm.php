<?php

namespace Application\Form;

use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Form\Element\Csrf;

class LoginForm extends Form {

    public function __construct($name) {

        parent::__construct($name);
        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'username',
            'type' => 'text',
            'required' => true,
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
            'required' => true,
            'options' => array(
                'label' => 'Senha',
                'id' => 'password',
                'attributes' => array(
                    'placeholder' => '*********',
                )
            )
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
                'value' => 'Entrar',
            ),
        ));
    }

}
