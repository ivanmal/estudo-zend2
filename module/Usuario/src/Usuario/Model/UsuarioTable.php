<?php

namespace Usuario\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class UsuarioTable implements UsuarioTableInterface {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    public function findAll($pagination = false) {

        if ($pagination) {
            $select = new Select('users');
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new Usuario());
            $paginatorAdapter = new DbSelect($select, $this->tableGateway->getAdapter(), $resultSetPrototype);
            $paginator = new Paginator($paginatorAdapter);
            return $paginator;
        }

        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function findUsuario($id) {
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Não foi possível encontrar o usuário $id");
        }
        return $row;
    }

    public function save(Usuario $usuario) {
        $data = array(
            'username' => $usuario->getUsername(),
            'password' => $usuario->getPassword(),
            'real_name' => $usuario->getRealName(),
            'status' => $usuario->getStatus(),
        );

        $id = $usuario->getId();
        if ($id == 0) {

            $this->tableGateway->insert($data);
        } else {
            if ($this->findUsuario($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Usuário não encontrado');
            }
        }
    }

    public function changeStatus($id) {

        $data = array();

        $usuario = $this->findUsuario($id);

        if ($usuario) {

            if ($usuario->getStatus() == 0) {
                $data['status'] = 1;
            } else {
                $data['status'] = 0;
            }

            $this->tableGateway->update($data, array('id' => $id));
        } else {
            throw new \Exception('Usuário não encontrado');
        }
    }

    public function checkUsername($username) {

        $rowset = $this->tableGateway->select(array('username' => $username));
        $row = $rowset->current();
        if ($row) {
            return true;
        }
        return false;
    }

}
