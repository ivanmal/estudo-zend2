<?php

namespace Usuario\Model;

interface UsuarioTableInterface {
    
    public function findAll($pagination);
    
    public function findUsuario($id);
    
    public function save(Usuario $usuario);
    
    public function changeStatus($id);
    
    public function checkUsername($username);
    
}
