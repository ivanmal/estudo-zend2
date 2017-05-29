<?php

namespace Application\Controller;

use Application\Form\Filter\LoginFilter;
use Application\Form\LoginForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class LoginController extends AbstractActionController {
    
    private $authService;
    
    public function getAuthService() {
        if(empty($this->authService)) {
            $this->authService = $this->getServiceLocator()->get('AuthService');
        }
        return $this->authService;
    }

    public function indexAction() {

        $request = $this->getRequest();

        $view = new ViewModel();

        $loginForm = new LoginForm('loginForm');
        $loginForm->setInputFilter(new LoginFilter());

        if ($request->isPost()) {
            $data = $request->getPost();
            $loginForm->setData($data);

            if ($loginForm->isValid()) {
                $data = $loginForm->getData();
                $authService = $this->getAuthService();

                $authService->getAdapter()
                        ->setIdentity($data['username'])
                        ->setCredential($data['password']);

                $result = $authService->authenticate();

                if ($result->isValid()) {
                    
                    $storage = $authService->getAdapter()->getResultRowObject(array('id', 'username', 'real_name'));
                    $this->getAuthService()->getStorage()->write($storage);                    
                    $user = $this->getAuthService()->getStorage()->read();

                    $this->flashMessenger()->addSuccessMessage(array('success' => 'Bem-vindo '.$user->real_name));
                } else {
                    $this->flashMessenger()->addErrorMessage(array('error' => 'Usuário e/ou Senha inválido(s).'));
                    return $this->redirect()->tourl('/login');
                }
                return $this->redirect()->tourl('/');              
            } else {
                $errors = $loginForm->getMessages();
                //var_dump($errors);
            }
        }

        $view->setVariable('loginForm', $loginForm);
        return $view;
    }

    public function logoutAction() {
        $authService = $this->getAuthService();
        $authService->clearIdentity();
        unset($this->authService);
        return $this->redirect()->toUrl('/login');
    }

}
