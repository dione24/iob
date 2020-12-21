<?php

namespace Library\Entities;

class User extends \Library\Entity
{
    protected $login;
    protected $password;

    public function isValid()
    {
        return !(empty($this->login) || empty($this->password));
    }

    public function login()
    {
        return $this->login;
    }
    public function password()
    {
        return $this->password;
    }


    public function setLogin($login)
    {
        $this->login = $login;
    }
    public function setPassword($password)
    {
        $this->password = $password;
    }
}
