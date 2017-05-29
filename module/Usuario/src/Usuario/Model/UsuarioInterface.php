<?php

namespace Usuario\Model;

interface UsuarioInterface {    
    
    public function getId();
    
    public function getUsername();
    
    public function getRealName();
    
    public function getPassword();
    
    public function getStatus();
}
