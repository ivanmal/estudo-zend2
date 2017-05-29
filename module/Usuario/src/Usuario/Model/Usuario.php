<?php

namespace Usuario\Model;

class Usuario implements UsuarioInterface {

    private $id;
    private $username;
    private $password;
    private $realName;
    private $status;

    public function exchangeArray($data) {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->username = (!empty($data['username'])) ? $data['username'] : null;
        $this->password = (!empty($data['password'])) ? $data['password'] : null;
        $this->realName = (!empty($data['real_name'])) ? $data['real_name'] : null;
        $this->status = (!empty($data['status'])) ? $data['status'] : null;
    }

    public function getArrayCopy() {
        return array(
            'id' => $this->id,
            'username' => $this->username,
            'real_name' => $this->realName,
            'status' => $this->status,
        );
    }

    public function getId() {
        return $this->id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getRealName() {
        return $this->realName;
    }

    public function getPassword() {
        return md5($this->password);
    }

    public function getStatus() {

        return $this->status;
    }

    public function getLabelStatus() {

        if ($this->status) {
            return 'Ativo';
        }

        return 'Inativo';
    }

}
