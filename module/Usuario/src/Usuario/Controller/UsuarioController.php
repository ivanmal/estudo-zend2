<?php

namespace Usuario\Controller;

use Usuario\Form\Filter\UsuarioFilter;
use Usuario\Form\UsuarioForm;
use Usuario\Model\Usuario;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UsuarioController extends AbstractActionController {

    private $usuarioTable;

    public function getUsuarioTable() {
        if (!$this->usuarioTable) {
            $sm = $this->getServiceLocator();
            $this->usuarioTable = $sm->get('Usuario\Model\UsuarioTable');
        }
        return $this->usuarioTable;
    }

    public function indexAction() {      
        
        $usuarios = $this->getUsuarioTable()->findAll(true);
        
        $usuarios->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
        $usuarios->setItemCountPerPage(10);

        return new ViewModel(array('usuarios' => $usuarios));
    }

    public function addAction() {
        $form = new UsuarioForm('usuarioForm');
        $form->setInputFilter(new UsuarioFilter());
        $form->get('submit')->setValue('Adicionar');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {

                $usuario = new Usuario();

                $usuario->exchangeArray($form->getData());

                if ($this->getUsuarioTable()->checkUsername($usuario->getUsername())) {
                    $this->flashMessenger()->addErrorMessage(array('error' => 'Login já cadastrado'));
                    return array('form' => $form);
                }

                $this->getUsuarioTable()->save($usuario);

                $this->flashMessenger()->addSuccessMessage(array('success' => 'Usuário criado com sucesso.'));
                return $this->redirect()->toRoute('usuario');
            } else {
                $this->flashMessenger()->addErrorMessage(array('error' => 'Verifique o(s) campo(s)'));
            }
        }
        return array('form' => $form);
    }

    public function editAction() {

        $id = $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('usuario');
        }

        try {
            $usuario = $this->getUsuarioTable()->findUsuario($id);
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage(array('error' => 'Usuário näo encontrado.'));
            return $this->redirect()->toRoute('usuario');
        }

        $form = new UsuarioForm('usuarioForm');
        $form->bind($usuario);
        $form->setInputFilter(new UsuarioFilter());
        $form->get('submit')->setValue('Editar');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getUsuarioTable()->save($usuario);
                $this->flashMessenger()->addSuccessMessage(array('success' => 'Usuário alterado.'));
                return $this->redirect()->toRoute('usuario');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function changeStatusAction() {

        $id = $this->params()->fromRoute('id', 0);

        if ($id) {
            try {
                $usuario = $this->getUsuarioTable()->changeStatus($id);

                $this->flashMessenger()->addSuccessMessage(array('success' => 'Status do usuário alterado.'));
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage(array('error' => 'Erro ao alterar o Usuário.'));
            }
        }

        return $this->redirect()->toRoute('usuario');
    }

}
